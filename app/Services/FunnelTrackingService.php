<?php

namespace App\Services;

use App\Models\FunnelEvent;
use Illuminate\Http\Request;

class FunnelTrackingService
{
    public function __construct(private readonly Request $request) {}

    public function track(string $event, ?string $leadId = null, array $metadata = []): void
    {
        FunnelEvent::create([
            'event' => $event,
            'lead_id' => $leadId,
            'session_id' => session()->getId(),
            'metadata' => $metadata ?: null,
            'utm' => $this->resolveUtm(),
            'ip' => $this->hashedIp(),
        ]);
    }

    private function hashedIp(): string
    {
        // SHA-256 truncated — sufficient for dedup, not reversible (GDPR-safe)
        return substr(hash('sha256', $this->request->ip() . config('app.key')), 0, 16);
    }

    private function resolveUtm(): ?array
    {
        $first = session('utm_first');
        $last = session('utm_last') ?? session('utm');

        if (! $first && ! $last) {
            return null;
        }

        return array_filter([
            'first' => $first,
            'last' => $last !== $first ? $last : null,
            'landing_page' => session('landing_page'),
            'referrer_first' => session('referrer_first'),
        ]);
    }
}
