<?php

namespace Database\Seeders;

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

        $this->call([
            ServiceSeeder::class,
            SkillSeeder::class,
            PortfolioSeeder::class,
        ]);
    }
}
