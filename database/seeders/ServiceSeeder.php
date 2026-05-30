<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Catalogue de services affichables. La source de vérité métier reste
     * l'enum App\Enums\ServiceType ; cette table porte le contenu vitrine
     * (prix indicatif, délai, description). Idempotent : re-runnable sans doublon.
     *
     * price_da = prix indicatif « à partir de » en dinars algériens.
     * À ajuster côté admin (/admin/services).
     */
    public function run(): void
    {
        $services = [
            [
                'type'          => 'branding',
                'name'          => 'Branding & identité visuelle',
                'description'   => "Logo, charte graphique, système de marque complet. Une identité cohérente, du contraste qui fait sens.",
                'price_da'      => 120000,
                'delivery_days' => 21,
            ],
            [
                'type'          => 'event_stand_3d',
                'name'          => 'Stand 3D événementiel',
                'description'   => "Conception et rendu 3D de stands pour salons et événements. Espace immersif pensé pour votre marque.",
                'price_da'      => 250000,
                'delivery_days' => 30,
            ],
            [
                'type'          => 'product_rendering_3d',
                'name'          => 'Rendu produit 3D',
                'description'   => "Visuels photoréalistes de vos produits pour le web, le print et la publicité. Plusieurs angles, sans shooting photo.",
                'price_da'      => 90000,
                'delivery_days' => 14,
            ],
            [
                'type'          => 'motion_design',
                'name'          => 'Motion design',
                'description'   => "Vidéos animées pour réseaux sociaux, pub et présentations. Du storytelling en mouvement.",
                'price_da'      => 110000,
                'delivery_days' => 18,
            ],
            [
                'type'          => 'social_campaign',
                'name'          => 'Campagne social media',
                'description'   => "Direction artistique et déclinaison de posts multi-plateformes. Une présence visuelle qui convertit.",
                'price_da'      => 80000,
                'delivery_days' => 14,
            ],
            [
                'type'          => 'website',
                'name'          => 'Site web',
                'description'   => "Vitrine, portfolio ou landing sur mesure. Rapide, responsive, pensé pour le référencement et la conversion.",
                'price_da'      => 180000,
                'delivery_days' => 28,
            ],
            [
                'type'          => 'ai_image_video',
                'name'          => 'Image & vidéo IA',
                'description'   => "Production de visuels et vidéos générés par IA, dirigés artistiquement. Volume et créativité au service de votre image.",
                'price_da'      => 95000,
                'delivery_days' => 12,
            ],
            [
                'type'          => 'automation',
                'name'          => 'Automatisation',
                'description'   => "Automatisation de vos workflows (Notion, Airtable, Make, n8n…). Gagnez du temps sur les tâches répétitives.",
                'price_da'      => 130000,
                'delivery_days' => 21,
            ],
            [
                'type'          => 'mixed_project',
                'name'          => 'Projet mixte',
                'description'   => "Combinaison de plusieurs services pour un projet sur mesure. Devis adapté à votre besoin précis.",
                'price_da'      => 200000,
                'delivery_days' => 30,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                array_merge($service, ['is_active' => true]),
            );
        }
    }
}
