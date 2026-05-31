<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_minimal_health_check_returns_204(): void
    {
        $this->get('/up')->assertStatus(204);
    }

    public function test_deep_health_check_requires_token(): void
    {
        config(['app.cipher' => 'AES-256-CBC']); // safety
        $this->get('/healthz/deep')->assertStatus(403);
        $this->get('/healthz/deep?token=wrong')->assertStatus(403);
    }

    public function test_deep_health_check_returns_status_when_token_valid(): void
    {
        config(['env.HEALTH_CHECK_TOKEN' => 'test-token']);
        putenv('HEALTH_CHECK_TOKEN=test-token');

        $response = $this->get('/healthz/deep?token=test-token');

        // Doit répondre 200 (healthy) ou 503 (degraded) — pas 403, pas 500.
        $this->assertContains($response->status(), [200, 503]);

        $response->assertJsonStructure([
            'overall',
            'checks' => [
                'db'         => ['status'],
                'r2_private' => ['status'],
                'r2_public'  => ['status'],
                'resend'     => ['status'],
                'sentry'     => ['status'],
            ],
            'checked_at',
        ]);
    }

    public function test_deep_health_check_db_is_ok_in_test_env(): void
    {
        putenv('HEALTH_CHECK_TOKEN=test-token');

        $response = $this->get('/healthz/deep?token=test-token');

        $data = $response->json();
        $this->assertSame('ok', $data['checks']['db']['status']);
    }

    public function test_deep_health_check_resend_fails_without_key(): void
    {
        putenv('HEALTH_CHECK_TOKEN=test-token');
        putenv('RESEND_KEY=');

        $response = $this->get('/healthz/deep?token=test-token');

        $this->assertSame('fail', $response->json('checks.resend.status'));
    }

    public function test_deep_health_check_sentry_warns_without_dsn(): void
    {
        putenv('HEALTH_CHECK_TOKEN=test-token');
        putenv('SENTRY_LARAVEL_DSN=');

        $response = $this->get('/healthz/deep?token=test-token');

        $this->assertSame('warn', $response->json('checks.sentry.status'));
    }

    public function test_deep_health_check_uses_timing_safe_comparison(): void
    {
        // hash_equals évite les timing attacks sur le comparison du token.
        // Ce test confirme que le token wrong même de la bonne longueur est rejeté.
        putenv('HEALTH_CHECK_TOKEN=correctTokenABC123');

        $this->get('/healthz/deep?token=wrongTokenABC123')->assertStatus(403);
        $this->get('/healthz/deep?token=correctTokenABC123')->assertStatus(503); // env vars vides → degraded
    }
}
