@php
    $envHealth = app(\App\Services\EnvHealthService::class);
    $warnings = $envHealth->warnings();
@endphp

@if(count($warnings) > 0 && auth()->user()?->role === 'admin')
    <div class="max-w-6xl mx-auto px-6 pt-6">
        <div class="rounded-2xl border p-5"
             style="background: rgba(185,28,28,0.04); border-color: rgba(185,28,28,0.2)">
            <div class="flex items-start gap-3 mb-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" style="color: var(--status-danger)">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3l-6.93-12a2 2 0 00-3.48 0L3.34 16a2 2 0 001.73 3z"/>
                </svg>
                <div>
                    <p class="font-semibold text-sm" style="color: var(--carbone)">
                        Configuration prod incomplète — {{ count($warnings) }} alerte{{ count($warnings) > 1 ? 's' : '' }}
                    </p>
                    <p class="text-xs mt-1" style="color: var(--gris-texte-soft)">
                        Variables manquantes dans l'environnement Railway. Tant qu'elles ne sont pas définies, les features concernées planteront silencieusement.
                    </p>
                </div>
            </div>

            <ul class="space-y-2 pl-8">
                @foreach($warnings as $w)
                    <li class="flex items-start gap-2.5 text-xs">
                        <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-[10px] font-mono uppercase tracking-wide leading-none"
                              style="
                                  @if($w['level'] === 'critical')
                                      background: var(--status-danger); color: var(--papier);
                                  @else
                                      background: var(--gris-bord); color: var(--carbone);
                                  @endif
                              ">
                            {{ $w['level'] }}
                        </span>
                        <code class="font-mono text-[11px] shrink-0" style="color: var(--carbone)">{{ $w['key'] }}</code>
                        <span style="color: var(--gris-texte-soft)">— {{ $w['message'] }}</span>
                    </li>
                @endforeach
            </ul>

            <p class="text-xs mt-4 pl-8" style="color: var(--gris-texte-soft)">
                Diagnostic complet :
                <code class="font-mono">/healthz/deep?token=…</code>
            </p>
        </div>
    </div>
@endif
