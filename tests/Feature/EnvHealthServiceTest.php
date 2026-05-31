<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\EnvHealthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnvHealthServiceTest extends TestCase
{
    use RefreshDatabase;

    /** Nettoie $_ENV/$_SERVER/getenv() pour éviter de polluer les autres tests. */
    protected function tearDown(): void
    {
        foreach ([
            'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'AWS_ENDPOINT', 'AWS_BUCKET',
            'RESEND_KEY', 'ADMIN_PASSWORD', 'SENTRY_LARAVEL_DSN',
        ] as $k) {
            putenv($k);
            unset($_ENV[$k], $_SERVER[$k]);
        }
        parent::tearDown();
    }

    /**
     * Tous les tests partent d'un environnement "tout configuré" et désactivent
     * seulement les vars sous test. Sentry reste vide partout sauf si on teste
     * Sentry — son DSN est strictement validé par le SDK au boot Laravel et
     * une valeur fake comme "https://x" crashe l'app, faussant les résultats.
     */
    private function configureEnv(array $overrides = []): void
    {
        $defaults = [
            'AWS_ACCESS_KEY_ID'     => 'fake-key',
            'AWS_SECRET_ACCESS_KEY' => 'fake-secret',
            'AWS_ENDPOINT'          => 'https://fake.r2.cloudflarestorage.com',
            'AWS_BUCKET'            => 'fake-bucket',
            'RESEND_KEY'            => 're_fakekey',
            'ADMIN_PASSWORD'        => 'real-strong-password',
            'SENTRY_LARAVEL_DSN'    => '', // vide → warning info, ne crash pas Sentry init
        ];

        foreach (array_merge($defaults, $overrides) as $k => $v) {
            // Laravel's env() reads from $_ENV / $_SERVER (cached via Dotenv repository),
            // pas seulement getenv(). putenv() seul ne suffit pas dans le contexte test.
            putenv("{$k}={$v}");
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }

        // Mail mailer via config (env() est caché dans certains contextes test).
        config(['mail.default' => $overrides['MAIL_MAILER'] ?? 'resend']);
    }

    public function test_warns_when_r2_keys_missing(): void
    {
        $this->configureEnv([
            'AWS_ACCESS_KEY_ID'     => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_ENDPOINT'          => '',
            'AWS_BUCKET'            => '',
        ]);

        $keys = array_column((new EnvHealthService())->warnings(), 'key');

        $this->assertContains('AWS_ACCESS_KEY_ID', $keys);
        $this->assertContains('AWS_SECRET_ACCESS_KEY', $keys);
        $this->assertContains('AWS_ENDPOINT', $keys);
        $this->assertContains('AWS_BUCKET', $keys);
    }

    public function test_warns_when_resend_key_missing_and_mailer_is_resend(): void
    {
        $this->configureEnv([
            'RESEND_KEY'  => '',
            'MAIL_MAILER' => 'resend',
        ]);

        $keys = array_column((new EnvHealthService())->warnings(), 'key');
        $this->assertContains('RESEND_KEY', $keys);
    }

    public function test_does_not_warn_resend_if_mailer_is_log(): void
    {
        $this->configureEnv([
            'RESEND_KEY'  => '',
            'MAIL_MAILER' => 'log',
        ]);

        $keys = array_column((new EnvHealthService())->warnings(), 'key');
        $this->assertNotContains('RESEND_KEY', $keys);
    }

    public function test_warns_admin_password_default(): void
    {
        $this->configureEnv(['ADMIN_PASSWORD' => 'changeme_before_deploy']);

        $keys = array_column((new EnvHealthService())->warnings(), 'key');
        $this->assertContains('ADMIN_PASSWORD', $keys);
    }

    public function test_sentry_warning_is_info_level_not_critical(): void
    {
        $this->configureEnv(['SENTRY_LARAVEL_DSN' => '']);

        $service = new EnvHealthService();
        $warnings = $service->warnings();

        $sentry = collect($warnings)->firstWhere('key', 'SENTRY_LARAVEL_DSN');
        $this->assertNotNull($sentry);
        $this->assertSame('info', $sentry['level']);
        $this->assertFalse($service->hasCritical());
    }

    public function test_no_critical_when_all_set_except_sentry(): void
    {
        $this->configureEnv(); // tout set, sauf SENTRY DSN (warning info, pas critical)

        $service = new EnvHealthService();
        $warnings = $service->warnings();

        // Seul warning = Sentry (info)
        $criticalCount = collect($warnings)->where('level', 'critical')->count();
        $this->assertSame(0, $criticalCount);
        $this->assertFalse($service->hasCritical());
    }

    public function test_banner_renders_in_admin_dashboard_when_envs_missing(): void
    {
        $this->configureEnv([
            'AWS_ACCESS_KEY_ID'     => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_ENDPOINT'          => '',
            'AWS_BUCKET'            => '',
        ]);

        $admin = User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Configuration prod incomplète');
        $response->assertSee('AWS_ACCESS_KEY_ID');
    }

    public function test_banner_does_not_render_for_non_admin(): void
    {
        // Même config "trouée" — un client ne doit PAS voir le banner.
        $this->configureEnv([
            'AWS_ACCESS_KEY_ID'     => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_ENDPOINT'          => '',
            'AWS_BUCKET'            => '',
        ]);

        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('client.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Configuration prod incomplète');
    }
}
