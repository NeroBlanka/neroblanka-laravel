<x-public-layout>
@php
use App\Enums\ServiceType;

$services = [
    'branding' => [
        'enum' => ServiceType::BRANDING,
        'title' => 'Identité Visuelle Premium',
        'tagline' => 'Une marque qui s\'impose.',
        'description' => 'Nous créons des identités visuelles qui survivent aux tendances. Logo, typographie, palette, brand book complet — chaque détail est pensé pour que votre marque soit immédiatement reconnaissable et mémorable.',
        'deliverables' => ['Logo primary + variantes', 'Palette chromatique + typographie', 'Brand book PDF', 'Kit réseaux sociaux', 'Mockups & applications'],
        'for' => ['Startups ambitieuses', 'PME en repositionnement', 'Marques export / diaspora'],
        'timeline' => '3 – 6 semaines',
        'starting' => '2 000$',
    ],
    'event_stand_3d' => [
        'enum' => ServiceType::EVENT_STAND_3D,
        'title' => '3D Event & Stand Design',
        'tagline' => 'Votre présence physique, designée en 3D.',
        'description' => 'Visualisation photoréaliste de stands, espaces événementiels et scénographies. Du concept au rendu final, nous livrons les fichiers prêts pour les fabricants et les comités d\'organisation.',
        'deliverables' => ['Rendu 3D photoréaliste (≥ 3 angles)', 'Plan technique coté', 'Fichiers source Blender / C4D', 'Révisions illimitées sur la phase concept'],
        'for' => ['Exposants salons professionnels', 'Agences événementielles', 'Collectivités & institutions'],
        'timeline' => '2 – 4 semaines',
        'starting' => '1 500$',
    ],
    'product_rendering_3d' => [
        'enum' => ServiceType::PRODUCT_RENDERING_3D,
        'title' => '3D Product Studio',
        'tagline' => 'Des visuels produit qui vendent.',
        'description' => 'Pack shots, mises en scène lifestyle, explosions de produit — nous produisons des visuels 3D haute fidélité pour e-commerce, packaging et campagnes publicitaires.',
        'deliverables' => ['Pack shots 360° (8–12 angles)', 'Mise en scène lifestyle', 'Format HD + web optimisé', 'Déclinaisons couleur incluses'],
        'for' => ['Marques FMCG', 'E-commerce premium', 'Fabricants & distributeurs'],
        'timeline' => '1 – 3 semaines',
        'starting' => '800$',
    ],
    'motion_design' => [
        'enum' => ServiceType::MOTION_DESIGN,
        'title' => 'Motion Design',
        'tagline' => 'Du mouvement qui communique.',
        'description' => 'Animations logo, explainers vidéo, intros broadcast, reels social — nous donnons vie à vos contenus avec un motion design qui respecte votre identité visuelle.',
        'deliverables' => ['Fichier source After Effects', 'Export MP4 + formats sociaux', 'Bande-son & sound design', 'Révisions en 2 tours'],
        'for' => ['Marques digitales', 'Médias & télévisions', 'Agences 360°'],
        'timeline' => '2 – 4 semaines',
        'starting' => '1 200$',
    ],
    'social_campaign' => [
        'enum' => ServiceType::SOCIAL_CAMPAIGN,
        'title' => 'Campagnes Réseaux Sociaux',
        'tagline' => 'Du contenu qui performe.',
        'description' => 'Stratégie éditoriale, production de contenu et gestion des campagnes publicitaires Meta & TikTok. Nous combinons créativité et data pour maximiser votre ROI social.',
        'deliverables' => ['Stratégie éditoriale mensuelle', '30 posts/mois (design + copy)', 'Setup & gestion Meta Ads', 'Reporting mensuel performance'],
        'for' => ['Marques retail', 'Startups consumer', 'Hôtels & restaurants premium'],
        'timeline' => 'Abonnement mensuel',
        'starting' => '600$ / mois',
    ],
    'website' => [
        'enum' => ServiceType::WEBSITE,
        'title' => 'Site Web',
        'tagline' => 'Un site qui travaille pour vous.',
        'description' => 'Sites vitrine, portfolios premium et plateformes e-commerce — développés sur mesure avec Laravel + Livewire ou Next.js selon votre besoin. Hébergement, SEO et performance inclus.',
        'deliverables' => ['Design UI sur mesure', 'Développement responsive', 'Panel d\'administration', 'SEO on-page + Analytics', 'Hébergement 1 an inclus'],
        'for' => ['Entreprises B2B', 'Boutiques e-commerce', 'Professions libérales premium'],
        'timeline' => '4 – 10 semaines',
        'starting' => '3 000$',
    ],
    'ai_image_video' => [
        'enum' => ServiceType::AI_IMAGE_VIDEO,
        'title' => 'IA & Image / Vidéo',
        'tagline' => 'L\'IA au service de votre créativité.',
        'description' => 'Production d\'images et vidéos génératives de haute qualité : campagnes publicitaires, contenus conceptuels, prototypes visuels rapides. Nous combinons prompting expert et post-production pour un rendu professionnel.',
        'deliverables' => ['Images IA haute résolution (upscaled)', 'Vidéos IA éditées & composées', 'Prompts documentés & réutilisables', 'Droits d\'utilisation commerciale'],
        'for' => ['Agences créatives', 'Annonceurs tech & innovation', 'Contenus R&D / prototypage'],
        'timeline' => '1 – 2 semaines',
        'starting' => '500$',
    ],
    'automation' => [
        'enum' => ServiceType::AUTOMATION,
        'title' => 'Automation',
        'tagline' => 'Automatisez. Scalez. Dormez.',
        'description' => 'Workflows automatisés avec Make, n8n et l\'API d\'OpenAI — intégration CRM, qualification de leads, reporting automatique, notifications intelligentes. Nous connectons vos outils pour éliminer les tâches répétitives.',
        'deliverables' => ['Audit des processus manuels', 'Architecture workflow', 'Développement & tests', 'Documentation & formation', 'Maintenance 3 mois incluse'],
        'for' => ['Équipes commerciales', 'E-commerce & SaaS', 'Agences & freelances'],
        'timeline' => '2 – 6 semaines',
        'starting' => '1 500$',
    ],
    'mixed_project' => [
        'enum' => ServiceType::MIXED_PROJECT,
        'title' => 'Projet Mixte',
        'tagline' => 'Un seul partenaire. Toutes les expertises.',
        'description' => 'Branding + web + motion + social — nous orchestrons des projets multi-disciplines avec une direction artistique cohérente. Un interlocuteur unique, une vision unifiée.',
        'deliverables' => ['Scoping détaillé en amont', 'Direction artistique globale', 'Coordination des expertises', 'Livraison packagée', 'Réunion de bilan incluse'],
        'for' => ['Lancements de marque', 'Rebranding complets', 'Projets ambitieux'],
        'timeline' => 'Sur devis',
        'starting' => 'Sur devis',
    ],
];

