<?php

namespace Tests\Feature\Admin;

use App\Enums\ServiceType;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceAdminTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);
    }

    public function test_services_index_renders(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.services.index'))
            ->assertOk()
            ->assertViewIs('admin.services.index');
    }

    public function test_admin_can_create_service(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.services.store'), [
                'name'          => 'Branding Premium',
                'type'          => ServiceType::BRANDING->value,
                'price_da'      => 80000,
                'delivery_days' => 21,
                'is_active'     => 1,
            ])
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'name'     => 'Branding Premium',
            'type'     => ServiceType::BRANDING->value,
            'price_da' => 80000,
        ]);
    }

    public function test_admin_can_update_service(): void
    {
        $admin   = $this->makeAdmin();
        $service = Service::create([
            'name' => 'Old Name', 'price_da' => 10000, 'delivery_days' => 7,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.services.update', $service), [
                'name'          => 'New Name',
                'price_da'      => 50000,
                'delivery_days' => 14,
            ])
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'New Name', 'price_da' => 50000]);
    }

    public function test_admin_can_toggle_service_active(): void
    {
        $admin   = $this->makeAdmin();
        $service = Service::create([
            'name' => 'Test Service', 'price_da' => 5000, 'delivery_days' => 5, 'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.services.toggle-active', $service))
            ->assertRedirect();

        $this->assertDatabaseHas('services', ['id' => $service->id, 'is_active' => false]);
    }

    public function test_admin_can_delete_service(): void
    {
        $admin   = $this->makeAdmin();
        $service = Service::create([
            'name' => 'À supprimer', 'price_da' => 1000, 'delivery_days' => 3,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.services.destroy', $service))
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_store_rejects_invalid_service_type(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.services.store'), [
                'name'          => 'Test',
                'type'          => 'invalid_type',
                'price_da'      => 1000,
                'delivery_days' => 5,
            ])
            ->assertSessionHasErrors('type');
    }

    public function test_non_admin_cannot_access_services_admin(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($client)
            ->get(route('admin.services.index'))
            ->assertRedirect(route('client.dashboard'));
    }
}
