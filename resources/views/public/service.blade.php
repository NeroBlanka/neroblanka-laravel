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
        <h1 class="text-4xl font-semibold" style="font-family:'Clash Grotesk',sans-serif;">404</h1>
        <p class="text-[#888780] mt-4">Ce service n'existe pas.</p>
        <a href="/services" class="mt-8 inline-block underline text-sm">Voir tous les services</a>
    </section>
@else
    <x-slot:title>{{ $service['title'] }} — Neroblanka</x-slot:title>
    <x-slot:description>{{ $service['description'] }}</x-slot:description>

    {{-- Hero --}}
    <section class="pt-32 pb-20 border-b border-white/[0.06]">
        <div class="max-w-4xl mx-auto px-6">
            <p class="text-[#888780] text-sm uppercase tracking-widest mb-4" style="font-family:'Clash Grotesk',sans-serif;">
                {{ $service['enum']->icon() }} {{ $service['enum']->label() }}
            </p>
            <h1 class="text-5xl md:text-6xl font-semibold text-white leading-tight mb-6" style="font-family:'Clash Grotesk',sans-serif;">
                {{ $service['tagline'] }}
            </h1>
            <p class="text-[#888780] text-lg max-w-2xl leading-relaxed">
                {{ $service['description'] }}
            </p>
            <div class="flex flex-wrap gap-4 mt-10">
                <a href="/brief"
                   class="px-6 py-3 bg-white text-[#0a0a0a] font-medium text-sm rounded-sm hover:bg-[#e8e7e2] transition-colors"
                   style="font-family:'Clash Grotesk',sans-serif;">
                    Demander un diagnostic
                </a>
                <a href="/services" class="px-6 py-3 border border-white/20 text-[#888780] text-sm rounded-sm hover:border-white/40 hover:text-white transition-colors">
                    Tous les services
                </a>
            </div>
        </div>
    </section>

    {{-- Détails --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6 grid md:grid-cols-3 gap-12">

            {{-- Livrables --}}
            <div class="md:col-span-2">
                <h2 class="text-sm uppercase tracking-widest text-[#888780] mb-6" style="font-family:'Clash Grotesk',sans-serif;">Ce que vous recevez</h2>
                <ul class="space-y-3">
                    @foreach($service['deliverables'] as $item)
                        <li class="flex items-start gap-3 text-[#e8e7e2]">
                            <span class="text-white mt-1 text-xs">◆</span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <h2 class="text-sm uppercase tracking-widest text-[#888780] mt-12 mb-6" style="font-family:'Clash Grotesk',sans-serif;">Fait pour vous si</h2>
                <ul class="space-y-2">
                    @foreach($service['for'] as $who)
                        <li class="text-[#888780] text-sm flex items-center gap-2">
                            <span class="w-1 h-1 bg-[#888780] rounded-full inline-block shrink-0"></span>
                            {{ $who }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="border border-white/[0.08] rounded p-6">
                    <p class="text-xs uppercase tracking-widest text-[#888780] mb-1">Délai typique</p>
                    <p class="text-white font-medium">{{ $service['timeline'] }}</p>
                </div>
                <div class="border border-white/[0.08] rounded p-6">
                    <p class="text-xs uppercase tracking-widest text-[#888780] mb-1">À partir de</p>
                    <p class="text-white font-medium text-xl" style="font-family:'Clash Grotesk',sans-serif;">{{ $service['starting'] }}</p>
                </div>
                <a href="/brief"
                   class="block w-full text-center px-5 py-3 bg-white text-[#0a0a0a] font-medium text-sm rounded-sm hover:bg-[#e8e7e2] transition-colors"
                   style="font-family:'Clash Grotesk',sans-serif;">
                    Démarrer ce projet →
                </a>
            </div>

        </div>
    </section>

    {{-- CTA bas de page --}}
    <section class="border-t border-white/[0.06] py-20">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <p class="text-[#888780] text-sm uppercase tracking-widest mb-4" style="font-family:'Clash Grotesk',sans-serif;">Prochaine étape</p>
            <h2 class="text-3xl md:text-4xl font-semibold text-white mb-6" style="font-family:'Clash Grotesk',sans-serif;">
                Du contraste naît la clarté.
            </h2>
            <p class="text-[#888780] max-w-lg mx-auto mb-8">
                Partagez votre projet en 3 minutes. Nous revenons vers vous sous 48h avec une vision claire et un plan d'action.
            </p>
            <a href="/brief"
               class="inline-block px-8 py-4 bg-white text-[#0a0a0a] font-medium text-sm rounded-sm hover:bg-[#e8e7e2] transition-colors"
               style="font-family:'Clash Grotesk',sans-serif;">
                Demander un diagnostic créatif
            </a>
        </div>
    </section>
@endif
</x-public-layout>
