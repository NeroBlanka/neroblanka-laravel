<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\ServiceType;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class LeadConversionTest extends TestCase
{
    use RefreshDatabase;

    private LeadService $service;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LeadService::class);
        $this->admin = User::factory()->create(['role' => 'admin']);
        Mail::fake();
    }

    private function makeQualifiedLead(array $overrides = []): Lead
    {
        return Lead::create(array_merge([
            'full_name' => 'Karim Mansouri',
            'email' => 'karim@example.com',
            'service_type' => ServiceType::BRANDING->value,
            'status' => LeadStatus::QUALIFIED->value,
            'score' => 75,
            'budget_range' => '2 000$ – 10 000$',
            'deadline_range' => '1 – 3 mois',
        ], $overrides));
    }

    public function test_converts_qualified_lead_to_project(): void
    {
        $lead = $this->makeQualifiedLead();

        $project = $this->service->convertToProject($lead, $this->admin->id);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertDatabaseHas('projects', [
            'lead_id' => $lead->id,
            'status' => 'draft',
        ]);
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => LeadStatus::WON->value,
        ]);
    }

    public function test_creates_client_user_when_email_not_found(): void
    {
        $lead = $this->makeQualifiedLead(['email' => 'nouveau@example.com']);

        $this->assertDatabaseMissing('users', ['email' => 'nouveau@example.com']);

        $this->service->convertToProject($lead, $this->admin->id);

        $this->assertDatabaseHas('users', [
            'email' => 'nouveau@example.com',
            'role' => 'client',
        ]);
    }

    public function test_sends_password_reset_for_new_client(): void
    {
        Password::shouldReceive('sendResetLink')->once()->andReturn(Password::RESET_LINK_SENT);

        $lead = $this->makeQualifiedLead(['email' => 'brand-new@example.com']);

        $this->service->convertToProject($lead, $this->admin->id);
    }

    public function test_reuses_existing_user_without_sending_reset(): void
    {
        $existing = User::factory()->create(['email' => 'existing@example.com', 'role' => 'client']);
        $lead = $this->makeQualifiedLead(['email' => 'existing@example.com']);

        Password::shouldReceive('sendResetLink')->never();

        $project = $this->service->convertToProject($lead, $this->admin->id);

        $this->assertEquals($existing->id, $project->client_id);
        $this->assertEquals(1, User::withoutGlobalScopes()->where('email', 'existing@example.com')->count());
    }

    public function test_rejects_non_qualified_lead(): void
    {
        $lead = $this->makeQualifiedLead(['status' => LeadStatus::NEW->value]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->service->convertToProject($lead, $this->admin->id);
    }

    public function test_rejects_double_conversion(): void
    {
        $lead = $this->makeQualifiedLead();
        $this->service->convertToProject($lead, $this->admin->id);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->service->convertToProject($lead->fresh(), $this->admin->id);
    }

    public function test_records_lead_event_on_conversion(): void
    {
        $lead = $this->makeQualifiedLead();

        $this->service->convertToProject($lead, $this->admin->id);

        $this->assertDatabaseHas('lead_events', [
            'lead_id' => $lead->id,
            'type' => 'status_changed',
            'to_status' => LeadStatus::WON->value,
        ]);
    }
}
