<x-public-layout>

    {{-- HERO --}}
    <section class="min-h-screen flex flex-col justify-center px-6 pt-32 pb-24">
        <div class="max-w-6xl mx-auto w-full">
            <div class="max-w-3xl">

                <p class="label-mono mb-8 fade-in">Studio créatif · Alger</p>

                <h1 class="text-5xl md:text-7xl lg:text-8xl font-semibold leading-[1.02] tracking-tight text-white mb-8 fade-up"
                    style="font-family: 'Clash Grotesk', sans-serif;">
                    Du contraste<br>naît la clarté.
                </h1>

                <p class="text-lg text-[#888780] max-w-xl leading-relaxed mb-12 fade-up" style="transition-delay: 80ms;">
                    Branding premium, 3D, motion, web et systèmes IA pour les marques qui refusent l'ordinaire.
                </p>

                <div class="flex flex-col sm:flex-row items-start gap-4 fade-up" style="transition-delay: 160ms;">
                    <a href="/brief" id="hero-cta" class="btn-primary px-6 py-4">
                        Demander un diagnostic créatif
                    </a>
                    <a href="/work" class="btn-secondary px-6 py-4">
                        Voir les travaux
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- MARQUEE --}}
    <div class="border-y border-white/[0.06] py-4 overflow-hidden select-none">
        <div class="marquee-track text-[#555350] text-xs font-mono tracking-widest uppercase">
            @foreach(['Branding', 'Motion Design', '3D Event', 'Product Studio', 'Campagnes Social', 'Site Web', 'Systèmes IA', 'Automation', 'Branding', 'Motion Design', '3D Event', 'Product Studio', 'Campagnes Social', 'Site Web', 'Systèmes IA', 'Automation'] as $item)
                <span class="px-8">{{ $item }}</span><span class="text-[#333330]">·</span>
            @endforeach
        </div>
    </div>

    {{-- SERVICES --}}
    <section class="py-24 border-b border-white/[0.06]">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-16 fade-up">
                <div>
                    <p class="label-mono mb-3">Expertise</p>
                    <h2 class="text-3xl md:text-4xl font-semibold text-white" style="font-family: 'Clash Grotesk', sans-serif;">
                        7 pôles d'expertise
                    </h2>
                </div>
                <a href="/services" class="text-sm text-[#888780] hover:text-white transition-colors hidden sm:block">Tout voir →</a>
            </div>

            @php
            $serviceDescriptions = [
                'branding'              => 'Identités visuelles complètes — logo, charte, système graphique.',
                'event_stand_3d'        => 'Stands et scénographies 3D pour salons et événements.',
                'product_rendering_3d'  => 'Rendus 3D studio, packshots et visuels produit photo-réalistes.',
                'motion_design'         => 'Animation, générique, vidéo explicative et contenu dynamique.',
                'social_campaign'       => 'Contenus visuels et campagnes graphiques pour les réseaux.',
                'website'               => 'Sites vitrines, portfolios et landing pages sur mesure.',
                'ai_image_video'        => 'Génération d\'images et vidéos IA intégrée au workflow créatif.',
                'automation'            => 'Automatisation de processus créatifs et de reporting.',
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-white/[0.06] stagger">
                @foreach(\App\Enums\ServiceType::cases() as $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        <a href="/services"
                            class="group p-8 bg-[#0a0a0a] hover:bg-white/[0.03] transition-all duration-300 flex flex-col gap-4 fade-up">
                            <span class="text-2xl transition-transform duration-300 group-hover:scale-110 inline-block" aria-hidden="true">{{ $service->icon() }}</span>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-white mb-2" style="font-family: 'Clash Grotesk', sans-serif;">
                                    {{ $service->label() }}
                                </h3>
                                <p class="text-xs text-[#555350] leading-relaxed group-hover:text-[#888780] transition-colors duration-300">
                                    {{ $serviceDescriptions[$service->value] ?? '' }}
                                </p>
                            </div>
                            <span class="text-xs text-[#555350] group-hover:text-white/60 transition-colors mt-auto">
                                Voir le service →
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="mt-6 sm:hidden text-center">
                <a href="/services" class="text-sm text-[#888780] hover:text-white transition-colors">Tous les services →</a>
            </div>

        </div>
    </section>

    {{-- TRAVAUX TEASER --}}
    <section class="py-24 border-b border-white/[0.06]">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-16 fade-up">
                <div>
                    <p class="label-mono mb-3">Portfolio</p>
                    <h2 class="text-3xl md:text-4xl font-semibold text-white" style="font-family: 'Clash Grotesk', sans-serif;">
                        Quelques projets
                    </h2>
                </div>
                <a href="/work" class="text-sm text-[#888780] hover:text-white transition-colors hidden sm:block">Tout voir →</a>
            </div>

            @php
                $featured = \App\Models\PortfolioItem::published()->where('featured', true)->limit(3)->get();
                if ($featured->isEmpty()) {
                    $featured = \App\Models\PortfolioItem::published()->limit(3)->get();
                }
            @endphp

            @if($featured->isEmpty())
                <div class="border border-white/[0.06] rounded-sm p-16 text-center fade-up">
                    <p class="text-[#555350] text-sm">Portfolio en cours de construction — bientôt.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 stagger">
                    @foreach($featured as $item)
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
                           class="group block border border-white/[0.06] rounded-sm hover:border-white/20 transition-all duration-300 overflow-hidden fade-up">

                            <div class="w-full h-48 flex items-center justify-center relative overflow-hidden"
                                 style="background: {{ $bg }};">
                                <span class="text-4xl opacity-30 transition-transform duration-500 group-hover:scale-125">{{ $icon }}</span>
                                {{-- Hover overlay --}}
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                                    <span class="text-white text-sm font-medium" style="font-family: 'Clash Grotesk', sans-serif;">
                                        Voir le projet →
                                    </span>
                                </div>
                            </div>

                            <div class="p-5">
                                <p class="text-xs text-[#555350] uppercase tracking-wider mb-2">
                                    {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
                                </p>
                                <h3 class="text-white text-sm font-medium" style="font-family: 'Clash Grotesk', sans-serif;">
                                    {{ $item->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 sm:hidden text-center">
                    <a href="/work" class="text-sm text-[#888780] hover:text-white transition-colors">Tous les projets →</a>
                </div>
            @endif

        </div>
    </section>

    {{-- MANIFESTE --}}
    <section class="py-32 border-b border-white/[0.06]">
        <div class="max-w-6xl mx-auto px-6">
            <div class="max-w-3xl">
                <p class="label-mono mb-8 fade-in">Pourquoi Neroblanka</p>
                <p class="text-3xl md:text-4xl lg:text-5xl font-semibold text-white leading-[1.15] tracking-tight fade-up"
                   style="font-family: 'Clash Grotesk', sans-serif;">
                    La médiocrité coûte plus cher que l'excellence.
                </p>
                <p class="mt-8 text-lg text-[#888780] max-w-xl leading-relaxed fade-up" style="transition-delay: 80ms;">
                    Chaque pixel, chaque frame, chaque ligne de code est pensée pour produire un résultat qui compte.
                    Pas de templates, pas de raccourcis. Un travail singulier pour une marque singulière.
                </p>
                <p class="mt-4 text-base text-[#555350] max-w-xl leading-relaxed fade-up" style="transition-delay: 160ms;">
                    Neroblanka est un studio créatif basé à Alger, fondé sur une conviction simple — le contraste crée la clarté.
                </p>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="py-32">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="label-mono mb-6 fade-in">Votre projet</p>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-semibold text-white leading-[1.1] tracking-tight mb-8 fade-up"
                style="font-family: 'Clash Grotesk', sans-serif;">
                Votre projet mérite mieux<br class="hidden md:block"> que l'ordinaire.
            </h2>
            <p class="text-[#888780] text-lg mb-12 max-w-lg mx-auto fade-up" style="transition-delay: 80ms;">
                Décrivez votre besoin en 5 minutes. On vous répond avec une vision, pas un devis générique.
            </p>
            <div class="fade-up" style="transition-delay: 160ms;">
                <a href="/brief" class="btn-primary px-8 py-4 text-base">
                    Démarrer le diagnostic créatif
                </a>
            </div>
        </div>
    </section>

    {{-- STICKY CTA (appears when hero-cta scrolls out) --}}
    <div id="sticky-cta"
        class="fixed bottom-6 right-6 z-40 opacity-0 translate-y-4 transition-all duration-300 pointer-events-none"
        x-data="{ visible: false }"
        x-init="
            const hero = document.getElementById('hero-cta');
            if (hero) {
                const obs = new IntersectionObserver(([e]) => {
                    visible = !e.isIntersecting;
                    $el.style.opacity = visible ? '1' : '0';
                    $el.style.transform = visible ? 'translateY(0)' : 'translateY(1rem)';
                    $el.style.pointerEvents = visible ? 'auto' : 'none';
                });
                obs.observe(hero);
            }
        ">
        <a href="/brief"
            class="px-5 py-3 bg-white text-[#0a0a0a] text-sm font-medium rounded-sm shadow-2xl hover:bg-[#e8e7e2] transition-colors"
            style="font-family: 'Clash Grotesk', sans-serif;">
            Diagnostic créatif →
        </a>
    </div>

</x-public-layout>
