<x-app-layout>
    <x-slot name="title">Funnel Analytics — Neroblanka Admin</x-slot>

    <div class="max-w-6xl mx-auto px-6 py-14">

        <div class="mb-12">
            <p class="label-mono mb-3">Admin · Analytics</p>
            <h1 class="text-4xl" style="color: var(--perle)">Funnel Acquisition</h1>
            <p class="text-sm mt-2" style="color: var(--gris-mid)">{{ $days }} derniers jours</p>
        </div>

        {{-- KPIs --}}
        <div class="grid grid-cols-3 gap-4 mb-10">
            @foreach([
                ['Briefs démarrés',  $totalStarted,   '#7c5cfc'],
                ['Briefs soumis',    $totalSubmitted, '#34d399'],
                ['Taux conversion',  $conversionRate . '%', '#fbbf24'],
            ] as [$label, $value, $color])
                <div class="stat-card neo-tilt-card relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl"
                         style="background: {{ $color }}; opacity: 0.5; box-shadow: 0 0 12px {{ $color }}"></div>
                    <p class="label-mono mb-4">{{ $label }}</p>
                    <p class="text-5xl font-semibold tracking-tight stat-number">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- UTM Sources --}}
            <div class="card p-6">
                <p class="label-mono mb-6" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px">
                    Sources d'acquisition
                </p>
                @if($utmSources->isEmpty())
                    <p class="text-sm" style="color: var(--gris-mid)">Aucune donnée UTM sur la période.</p>
                @else
                    @php $utmMax = $utmSources->max(); @endphp
                    <div class="space-y-3">
                        @foreach($utmSources as $source => $count)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium" style="color: var(--perle)">{{ $source }}</span>
                                    <span class="label-mono text-xs" style="color: var(--gris-mid)">{{ $count }}</span>
                                </div>
                                <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06)">
                                    <div class="h-1.5 rounded-full" style="background: var(--purple); width: {{ $utmMax ? round($count / $utmMax * 100) : 0 }}%; opacity: 0.8"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Services pré-sélectionnés --}}
            <div class="card p-6">
                <p class="label-mono mb-6" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px">
                    Services les plus demandés
                </p>
                @if($serviceStats->isEmpty())
                    <p class="text-sm" style="color: var(--gris-mid)">Aucun brief démarré depuis un service.</p>
                @else
                    @php $svcMax = $serviceStats->max(); @endphp
                    <div class="space-y-3">
                        @foreach($serviceStats as $service => $count)
                            @php $label = \App\Enums\ServiceType::tryFrom($service)?->label() ?? $service; @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium" style="color: var(--perle)">{{ $label }}</span>
                                    <span class="label-mono text-xs" style="color: var(--gris-mid)">{{ $count }}</span>
                                </div>
                                <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06)">
                                    <div class="h-1.5 rounded-full" style="background: #34d399; width: {{ $svcMax ? round($count / $svcMax * 100) : 0 }}%; opacity: 0.8"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Abandons par étape --}}
        <div class="card p-6 mb-6">
            <p class="label-mono mb-6" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px">
                Complétions par étape du wizard
            </p>
            @if($stepCounts->isEmpty())
                <p class="text-sm" style="color: var(--gris-mid)">Aucune donnée d'étape sur la période.</p>
            @else
                @php $stepMax = $stepCounts->max(); @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                    @foreach($stepCounts as $step => $count)
                        @php
                            $pct = $stepMax ? round($count / $stepMax * 100) : 0;
                            $labels = ['1' => 'Service', '2' => 'Contact', '3' => 'Budget', '4' => 'Brief', '5' => 'Questions', '6' => 'Fichiers', '7' => 'Confirmation'];
                        @endphp
                        <div class="text-center">
                            <div class="relative mx-auto mb-2" style="width: 56px; height: 56px">
                                <svg viewBox="0 0 36 36" class="w-14 h-14 -rotate-90">
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="3"/>
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#7c5cfc" stroke-width="3"
                                            stroke-dasharray="{{ $pct }} {{ 100 - $pct }}" stroke-linecap="round"/>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-xs font-semibold" style="color: var(--perle)">{{ $count }}</span>
                            </div>
                            <p class="label-mono text-[10px]" style="color: var(--gris-mid)">Étape {{ $step }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--gris)">{{ $labels[(string) $step] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="card p-6">
            <p class="label-mono mb-6" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px">
                Activité quotidienne — {{ $days }} jours
            </p>
            @php
                $started   = $timeline->get('brief_started', collect())->keyBy('day');
                $submitted = $timeline->get('brief_submitted', collect())->keyBy('day');
                $allDays   = collect();
                for ($i = $days - 1; $i >= 0; $i--) {
                    $allDays->push(now()->subDays($i)->format('Y-m-d'));
                }
                $maxDay = max(
                    $started->max('total') ?? 0,
                    $submitted->max('total') ?? 0,
                    1
                );
            @endphp
            <div class="flex items-end gap-1 h-24">
                @foreach($allDays as $day)
                    @php
                        $s = $started->get($day)?->total ?? 0;
                        $b = $submitted->get($day)?->total ?? 0;
                        $sh = $maxDay > 0 ? round($s / $maxDay * 96) : 0;
                        $bh = $maxDay > 0 ? round($b / $maxDay * 96) : 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center justify-end gap-0.5 group relative" title="{{ $day }}: {{ $s }} démarrés, {{ $b }} soumis">
                        @if($sh > 0)
                            <div style="height: {{ $sh }}px; background: rgba(124,92,252,0.5); min-height: 2px" class="w-full rounded-t-sm"></div>
                        @endif
                        @if($bh > 0)
                            <div style="height: {{ $bh }}px; background: rgba(52,211,153,0.6); min-height: 2px" class="w-full rounded-t-sm absolute bottom-0"></div>
                        @endif
                        @if($sh === 0 && $bh === 0)
                            <div style="height: 2px; background: rgba(255,255,255,0.06)" class="w-full"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex items-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-sm" style="background: rgba(124,92,252,0.5)"></div>
                    <span class="text-xs" style="color: var(--gris-mid)">Briefs démarrés</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-sm" style="background: rgba(52,211,153,0.6)"></div>
                    <span class="text-xs" style="color: var(--gris-mid)">Briefs soumis</span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
