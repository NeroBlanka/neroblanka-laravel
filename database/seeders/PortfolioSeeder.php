<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Tadjine & Co — Identité premium pour une épicerie fine',
                'slug' => 'tadjine-co-identite',
                'client_name' => 'Tadjine & Co',
                'service_type' => 'branding',
                'excerpt' => 'Refonte complète de l\'identité visuelle d\'une épicerie fine algérienne visant le marché export. Logo, packaging, brand book.',
                'content' => 'Tadjine & Co souhaitait se repositionner sur le segment premium pour attaquer les marchés européens. Nous avons créé une identité visuelle qui marie le patrimoine artisanal algérien à une esthétique contemporaine minimaliste.',
                'tags' => ['branding', 'packaging', 'export'],
                'featured' => true,
                'published_at' => '2025-01-15',
            ],
            [
                'title' => 'AlgerTech — Stand 3D pour le Salon Africain du Digital',
                'slug' => 'algertech-stand-3d',
                'client_name' => 'AlgerTech',
                'service_type' => 'event_stand_3d',
                'excerpt' => 'Conception et visualisation 3D d\'un stand de 120m² pour le plus grand salon tech d\'Afrique du Nord. Livré en 3 semaines.',
                'content' => 'AlgerTech avait besoin d\'un stand imposant pour son premier Salon Africain du Digital. Nous avons produit une visualisation photoréaliste complète avec plans techniques, permettant une fabrication sans aller-retour.',
                'tags' => ['3d', 'event', 'stand'],
                'featured' => true,
                'published_at' => '2025-02-01',
            ],
            [
                'title' => 'Naturella — Pack shots 3D pour lancement e-commerce',
                'slug' => 'naturella-pack-shots',
                'client_name' => 'Naturella Cosmétiques',
                'service_type' => 'product_rendering_3d',
                'excerpt' => '48 visuels produit 3D haute définition pour une ligne de 6 cosmétiques naturels. Déclinaisons couleur et mises en scène lifestyle.',
                'content' => 'Naturella lançait sa boutique en ligne et avait besoin de visuels produit irréprochables sans budget photographie. Nous avons modélisé les 6 références et produit 48 visuels exploitables immédiatement.',
                'tags' => ['3d', 'product', 'e-commerce'],
                'featured' => false,
                'published_at' => '2025-02-20',
            ],
            [
                'title' => 'Dzair Invest — Motion brand pour fintech',
                'slug' => 'dzair-invest-motion',
                'client_name' => 'Dzair Invest',
                'service_type' => 'motion_design',
                'excerpt' => 'Animation complète de l\'identité — logo reveal, explainer vidéo 90 secondes, reels sociaux. Pour une fintech algérienne en levée de fonds.',
                'content' => 'Dzair Invest préparait sa levée de fonds Serie A et avait besoin de supports vidéo pour convaincre les investisseurs. Nous avons produit un kit motion complet en 4 semaines.',
                'tags' => ['motion', 'fintech', 'brand'],
                'featured' => true,
                'published_at' => '2025-03-05',
            ],
            [
                'title' => 'Maison Satin — Social content pour prêt-à-porter premium',
                'slug' => 'maison-satin-social',
                'client_name' => 'Maison Satin',
                'service_type' => 'social_campaign',
                'excerpt' => '6 mois de contenu éditorial et gestion Meta Ads pour une marque de mode algérienne. +340% d\'engagement, ROAS 4.2x.',
                'content' => 'Maison Satin avait une identité forte mais une présence digitale inexistante. Stratégie éditoriale, production photo & vidéo, gestion publicitaire — nous avons construit leur communauté de zéro.',
                'tags' => ['social', 'mode', 'ads'],
                'featured' => false,
                'published_at' => '2025-03-20',
            ],
            [
                'title' => 'Atlas Legal — Plateforme web pour cabinet d\'avocats',
                'slug' => 'atlas-legal-web',
                'client_name' => 'Atlas Legal',
                'service_type' => 'website',
                'excerpt' => 'Site vitrine premium + espace client sécurisé pour un cabinet d\'avocats d\'affaires. Laravel, design sur mesure, temps de chargement < 1s.',
                'content' => 'Atlas Legal voulait une présence digitale à la hauteur de leur réputation. Nous avons développé un site vitrine sobre et puissant avec un espace client pour le suivi des dossiers.',
                'tags' => ['web', 'legal', 'laravel'],
                'featured' => false,
                'published_at' => '2025-04-01',
            ],
        ];

        foreach ($items as $data) {
            PortfolioItem::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
