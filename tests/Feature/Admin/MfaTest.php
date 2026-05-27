<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MfaTest extends TestCase
{
    use RefreshDatabase;

    private function adminWithoutMfa(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'totp_enabled' => false,
            'totp_secret' => null,
        ]);
    }

    private function adminWithMfa(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'totp_enabled' => true,
            'totp_secret' => 'JBSWY3DPEHPK3PXP',
        ]);
    }

    public function test_mfa_verify_page_loads_for_admin(): void
    {
        $admin = $this->adminWithMfa();

        $response = $this->actingAs($admin)->get(route('admin.mfa.verify'));

        $response->assertStatus(200);
    }

    public function test_mfa_skipped_when_totp_not_enabled(): void
    {
        $admin = $this->adminWithoutMfa();

        $response = $this->actingAs($admin)
            ->post(route('admin.mfa.check'), ['code' => '000000']);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(session('admin_mfa_passed'));
    }

    private function mockGoogle2fa(bool $returns): void
    {
        $mock = \Mockery::mock();
        $mock->shouldReceive('verifyKey')->andReturn($returns);
        $this->app->instance('pragmarx.google2fa', $mock);
    }

    public function test_valid_totp_code_passes_mfa(): void
    {
        $admin = $this->adminWithMfa();
        $this->mockGoogle2fa(true);

        $response = $this->actingAs($admin)
            ->post(route('admin.mfa.check'), ['code' => '123456']);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(session('admin_mfa_passed'));
    }

    public function test_invalid_totp_code_returns_error(): void
    {
        $admin = $this->adminWithMfa();
        $this->mockGoogle2fa(false);

        $response = $this->actingAs($admin)
            ->post(route('admin.mfa.check'), ['code' => '000000']);

        $response->assertRedirect();
        $response->assertSessionHasErrors('code');
        $this->assertNull(session('admin_mfa_passed'));
    }

    public function test_mfa_verify_throttled_after_five_failures(): void
    {
        $admin = $this->adminWithMfa();
        $this->mockGoogle2fa(false);

        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($admin)->post(route('admin.mfa.check'), ['code' => '000000']);
        }

        $response = $this->actingAs($admin)
            ->post(route('admin.mfa.check'), ['code' => '000000']);

        $response->assertStatus(429);
    }

    public function test_admin_dashboard_redirects_to_mfa_when_not_passed(): void
    {
        $admin = $this->adminWithMfa();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.mfa.verify'));
    }

    public function test_admin_dashboard_accessible_after_mfa_passed(): void
    {
        $admin = $this->adminWithoutMfa();

        $response = $this->actingAs($admin)
            ->withSession(['admin_mfa_passed' => true])
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_mfa_code_required(): void
    {
        $admin = $this->adminWithMfa();

        $response = $this->actingAs($admin)
            ->post(route('admin.mfa.check'), ['code' => '']);

        $response->assertSessionHasErrors('code');
    }

    public function test_register_route_does_not_exist(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }
}
