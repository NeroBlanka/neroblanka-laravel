<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\LeadEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeLeadWithAIJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 2;
    public int $timeout = 60;

    public function __construct(public readonly Lead $lead) {}

    public function handle(): void
    {
        // Max 1 appel IA par lead — idempotence garantie
        if ($this->lead->ai_analyzed_at !== null) {
            return;
        }

        $apiKey = config('services.openai.key');
        if (! $apiKey) {
            Log::warning('AnalyzeLeadWithAIJob: OPENAI_API_KEY non configuré', ['lead_id' => $this->lead->id]);
            return;
        }

        $prompt = $this->buildPrompt();

        $response = Http::withToken($apiKey)
            ->timeout(45)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'temperature' => 0.3,
                'max_tokens' => 400,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un consultant business spécialisé dans les studios créatifs premium. Analyse ce lead et retourne UNIQUEMENT un JSON valide avec les clés: "summary" (string, 2-3 phrases en français), "score_adjustment" (integer entre -20 et +20), "flags" (array de strings, max 3 alertes ou points positifs).',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

        if (! $response->successful()) {
            Log::error('AnalyzeLeadWithAIJob: API error', [
                'lead_id' => $this->lead->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $this->fail(new \RuntimeException('OpenAI API error: ' . $response->status()));
            return;
        }

        $data = $response->json();
        $usage = $data['usage'] ?? [];
        $content = $data['choices'][0]['message']['content'] ?? '{}';

        $parsed = json_decode($content, true);
        if (! is_array($parsed)) {
            Log::warning('AnalyzeLeadWithAIJob: réponse JSON invalide', ['lead_id' => $this->lead->id, 'content' => $content]);
            return;
        }

        $summary = isset($parsed['summary']) ? strip_tags((string) $parsed['summary']) : null;
        $adjustment = isset($parsed['score_adjustment']) ? max(-20, min(20, (int) $parsed['score_adjustment'])) : 0;
        $flags = isset($parsed['flags']) && is_array($parsed['flags'])
            ? array_map('strip_tags', array_slice($parsed['flags'], 0, 3))
            : [];

        $newScore = min(100, max(0, $this->lead->score + $adjustment));

        $this->lead->update([
            'ai_summary' => $summary,
            'ai_score_adjustment' => $adjustment,
            'ai_analyzed_at' => now(),
            'score' => $newScore,
        ]);

        $tokensUsed = ($usage['total_tokens'] ?? 0);
        $flagsSummary = empty($flags) ? '' : ' | ' . implode(', ', $flags);
        $sign = $adjustment >= 0 ? '+' : '';

        LeadEvent::create([
            'lead_id' => $this->lead->id,
            'type' => 'ai_analyzed',
            'note' => "Ajustement score: {$sign}{$adjustment} pts. Tokens: {$tokensUsed}.{$flagsSummary}",
        ]);
    }

    private function buildPrompt(): string
    {
        $lead = $this->lead;
        $answers = $lead->brief?->answers ?? [];

        $answersText = collect($answers)
            ->filter(fn($v) => is_string($v) && strlen($v) > 3)
            ->map(fn($v, $k) => ucfirst(str_replace('_', ' ', $k)) . ': ' . $v)
            ->values()
            ->implode("\n");

        $service = is_string($lead->service_type) ? $lead->service_type : $lead->service_type?->label();
        $company = $lead->company ?? 'Non renseigné';
        $filesCount = $lead->files->count();

        return <<<PROMPT
Lead pour Neroblanka Studio (studio créatif premium, Alger):

Nom: {$lead->full_name}
Entreprise: {$company}
Service demandé: {$service}
Budget: {$lead->budget_range}
Délai: {$lead->deadline_range}
Type de client: {$lead->client_type}
Score initial: {$lead->score}/100

Brief:
{$answersText}

Fichiers joints: {$filesCount}
PROMPT;
    }
}
