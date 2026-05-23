<div>
    @if($success)
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded">
            {{ $message }}
        </div>
    @endif

    <div class="border border-black/10 rounded overflow-hidden">
        <div class="px-5 py-4 border-b border-black/[0.08] flex items-center justify-between">
            <h3 class="font-semibold text-sm uppercase tracking-wider text-gray-400">Matching freelances</h3>
            <span class="text-xs text-gray-400">Service : {{ is_string($project->service_type) ? $project->service_type : $project->service_type?->label() }}</span>
        </div>

        @if($ranked->isEmpty())
            <div class="px-5 py-8 text-sm text-[#888780] text-center">
                Aucun freelance enregistré.
            </div>
        @else
            <div class="divide-y divide-black/[0.06]">
                @foreach($ranked as $item)
                    @php
                        $freelance = $item['freelance'];
                        $score = $item['score'];
                        $bd = $item['breakdown'];
                        $profile = $freelance->freelanceProfile;
                    @endphp
                    <div class="px-5 py-4 flex items-start gap-4 {{ !$freelance->is_available ? 'opacity-50' : '' }}">
                        {{-- Score badge --}}
                        <div class="shrink-0 w-12 h-12 rounded flex items-center justify-center text-sm font-bold
                            {{ $score >= 70 ? 'bg-green-100 text-green-700' : ($score >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $score }}
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="font-medium text-sm">{{ $freelance->full_name }}</p>
                                @if(!$freelance->is_available)
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded">Indisponible</span>
                                @else
                                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded">Disponible</span>
                                @endif
                                @if($profile?->rating > 0)
                                    <span class="text-xs text-gray-400">★ {{ number_format($profile->rating, 1) }}</span>
                                @endif
                            </div>

                            {{-- Score breakdown --}}
                            <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs text-gray-400 mt-1">
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
                                        <span class="text-[10px] bg-[#e8e7e2] text-[#555350] px-2 py-0.5 rounded">{{ $skill->name }}</span>
                                    @endforeach
                                    @if($freelance->skills->count() > 4)
                                        <span class="text-[10px] text-gray-400">+{{ $freelance->skills->count() - 4 }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($profile?->portfolio_url)
                                <a href="{{ $profile->portfolio_url }}" target="_blank" rel="noopener"
                                   class="text-xs text-blue-500 hover:underline mt-1 inline-block">
                                    Portfolio →
                                </a>
                            @endif
                        </div>

                        {{-- Assign button --}}
                        <div class="shrink-0">
                            <button wire:click="assign('{{ $freelance->id }}')"
                                    wire:confirm="Assigner {{ $freelance->full_name }} à ce projet ?"
                                    class="px-3 py-1.5 text-xs font-medium rounded-sm transition-colors
                                        {{ $freelance->is_available
                                            ? 'bg-[#0a0a0a] text-white hover:bg-[#333]'
                                            : 'border border-black/20 text-gray-500 hover:border-black/40' }}">
                                Assigner
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
