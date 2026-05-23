<?php

namespace App\Enums;

enum ServiceType: string
{
    case BRANDING = 'branding';
    case EVENT_STAND_3D = 'event_stand_3d';
    case PRODUCT_RENDERING_3D = 'product_rendering_3d';
    case MOTION_DESIGN = 'motion_design';
    case SOCIAL_CAMPAIGN = 'social_campaign';
    case WEBSITE = 'website';
    case AI_IMAGE_VIDEO = 'ai_image_video';
    case AUTOMATION = 'automation';
    case MIXED_PROJECT = 'mixed_project';

    public function label(): string
    {
        return match($this) {
            self::BRANDING => 'Identité Visuelle Premium',
            self::EVENT_STAND_3D => '3D Event & Stand Design',
            self::PRODUCT_RENDERING_3D => '3D Product Studio',
            self::MOTION_DESIGN => 'Motion Design',
            self::SOCIAL_CAMPAIGN => 'Campagnes Réseaux Sociaux',
            self::WEBSITE => 'Site Web',
            self::AI_IMAGE_VIDEO => 'IA & Image / Vidéo',
            self::AUTOMATION => 'Automation',
            self::MIXED_PROJECT => 'Projet Mixte',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::BRANDING => '◆',
            self::EVENT_STAND_3D => '⬡',
            self::PRODUCT_RENDERING_3D => '◉',
            self::MOTION_DESIGN => '▷',
            self::SOCIAL_CAMPAIGN => '◈',
            self::WEBSITE => '⬜',
            self::AI_IMAGE_VIDEO => '◌',
            self::AUTOMATION => '⟳',
            self::MIXED_PROJECT => '⊕',
        };
    }
}
