<x-public-layout title="Travaux — Neroblanka" description="Projets sélectionnés — branding, 3D, motion design, web et IA.">
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">

            <p class="text-[#888780] text-xs font-mono tracking-[0.2em] uppercase mb-6">Portfolio</p>
            <h1 class="text-4xl md:text-5xl font-semibold text-white mb-16" style="font-family: 'Clash Grotesk', sans-serif;">
                Travaux sélectionnés
            </h1>

            @php
                $items = \App\Models\PortfolioItem::published()->orderBy('published_at', 'desc')->get();
            @endphp

            @if($items->isEmpty())
                <p class="text-[#888780]">Portfolio en cours de construction.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($items as $item)
                        <a href="/work/{{ $item->slug }}"
                           class="group block border border-white/[0.06] rounded hover:border-white/20 transition-colors p-6 {{ $item->featured ? 'md:col-span-2' : '' }}">

                            {{-- Cover placeholder (color basé sur service) --}}
                            @php
                                $colors = [
                                    'branding' => '#1a1a2e',
                                    'event_stand_3d' => '#0d1b2a',
                                    'product_rendering_3d' => '#16213e',
                                    'motion_design' => '#1a0a2e',
                                    'social_campaign' => '#0a1a1a',
                                    'website' => '#0a1a0a',
                                    'ai_image_video' => '#1a1a0a',
                                    'automation' => '#1a0a0a',
                                    'mixed_project' => '#1a1010',
                                ];
                                $bg = $colors[$item->service_type] ?? '#111';
                            @endphp
                            <div class="w-full h-40 rounded mb-5 flex items-center justify-center"
                                 style="background: {{ $bg }};">
                                @php
                                    try {
                                        $enum = \App\Enums\ServiceType::from($item->service_type);
                                        $icon = $enum->icon();
                                    } catch (\ValueError $e) {
                                        $icon = '◆';
                                    }
                                @endphp
                                <span class="text-3xl opacity-40">{{ $icon }}</span>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs text-[#888780] uppercase tracking-wider mb-2">
                                        {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                    </p>
                                    <h2 class="text-white font-medium group-hover:text-[#e8e7e2] transition-colors mb-2"
                                        style="font-family: 'Clash Grotesk', sans-serif;">
                                        {{ $item->title }}
                                    </h2>
                                    <p class="text-[#888780] text-sm leading-relaxed">{{ $item->excerpt }}</p>
                                </div>
                                <span class="text-[#888780] group-hover:text-white transition-colors shrink-0 mt-1">→</span>
                            </div>

                            @if($item->tags)
                                <div class="flex flex-wrap gap-2 mt-4">
                                    @foreach($item->tags as $tag)
                                        <span class="text-[10px] text-[#888780] border border-white/10 px-2 py-0.5 rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-public-layout>
