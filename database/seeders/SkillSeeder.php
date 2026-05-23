<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // 3D
            ['name' => 'Blender', 'category' => '3d'],
            ['name' => 'Cinema 4D', 'category' => '3d'],
            ['name' => 'KeyShot', 'category' => '3d'],
            ['name' => 'Unreal Engine', 'category' => '3d'],
            ['name' => 'Substance Painter', 'category' => '3d'],
            // Motion
            ['name' => 'After Effects', 'category' => 'motion'],
            ['name' => 'DaVinci Resolve', 'category' => 'motion'],
            ['name' => 'Premiere Pro', 'category' => 'motion'],
            ['name' => 'CapCut Pro', 'category' => 'motion'],
            // Branding
            ['name' => 'Illustrator', 'category' => 'branding'],
            ['name' => 'Figma', 'category' => 'branding'],
            ['name' => 'InDesign', 'category' => 'branding'],
            // Web
            ['name' => 'Laravel', 'category' => 'website'],
            ['name' => 'Next.js', 'category' => 'website'],
            ['name' => 'Tailwind CSS', 'category' => 'website'],
            ['name' => 'WordPress', 'category' => 'website'],
            // Social
            ['name' => 'Community Management', 'category' => 'social_campaign'],
            ['name' => 'Copywriting', 'category' => 'social_campaign'],
            ['name' => 'Meta Ads', 'category' => 'social_campaign'],
            // IA
            ['name' => 'Midjourney', 'category' => 'ai_image_video'],
            ['name' => 'Stable Diffusion', 'category' => 'ai_image_video'],
            ['name' => 'Sora / Runway', 'category' => 'ai_image_video'],
            ['name' => 'Make / n8n', 'category' => 'automation'],
            ['name' => 'Prompt Engineering', 'category' => 'ai_image_video'],
        ];

        foreach ($skills as $data) {
            Skill::firstOrCreate(
                ['slug' => Str::slug($data['name'])],
                ['name' => $data['name'], 'category' => $data['category']],
            );
        }
    }
}
