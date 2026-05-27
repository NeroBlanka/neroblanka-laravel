<?php

namespace Tests\Feature;

use App\Enums\ServiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ClientBriefTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function makeClient(): User
    {
        return User::factory()->create(['role' => 'client']);
    }

    // ─── admin freelances ─────────────────────────────────────────────────────

    public function test_admin_freelances_index_renders(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);

        $this->actingAs($admin)
            ->get(route('admin.freelances'))
            ->assertOk()
            ->assertViewIs('admin.freelances.index');
    }

    // ─── client brief create ──────────────────────────────────────────────────

    public function test_client_brief_create_displays_service_options(): void
    {
        $client = $this->makeClient();

        $response = $this->actingAs($client)
            ->get(route('client.brief.create'))
            ->assertOk();

        foreach (ServiceType::options() as $option) {
            if ($option['value'] === ServiceType::MIXED_PROJECT->value) {
                continue;
            }
            $response->assertSee($option['label']);
        }
    }

    // ─── client brief store ───────────────────────────────────────────────────

    public function test_client_brief_store_accepts_current_service_type_value(): void
    {
        $client = $this->makeClient();

        $this->actingAs($client)
            ->post(route('client.brief.store'), [
                'title'        => 'Projet identité',
                'description'  => 'Description du projet identité visuelle',
                'service_type' => ServiceType::BRANDING->value,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_client_brief_store_rejects_invalid_service_type(): void
    {
        $client = $this->makeClient();

        $this->actingAs($client)
            ->post(route('client.brief.store'), [
                'title'        => 'Projet test',
                'description'  => 'Description',
                'service_type' => 'identite_visuelle',
            ])
            ->assertSessionHasErrors('service_type');
    }
}
