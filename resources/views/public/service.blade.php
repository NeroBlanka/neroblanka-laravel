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
    <section class="pt-32 pb-24 text-center">
        <h1 class="font-clash text-4xl font-semibold" style="color: var(--perle)">404</h1>
        <p class="mt-4" style="color: var(--gris)">Ce service n'existe pas.</p>
        <a href="/services" class="mt-8 inline-block text-sm transition-opacity hover:opacity-60" style="color: var(--purple)">← Voir tous les services</a>
    </section>
@else
    <x-slot:title>{{ $service['title'] }} — Neroblanka</x-slot:title>
    <x-slot:description>{{ $service['description'] }}</x-slot:description>

    {{-- Hero --}}
    <section class="pt-32 pb-20" style="border-bottom: 1px solid rgba(255,255,255,0.06)">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-base"
                     style="background: rgba(124,92,252,0.1); border: 1px solid rgba(124,92,252,0.2)">
                    {{ $service['enum']->icon() }}
                </div>
                <span class="label-mono" style="color: var(--purple)">{{ $service['enum']->label() }}</span>
            </div>
            <h1 class="font-clash text-5xl md:text-6xl font-semibold leading-tight mb-6" style="color: var(--perle)">
                {{ $service['tagline'] }}
            </h1>
            <p class="text-lg max-w-2xl leading-relaxed" style="color: var(--gris)">
                {{ $service['description'] }}
            </p>
            <div class="flex flex-wrap gap-4 mt-10">
                <a href="/brief?service={{ $slug }}" class="btn-primary px-6 py-3">
                    Demander un diagnostic
                </a>
                <a href="/services" class="btn-secondary px-6 py-3">
                    Tous les services
                </a>
            </div>
        </div>
    </section>

    {{-- Détails --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6 grid md:grid-cols-3 gap-12">

            <div class="md:col-span-2">
                <h2 class="label-mono mb-6" style="color: var(--gris)">Ce que vous recevez</h2>
                <ul class="space-y-3">
                    @foreach($service['deliverables'] as $item)
                        <li class="flex items-start gap-3" style="color: var(--perle)">
                            <span class="mt-1 text-xs shrink-0" style="color: var(--purple)">◆</span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <h2 class="label-mono mt-12 mb-6" style="color: var(--gris)">Fait pour vous si</h2>
                <ul class="space-y-2">
                    @foreach($service['for'] as $who)
                        <li class="text-sm flex items-center gap-2" style="color: var(--gris)">
                            <span class="w-1 h-1 rounded-full inline-block shrink-0" style="background: var(--gris-mid)"></span>
                            {{ $who }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                <div class="card p-6">
                    <p class="label-mono mb-1" style="color: var(--gris-mid)">Délai typique</p>
                    <p class="font-medium" style="color: var(--perle)">{{ $service['timeline'] }}</p>
                </div>
                <div class="card p-6">
                    <p class="label-mono mb-1" style="color: var(--gris-mid)">À partir de</p>
                    <p class="font-semibold text-xl" style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent">
                        {{ $service['starting'] }}
                    </p>
                </div>
                <a href="/brief?service={{ $slug }}" class="btn-primary block w-full text-center px-5 py-3">
                    Démarrer ce projet →
                </a>
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6" style="border-top: 1px solid rgba(255,255,255,0.06)">
        <div class="max-w-4xl mx-auto">
            <div class="rounded-2xl p-12 md:p-16 text-center relative overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(124,92,252,0.12) 0%, rgba(240,89,218,0.06) 100%);
                        border: 1px solid rgba(124,92,252,0.2)">
                <div class="absolute inset-0 opacity-20 pointer-events-none"
                     style="background: radial-gradient(circle at 80% 50%, rgba(240,89,218,0.3) 0%, transparent 60%)"></div>
                <div class="relative">
                    <p class="label-mono mb-4" style="color: var(--purple)">Prochaine étape</p>
                    <h2 class="font-clash text-3xl md:text-4xl font-semibold mb-6" style="color: var(--perle)">
                        Du contraste naît la clarté.
                    </h2>
                    <p class="max-w-lg mx-auto mb-8" style="color: var(--gris)">
                        Partagez votre projet en 3 minutes. Nous revenons vers vous sous 48h avec une vision claire et un plan d'action.
                    </p>
                    <a href="/brief?service={{ $slug }}" class="btn-primary px-8 py-4">
                        Demander un diagnostic créatif
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif
</x-public-layout>
