<?php

namespace Tests\Feature;

use App\Enums\ServiceType;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;
use App\Scopes\ClientOwnedScope;
use App\Scopes\FreelanceOwnedScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class IsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function makeProject(User $client, string $status = 'draft'): Project
    {
        return Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id,
            'title' => 'Projet Test',
            'description' => 'Description test',
            'service_type' => ServiceType::BRANDING->value,
            'status' => $status,
        ]);
    }

    private function makeAssignment(Project $project, User $freelance): Assignment
    {
        return Assignment::withoutGlobalScope(FreelanceOwnedScope::class)->create([
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
            'status' => 'active',
        ]);
    }

    // ─── CLIENT ISOLATION ──────────────────────────────────────────────────────

    public function test_client_cannot_view_another_clients_project(): void
    {
        $ownerClient = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $project = $this->makeProject($ownerClient);

        // Policy denies access (403) — project is found but not owned by otherClient
        $this->actingAs($otherClient)
            ->get(route('client.projects.show', $project))
            ->assertForbidden();
    }

    public function test_client_cannot_approve_another_clients_deliverable(): void
    {
        $ownerClient = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $freelance = User::factory()->create(['role' => 'freelance']);
        $project = $this->makeProject($ownerClient, 'submitted');
        $assignment = $this->makeAssignment($project, $freelance);
        $deliverable = \App\Models\Deliverable::create(['assignment_id' => $assignment->id, 'file_url' => 'test.pdf', 'file_name' => 'test.pdf']);

        $this->actingAs($otherClient)
            ->post(route('client.deliverables.approve', $deliverable))
            ->assertForbidden();
    }

    public function test_client_cannot_request_revision_on_another_clients_deliverable(): void
    {
        $ownerClient = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $freelance = User::factory()->create(['role' => 'freelance']);
        $project = $this->makeProject($ownerClient, 'submitted');
        $assignment = $this->makeAssignment($project, $freelance);
        $deliverable = \App\Models\Deliverable::create(['assignment_id' => $assignment->id, 'file_url' => 'test.pdf', 'file_name' => 'test.pdf']);

        $this->actingAs($otherClient)
            ->post(route('client.deliverables.revision', $deliverable), ['revision_notes' => 'Modifiez ceci'])
            ->assertForbidden();
    }

    public function test_client_can_view_own_project(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $project = $this->makeProject($client);

        $this->actingAs($client)
            ->get(route('client.projects.show', $project))
            ->assertStatus(200);
    }

    // ─── FREELANCE ISOLATION ───────────────────────────────────────────────────

    public function test_freelance_cannot_view_another_freelances_assignment(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $ownerFreelance = User::factory()->create(['role' => 'freelance']);
        $otherFreelance = User::factory()->create(['role' => 'freelance']);

        $project = $this->makeProject($client);
        $this->makeAssignment($project, $ownerFreelance);

        // FreelanceOwnedScope filters out assignments not belonging to the authenticated user
        $visibleToOther = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)
            ->where('project_id', $project->id)
            ->get();

        // Scope should hide it from otherFreelance
        $this->actingAs($otherFreelance);
        $hidden = Assignment::where('project_id', $project->id)->get();
        $this->assertCount(0, $hidden);
        $this->assertCount(1, $visibleToOther);
    }

    public function test_freelance_can_see_own_assignment(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelance = User::factory()->create(['role' => 'freelance']);

        $project = $this->makeProject($client);
        $this->makeAssignment($project, $freelance);

        $this->actingAs($freelance);
        $visible = Assignment::where('project_id', $project->id)->get();
        $this->assertCount(1, $visible);
    }

    // ─── ADMIN LIVEWIRE ACTIONS ────────────────────────────────────────────────

    public function test_non_admin_cannot_call_lead_inbox_change_status(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $lead = \App\Models\Lead::create([
            'full_name' => 'Test Lead',
            'email' => 'lead@example.com',
            'service_type' => ServiceType::BRANDING->value,
            'status' => 'new',
        ]);

        $this->actingAs($client);

        Livewire::test(\App\Livewire\Admin\LeadInbox::class)
            ->call('changeStatus', $lead->id, 'in_review')
            ->assertStatus(403);
    }

    public function test_non_admin_cannot_call_lead_inbox_add_note(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $lead = \App\Models\Lead::create([
            'full_name' => 'Test Lead',
            'email' => 'lead2@example.com',
            'service_type' => ServiceType::BRANDING->value,
            'status' => 'new',
        ]);

        $this->actingAs($client);

        Livewire::test(\App\Livewire\Admin\LeadInbox::class)
            ->set('newNote', 'Note malveillante')
            ->call('addNote', $lead->id)
            ->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        // EnsureAdmin redirects non-admins to their own dashboard
        $this->actingAs($client)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('client.dashboard'));
    }

    public function test_freelance_cannot_access_admin_dashboard(): void
    {
        $freelance = User::factory()->create(['role' => 'freelance']);

        $this->actingAs($freelance)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('freelance.dashboard'));
    }
}
