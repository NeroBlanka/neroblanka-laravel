<div>
    @if($success)
        <div class="mb-4 text-sm px-4 py-3 rounded-xl" style="color: #15803d; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.25)">
            {{ $message }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(5,5,5,0.07)">
            <p class="label-mono" style="color: var(--carbone)">Matching freelances</p>
            <span class="text-xs" style="color: var(--gris-mid)">
                Service : {{ is_string($project->service_type) ? $project->service_type : $project->service_type?->label() }}
            </span>
        </div>

        @if($ranked->isEmpty())
            <div class="px-5 py-10 text-sm text-center" style="color: var(--gris-mid)">
                Aucun freelance enregistré.
            </div>
        @else
            <div>
                @foreach($ranked as $item)
                    @php
                        $freelance = $item['freelance'];
                        $score = $item['score'];
                        $bd = $item['breakdown'];
                        $profile = $freelance->freelanceProfile;
                    @endphp
                    <div class="px-5 py-4 flex items-start gap-4 transition-colors hover:bg-black/[0.03] {{ !$freelance->is_available ? 'opacity-50' : '' }}"
                         style="border-bottom: 1px solid rgba(5,5,5,0.07)">

                        {{-- Score badge --}}
                        <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center text-sm font-bold"
                             style="{{ $score >= 70
                                 ? 'background: rgba(52,211,153,0.12); color: #15803d; border: 1px solid rgba(52,211,153,0.25)'
                                 : ($score >= 40
                                     ? 'background: rgba(251,191,36,0.12); color: #c87f0a; border: 1px solid rgba(251,191,36,0.25)'
                                     : 'background: rgba(5,5,5,0.07); color: var(--gris); border: 1px solid rgba(5,5,5,0.10)') }}">
                            {{ $score }}
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-medium text-sm" style="color: var(--carbone)">{{ $freelance->full_name }}</p>
                                @if(!$freelance->is_available)
                                    <span class="text-xs px-2 py-0.5 rounded-lg"
                                          style="color: var(--gris); background: rgba(5,5,5,0.07); border: 1px solid rgba(5,5,5,0.10)">
                                        Indisponible
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-lg"
                                          style="color: #15803d; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.25)">
                                        Disponible
                                    </span>
                                @endif
                                @if($profile?->rating > 0)
                                    <span class="text-xs" style="color: var(--gris-mid)">★ {{ number_format($profile->rating, 1) }}</span>
                                @endif
                            </div>

                            {{-- Score breakdown --}}
                            <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs mt-1" style="color: var(--gris-mid)">
                                <span>Service {{ $bd['service'] }}/30</span>
                                <span>Dispo {{ $bd['availability'] }}/20</span>
                                <span>Skills {{ $bd['skills'] }}/20</span>
                                <span>Rating {{ $bd['rating'] }}/15</span>
                                <span>Charge {{ $bd['workload'] }}/10</span>
                                <span>Profil {{ $bd['profile'] }}/5</span>
                            </div>

                            {{-- Skills chips --}}
                            @if($freelance->skills->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($freelance->skills->take(4) as $skill)
                                        <span class="label-mono px-2 py-0.5 rounded-md"
                                              style="background: rgba(124,92,252,0.08); border: 1px solid rgba(124,92,252,0.2); color: var(--gris)">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                    @if($freelance->skills->count() > 4)
                                        <span class="text-[10px]" style="color: var(--gris-mid)">+{{ $freelance->skills->count() - 4 }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($profile?->portfolio_url)
                                <a href="{{ $profile->portfolio_url }}" target="_blank" rel="noopener"
                                   class="text-xs mt-1 inline-block transition-opacity hover:opacity-70" style="color: var(--purple)">
                                    Portfolio →
                                </a>
                            @endif
                        </div>

                        {{-- Assign button --}}
                        <div class="shrink-0">
                            <button wire:click="assign('{{ $freelance->id }}')"
                                    wire:confirm="Assigner {{ $freelance->full_name }} à ce projet ?"
                                    class="{{ $freelance->is_available ? 'btn-primary' : 'btn-secondary' }} text-xs px-3 py-1.5 cursor-pointer">
                                Assigner
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
