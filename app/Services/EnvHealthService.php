<?php

namespace App\Services;

/**
 * Détecte les variables d'environnement critiques manquantes au runtime
 * (R2, mailer, password admin par défaut, etc.) pour les surfacer dans
 * le portail admin via banner.
 *
 * Pas un health-check réseau (pour ça → /healthz/deep). Juste un check
 * presence/valeur évidente, suffisamment léger pour tourner à chaque vue admin.
 */
class EnvHealthService
{
    /**
     * @return array<int,array{key:string,level:string,message:string}>
     */
    public function warnings(): array
    {
        $warnings = [];

        // R2 privé — chemin upload brief + livrables
        foreach (['AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'AWS_ENDPOINT', 'AWS_BUCKET'] as $k) {
            if (! env($k)) {
                $warnings[] = [
                    'key'     => $k,
                    'level'   => 'critical',
                    'message' => "{$k} vide — uploads R2 cassés (briefs + livrables).",
                ];
            }
        }

        // Mailer Resend — emails transactionnels
        if (config('mail.default') === 'resend' && ! env('RESEND_KEY')) {
            $warnings[] = [
                'key'     => 'RESEND_KEY',
                'level'   => 'critical',
                'message' => 'RESEND_KEY vide alors que MAIL_MAILER=resend — tous les emails échouent.',
            ];
        }

        // Mot de passe admin par défaut
        if (env('ADMIN_PASSWORD') === 'changeme_before_deploy') {
            $warnings[] = [
                'key'     => 'ADMIN_PASSWORD',
                'level'   => 'critical',
                'message' => "ADMIN_PASSWORD est encore 'changeme_before_deploy' — change-le tout de suite.",
            ];
        }

        // Sentry — monitoring (warning, pas critical)
        if (! env('SENTRY_LARAVEL_DSN')) {
            $warnings[] = [
                'key'     => 'SENTRY_LARAVEL_DSN',
                'level'   => 'info',
                'message' => 'SENTRY_LARAVEL_DSN vide — pannes en prod ne remontent à aucune alerte externe.',
            ];
        }

        return $warnings;
    }

    public function hasCritical(): bool
    {
        foreach ($this->warnings() as $w) {
            if ($w['level'] === 'critical') {
                return true;
            }
        }
        return false;
    }
}
