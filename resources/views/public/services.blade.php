<x-public-layout title="Services — Neroblanka" description="Branding, 3D, motion design, web, IA — 7 pôles d'expertise créative à Alger.">
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">

            <p class="label-mono mb-6 fade-in">Expertise</p>
            <h1 class="text-4xl md:text-5xl font-semibold text-white mb-4 fade-up" style="font-family: 'Clash Grotesk', sans-serif;">
                7 pôles de création
            </h1>
            <p class="text-[#888780] text-base mb-16 max-w-xl fade-up" style="transition-delay: 60ms;">
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

            <div class="divide-y divide-white/[0.06] fade-up stagger" x-data="{ open: null }">
                @foreach(\App\Enums\ServiceType::cases() as $i => $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        @php $desc = $descriptions[$service->value] ?? ['short' => '', 'long' => '']; @endphp

                        <div class="py-1">
                            <button
                                @click="open = open === {{ $i }} ? null : {{ $i }}"
                                class="w-full flex items-center gap-6 py-7 text-left group"
                                :aria-expanded="open === {{ $i }}">

                                {{-- Icon --}}
                                <span class="text-xl text-[#555350] group-hover:text-[#888780] transition-colors w-8 shrink-0 text-center"
                                      aria-hidden="true">{{ $service->icon() }}</span>

                                {{-- Label + short desc --}}
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-white text-base md:text-lg font-semibold group-hover:text-[#e8e7e2] transition-colors"
                                        style="font-family: 'Clash Grotesk', sans-serif;">
                                        {{ $service->label() }}
                                    </h2>
                                    <p class="text-[#555350] text-sm mt-0.5 group-hover:text-[#888780] transition-colors">
                                        {{ $desc['short'] }}
                                    </p>
                                </div>

                                {{-- Toggle indicator --}}
                                <span class="shrink-0 text-[#555350] group-hover:text-white transition-all duration-300 text-lg"
                                      :class="open === {{ $i }} ? 'rotate-45 text-white' : ''">+</span>
                            </button>

                            {{-- Expand panel --}}
                            <div
                                x-show="open === {{ $i }}"
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="pb-8 pl-14">

                                <p class="text-[#888780] text-sm leading-relaxed max-w-2xl mb-6">
                                    {{ $desc['long'] }}
                                </p>

                                <a href="/brief"
                                   class="inline-flex items-center gap-2 text-xs font-mono tracking-widest uppercase text-white border border-white/20 px-5 py-3 rounded-sm hover:bg-white hover:text-[#0a0a0a] transition-all duration-200">
                                    Démarrer un projet →
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Bottom CTA --}}
            <div class="mt-20 border border-white/[0.06] rounded-sm p-10 md:p-14 text-center fade-up">
                <p class="label-mono mb-4">Votre besoin ne rentre pas dans une case ?</p>
                <h2 class="text-2xl md:text-3xl font-semibold text-white mb-6" style="font-family: 'Clash Grotesk', sans-serif;">
                    Parlez-nous de votre projet.
                </h2>
                <a href="/brief" class="btn-primary px-7 py-4">
                    Démarrer le diagnostic créatif
                </a>
            </div>

        </div>
    </div>
</x-public-layout>
