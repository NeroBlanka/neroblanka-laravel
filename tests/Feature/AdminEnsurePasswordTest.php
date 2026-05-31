<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminEnsurePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        foreach (['ADMIN_EMAIL', 'ADMIN_PASSWORD'] as $k) {
            putenv($k);
            unset($_ENV[$k], $_SERVER[$k]);
        }
        parent::tearDown();
    }

    private function setEnv(string $email, string $password): void
    {
        foreach (['ADMIN_EMAIL' => $email, 'ADMIN_PASSWORD' => $password] as $k => $v) {
            putenv("{$k}={$v}");
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }
    }

    public function test_updates_password_when_admin_has_default(): void
    {
        $this->setEnv('admin@neroblanka.com', 'NewStrongP@ssw0rd!');

        $admin = User::factory()->create([
            'email' => 'admin@neroblanka.com',
            'role'  => 'admin',
            'password' => Hash::make('changeme_before_deploy'),
        ]);

        $this->artisan('admin:ensure-password')
            ->expectsOutputToContain('password updated')
            ->assertSuccessful();

        $admin->refresh();
        $this->assertTrue(Hash::check('NewStrongP@ssw0rd!', $admin->password));
        $this->assertFalse(Hash::check('changeme_before_deploy', $admin->password));
    }

    public function test_does_not_overwrite_real_password(): void
    {
        $this->setEnv('admin@neroblanka.com', 'NewStrongP@ssw0rd!');

        $admin = User::factory()->create([
            'email' => 'admin@neroblanka.com',
            'role'  => 'admin',
            'password' => Hash::make('UserAlreadyChangedThis!'),
        ]);

        $this->artisan('admin:ensure-password')
            ->expectsOutputToContain('déjà personnalisé')
            ->assertSuccessful();

        $admin->refresh();
        $this->assertTrue(Hash::check('UserAlreadyChangedThis!', $admin->password));
        $this->assertFalse(Hash::check('NewStrongP@ssw0rd!', $admin->password));
    }

    public function test_skip_when_env_password_is_default(): void
    {
        $this->setEnv('admin@neroblanka.com', 'changeme_before_deploy');

        $this->artisan('admin:ensure-password')
            ->expectsOutputToContain('trivial')
            ->assertSuccessful();
    }

    public function test_creates_admin_when_does_not_exist(): void
    {
        $this->setEnv('newadmin@neroblanka.com', 'NewStrongP@ssw0rd!');

        $this->assertDatabaseMissing('users', ['email' => 'newadmin@neroblanka.com']);

        $this->artisan('admin:ensure-password')
            ->expectsOutputToContain('créé en DB')
            ->assertSuccessful();

        $admin = User::withoutGlobalScopes()->where('email', 'newadmin@neroblanka.com')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('NewStrongP@ssw0rd!', $admin->password));
    }

    public function test_skip_when_user_with_email_exists_but_is_not_admin(): void
    {
        $this->setEnv('client@neroblanka.com', 'NewStrongP@ssw0rd!');

        User::factory()->create([
            'email' => 'client@neroblanka.com',
            'role'  => 'client',
            'password' => Hash::make('changeme_before_deploy'),
        ]);

        // Le command détecte le user non-admin, skip pour éviter UNIQUE conflict.
        $this->artisan('admin:ensure-password')
            ->expectsOutputToContain('skip pour éviter conflit')
            ->assertSuccessful();
    }
}
