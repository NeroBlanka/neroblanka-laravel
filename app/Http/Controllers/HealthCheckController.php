<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * /healthz/deep — diagnostic des dépendances externes.
 *
 * Protégé par HEALTH_CHECK_TOKEN (query string ?token=…) pour éviter
 * que la liste des services casssés soit publique. Si HEALTH_CHECK_TOKEN
 * n'est pas défini, l'endpoint renvoie 403 (fail-closed).
 *
 * À utiliser depuis curl/Postman/monitoring externe, PAS pour le healthcheck
 * Railway (qui reste sur /up minimaliste 204).
 */
class HealthCheckController extends Controller
{
    public function deep(Request $request): JsonResponse
    {
        $expected = env('HEALTH_CHECK_TOKEN');

        if (! $expected || ! hash_equals($expected, (string) $request->query('token', ''))) {
            abort(403);
        }

        $checks = [
            'db'          => $this->checkDb(),
            'r2_private'  => $this->checkS3Disk('s3', 'AWS_ACCESS_KEY_ID/SECRET/ENDPOINT/BUCKET'),
            'r2_public'   => $this->checkS3Disk('s3_public', 'AWS_PUBLIC_*'),
            'resend'      => $this->checkResend(),
            'sentry'      => $this->checkSentry(),
        ];

        // Services qui crashent réellement la prod si KO : DB, R2 privé, Resend.
        $criticalOk = $checks['db']['status'] === 'ok'
            && $checks['r2_private']['status'] === 'ok'
            && $checks['resend']['status'] === 'ok';

        return response()->json([
            'overall'    => $criticalOk ? 'healthy' : 'degraded',
            'checks'     => $checks,
            'checked_at' => now()->toIso8601String(),
        ], $criticalOk ? 200 : 503);
    }

    private function checkDb(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok'];
        } catch (Throwable $e) {
            return ['status' => 'fail', 'reason' => $this->safeReason($e)];
        }
    }

    /**
     * Test connectivité d'un disque S3-compatible (R2) sans rien écrire :
     * un listContents() sur la racine déclenche un SignedRequest qui valide
     * key/secret/endpoint/bucket. Si une de ces 4 est vide → exception lisible.
     */
    private function checkS3Disk(string $diskName, string $envHint): array
    {
        try {
            $disk = Storage::disk($diskName);
            // Itère seulement le 1er élément pour ne pas charger tout le bucket.
            $iter = $disk->files();
            // Force l'évaluation lazy
            count($iter);
            return ['status' => 'ok'];
        } catch (Throwable $e) {
            return [
                'status' => 'fail',
                'reason' => "vérifier {$envHint}",
                'error'  => $this->safeReason($e),
            ];
        }
    }

    private function checkResend(): array
    {
        $key = env('RESEND_KEY');
        if (! $key) {
            return ['status' => 'fail', 'reason' => 'RESEND_KEY vide'];
        }
        // Pas de vrai send côté health check (coût + risque rate-limit). Juste
        // format check : Resend tokens commencent par "re_".
        if (! str_starts_with($key, 're_')) {
            return ['status' => 'warn', 'reason' => 'RESEND_KEY ne commence pas par "re_" — format suspect'];
        }
        return ['status' => 'ok', 'note' => 'token format valide (envoi réel non testé)'];
    }

    private function checkSentry(): array
    {
        $dsn = env('SENTRY_LARAVEL_DSN');
        return $dsn
            ? ['status' => 'ok', 'note' => 'DSN configuré']
            : ['status' => 'warn', 'reason' => 'SENTRY_LARAVEL_DSN vide — monitoring désactivé (handler câblé mais no-op)'];
    }

    /** Tronque + nettoie les messages d'erreur (éviter info disclosure sur stack). */
    private function safeReason(Throwable $e): string
    {
        return mb_substr(preg_replace('/\s+/', ' ', $e->getMessage()), 0, 200);
    }
}
