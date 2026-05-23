<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Enums\ServiceType;
use App\Jobs\NotifyAdminNewLeadJob;
use App\Jobs\SendBriefConfirmationJob;
use App\Models\Brief;
use App\Models\Lead;
use App\Models\LeadEvent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

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
