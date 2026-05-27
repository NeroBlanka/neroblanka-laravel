<x-public-layout>

    {{-- ═══════════════════════════════════════════════════════
         HERO — Sombre, headline massive, deux boutons
    ═══════════════════════════════════════════════════════ --}}
    <section class="min-h-screen flex flex-col justify-center px-6 pt-32 pb-16 relative overflow-hidden">
        <div class="max-w-6xl mx-auto w-full">
            <div class="max-w-3xl">

                {{-- Pill tag Creatiwise style --}}
                <div class="inline-flex items-center gap-2 mb-8 fade-in"
                     style="border: 1px solid rgba(255,255,255,0.15); border-radius: 999px; padding: 5px 14px">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--purple)"></span>
                    <span class="label-mono" style="color: var(--gris)">Studio créatif · Alger</span>
                </div>

                <h1 class="font-clash font-semibold leading-[1.02] tracking-tight mb-8 fade-up"
                    style="font-size: clamp(3rem, 8vw, 6.5rem); color: var(--perle)">
                    Du contraste<br>naît la clarté.
                </h1>

                <p class="text-lg max-w-xl leading-relaxed mb-4 fade-up" style="color: var(--gris); transition-delay: 80ms;">
                    Branding premium, 3D, motion, web et systèmes IA pour les marques qui refusent l'ordinaire.
                </p>
                <p class="text-base max-w-xl leading-relaxed mb-12 fade-up" style="color: var(--gris-mid); transition-delay: 120ms;">
                    Supprimer les compromis est notre point de départ — pas notre limite.
                </p>

                <div class="flex flex-col sm:flex-row items-start gap-4 fade-up" style="transition-delay: 160ms;">
                    <a href="/brief" id="hero-cta" class="btn-primary px-7 py-4 text-base">
                        Démarrer un projet
                    </a>
                    <a href="/work" class="btn-secondary px-7 py-4 text-base">
                        Voir les réalisations
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         MARQUEE
    ═══════════════════════════════════════════════════════ --}}
    <div class="py-4 overflow-hidden select-none"
         style="border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06)">
        <div class="marquee-track font-mono tracking-widest uppercase text-xs" style="color: var(--gris-mid)">
            @foreach(['Branding', 'Motion Design', '3D Event', 'Product Studio', 'Campagnes Social', 'Site Web', 'Systèmes IA', 'Automation', 'Branding', 'Motion Design', '3D Event', 'Product Studio', 'Campagnes Social', 'Site Web', 'Systèmes IA', 'Automation'] as $item)
                <span class="px-8">{{ $item }}</span><span style="color: rgba(255,255,255,0.12)">·</span>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         WHAT WE DO — Section sombre, grille 3×2 comme Creatiwise
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-28" style="background: var(--bg-surface); border-bottom: 1px solid rgba(255,255,255,0.05)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-12 mb-16 fade-up">
                <div>
                    <div class="inline-block mb-5 px-3 py-1.5 rounded-full text-xs font-mono tracking-wider"
                         style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)">Ce qu'on fait</div>
                    <h2 class="font-clash text-3xl md:text-4xl lg:text-5xl font-semibold leading-tight" style="color: var(--perle)">
                        Nous créons ce<br>qui ne s'oublie pas.
                    </h2>
                </div>
                <div class="flex items-end">
                    <p class="text-base leading-relaxed max-w-md" style="color: var(--gris)">
                        Du concept à la production, un studio complet pour les marques qui ont l'ambition de s'imposer — en Algérie et au-delà.
                    </p>
                </div>
            </div>

            @php
            $serviceDescriptions = [
                'branding'              => 'Logo, charte graphique, typographies — le système de marque pensé pour durer.',
                'event_stand_3d'        => 'Stands et scénographies 3D photoréalistes pour salons et événements.',
                'product_rendering_3d'  => 'Packshots, rendus studio et visuels produit haute fidélité.',
                'motion_design'         => 'Animation, génération de motion graphics et contenus vidéo.',
                'social_campaign'       => 'Stratégie visuelle et contenus performants pour les réseaux.',
                'website'               => 'Sites vitrines, portfolios et landing pages sur mesure.',
                'ai_image_video'        => 'Images et vidéos IA intégrées au pipeline créatif.',
                'automation'            => 'Automatisation de processus créatifs et reporting.',
            ];

            $serviceIcons = [
                'branding'              => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
                'event_stand_3d'        => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>',
                'product_rendering_3d'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
                'motion_design'         => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>',
                'social_campaign'       => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>',
                'website'               => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                'ai_image_video'        => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/><path d="M22 2 12 12"/></svg>',
                'automation'            => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>',
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach(\App\Enums\ServiceType::cases() as $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        <a href="/services"
                           class="group block rounded-2xl p-7 transition-all duration-300 fade-up cursor-pointer"
                           style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);"
                           onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.borderColor='rgba(255,255,255,0.12)';"
                           onmouseout="this.style.background='rgba(255,255,255,0.03)'; this.style.borderColor='rgba(255,255,255,0.07)';">
                            <div class="mb-5 text-white opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                                {!! $serviceIcons[$service->value] ?? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>' !!}
                            </div>
                            <h3 class="font-semibold text-base mb-3 transition-colors duration-300" style="color: var(--perle)">
                                {{ $service->label() }}
                            </h3>
                            <p class="text-xs leading-relaxed" style="color: var(--gris-mid)">
                                {{ $serviceDescriptions[$service->value] ?? '' }}
                            </p>
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="mt-10 text-center fade-up">
                <a href="/services" class="btn-secondary inline-flex items-center gap-2 px-6 py-3">
                    Tous les services →
                </a>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         HOW WE WORK — Process steps inclinés, style Creatiwise
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-28 overflow-hidden" style="border-bottom: 1px solid rgba(255,255,255,0.05)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-12 mb-20 fade-up">
                <div>
                    <div class="inline-block mb-5 px-3 py-1.5 rounded-full text-xs font-mono tracking-wider"
                         style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)">Notre process</div>
                    <h2 class="font-clash text-3xl md:text-4xl lg:text-5xl font-semibold leading-tight" style="color: var(--perle)">
                        Du brief au lancement,<br>on vous guide.
                    </h2>
                </div>
                <div class="flex items-end">
                    <p class="text-base leading-relaxed max-w-md" style="color: var(--gris)">
                        Un process clair, des jalons maîtrisés et une communication directe — pas de zones d'ombre, pas de mauvaises surprises.
                    </p>
                </div>
            </div>

            {{-- Process cards — inclinés style Creatiwise --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['num' => '01', 'title' => 'Analyser', 'desc' => 'On étudie votre marché, vos concurrents et vos objectifs avant de tracer la moindre ligne.', 'rotate' => '-3deg'],
                    ['num' => '02', 'title' => 'Concevoir', 'desc' => 'Exploration visuelle, proposition de directions artistiques et itérations rapides.', 'rotate' => '2deg'],
                    ['num' => '03', 'title' => 'Produire', 'desc' => 'Exécution pixel-perfect avec des outils de production modernes — 3D, motion, code.', 'rotate' => '-2deg'],
                    ['num' => '04', 'title' => 'Livrer', 'desc' => 'Fichiers sources organisés, brief de brand et accompagnement au déploiement.', 'rotate' => '3deg'],
                ] as $step)
                    <div class="neo-tilt-card rounded-2xl p-7 fade-up"
                         style="background: var(--glass); backdrop-filter: blur(16px);
                                border: 1px solid rgba(255,255,255,0.10);
                                transform: rotate({{ $step['rotate'] }});
                                transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <p class="font-clash text-4xl font-semibold mb-5 opacity-20" style="color: var(--perle)">{{ $step['num'] }}</p>
                        <h3 class="font-clash text-xl font-semibold mb-3" style="color: var(--perle)">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-relaxed" style="color: var(--gris)">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         PORTFOLIO — Projects cards style Creatiwise
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-28" style="border-bottom: 1px solid rgba(255,255,255,0.05)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-16 fade-up">
                <div>
                    <div class="inline-block mb-5 px-3 py-1.5 rounded-full text-xs font-mono tracking-wider"
                         style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)">Portfolio</div>
                    <h2 class="font-clash text-3xl md:text-4xl font-semibold" style="color: var(--perle)">
                        Explorez nos projets<br>les plus marquants.
                    </h2>
                </div>
                <a href="/work"
                   class="hidden sm:flex items-center gap-2 px-5 py-2.5 rounded-full text-sm transition-all duration-200"
                   style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)"
                   onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.color='var(--perle)';"
                   onmouseout="this.style.background='transparent'; this.style.color='var(--gris)';">
                    Voir tous les projets →
                </a>
            </div>

            @php
                $featured = \App\Models\PortfolioItem::published()->where('featured', true)->limit(3)->get();
                if ($featured->isEmpty()) {
                    $featured = \App\Models\PortfolioItem::published()->limit(3)->get();
                }
            @endphp

            @if($featured->isEmpty())
                <div class="rounded-2xl px-6 py-16 text-center fade-up"
                     style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06)">
                    <p class="text-sm" style="color: var(--gris-mid)">Portfolio en cours de construction — bientôt.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 stagger">
                    @foreach($featured as $item)
                        @php
                            $gradients = [
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
                            $bg = $gradients[$item->service_type->value] ?? 'linear-gradient(135deg, #111 0%, #1a1a1a 100%)';
                            $icon = $item->service_type->icon();
                        @endphp
                        <a href="/work/{{ $item->slug }}"
                           class="group block rounded-2xl overflow-hidden transition-all duration-300 fade-up"
                           style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07)"
                           onmouseover="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(-4px)'"
                           onmouseout="this.style.borderColor='rgba(255,255,255,0.07)'; this.style.transform='translateY(0)'">

                            {{-- Image area --}}
                            <div class="w-full h-48 flex items-center justify-center relative overflow-hidden"
                                 style="background: {{ $bg }}">
                                <span class="text-4xl opacity-20 transition-transform duration-500 group-hover:scale-110">{{ $icon }}</span>
                                {{-- Arrow button Creatiwise style --}}
                                <div class="absolute top-4 right-4 w-9 h-9 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-y-1 group-hover:translate-y-0"
                                     style="background: var(--perle); color: var(--bg)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M7 17L17 7M17 7H7M17 7v10"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Meta --}}
                            <div class="p-5">
                                <p class="text-xs uppercase tracking-wider mb-2" style="color: var(--gris-mid)">
                                    {{ $item->service_type->label() }}
                                </p>
                                <h3 class="font-semibold" style="color: var(--perle)">
                                    {{ $item->title }}
                                </h3>
                                @if($item->client_name)
                                    <p class="text-xs mt-1" style="color: var(--gris-mid)">{{ $item->client_name }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10 text-center sm:hidden fade-up">
                    <a href="/work" class="btn-secondary inline-flex items-center gap-2 px-6 py-3">
                        Voir tous les projets →
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         MANIFESTE — Quote forte
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-28" style="border-bottom: 1px solid rgba(255,255,255,0.05)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="max-w-4xl fade-up">
                <p class="font-clash text-4xl md:text-5xl lg:text-6xl font-semibold leading-[1.1] tracking-tight" style="color: var(--perle)">
                    "La médiocrité<br>coûte plus cher<br>que l'excellence."
                </p>
                <p class="mt-8 text-base leading-relaxed max-w-lg" style="color: var(--gris)">
                    Nadir Allek — fondateur Neroblanka, directeur artistique depuis 10 ans à Alger.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         CTA FINAL — Dark rounded card comme Creatiwise
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="rounded-2xl p-12 md:p-20 text-center relative overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(124,92,252,0.15) 0%, rgba(0,0,0,0) 60%, rgba(240,89,218,0.08) 100%);
                        border: 1px solid rgba(255,255,255,0.1)">

                {{-- Ambient blob --}}
                <div class="absolute inset-0 pointer-events-none opacity-40"
                     style="background: radial-gradient(ellipse at 30% 50%, rgba(124,92,252,0.25) 0%, transparent 55%),
                                        radial-gradient(ellipse at 70% 50%, rgba(240,89,218,0.15) 0%, transparent 55%)"></div>

                <div class="relative fade-up">
                    <div class="inline-block mb-8 px-3 py-1.5 rounded-full text-xs font-mono tracking-wider"
                         style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)">Démarrez aujourd'hui</div>
                    <h2 class="font-clash text-4xl md:text-5xl lg:text-6xl font-semibold leading-[1.1] tracking-tight mb-6" style="color: var(--perle)">
                        Votre projet mérite<br>mieux que l'ordinaire.
                    </h2>
                    <p class="text-lg mb-10 max-w-lg mx-auto" style="color: var(--gris)">
                        Décrivez votre besoin en 5 minutes. On répond avec une vision, pas un devis générique.
                    </p>
                    <a href="/brief" class="btn-primary px-8 py-4 text-base inline-flex items-center gap-2">
                        Démarrer le diagnostic créatif
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- STICKY CTA --}}
    <div id="sticky-cta"
        class="fixed bottom-6 right-6 z-40 transition-all duration-300"
        style="opacity: 0; transform: translateY(1rem); pointer-events: none"
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
        <a href="/brief" class="btn-primary px-5 py-3 shadow-2xl text-sm inline-flex items-center gap-2">
            Diagnostic créatif →
        </a>
    </div>

</x-public-layout>
