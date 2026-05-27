<?php

namespace Tests\Feature\Admin;

use App\Models\Assignment;
use App\Models\Deliverable;
use App\Models\Project;
use App\Models\User;
use App\Scopes\ClientOwnedScope;
use App\Scopes\FreelanceOwnedScope;
use App\Services\AssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAdminTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);
    }

    private function makeClient(): User
    {
        return User::factory()->create(['role' => 'client']);
    }

    private function makeFreelance(): User
    {
        return User::factory()->create(['role' => 'freelance', 'is_available' => true]);
    }

    public function test_admin_projects_index_renders(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.projects.index'))
            ->assertOk()
            ->assertViewIs('admin.projects.index');
    }

    public function test_admin_projects_index_lists_all_projects(): void
    {
        $admin = $this->makeAdmin();
        $client = $this->makeClient();

        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id,
            'title' => 'Test Project',
            'description' => 'desc',
            'status' => 'draft',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.projects.index'))
            ->assertOk()
            ->assertSee('Test Project');
    }

    public function test_deliverable_status_is_submitted_by_default(): void
    {
        $deliverable = new Deliverable([
            'submitted_at' => now(),
        ]);

        $this->assertSame('submitted', $deliverable->status);
    }

    public function test_deliverable_status_is_approved_when_approved_at_set(): void
    {
        $deliverable = new Deliverable([
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);

        $this->assertSame('approved', $deliverable->status);
    }

    public function test_deliverable_status_is_revision_when_revision_notes_set(): void
    {
        $deliverable = new Deliverable([
            'submitted_at' => now(),
            'revision_notes' => 'Please fix X',
        ]);

        $this->assertSame('revision', $deliverable->status);
    }

    public function test_assignment_service_blocks_duplicate_active_assignment(): void
    {
        $client = $this->makeClient();
        $freelance1 = $this->makeFreelance();
        $freelance2 = $this->makeFreelance();

        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id,
            'title' => 'Test',
            'description' => 'desc',
            'status' => 'draft',
        ]);

        $service = app(AssignmentService::class);
        $service->create($project, $freelance1);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $service->create($project, $freelance2);
    }

    public function test_admin_approve_deliverable_is_idempotency_safe(): void
    {
        $admin = $this->makeAdmin();
        $client = $this->makeClient();
        $freelance = $this->makeFreelance();

        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id, 'title' => 'P', 'description' => 'd', 'status' => 'submitted',
        ]);

        $assignment = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)->create([
            'project_id' => $project->id, 'freelance_id' => $freelance->id, 'status' => 'active',
        ]);

        $deliverable = Deliverable::create([
            'assignment_id' => $assignment->id,
            'file_url' => 'deliverables/test.pdf',
            'file_name' => 'test.pdf',
            'version' => 1,
            'submitted_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.deliverables.approve', $deliverable))
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('admin.deliverables.approve', $deliverable))
            ->assertStatus(422);
    }
}
