<x-public-layout title="Travaux — Neroblanka" description="Projets sélectionnés — branding, 3D, motion design, web et IA.">
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">

            <p class="label-mono mb-6 fade-in">Portfolio</p>
            <h1 class="text-4xl md:text-5xl font-semibold text-white mb-4 fade-up" style="font-family: 'Clash Grotesk', sans-serif;">
                Travaux sélectionnés
            </h1>
            <p class="text-[#888780] text-base mb-12 fade-up" style="transition-delay: 60ms;">
                Branding, 3D, motion, web, IA — un studio complet, une exigence unique.
            </p>

            @php
                $items = \App\Models\PortfolioItem::published()->orderBy('published_at', 'desc')->get();
                $serviceTypes = $items->pluck('service_type')->unique()->values()->all();

                $serviceLabels = [];
                foreach ($serviceTypes as $type) {
                    try { $serviceLabels[$type] = \App\Enums\ServiceType::from($type)->label(); }
                    catch (\ValueError $e) { $serviceLabels[$type] = $type; }
                }
            @endphp

            @if($items->isNotEmpty() && count($serviceTypes) > 1)
                {{-- Filters --}}
                <div
                    x-data="{ active: 'all' }"
                    class="fade-up mb-12" style="transition-delay: 100ms;">

                    <div class="flex flex-wrap gap-2">
                        <button
                            @click="active = 'all'"
                            :class="active === 'all' ? 'bg-white text-[#0a0a0a]' : 'border border-white/[0.12] text-[#888780] hover:text-white'"
                            class="px-4 py-2 text-xs rounded-sm transition-all duration-200 font-mono tracking-wider uppercase">
                            Tout
                        </button>
                        @foreach($serviceTypes as $type)
                            <button
                                @click="active = '{{ $type }}'"
                                :class="active === '{{ $type }}' ? 'bg-white text-[#0a0a0a]' : 'border border-white/[0.12] text-[#888780] hover:text-white'"
                                class="px-4 py-2 text-xs rounded-sm transition-all duration-200 font-mono tracking-wider uppercase">
                                {{ $serviceLabels[$type] ?? $type }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Grid --}}
                    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 stagger">
                        @foreach($items as $i => $item)
                            @php
                                $colors = [
                                    'branding'              => '#1a1a2e',
                                    'event_stand_3d'        => '#0d1b2a',
                                    'product_rendering_3d'  => '#16213e',
                                    'motion_design'         => '#1a0a2e',
                                    'social_campaign'       => '#0a1a1a',
                                    'website'               => '#0a1a0a',
                                    'ai_image_video'        => '#1a1a0a',
                                    'automation'            => '#1a0a0a',
                                    'mixed_project'         => '#1a1010',
                                ];
                                $bg = $colors[$item->service_type] ?? '#111';
                                try { $icon = \App\Enums\ServiceType::from($item->service_type)->icon(); }
                                catch (\ValueError $e) { $icon = '◆'; }
                                $isFeatured = $item->featured;
                            @endphp

                            <a href="/work/{{ $item->slug }}"
                               x-show="active === 'all' || active === '{{ $item->service_type }}'"
                               x-transition:enter="transition duration-300 ease-out"
                               x-transition:enter-start="opacity-0 scale-95"
                               x-transition:enter-end="opacity-100 scale-100"
                               x-transition:leave="transition duration-200 ease-in"
                               x-transition:leave-start="opacity-100 scale-100"
                               x-transition:leave-end="opacity-0 scale-95"
                               class="group block border border-white/[0.06] rounded-sm overflow-hidden hover:border-white/20 transition-all duration-300 fade-up {{ $isFeatured ? 'md:col-span-2' : '' }}">

                                {{-- Cover --}}
                                <div class="relative overflow-hidden {{ $isFeatured ? 'h-72 md:h-80' : 'h-52' }}"
                                     style="background: {{ $bg }};">
                                    <span class="absolute inset-0 flex items-center justify-center text-4xl {{ $isFeatured ? 'md:text-6xl' : '' }} opacity-25 transition-transform duration-500 group-hover:scale-110">
                                        {{ $icon }}
                                    </span>

                                    {{-- Hover reveal overlay --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                        <div class="flex items-center justify-between w-full">
                                            <span class="text-white font-medium text-sm" style="font-family: 'Clash Grotesk', sans-serif;">
                                                {{ $item->title }}
                                            </span>
                                            <span class="text-white text-lg transition-transform duration-300 group-hover:translate-x-1">→</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Meta --}}
                                <div class="p-5 flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs text-[#555350] uppercase tracking-wider mb-1.5">
                                            {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                        </p>
                                        <h2 class="text-white text-sm font-medium group-hover:text-[#e8e7e2] transition-colors" style="font-family: 'Clash Grotesk', sans-serif;">
                                            {{ $item->title }}
                                        </h2>
                                        @if($item->excerpt)
                                            <p class="text-[#888780] text-xs mt-1.5 leading-relaxed max-w-md">{{ $item->excerpt }}</p>
                                        @endif
                                    </div>
                                    <span class="text-[#555350] group-hover:text-white transition-colors shrink-0 mt-0.5">→</span>
                                </div>

                                @if($item->tags)
                                    <div class="flex flex-wrap gap-1.5 px-5 pb-5">
                                        @foreach($item->tags as $tag)
                                            <span class="text-[10px] text-[#555350] border border-white/[0.08] px-2 py-0.5 rounded-full">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

            @elseif($items->isNotEmpty())
                {{-- No filters needed (single type or very few items) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 stagger">
                    @foreach($items as $item)
                        @php
                            $colors = [
                                'branding'              => '#1a1a2e',
                                'event_stand_3d'        => '#0d1b2a',
                                'product_rendering_3d'  => '#16213e',
                                'motion_design'         => '#1a0a2e',
                                'social_campaign'       => '#0a1a1a',
                                'website'               => '#0a1a0a',
                                'ai_image_video'        => '#1a1a0a',
                                'automation'            => '#1a0a0a',
                                'mixed_project'         => '#1a1010',
                            ];
                            $bg = $colors[$item->service_type] ?? '#111';
                            try { $icon = \App\Enums\ServiceType::from($item->service_type)->icon(); }
                            catch (\ValueError $e) { $icon = '◆'; }
                        @endphp

                        <a href="/work/{{ $item->slug }}"
                           class="group block border border-white/[0.06] rounded-sm overflow-hidden hover:border-white/20 transition-all duration-300 fade-up {{ $item->featured ? 'md:col-span-2' : '' }}">

                            <div class="relative overflow-hidden {{ $item->featured ? 'h-72 md:h-80' : 'h-52' }}"
                                 style="background: {{ $bg }};">
                                <span class="absolute inset-0 flex items-center justify-center text-4xl opacity-25 transition-transform duration-500 group-hover:scale-110">
                                    {{ $icon }}
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="text-white font-medium text-sm" style="font-family: 'Clash Grotesk', sans-serif;">{{ $item->title }}</span>
                                        <span class="text-white">→</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs text-[#555350] uppercase tracking-wider mb-1.5">
                                        {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                    </p>
                                    <h2 class="text-white text-sm font-medium" style="font-family: 'Clash Grotesk', sans-serif;">{{ $item->title }}</h2>
                                    @if($item->excerpt)
                                        <p class="text-[#888780] text-xs mt-1.5 leading-relaxed">{{ $item->excerpt }}</p>
                                    @endif
                                </div>
                                <span class="text-[#555350] group-hover:text-white transition-colors shrink-0">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>

            @else
                <div class="border border-white/[0.06] rounded-sm p-16 text-center fade-up">
                    <p class="text-[#888780] text-sm mb-2">Portfolio en cours de construction.</p>
                    <p class="text-[#555350] text-xs">Les premiers projets arrivent bientôt.</p>
                </div>
            @endif

        </div>
    </div>
</x-public-layout>