$service = $services[$slug] ?? null;
@endphp

@if(! $service)
    <x-slot:title>Service introuvable — Neroblanka</x-slot:title>
    <section class="pt-32 pb-24 text-center px-6">
        <h1 class="display text-6xl mb-4">404</h1>
        <p class="text-gris mb-8">Ce service n'existe pas.</p>
        <a href="/services" class="btn-secondary px-6 py-3 text-sm">← Voir tous les services</a>
    </section>
@else
    <x-slot:title>{{ $service['title'] }} — Neroblanka</x-slot:title>
    <x-slot:description>{{ $service['description'] }}</x-slot:description>

    {{-- Hero --}}
    <section class="pt-24 md:pt-32 pb-20 px-6 border-b" style="border-color: var(--gris-bord-soft)">
        <div class="max-w-4xl mx-auto">
            <a href="/services" class="label-mono inline-flex items-center gap-2 mb-10 hover:opacity-60 transition-opacity" data-reveal>← Services</a>
            <div class="flex items-center gap-3 mb-6" data-reveal>
                <span class="label-pill">{{ $service['enum']->label() }}</span>
            </div>
            <h1 class="display text-4xl md:text-6xl leading-tight mb-6" data-reveal>
                {{ $service['tagline'] }}
            </h1>
            <p class="text-lg max-w-2xl leading-relaxed text-gris" data-reveal>
                {{ $service['description'] }}
            </p>
            <div class="flex flex-wrap gap-3 mt-10" data-reveal>
                <a href="/brief?service={{ $slug }}" class="btn-primary px-6 py-3" data-magnetic="0.25">
                    Demander un diagnostic
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a href="/services" class="btn-secondary px-6 py-3">Tous les services</a>
            </div>
        </div>
    </section>

    {{-- Détails --}}
    <section class="py-20 px-6">
        <div class="max-w-4xl mx-auto grid md:grid-cols-3 gap-12">

            <div class="md:col-span-2" data-reveal>
                <h2 class="label-mono mb-6">Ce que vous recevez</h2>
                <ul class="space-y-3">
                    @foreach($service['deliverables'] as $item)
                        <li class="flex items-start gap-3 text-carbone">
                            <svg class="mt-1 shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <h2 class="label-mono mt-12 mb-6">Fait pour vous si</h2>
                <ul class="space-y-2">
                    @foreach($service['for'] as $who)
                        <li class="text-sm flex items-center gap-2 text-gris">
                            <span class="w-1 h-1 rounded-full inline-block shrink-0" style="background: var(--gris-texte-soft)"></span>
                            {{ $who }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4" data-reveal>
                <div class="card-on-perle rounded-2xl p-6">
                    <p class="label-mono mb-1">Délai typique</p>
                    <p class="font-medium text-carbone">{{ $service['timeline'] }}</p>
                </div>
                <div class="card-on-perle rounded-2xl p-6">
                    <p class="label-mono mb-1">À partir de</p>
                    <p class="font-clash font-semibold text-2xl text-carbone">{{ $service['starting'] }}</p>
                </div>
                <a href="/brief?service={{ $slug }}" class="btn-primary block w-full text-center px-5 py-3">
                    Démarrer ce projet
                </a>
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="px-6 pb-24">
        <div class="max-w-4xl mx-auto">
            <div class="rounded-3xl p-12 md:p-16 text-center relative overflow-hidden bg-carbone" data-reveal>
                <div class="absolute inset-0 opacity-10 pointer-events-none"
                     style="background-image: linear-gradient(rgba(245,242,236,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(245,242,236,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="relative">
                    <p class="label-mono mb-4" style="color: rgba(245,242,236,0.5)">Prochaine étape</p>
                    <h2 class="display text-3xl md:text-4xl text-perle mb-6">
                        Du contraste naît la clarté.
                    </h2>
                    <p class="max-w-lg mx-auto mb-8" style="color: rgba(245,242,236,0.7)">
                        Partagez votre projet en 3 minutes. Nous revenons vers vous sous 48h avec une vision claire et un plan d'action.
                    </p>
                    <a href="/brief?service={{ $slug }}" class="btn-primary px-8 py-4" style="background: var(--perle); color: var(--carbone); border-color: var(--perle);" data-magnetic="0.3">
                        Demander un diagnostic créatif
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif
</x-public-layout>
