<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Enums\ProjectStatus;
use App\Enums\ServiceType;
use App\Enums\UserRole;
use App\Jobs\AnalyzeLeadWithAIJob;
use App\Jobs\NotifyAdminNewLeadJob;
use App\Jobs\SendBriefConfirmationJob;
use App\Models\Brief;
use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class LeadService
{
    public function __construct(private readonly FileService $fileService) {}

    /**
     * Soumet un brief complet. Tout s'exécute dans une transaction.
     * Ne jamais appeler d'IA ici — dispatché séparément via AnalyzeLeadWithAIJob.
     */
    public function submitBrief(array $data, array $answers, array $files = []): Lead
    {
        return DB::transaction(function () use ($data, $answers, $files) {
            $lead = Lead::create([
                'full_name' => strip_tags($data['full_name']),
                'email' => $data['email'],
                'phone' => isset($data['phone']) ? strip_tags($data['phone']) : null,
                'company' => isset($data['company']) ? strip_tags($data['company']) : null,
                'service_type' => $data['service_type'],
                'status' => LeadStatus::NEW,
                'budget_range' => $data['budget_range'] ?? null,
                'deadline_range' => $data['deadline_range'] ?? null,
                'client_type' => $data['client_type'] ?? null,
                'source' => $data['source'] ?? null,
                'utm' => $data['utm'] ?? null,
                'raw_payload' => $data,
            ]);

            Brief::create([
                'lead_id' => $lead->id,
                'answers' => $this->sanitizeAnswers($answers),
            ]);

            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $this->fileService->uploadForLead($file, $lead);
                }
            }

            $score = $this->calculateScore($lead, $answers);
            $lead->update(['score' => $score]);

            LeadEvent::create([
                'lead_id' => $lead->id,
                'type' => 'brief_submitted',
                'note' => "Score initial: {$score}/100",
            ]);

            dispatch(new SendBriefConfirmationJob($lead))->onQueue('emails');
            dispatch(new NotifyAdminNewLeadJob($lead))->onQueue('emails');
            dispatch(new AnalyzeLeadWithAIJob($lead))->onQueue('ai')->delay(now()->addSeconds(5));

            return $lead;
        });
    }

    public function changeStatus(Lead $lead, LeadStatus $newStatus, ?string $note = null, ?string $adminId = null): void
    {
        $oldStatus = $lead->status;

        $lead->update(['status' => $newStatus]);

        LeadEvent::create([
            'lead_id' => $lead->id,
            'user_id' => $adminId,
            'type' => 'status_changed',
            'from_status' => $oldStatus->value,
            'to_status' => $newStatus->value,
            'note' => $note,
        ]);
    }

    public function addNote(Lead $lead, string $note, string $adminId): void
    {
        LeadEvent::create([
            'lead_id' => $lead->id,
            'user_id' => $adminId,
            'type' => 'note_added',
            'note' => $note,
        ]);
    }

    public function calculateScore(Lead $lead, array $answers): int
    {
        $score = 0;

        // Budget (30 pts)
        $score += match($lead->budget_range) {
            '< 500$' => 5,
            '500$ – 2 000$' => 15,
            '2 000$ – 10 000$' => 25,
            '10 000$ – 30 000$', '> 30 000$' => 30,
            default => 0,
        };

        // Deadline (20 pts) — plus la deadline est réaliste, meilleur le score
        $score += match($lead->deadline_range) {
            '< 2 semaines' => 5,  // urgent = risqué
            '2 – 4 semaines' => 15,
            '1 – 3 mois' => 20,
            '> 3 mois' => 18,
            default => 0,
        };

        // Service clair (20 pts)
        $score += ($lead->service_type !== ServiceType::MIXED_PROJECT) ? 20 : 10;

        // Clarté du brief (15 pts) — basé sur la longueur et richesse des réponses
        $totalLength = collect($answers)->map(fn($v) => is_string($v) ? strlen($v) : 0)->sum();
        $score += match(true) {
            $totalLength > 500 => 15,
            $totalLength > 200 => 10,
            $totalLength > 50 => 5,
            default => 0,
        };

        // Type client (10 pts)
        $score += match($lead->client_type) {
            'startup', 'export', 'diaspora' => 10,
            'pme' => 7,
            'event' => 5,
            default => 3,
        };

        // Présence fichiers de référence (5 pts)
        if (isset($answers['references']) || isset($answers['fichiers'])) {
            $score += 5;
        }

        return min(100, $score);
    }

    /**
     * Convertit un lead qualifié en projet client.
     * Crée l'utilisateur client si inexistant, puis crée le projet.
     * Envoie un email de réinitialisation de mot de passe si l'utilisateur est nouveau.
     */
    public function convertToProject(Lead $lead, string $adminId): Project
    {
        abort_unless($lead->status === LeadStatus::QUALIFIED, 422, 'Seul un lead qualifié peut être converti.');
        abort_if(
            Project::withoutGlobalScopes()->where('lead_id', $lead->id)->exists(),
            422,
            'Ce lead a déjà été converti en projet.'
        );

        return DB::transaction(function () use ($lead, $adminId) {
            $isNew = false;
            $client = User::withoutGlobalScopes()->where('email', $lead->email)->first();

            if (! $client) {
                $isNew = true;
                $client = User::withoutGlobalScopes()->create([
                    'full_name' => $lead->full_name,
                    'email' => $lead->email,
                    'password' => bcrypt(Str::random(32)),
                    'role' => UserRole::CLIENT->value,
                    'email_verified_at' => now(),
                ]);
            }

            $answers = $lead->brief?->answers ?? [];
            $description = $answers['project_description'] ?? $lead->service_type->label();

            $project = Project::withoutGlobalScopes()->create([
                'lead_id' => $lead->id,
                'client_id' => $client->id,
                'title' => $lead->full_name . ' — ' . $lead->service_type->label(),
                'description' => strip_tags($description),
                'service_type' => $lead->service_type->value,
                'status' => ProjectStatus::DRAFT->value,
                'notes' => $lead->company ? 'Entreprise : ' . $lead->company : null,
            ]);

            $this->changeStatus($lead, LeadStatus::WON, "Converti en projet #{$project->id}", $adminId);

            if ($isNew) {
                Password::sendResetLink(['email' => $client->email]);
            }

            return $project;
        });
    }

    private function sanitizeAnswers(array $answers): array
    {
        return collect($answers)->map(function ($value) {
            if (is_string($value)) {
                return strip_tags($value);
            }
            if (is_array($value)) {
                return $this->sanitizeAnswers($value);
            }
            return $value;
        })->toArray();
    }
}
