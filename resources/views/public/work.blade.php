<x-public-layout title="Travaux — Neroblanka" description="Projets sélectionnés — branding, 3D, motion design, web et IA.">
    <div class="pt-24 md:pt-32 pb-24 px-6">
        <div class="max-w-7xl mx-auto">

            <p class="label-mono mb-5" data-reveal>Portfolio</p>
            <h1 class="display text-4xl md:text-6xl mb-6" data-reveal>
                Travaux sélectionnés.
            </h1>
            <p class="text-lg max-w-xl text-gris mb-12" data-reveal>
                Branding, 3D, motion, web, IA — un studio complet, une exigence unique.
            </p>

            @php
                $items = \App\Models\PortfolioItem::published()->orderBy('published_at', 'desc')->get();
                $serviceTypes = $items->pluck('service_type')->unique()->values()->all();
                $serviceLabels = [];
                foreach ($serviceTypes as $type) {
                    $serviceLabels[$type->value] = $type->label();
                }
            @endphp

            @if($items->isNotEmpty())
                <div x-data="{ active: 'all' }" data-reveal>

                    @if(count($serviceTypes) > 1)
                        {{-- Filters --}}
                        <div class="flex flex-wrap gap-2 mb-10">
                            <button @click="active = 'all'"
                                class="px-4 py-2 text-xs font-medium uppercase tracking-wider rounded-full transition-all duration-200 cursor-pointer"
                                :style="active === 'all'
                                    ? 'background: var(--carbone); color: var(--papier); border: 1px solid var(--carbone)'
                                    : 'background: transparent; color: var(--gris-texte); border: 1px solid var(--gris-bord)'">
                                Tout
                            </button>
                            @foreach($serviceTypes as $type)
                                <button @click="active = '{{ $type->value }}'"
                                    class="px-4 py-2 text-xs font-medium uppercase tracking-wider rounded-full transition-all duration-200 cursor-pointer"
                                    :style="active === '{{ $type->value }}'
                                        ? 'background: var(--carbone); color: var(--papier); border: 1px solid var(--carbone)'
                                        : 'background: transparent; color: var(--gris-texte); border: 1px solid var(--gris-bord)'">
                                    {{ $serviceLabels[$type->value] ?? $type->value }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    {{-- Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        @foreach($items as $item)
                            @php $isFeatured = $item->featured; @endphp
                            <a href="/work/{{ $item->slug }}"
                               x-show="active === 'all' || active === '{{ $item->service_type->value }}'"
                               x-transition:enter="transition duration-300 ease-out"
                               x-transition:enter-start="opacity-0 scale-95"
                               x-transition:enter-end="opacity-100 scale-100"
                               data-tilt="5"
                               class="group block card-interactive rounded-3xl overflow-hidden {{ $isFeatured ? 'md:col-span-2' : '' }}"
                               style="transform-style: preserve-3d; will-change: transform;">

                                <div class="relative overflow-hidden {{ $isFeatured ? 'h-72 md:h-96' : 'h-56' }}"
                                     style="background: linear-gradient(135deg, var(--noir) 0%, var(--carbone) 100%)">
                                    <div class="absolute inset-0 opacity-25"
                                         style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 24px 24px;"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-5xl opacity-50" style="transform: translateZ(20px)">
                                        {{ $item->service_type->icon() }}
                                    </span>
                                    @if($item->is_concept ?? false)
                                        <span class="absolute top-5 left-5 label-pill label-pill-on-dark text-[10px] px-2.5 py-1">Concept</span>
                                    @endif
                                    <div class="absolute top-5 right-5 arrow-circle opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 17L17 7M17 7H7M17 7v10" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <p class="label-mono mb-2">
                                        {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                    </p>
                                    <h2 class="font-clash text-xl font-semibold text-carbone">{{ $item->title }}</h2>
                                    @if($item->excerpt)
                                        <p class="text-sm mt-2 leading-relaxed text-gris max-w-md">{{ $item->excerpt }}</p>
                                    @endif
                                    @if($item->tags)
                                        <div class="flex flex-wrap gap-1.5 mt-4">
                                            @foreach($item->tags as $tag)
                                                <span class="text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-full"
                                                      style="color: var(--gris-texte); border: 1px solid var(--gris-bord)">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            @else
                <div class="card-on-perle rounded-3xl px-6 py-20 text-center" data-reveal>
                    <p class="label-mono mb-4">Portfolio en cours de construction</p>
                    <p class="text-base text-gris max-w-md mx-auto mb-8">Les premiers projets arrivent bientôt. Vous avez un projet exigeant ? Parlons-en.</p>
                    <a href="/brief" class="btn-primary px-6 py-3 text-sm" data-magnetic="0.25">Démarrer une conversation</a>
                </div>
            @endif

        </div>
    </div>
</x-public-layout>
