<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FunnelEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FunnelController extends Controller
{
    public function index(): View
    {
        $days = 30;
        $since = now()->subDays($days)->startOfDay();

        // Volume global
        $totalStarted   = FunnelEvent::where('event', 'brief_started')->where('created_at', '>=', $since)->count();
        $totalSubmitted = FunnelEvent::where('event', 'brief_submitted')->where('created_at', '>=', $since)->count();
        $conversionRate = $totalStarted > 0 ? round($totalSubmitted / $totalStarted * 100, 1) : 0;

        // Abandons par étape (metadata.step des step_completed)
        $stepCounts = FunnelEvent::where('event', 'step_completed')
            ->where('created_at', '>=', $since)
            ->get()
            ->groupBy(fn($e) => $e->metadata['step'] ?? 'unknown')
            ->map->count()
            ->sortKeys();

        // Top sources UTM (utm.first.utm_source)
        $utmSources = FunnelEvent::where('event', 'brief_started')
            ->where('created_at', '>=', $since)
            ->whereNotNull('utm')
            ->get()
            ->groupBy(fn($e) => $e->utm['first']['utm_source'] ?? $e->utm['utm_source'] ?? 'direct')
            ->map->count()
            ->sortDesc()
            ->take(8);

        // Événements par jour (timeline)
        $timeline = FunnelEvent::select(
                DB::raw("DATE(created_at) as day"),
                'event',
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $since)
            ->whereIn('event', ['brief_started', 'brief_submitted'])
            ->groupBy('day', 'event')
            ->orderBy('day')
            ->get()
            ->groupBy('event');

        // Services pré-remplis les plus choisis
        $serviceStats = FunnelEvent::where('event', 'brief_started')
            ->where('created_at', '>=', $since)
            ->get()
            ->filter(fn($e) => ! empty($e->metadata['service']))
            ->groupBy(fn($e) => $e->metadata['service'])
            ->map->count()
            ->sortDesc();

        return view('admin.funnel', compact(
            'totalStarted',
            'totalSubmitted',
            'conversionRate',
            'stepCounts',
            'utmSources',
            'timeline',
            'serviceStats',
            'days',
        ));
    }
}
