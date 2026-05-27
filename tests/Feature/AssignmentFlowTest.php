<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use App\Services\AssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AssignmentFlowTest extends TestCase
{
    use RefreshDatabase;

    private AssignmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AssignmentService::class);
        Mail::fake();
    }

    private function makeProject(string $status = 'draft'): Project
    {
        $client = User::factory()->create(['role' => 'client']);

        return Project::withoutGlobalScopes()->create([
            'client_id' => $client->id,
            'title' => 'Projet Test',
            'description' => 'Description test',
            'service_type' => 'branding',
            'status' => $status,
        ]);
    }

    private function makeFreelance(): User
    {
        return User::factory()->create([
            'role' => 'freelance',
            'is_available' => true,
        ]);
    }

    public function test_creates_assignment_and_updates_project_status(): void
    {
        $project = $this->makeProject();
        $freelance = $this->makeFreelance();

        $assignment = $this->service->create($project, $freelance);

        $this->assertInstanceOf(Assignment::class, $assignment);
        $this->assertDatabaseHas('assignments', [
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'assigned',
        ]);
    }

    public function test_assignment_email_sent_to_freelance(): void
    {
        $project = $this->makeProject();
        $freelance = $this->makeFreelance();

        $this->service->create($project, $freelance);

        Mail::assertSent(\App\Mail\AssignmentCreated::class, function ($mail) use ($freelance) {
            return $mail->hasTo($freelance->email);
        });
    }

    public function test_admin_can_assign_project_via_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = $this->makeProject();
        $freelance = $this->makeFreelance();

        $response = $this->actingAs($admin)
            ->withSession(['admin_mfa_passed' => true])
            ->post(route('admin.assignments.store'), [
                'project_id' => $project->id,
                'freelance_id' => $freelance->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assignments', [
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
        ]);
    }

    public function test_client_cannot_access_assignment_route(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $project = $this->makeProject();
        $freelance = $this->makeFreelance();

        $response = $this->actingAs($client)
            ->post(route('admin.assignments.store'), [
                'project_id' => $project->id,
                'freelance_id' => $freelance->id,
            ]);

        // EnsureAdmin redirects non-admins to their own dashboard
        $response->assertRedirect(route('client.dashboard'));
    }

    public function test_unauthenticated_cannot_access_admin_routes(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
