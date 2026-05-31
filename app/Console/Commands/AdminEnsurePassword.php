<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Idempotent : si l'admin a encore le mot de passe par défaut
 * (`changeme_before_deploy`), met à jour son hash vers la valeur courante
 * d'ADMIN_PASSWORD. Sinon no-op — on ne réécrase JAMAIS un mot de passe
 * légitime que l'admin aurait changé via /password.
 *
 * Conçu pour tourner au boot via start.sh sans risque de régression.
 */
class AdminEnsurePassword extends Command
{
    protected $signature = 'admin:ensure-password';
    protected $description = 'Met à jour le mdp admin SI il est encore le défaut "changeme_before_deploy"';

    public function handle(): int
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email) {
            $this->warn('ADMIN_EMAIL vide — skip.');
            return self::SUCCESS;
        }
        if (! $password || $password === 'changeme_before_deploy') {
            $this->warn('ADMIN_PASSWORD vide ou trivial — skip.');
            return self::SUCCESS;
        }

        // On vérifie d'abord s'il existe N'IMPORTE QUEL user avec ADMIN_EMAIL
        // (pour éviter UNIQUE constraint si un client a usurpé l'email).
        $anyUserWithEmail = User::withoutGlobalScopes()->where('email', $email)->first();

        if ($anyUserWithEmail && $anyUserWithEmail->role !== 'admin') {
            $this->warn("User à {$email} existe mais role={$anyUserWithEmail->role} (pas admin) — skip pour éviter conflit.");
            return self::SUCCESS;
        }

        $admin = $anyUserWithEmail; // null ou role=admin

        // Cas 1 : admin absent → CREATE avec ADMIN_PASSWORD courant.
        // DatabaseSeeder fait pareil, mais n'est jamais auto-run en prod.
        if (! $admin) {
            User::withoutGlobalScopes()->create([
                'email'     => $email,
                'full_name' => env('ADMIN_FULL_NAME', 'Nadir Allek'),
                'password'  => Hash::make($password),
                'role'      => 'admin',
            ]);
            $this->info("✅ Admin {$email} créé en DB avec ADMIN_PASSWORD env value.");
            return self::SUCCESS;
        }

        // Le seul cas où on update : password hash matche encore 'changeme_before_deploy'
        if (Hash::check('changeme_before_deploy', $admin->password)) {
            $admin->password = Hash::make($password);
            $admin->save();
            $this->info("✅ Admin {$email} : password updated from default to ADMIN_PASSWORD env value.");
        } else {
            $this->info("Admin {$email} : password déjà personnalisé — pas touché.");
        }

        return self::SUCCESS;
    }
}
