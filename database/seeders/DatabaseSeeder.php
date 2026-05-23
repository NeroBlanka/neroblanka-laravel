<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\SkillSeeder;
use Database\Seeders\PortfolioSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'contact@neroblanka.com');
        $adminPassword = env('ADMIN_PASSWORD');

        if ($adminPassword) {
            User::withoutGlobalScopes()->firstOrCreate(['email' => $adminEmail], [
                'full_name' => 'Nadir Allek',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]);
        }

        if (app()->isLocal()) {
            User::firstOrCreate(['email' => 'client1@test.com'], [
                'full_name' => 'Client Test 1',
                'password' => Hash::make('password'),
                'role' => 'client',
                'company' => 'Entreprise Alpha',
            ]);

            User::firstOrCreate(['email' => 'client2@test.com'], [
                'full_name' => 'Client Test 2',
                'password' => Hash::make('password'),
                'role' => 'client',
                'company' => 'Startup Beta',
            ]);

            User::firstOrCreate(['email' => 'freelance@test.com'], [
                'full_name' => 'Freelance Test',
                'password' => Hash::make('password'),
                'role' => 'freelance',
                'specialties' => ['Branding', 'Motion Design', '3D'],
                'is_available' => true,
            ]);
        }

        Service::firstOrCreate(['type' => 'identite_visuelle'], [
            'name' => 'Identité Visuelle',
            'description' => 'Logo, charte graphique, système visuel complet.',
            'price_da' => 150000,
            'delivery_days' => 21,
            'is_active' => true,
        ]);

        Service::firstOrCreate(['type' => 'direction_3d_ia'], [
            'name' => 'Direction 3D & IA',
            'description' => 'Visuels 3D, direction artistique IA, rendus produits.',
            'price_da' => 200000,
            'delivery_days' => 14,
            'is_active' => true,
        ]);

        Service::firstOrCreate(['type' => 'contenu_mensuel'], [
            'name' => 'Contenu Mensuel',
            'description' => 'Pack contenu mensuel réseaux sociaux — posts, stories, reels.',
            'price_da' => 80000,
            'delivery_days' => 30,
            'is_active' => true,
        ]);

        $this->call([
            SkillSeeder::class,
            PortfolioSeeder::class,
        ]);
    }
}
