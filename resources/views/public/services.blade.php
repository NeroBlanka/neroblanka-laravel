<x-public-layout title="Services — Neroblanka" description="Branding, 3D, motion design, web, IA — 8 pôles d'expertise créative à Blida.">
    <div class="pt-24 md:pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">

            <p class="label-mono mb-5" data-reveal>Expertise</p>
            <h1 class="display text-4xl md:text-6xl mb-6" data-reveal>
                8 pôles de création.
            </h1>
            <p class="text-lg max-w-xl text-gris mb-16" data-reveal>
                Du concept à la production, un accompagnement complet pour les marques qui ont des ambitions.
            </p>

            @php
            $descriptions = [
                'branding' => [
                    'short' => 'Identités visuelles complètes et cohérentes.',
                    'long'  => 'Logo, charte graphique, typographies, couleurs, patterns et déclinaisons — tout le système de marque pensé pour durer et s\'imposer dans son secteur. Du brief stratégique au livrable final, aucun compromis sur la qualité.',
                ],
                'event_stand_3d' => [
                    'short' => 'Scénographies 3D pour salons et événements.',
                    'long'  => 'Conception et visualisation 3D de stands, espaces d\'exposition et décors événementiels. Des rendus photo-réalistes qui permettent de valider chaque détail avant fabrication, et de pitcher efficacement vos espaces aux décideurs.',
                ],
                'product_rendering_3d' => [
                    'short' => 'Visuels produit 3D photo-réalistes.',
                    'long'  => 'Packshots, rendus studio, mises en ambiance et animatiques. Un rendu 3D de qualité remplace des séances photo coûteuses et offre une flexibilité totale sur les angles, les matériaux et les décors.',
                ],
                'motion_design' => [
                    'short' => 'Animation, vidéo et contenu dynamique.',
                    'long'  => 'Génériques, vidéos explicatives, teasers produit, animations de marque et motion graphics pour les réseaux et les présentations. Du storytelling visuel qui retient l\'attention dans un flux saturé d\'images statiques.',
                ],
                'social_campaign' => [
                    'short' => 'Contenus visuels pour les réseaux sociaux.',
                    'long'  => 'Stratégie visuelle, templates de campagne, visuels de post, stories et reels. Des contenus pensés pour la plateforme, cohérents avec l\'identité de marque et conçus pour performer — pas juste pour exister.',
                ],
                'website' => [
                    'short' => 'Sites vitrines et landing pages sur mesure.',
                    'long'  => 'Design et développement de sites web — portfolios, vitrines institutionnelles, landing pages de campagne. Des expériences numériques rapides, accessibles et pensées pour convertir.',
                ],
                'ai_image_video' => [
                    'short' => 'IA créative intégrée au workflow.',
                    'long'  => 'Génération d\'images et de vidéos par IA, intégrée intelligemment dans le pipeline créatif. Pas de remplacement de la création humaine, mais une amplification — pour produire plus vite sans sacrifier la singularité.',
                ],
                'automation' => [
                    'short' => 'Automatisation de processus créatifs.',
                    'long'  => 'Scripts, workflows et systèmes qui automatisent les tâches répétitives — reporting, déclinaisons de formats, exports multi-supports. Gagnez du temps pour le travail qui compte vraiment.',
                ],
            ];
            @endphp

            <div data-reveal x-data="{ open: 0 }" style="border-top: 1px solid var(--gris-bord-soft)">
                @foreach(\App\Enums\ServiceType::cases() as $i => $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        @php $desc = $descriptions[$service->value] ?? ['short' => '', 'long' => '']; @endphp

                        <div style="border-bottom: 1px solid var(--gris-bord-soft)">
                            <button
                                @click="open = open === {{ $i }} ? null : {{ $i }}"
                                class="w-full flex items-center gap-6 py-7 text-left group cursor-pointer"
                                :aria-expanded="open === {{ $i }}">

                                <span class="font-clash text-lg font-semibold w-8 shrink-0 transition-colors"
                                      :style="open === {{ $i }} ? 'color: var(--carbone)' : 'color: var(--gris-texte-soft)'">
                                    0{{ $i + 1 }}
                                </span>

                                <div class="flex-1 min-w-0">
                                    <h2 class="font-clash text-lg md:text-2xl font-semibold text-carbone">
                                        {{ $service->label() }}
                                    </h2>
                                    <p class="text-sm mt-1 text-gris">{{ $desc['short'] }}</p>
                                </div>

                                <span class="shrink-0 text-2xl font-light text-carbone transition-transform duration-300"
                                      :class="open === {{ $i }} ? 'rotate-45' : ''">+</span>
                            </button>

                            <div
                                x-show="open === {{ $i }}"
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="pb-8 pl-14 md:pl-14">

                                <p class="text-base leading-relaxed max-w-2xl mb-6 text-gris">
                                    {{ $desc['long'] }}
                                </p>

                                <a href="{{ route('brief') }}?service={{ $service->slug() }}" class="btn-primary text-sm px-5 py-3" data-magnetic="0.2">
                                    Démarrer un projet
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Bottom CTA --}}
            <div class="mt-20 rounded-3xl p-10 md:p-16 text-center relative overflow-hidden bg-carbone" data-reveal>
                <div class="absolute inset-0 opacity-10 pointer-events-none"
                     style="background-image: linear-gradient(rgba(245,242,236,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(245,242,236,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="relative">
                    <p class="label-mono mb-4" style="color: rgba(245,242,236,0.5)">Votre besoin ne rentre pas dans une case ?</p>
                    <h2 class="display text-3xl md:text-4xl text-perle mb-8">
                        Parlez-nous de votre projet.
                    </h2>
                    <a href="/brief" class="btn-primary px-7 py-4" style="background: var(--perle); color: var(--carbone); border-color: var(--perle);" data-magnetic="0.3">
                        Demander un diagnostic créatif
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
