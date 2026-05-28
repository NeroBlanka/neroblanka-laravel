<x-app-layout>
    <x-slot:title>Lead — {{ $lead->full_name }}</x-slot:title>

    <div class="max-w-4xl mx-auto px-6 py-10">

        @if(session('success'))
            <div class="mb-6 text-sm px-4 py-3 rounded-xl" style="color: #6ee7b7; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.25)">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-start justify-between mb-8 gap-4">
            <div>
                <a href="{{ route('admin.leads') }}" class="text-sm mb-3 inline-block transition-opacity hover:opacity-60" style="color: var(--gris)">← Lead Inbox</a>
                <h1 class="text-3xl" style="color: var(--carbone)">{{ $lead->full_name }}</h1>
                @if($lead->company)
                    <p class="text-sm mt-1" style="color: var(--gris)">{{ $lead->company }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <span class="inline-block px-3 py-1.5 text-sm font-semibold rounded-xl"
                      style="color: {{ $lead->isHot() ? '#6ee7b7' : ($lead->isWarm() ? '#fbbf24' : '#fca5a5') }};
                             background: {{ $lead->isHot() ? 'rgba(52,211,153,0.1)' : ($lead->isWarm() ? 'rgba(251,191,36,0.1)' : 'rgba(248,113,113,0.1)') }};
                             border: 1px solid {{ $lead->isHot() ? 'rgba(52,211,153,0.3)' : ($lead->isWarm() ? 'rgba(251,191,36,0.3)' : 'rgba(248,113,113,0.3)') }}">
                    Score {{ $lead->score }}/100
                </span>

                @if($lead->status === \App\Enums\LeadStatus::QUALIFIED && ! $lead->project)
                    <form method="POST" action="{{ route('admin.leads.convert', $lead) }}"
                          onsubmit="return confirm('Convertir ce lead en projet client ?')">
                        @csrf
                        <button type="submit" class="btn-primary text-sm">→ Convertir en projet</button>
                    </form>
                @elseif($lead->project)
                    <a href="{{ route('admin.projects.show', $lead->project) }}" class="btn-secondary text-sm">
                        Voir le projet →
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2 space-y-4">

                @if($lead->ai_summary)
                    <div class="card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="label-mono" style="color: var(--purple)">Analyse IA</p>
                            <span class="text-xs" style="color: var(--gris-mid)">{{ $lead->ai_analyzed_at?->format('d/m à H:i') }}</span>
                        </div>
                        <p class="text-sm leading-relaxed" style="color: var(--gris)">{{ $lead->ai_summary }}</p>
                        @if($lead->ai_score_adjustment !== 0)
                            <p class="text-xs mt-2" style="color: {{ $lead->ai_score_adjustment > 0 ? '#6ee7b7' : '#fca5a5' }}">
                                Ajustement score IA : {{ $lead->ai_score_adjustment > 0 ? '+' : '' }}{{ $lead->ai_score_adjustment }} pts
                            </p>
                        @endif
                    </div>
                @elseif(! $lead->ai_analyzed_at)
                    <div class="card p-4 text-xs text-center" style="color: var(--gris-mid); border-style: dashed">
                        Analyse IA en attente de traitement (queue:ai)
                    </div>
                @endif

                @if($lead->brief)
                    <div class="card p-5">
                        <p class="label-mono mb-4">Brief</p>
                        @foreach($lead->brief->answers as $key => $value)
                            @if($value)
                                <div class="mb-3">
                                    <p class="label-mono mb-1">{{ str_replace('_', ' ', $key) }}</p>
                                    <p class="text-sm" style="color: var(--gris)">{{ $value }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if($lead->files->count())
                    <div class="card p-5">
                        <p class="label-mono mb-4">Fichiers ({{ $lead->files->count() }})</p>
                        <ul class="space-y-2">
                            @foreach($lead->files as $file)
                                <li class="flex items-center gap-3">
                                    <a href="{{ $file->temporaryUrl(30) }}" target="_blank" rel="noopener"
                                        class="text-sm transition-opacity hover:opacity-70" style="color: var(--purple)">
                                        ↓ {{ $file->original_name }}
                                    </a>
                                    <span class="text-xs" style="color: var(--gris-mid)">
                                        {{ number_format($file->size_bytes / 1024, 0) }} Ko
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($lead->events->count())
                    <div class="card p-5">
                        <p class="label-mono mb-4">Historique</p>
                        <ul class="space-y-3">
                            @foreach($lead->events as $event)
                                <li class="flex items-start gap-3 text-sm">
                                    <span class="text-xs mt-0.5 whitespace-nowrap" style="color: var(--gris-mid)">{{ $event->created_at->format('d/m H:i') }}</span>
                                    <div>
                                        <span style="color: var(--carbone)">{{ $event->type }}</span>
                                        @if($event->note)
                                            <p class="mt-0.5 text-xs" style="color: var(--gris)">{{ $event->note }}</p>
                                        @endif
                                        @if($event->user)
                                            <span class="text-xs" style="color: var(--gris-mid)">par {{ $event->user->full_name }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="card p-5">
                    <p class="label-mono mb-4">Informations</p>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="label-mono mb-0.5">Email</dt><dd style="color: var(--carbone)">{{ $lead->email }}</dd></div>
                        @if($lead->phone)
                            <div><dt class="label-mono mb-0.5">Téléphone</dt><dd style="color: var(--carbone)">{{ $lead->phone }}</dd></div>
                        @endif
                        <div><dt class="label-mono mb-0.5">Service</dt><dd style="color: var(--carbone)">{{ $lead->service_type->label() }}</dd></div>
                        <div><dt class="label-mono mb-0.5">Budget</dt><dd style="color: var(--carbone)">{{ $lead->budget_range }}</dd></div>
                        <div><dt class="label-mono mb-0.5">Délai</dt><dd style="color: var(--carbone)">{{ $lead->deadline_range }}</dd></div>
                        <div>
                            <dt class="label-mono mb-0.5">Statut</dt>
                            <dd style="color: var(--carbone)">{{ $lead->status->label() }}</dd>
                        </div>
                        @if($lead->no_fit_reason)
                            <div>
                                <dt class="label-mono mb-0.5">Raison no-fit</dt>
                                <dd style="color: #fca5a5">{{ $lead->no_fit_reason->label() }}</dd>
                            </div>
                        @endif
                        <div><dt class="label-mono mb-0.5">Reçu</dt><dd style="color: var(--gris)">{{ $lead->created_at->format('d/m/Y à H:i') }}</dd></div>
                    </dl>
                </div>

                @if($lead->status !== \App\Enums\LeadStatus::NO_FIT && $lead->status !== \App\Enums\LeadStatus::WON && $lead->status !== \App\Enums\LeadStatus::ARCHIVED)
                    <div class="card p-5">
                        <p class="label-mono mb-4">Marquer no-fit</p>
                        <form method="POST" action="{{ route('admin.leads.no-fit', $lead) }}">
                            @csrf
                            <select name="no_fit_reason" required class="select-base w-full mb-3">
                                <option value="">Raison...</option>
                                @foreach(\App\Enums\NoFitReason::cases() as $reason)
                                    <option value="{{ $reason->value }}">{{ $reason->label() }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="note" placeholder="Note optionnelle"
                                   class="input-base w-full mb-3" maxlength="500">
                            <button type="submit" class="w-full text-sm py-2 rounded-xl transition-opacity hover:opacity-80 text-center"
                                    style="background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.3); color: #fca5a5">
                                Confirmer no-fit
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
