<x-public-layout title="Services — Neroblanka" description="Branding, 3D, motion design, web, IA — 7 pôles d'expertise créative à Alger.">
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">

            <div class="inline-block mb-4 px-3 py-1 rounded-full text-xs font-mono tracking-wider fade-in"
                 style="border: 1px solid rgba(255,255,255,0.12); color: var(--gris)">Expertise</div>
            <h1 class="font-clash text-4xl md:text-5xl font-semibold mb-4 fade-up" style="color: var(--perle)">
                7 pôles de création
            </h1>
            <p class="text-base mb-16 max-w-xl fade-up" style="color: var(--gris); transition-delay: 60ms;">
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

            <div class="fade-up stagger" x-data="{ open: null }" style="border-top: 1px solid rgba(255,255,255,0.06)">
                @foreach(\App\Enums\ServiceType::cases() as $i => $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        @php $desc = $descriptions[$service->value] ?? ['short' => '', 'long' => '']; @endphp

                        <div style="border-bottom: 1px solid rgba(255,255,255,0.06)">
                            <button
                                @click="open = open === {{ $i }} ? null : {{ $i }}"
                                class="w-full flex items-center gap-6 py-7 text-left group cursor-pointer"
                                :aria-expanded="open === {{ $i }}">

                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-base shrink-0 transition-all duration-300"
                                     :style="open === {{ $i }}
                                         ? 'background: rgba(124,92,252,0.15); border: 1px solid rgba(124,92,252,0.3)'
                                         : 'background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08)'">
                                    {{ $service->icon() }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h2 class="text-base md:text-lg font-semibold transition-colors" style="color: var(--perle)">
                                        {{ $service->label() }}
                                    </h2>
                                    <p class="text-sm mt-0.5 transition-colors" style="color: var(--gris-mid)">
                                        {{ $desc['short'] }}
                                    </p>
                                </div>

                                <span class="shrink-0 text-lg font-light transition-all duration-300"
                                      style="color: var(--gris)"
                                      :class="open === {{ $i }} ? 'rotate-45' : ''"
                                      :style="open === {{ $i }} ? 'color: var(--purple)' : 'color: var(--gris)'">+</span>
                            </button>

                            <div
                                x-show="open === {{ $i }}"
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="pb-8 pl-16">

                                <p class="text-sm leading-relaxed max-w-2xl mb-6" style="color: var(--gris)">
                                    {{ $desc['long'] }}
                                </p>

                                <a href="{{ route('brief') }}?service={{ $service->slug() }}" class="btn-primary text-sm px-5 py-3 inline-flex items-center gap-2">
                                    Démarrer un projet →
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Bottom CTA --}}
            <div class="mt-20 rounded-2xl p-10 md:p-14 text-center fade-up relative overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(124,92,252,0.12) 0%, rgba(240,89,218,0.06) 100%);
                        border: 1px solid rgba(124,92,252,0.2)">
                <div class="absolute inset-0 opacity-20 pointer-events-none"
                     style="background: radial-gradient(circle at 70% 50%, rgba(240,89,218,0.3) 0%, transparent 60%)"></div>
                <div class="relative">
                    <p class="label-mono mb-4" style="color: var(--purple)">Votre besoin ne rentre pas dans une case ?</p>
                    <h2 class="font-clash text-2xl md:text-3xl font-semibold mb-6" style="color: var(--perle)">
                        Parlez-nous de votre projet.
                    </h2>
                    <a href="/brief" class="btn-primary px-7 py-4">
                        Démarrer le diagnostic créatif
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
