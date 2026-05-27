<x-public-layout title="Travaux — Neroblanka" description="Projets sélectionnés — branding, 3D, motion design, web et IA.">
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">

            <div class="inline-block mb-4 px-3 py-1 rounded-full text-xs font-mono tracking-wider fade-in"
                 style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)">Portfolio</div>
            <h1 class="font-clash text-4xl md:text-5xl font-semibold mb-4 fade-up" style="color: var(--perle)">
                Travaux sélectionnés
            </h1>
            <p class="text-base mb-12 fade-up" style="color: var(--gris); transition-delay: 60ms;">
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

            @if($items->isNotEmpty() && count($serviceTypes) > 1)
                <div x-data="{ active: 'all' }" class="fade-up mb-12" style="transition-delay: 100ms;">

                    {{-- Filters — Creatiwise pill style --}}
                    <div class="flex flex-wrap gap-2">
                        <button
                            @click="active = 'all'"
                            class="px-4 py-2 text-xs font-mono tracking-wider uppercase rounded-full transition-all duration-200"
                            :style="active === 'all'
                                ? 'background: var(--gradient); color: #fff; border: 1px solid transparent'
                                : 'background: transparent; color: var(--gris); border: 1px solid rgba(255,255,255,0.12)'">
                            Tout
                        </button>
                        @foreach($serviceTypes as $type)
                            <button
                                @click="active = '{{ $type->value }}'"
                                class="px-4 py-2 text-xs font-mono tracking-wider uppercase rounded-full transition-all duration-200"
                                :style="active === '{{ $type->value }}'
                                    ? 'background: var(--gradient); color: #fff; border: 1px solid transparent'
                                    : 'background: transparent; color: var(--gris); border: 1px solid rgba(255,255,255,0.12)'">
                                {{ $serviceLabels[$type->value] ?? $type->value }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Grid --}}
                    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 stagger">
                        @foreach($items as $i => $item)
                            @php
                                $colors = [
                                    'branding'              => 'linear-gradient(135deg, #1a1a2e 0%, #16213e 100%)',
                                    'event_stand_3d'        => 'linear-gradient(135deg, #0d1b2a 0%, #1a2a3a 100%)',
                                    'product_rendering_3d'  => 'linear-gradient(135deg, #16213e 0%, #0f3460 100%)',
                                    'motion_design'         => 'linear-gradient(135deg, #1a0a2e 0%, #2d1b5e 100%)',
                                    'social_campaign'       => 'linear-gradient(135deg, #0a1a1a 0%, #0d2d2d 100%)',
                                    'website'               => 'linear-gradient(135deg, #0a1a0a 0%, #0d2a0d 100%)',
                                    'ai_image_video'        => 'linear-gradient(135deg, #1a1a0a 0%, #2a2a0d 100%)',
                                    'automation'            => 'linear-gradient(135deg, #1a0a0a 0%, #2a1010 100%)',
                                    'mixed_project'         => 'linear-gradient(135deg, #1a1010 0%, #2a1a1a 100%)',
                                ];
                                $bg = $colors[$item->service_type->value] ?? 'linear-gradient(135deg, #111 0%, #1a1a1a 100%)';
                                $icon = $item->service_type->icon();
                                $isFeatured = $item->featured;
                            @endphp

                            <a href="/work/{{ $item->slug }}"
                               x-show="active === 'all' || active === '{{ $item->service_type->value }}'"
                               x-transition:enter="transition duration-300 ease-out"
                               x-transition:enter-start="opacity-0 scale-95"
                               x-transition:enter-end="opacity-100 scale-100"
                               x-transition:leave="transition duration-200 ease-in"
                               x-transition:leave-start="opacity-100 scale-100"
                               x-transition:leave-end="opacity-0 scale-95"
                               class="group block card card-interactive overflow-hidden transition-all duration-300 fade-up {{ $isFeatured ? 'md:col-span-2' : '' }}">

                                <div class="relative overflow-hidden {{ $isFeatured ? 'h-72 md:h-80' : 'h-52' }}"
                                     style="background: {{ $bg }}">
                                    <span class="absolute inset-0 flex items-center justify-center text-4xl {{ $isFeatured ? 'md:text-6xl' : '' }} opacity-20 transition-transform duration-500 group-hover:scale-110 group-hover:opacity-30">
                                        {{ $icon }}
                                    </span>
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6"
                                         style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent)">
                                        <div class="flex items-center justify-between w-full">
                                            <span class="font-medium text-sm" style="color: var(--perle)">
                                                {{ $item->title }}
                                            </span>
                                            <span class="text-lg transition-transform duration-300 group-hover:translate-x-1" style="color: var(--perle)">→</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-5 flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-wider mb-1.5" style="color: var(--gris-mid)">
                                            {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                        </p>
                                        <h2 class="text-sm font-medium" style="color: var(--perle)">
                                            {{ $item->title }}
                                        </h2>
                                        @if($item->excerpt)
                                            <p class="text-xs mt-1.5 leading-relaxed max-w-md" style="color: var(--gris)">{{ $item->excerpt }}</p>
                                        @endif
                                    </div>
                                    <span class="shrink-0 mt-0.5 transition-colors duration-300" style="color: var(--gris-mid)">→</span>
                                </div>

                                @if($item->tags)
                                    <div class="flex flex-wrap gap-1.5 px-5 pb-5">
                                        @foreach($item->tags as $tag)
                                            <span class="label-mono px-2 py-0.5 rounded-full text-[10px]"
                                                  style="color: var(--gris-mid); border: 1px solid rgba(255,255,255,0.06)">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

            @elseif($items->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 stagger">
                    @foreach($items as $item)
                        @php
                            $colors = [
                                'branding'              => 'linear-gradient(135deg, #1a1a2e 0%, #16213e 100%)',
                                'event_stand_3d'        => 'linear-gradient(135deg, #0d1b2a 0%, #1a2a3a 100%)',
                                'product_rendering_3d'  => 'linear-gradient(135deg, #16213e 0%, #0f3460 100%)',
                                'motion_design'         => 'linear-gradient(135deg, #1a0a2e 0%, #2d1b5e 100%)',
                                'social_campaign'       => 'linear-gradient(135deg, #0a1a1a 0%, #0d2d2d 100%)',
                                'website'               => 'linear-gradient(135deg, #0a1a0a 0%, #0d2a0d 100%)',
                                'ai_image_video'        => 'linear-gradient(135deg, #1a1a0a 0%, #2a2a0d 100%)',
                                'automation'            => 'linear-gradient(135deg, #1a0a0a 0%, #2a1010 100%)',
                                'mixed_project'         => 'linear-gradient(135deg, #1a1010 0%, #2a1a1a 100%)',
                            ];
                            $bg = $colors[$item->service_type->value] ?? 'linear-gradient(135deg, #111 0%, #1a1a1a 100%)';
                            $icon = $item->service_type->icon();
                        @endphp

                        <a href="/work/{{ $item->slug }}"
                           class="group block card card-interactive overflow-hidden transition-all duration-300 fade-up {{ $item->featured ? 'md:col-span-2' : '' }}">

                            <div class="relative overflow-hidden {{ $item->featured ? 'h-72 md:h-80' : 'h-52' }}"
                                 style="background: {{ $bg }}">
                                <span class="absolute inset-0 flex items-center justify-center text-4xl opacity-20 transition-transform duration-500 group-hover:scale-110">
                                    {{ $icon }}
                                </span>
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6"
                                     style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent)">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="font-medium text-sm" style="color: var(--perle)">{{ $item->title }}</span>
                                        <span style="color: var(--perle)">→</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wider mb-1.5" style="color: var(--gris-mid)">
                                        {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                    </p>
                                    <h2 class="text-sm font-medium" style="color: var(--perle)">{{ $item->title }}</h2>
                                    @if($item->excerpt)
                                        <p class="text-xs mt-1.5 leading-relaxed" style="color: var(--gris)">{{ $item->excerpt }}</p>
                                    @endif
                                </div>
                                <span class="shrink-0 transition-colors" style="color: var(--gris-mid)">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>

            @else
                <div class="card px-6 py-16 text-center fade-up">
                    <p class="text-sm mb-2" style="color: var(--gris)">Portfolio en cours de construction.</p>
                    <p class="text-xs" style="color: var(--gris-mid)">Les premiers projets arrivent bientôt.</p>
                </div>
            @endif

        </div>
    </div>
</x-public-layout>
