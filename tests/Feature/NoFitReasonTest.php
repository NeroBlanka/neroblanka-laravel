<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\NoFitReason;
use App\Enums\ServiceType;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NoFitReasonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'totp_secret' => null]);
    }

    private function makeLead(LeadStatus $status = LeadStatus::QUALIFIED): Lead
    {
        return Lead::create([
            'full_name' => 'Test Lead',
            'email' => 'test@example.com',
            'service_type' => ServiceType::BRANDING->value,
            'status' => $status->value,
        ]);
    }

    public function test_admin_can_mark_lead_no_fit_with_reason(): void
    {
        $admin = $this->makeAdmin();
        $lead = $this->makeLead();

        $this->actingAs($admin)
            ->post(route('admin.leads.no-fit', $lead), [
                'no_fit_reason' => NoFitReason::BUDGET_TOO_LOW->value,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => LeadStatus::NO_FIT->value,
            'no_fit_reason' => NoFitReason::BUDGET_TOO_LOW->value,
        ]);
    }

    public function test_no_fit_stores_reason_in_lead_event(): void
    {
        $admin = $this->makeAdmin();
        $lead = $this->makeLead();

        $this->actingAs($admin)
            ->post(route('admin.leads.no-fit', $lead), [
                'no_fit_reason' => NoFitReason::OUTSIDE_SCOPE->value,
            ]);

        $this->assertDatabaseHas('lead_events', [
            'lead_id' => $lead->id,
            'type' => 'status_changed',
            'to_status' => LeadStatus::NO_FIT->value,
        ]);
    }

    public function test_no_fit_requires_valid_reason(): void
    {
        $admin = $this->makeAdmin();
        $lead = $this->makeLead();

        $this->actingAs($admin)
            ->post(route('admin.leads.no-fit', $lead), [
                'no_fit_reason' => 'invalid_reason',
            ])
            ->assertSessionHasErrors('no_fit_reason');
    }

    public function test_non_admin_cannot_mark_no_fit(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $lead = $this->makeLead();

        $this->actingAs($client)
            ->post(route('admin.leads.no-fit', $lead), [
                'no_fit_reason' => NoFitReason::BUDGET_TOO_LOW->value,
            ])
            ->assertRedirect(route('client.dashboard'));
    }

    public function test_non_admin_cannot_convert_lead(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $lead = $this->makeLead();

        $this->actingAs($client)
            ->post(route('admin.leads.convert', $lead))
            ->assertRedirect(route('client.dashboard'));
    }
}
