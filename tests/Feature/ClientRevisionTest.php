<?php

namespace Tests\Feature;

use App\Enums\ServiceType;
use App\Models\Assignment;
use App\Models\Deliverable;
use App\Models\Project;
use App\Models\User;
use App\Scopes\ClientOwnedScope;
use App\Scopes\FreelanceOwnedScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ClientRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function scaffold(string $projectStatus = 'submitted'): array
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelance = User::factory()->create(['role' => 'freelance']);

        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id,
            'title' => 'Projet Client',
            'description' => 'desc',
            'service_type' => ServiceType::BRANDING->value,
            'status' => $projectStatus,
        ]);

        $assignment = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)->create([
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
            'status' => 'active',
        ]);

        return [$client, $project, $assignment];
    }

    public function test_client_sees_approve_form_on_submitted_deliverable(): void
    {
        [$client, $project, $assignment] = $this->scaffold('submitted');

        Deliverable::create([
            'assignment_id' => $assignment->id,
            'file_url' => 'deliverables/v1.pdf', 'file_name' => 'v1.pdf',
            'version' => 1, 'submitted_at' => now(),
        ]);

        $this->actingAs($client)
            ->get(route('client.projects.show', $project))
            ->assertOk()
            ->assertSee('Demander révision');
    }

    public function test_client_does_not_see_approve_form_on_already_approved_deliverable(): void
    {
        // Regression: gating was by PROJECT status, showing forms on approved deliverables.
        [$client, $project, $assignment] = $this->scaffold('submitted');

        Deliverable::create([
            'assignment_id' => $assignment->id,
            'file_url' => 'deliverables/v1.pdf', 'file_name' => 'v1.pdf',
            'version' => 1, 'submitted_at' => now()->subDay(), 'approved_at' => now(),
        ]);

        $this->actingAs($client)
            ->get(route('client.projects.show', $project))
            ->assertOk()
            ->assertDontSee('Demander révision');
    }

    public function test_client_sees_their_revision_notes(): void
    {
        [$client, $project, $assignment] = $this->scaffold('revision');

        Deliverable::create([
            'assignment_id' => $assignment->id,
            'file_url' => 'deliverables/v1.pdf', 'file_name' => 'v1.pdf',
            'version' => 1, 'submitted_at' => now()->subDay(),
            'revision_notes' => 'Merci de revoir la palette de couleurs.',
        ]);

        $this->actingAs($client)
            ->get(route('client.projects.show', $project))
            ->assertOk()
            ->assertSee('Merci de revoir la palette de couleurs.');
    }
}
