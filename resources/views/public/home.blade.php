<x-public-layout>

    {{-- ═══════════════════════════════════════════════════════
         HERO — Deux colonnes, titre massif éditorial
    ═══════════════════════════════════════════════════════ --}}
    <section class="px-6 pt-20 pb-24 md:pt-28 md:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                {{-- Left: text --}}
                <div class="lg:col-span-7 fade-up">
                    <div class="label-pill mb-8">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--status-success)"></span>
                        <span>Studio premium basé à Blida · Algérie</span>
                    </div>

                    <h1 class="display mb-8" style="font-size: clamp(2.5rem, 6.5vw, 5.5rem)">
                        Nous concevons des marques,<br>
                        des visuels 3D &amp; des expériences<br>
                        digitales <span style="color: var(--gris-texte-soft)">premium.</span>
                    </h1>

                    <p class="text-lg leading-relaxed text-gris max-w-xl mb-10">
                        Neroblanka Studio accompagne les marques ambitieuses avec une direction artistique exigeante : identité visuelle, 3D, motion, web, campagnes social media et systèmes IA.
                    </p>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <a href="/brief" id="hero-cta" class="btn-primary px-7 py-4 text-base">
                            Demander un diagnostic créatif
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <a href="/work" class="btn-secondary px-7 py-4 text-base">
                            Voir le portfolio
                        </a>
                    </div>
                </div>

                {{-- Right: visual card --}}
                <div class="lg:col-span-5 fade-up" style="transition-delay: 100ms;">
                    <div class="relative">
                        <div class="aspect-[4/5] rounded-3xl overflow-hidden relative"
                             style="background: linear-gradient(135deg, var(--noir) 0%, var(--carbone) 100%); border: 1px solid var(--gris-bord-soft);">
                            {{-- Pattern grid --}}
                            <div class="absolute inset-0 opacity-20"
                                 style="background-image:
                                    linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
                                    linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
                                    background-size: 32px 32px;"></div>

                            {{-- Central glyph --}}
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="font-clash font-semibold tracking-tighter text-perle opacity-90"
                                     style="font-size: clamp(8rem, 22vw, 14rem); line-height: 0.85;">
                                    N<span style="color: var(--accent-rose); opacity: 0.7">·</span>
                                </div>
                            </div>

                            {{-- Floating badge top-left --}}
                            <div class="absolute top-6 left-6 rounded-2xl px-4 py-3"
                                 style="background: rgba(251,250,247,0.95); backdrop-filter: blur(12px);">
                                <p class="label-mono mb-1" style="color: var(--gris-texte)">Triple expertise</p>
                                <p class="text-sm font-medium text-carbone">Design · 3D · IA</p>
                            </div>

                            {{-- Floating badge bottom-right --}}
                            <div class="absolute bottom-6 right-6 rounded-2xl px-4 py-3 max-w-[200px]"
                                 style="background: rgba(251,250,247,0.95); backdrop-filter: blur(12px);">
                                <p class="text-sm font-medium text-carbone leading-snug">15–20 directions créatives explorées en 48h</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         MARQUEE — Capabilities band
    ═══════════════════════════════════════════════════════ --}}
    <div class="py-6 border-y overflow-hidden select-none"
         style="border-color: var(--gris-bord-soft); background: var(--perle);">
        <div class="marquee-track">
            @foreach(['Branding', '3D Product', 'Event Stand', 'Motion', 'Web', 'Social Campaign', 'AI Image', 'Automation', 'Branding', '3D Product', 'Event Stand', 'Motion', 'Web', 'Social Campaign', 'AI Image', 'Automation'] as $item)
                <span class="px-10 text-sm font-medium tracking-wide text-carbone whitespace-nowrap">{{ $item }}</span>
                <span class="text-gris-soft" aria-hidden="true">·</span>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         ABOUT — Studio d'auteur
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-24 md:py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                {{-- Left: visual --}}
                <div class="lg:col-span-5 order-2 lg:order-1 fade-up">
                    <div class="aspect-square rounded-3xl overflow-hidden relative"
                         style="background: var(--perle); border: 1px solid var(--gris-bord-soft);">
                        {{-- Diagonal contrast --}}
                        <div class="absolute inset-0"
                             style="background: linear-gradient(135deg, var(--carbone) 0%, var(--carbone) 50%, var(--perle) 50%, var(--perle) 100%);"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <p class="font-clash text-7xl md:text-8xl font-semibold tracking-tighter"
                               style="background: linear-gradient(135deg, var(--perle) 0%, var(--perle) 50%, var(--carbone) 50%, var(--carbone) 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                                N·A
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right: text --}}
                <div class="lg:col-span-7 order-1 lg:order-2 fade-up">
                    <p class="label-mono mb-5">À propos</p>
                    <h2 class="display text-4xl md:text-5xl lg:text-6xl mb-8">
                        Un studio d'auteur,<br>pas une agence anonyme.
                    </h2>
                    <p class="text-lg leading-relaxed text-gris mb-6 max-w-xl">
                        Neroblanka est dirigé par <span class="text-carbone font-medium">Nadir Allek</span>. Chaque projet passe par une direction artistique claire, une exigence visuelle forte et une combinaison rare en Algérie : design graphique, 3D et IA générative.
                    </p>
                    <div class="inline-block rounded-2xl px-6 py-5 mt-2"
                         style="background: var(--carbone); color: var(--perle);">
                        <p class="label-mono mb-2" style="color: rgba(245,242,236,0.5)">3 expertises</p>
                        <p class="font-clash text-xl font-semibold">Graphic Design · 3D · IA générative</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         PROCESS — 4 cartes inclinées
    ═══════════════════════════════════════════════════════ --}}
    <section id="process" class="py-24 md:py-32 px-6 border-y" style="background: var(--perle); border-color: var(--gris-bord-soft);">
        <div class="max-w-7xl mx-auto">

            <div class="max-w-3xl mb-16 fade-up">
                <p class="label-mono mb-5">Process</p>
                <h2 class="display text-4xl md:text-5xl">
                    Un process clair pour transformer une demande floue en direction visuelle forte.
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                @foreach([
                    ['num' => '01', 'title' => 'Clarifier', 'desc' => "Analyse du brief, du marché, de la cible et du niveau d'ambition.", 'tilt' => 'tilt-1'],
                    ['num' => '02', 'title' => 'Diriger',  'desc' => 'Création d\'une direction artistique : moodboard, références, système visuel.', 'tilt' => 'tilt-2'],
                    ['num' => '03', 'title' => 'Produire', 'desc' => 'Design, 3D, motion, web ou IA selon le besoin du projet.', 'tilt' => 'tilt-3'],
                    ['num' => '04', 'title' => 'Livrer',   'desc' => 'Validation Neroblanka, livraison sécurisée, révisions cadrées.', 'tilt' => 'tilt-4'],
                ] as $step)
                    <div class="card-on-perle rounded-3xl p-7 {{ $step['tilt'] }} fade-up transition-transform duration-300 hover:!rotate-0 hover:-translate-y-1">
                        <p class="font-clash text-3xl font-semibold mb-6" style="color: var(--gris-texte-soft)">{{ $step['num'] }}</p>
                        <h3 class="font-clash text-xl font-semibold mb-3 text-carbone">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-gris">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         SERVICES — Section noire 8 cartes
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-24 md:py-32 px-6 bg-carbone">
        <div class="max-w-7xl mx-auto">

            <div class="max-w-3xl mb-16 fade-up">
                <p class="label-mono mb-5" style="color: rgba(245,242,236,0.5)">Services</p>
                <h2 class="display text-4xl md:text-5xl lg:text-6xl" style="color: var(--perle)">
                    Nous ne produisons pas juste du contenu.<br>
                    <span style="color: var(--gris-texte-soft)">Nous construisons une présence visuelle cohérente.</span>
                </h2>
            </div>

            @php
            $services = [
                ['title' => 'Identité Visuelle Premium', 'desc' => 'Logo, charte, système visuel, direction artistique.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/></svg>'],
                ['title' => '3D Event & Stand Design', 'desc' => 'Modélisation de stands pour foires, salons et événements.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-6 9 6v12H3V9z"/><path d="M9 21V12h6v9"/></svg>'],
                ['title' => '3D Product Studio', 'desc' => 'Modélisation produit, rendu studio, packshot, visuels publicitaires.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>'],
                ['title' => 'Motion Design', 'desc' => 'Animation logo, reels, launch videos, animations produit.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="6 4 20 12 6 20 6 4"/></svg>'],
                ['title' => 'Campagnes Réseaux Sociaux', 'desc' => 'Concepts créatifs, visuels, posts, stories, reels, direction de campagne.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 11V20M12 4V20M20 14V20"/></svg>'],
                ['title' => 'Sites Web Premium', 'desc' => 'Landing pages, sites vitrines, portfolios, sites de marque.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="14" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>'],
                ['title' => 'IA Image & Vidéo', 'desc' => 'Génération d\'images, vidéos IA, exploration créative sous direction.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><circle cx="9" cy="9" r="1" fill="currentColor"/><circle cx="15" cy="9" r="1" fill="currentColor"/></svg>'],
                ['title' => 'Automation Créative', 'desc' => 'Workflows IA, automatisation de contenu, systèmes internes.', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>'],
            ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                @foreach($services as $service)
                    <div class="card-on-dark rounded-2xl p-6 transition-all duration-200 hover:border-white/20 fade-up group">
                        <div class="mb-5 text-perle opacity-70 group-hover:opacity-100 transition-opacity">
                            {!! $service['icon'] !!}
                        </div>
                        <h3 class="text-base font-medium mb-2 text-perle">{{ $service['title'] }}</h3>
                        <p class="text-xs leading-relaxed" style="color: rgba(245,242,236,0.55)">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 fade-up">
                <a href="/services" class="btn-secondary btn-secondary-on-dark px-6 py-3 text-sm">
                    Tous les services
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         PORTFOLIO PREVIEW
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-24 md:py-32 px-6" style="background: var(--perle);">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-14 fade-up">
                <div class="max-w-2xl">
                    <p class="label-mono mb-5">Portfolio</p>
                    <h2 class="display text-4xl md:text-5xl">
                        Projets sélectionnés.<br>
                        <span class="text-gris">Directions visuelles, 3D, campagnes et systèmes digitaux.</span>
                    </h2>
                </div>
                <a href="/work" class="btn-secondary px-5 py-2.5 text-sm self-start lg:self-end">
                    Voir tous les projets
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            @php
                $featured = \App\Models\PortfolioItem::published()->where('featured', true)->limit(3)->get();
                if ($featured->isEmpty()) {
                    $featured = \App\Models\PortfolioItem::published()->limit(3)->get();
                }
            @endphp

            @if($featured->isEmpty())
                <div class="card-on-perle rounded-3xl px-6 py-20 text-center fade-up">
                    <p class="label-mono mb-4">Portfolio en cours de construction</p>
                    <p class="text-base text-gris max-w-md mx-auto">Les premières sélections seront publiées prochainement. Vous avez un projet exigeant ? Parlons-en.</p>
                    <a href="/brief" class="btn-primary mt-8 px-6 py-3 text-sm">Démarrer une conversation</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($featured as $item)
                        <a href="/work/{{ $item->slug }}" class="card-interactive rounded-3xl overflow-hidden group fade-up">
                            <div class="aspect-[4/3] relative overflow-hidden"
                                 style="background: linear-gradient(135deg, var(--noir) 0%, var(--carbone) 100%);">
                                {{-- Pattern --}}
                                <div class="absolute inset-0 opacity-30"
                                     style="background-image:
                                        linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
                                        linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
                                        background-size: 24px 24px;"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-5xl opacity-50">{{ $item->service_type?->icon() ?? '◉' }}</span>
                                </div>
                                <div class="absolute top-5 right-5 arrow-circle opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M7 17L17 7M17 7H7M17 7v10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="label-mono mb-2">{{ $item->service_type?->label() ?? 'Projet' }}</p>
                                <h3 class="font-clash text-xl font-semibold text-carbone mb-1">{{ $item->title }}</h3>
                                @if($item->client_name)
                                    <p class="text-sm text-gris">{{ $item->client_name }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         MANIFESTE
    ═══════════════════════════════════════════════════════ --}}
    <section class="py-24 md:py-32 px-6">
        <div class="max-w-5xl mx-auto fade-up">
            <p class="label-mono mb-8">Manifeste</p>
            <h2 class="display text-4xl md:text-6xl lg:text-7xl mb-10 leading-[0.95]">
                Le premium n'est pas<br>une décoration.<br>
                <span style="color: var(--gris-texte-soft)">C'est une discipline.</span>
            </h2>
            <p class="text-lg leading-relaxed text-gris max-w-2xl">
                Chaque ligne, chaque contraste, chaque rendu et chaque interaction doit servir une perception plus claire de la marque.
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         CTA FINAL — Grande carte noire
    ═══════════════════════════════════════════════════════ --}}
    <section id="contact" class="px-6 pb-24 md:pb-32">
        <div class="max-w-7xl mx-auto">
            <div class="rounded-3xl px-8 py-16 md:px-16 md:py-24 lg:py-28 relative overflow-hidden bg-carbone fade-up">
                {{-- Pattern grid --}}
                <div class="absolute inset-0 opacity-10 pointer-events-none"
                     style="background-image:
                        linear-gradient(rgba(245,242,236,0.5) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(245,242,236,0.5) 1px, transparent 1px);
                        background-size: 40px 40px;"></div>

                <div class="relative max-w-3xl mx-auto text-center">
                    <div class="label-pill label-pill-on-dark mb-8 mx-auto">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--status-success)"></span>
                        <span>Disponible pour 2-3 projets par trimestre</span>
                    </div>
                    <h2 class="display text-4xl md:text-5xl lg:text-6xl text-perle mb-8 leading-[1.05]">
                        Tu veux construire une image plus claire, plus forte, plus premium ?
                    </h2>
                    <p class="text-lg leading-relaxed mb-10 max-w-xl mx-auto" style="color: rgba(245,242,236,0.7)">
                        Envoie ton brief. Neroblanka analyse chaque demande manuellement pour vérifier si le projet correspond au niveau d'exigence du studio.
                    </p>
                    <a href="/brief" class="btn-primary px-8 py-4 text-base"
                       style="background: var(--perle); color: var(--carbone); border-color: var(--perle);">
                        Demander un diagnostic créatif
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <p class="text-xs mt-6" style="color: rgba(245,242,236,0.5)">
                        Réponse sous 24–48h si le projet est compatible.
                    </p>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
