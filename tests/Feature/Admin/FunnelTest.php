<?php

namespace Tests\Feature\Admin;

use App\Enums\ServiceType;
use App\Models\FunnelEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FunnelTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);
    }

    public function test_funnel_dashboard_renders_for_admin(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.funnel'))
            ->assertOk()
            ->assertViewIs('admin.funnel')
            ->assertViewHas(['totalStarted', 'totalSubmitted', 'conversionRate']);
    }

    public function test_funnel_counts_brief_started_events(): void
    {
        $admin = $this->makeAdmin();

        FunnelEvent::create(['event' => 'brief_started', 'session_id' => 'a', 'ip' => 'hash1']);
        FunnelEvent::create(['event' => 'brief_started', 'session_id' => 'b', 'ip' => 'hash2']);
        FunnelEvent::create(['event' => 'brief_submitted', 'session_id' => 'a', 'ip' => 'hash1']);

        $response = $this->actingAs($admin)->get(route('admin.funnel'));

        $response->assertViewHas('totalStarted', 2);
        $response->assertViewHas('totalSubmitted', 1);
        $response->assertViewHas('conversionRate', 50.0);
    }

    public function test_non_admin_cannot_access_funnel(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($client)
            ->get(route('admin.funnel'))
            ->assertRedirect(route('client.dashboard'));
    }
}
