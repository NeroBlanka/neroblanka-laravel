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

class FreelanceRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function scaffold(): array
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelance = User::factory()->create(['role' => 'freelance']);

        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id,
            'title' => 'Projet Révision',
            'description' => 'desc',
            'service_type' => ServiceType::BRANDING->value,
            'status' => 'revision',
        ]);

        $assignment = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)->create([
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
            'status' => 'active',
        ]);

        return [$freelance, $assignment];
    }

    public function test_freelance_sees_revision_notes_on_upload_page(): void
    {
        [$freelance, $assignment] = $this->scaffold();

        Deliverable::create([
            'assignment_id' => $assignment->id,
            'file_url' => 'deliverables/v1.pdf',
            'file_name' => 'v1.pdf',
            'version' => 1,
            'submitted_at' => now()->subDay(),
            'revision_notes' => 'Le logo doit être plus contrasté et centré.',
        ]);

        $this->actingAs($freelance)
            ->get(route('freelance.assignments.show', $assignment))
            ->assertOk()
            ->assertSee('Révision demandée sur la version 1')
            ->assertSee('Le logo doit être plus contrasté et centré.');
    }

    public function test_no_revision_banner_when_deliverable_just_submitted(): void
    {
        [$freelance, $assignment] = $this->scaffold();

        Deliverable::create([
            'assignment_id' => $assignment->id,
            'file_url' => 'deliverables/v1.pdf',
            'file_name' => 'v1.pdf',
            'version' => 1,
            'submitted_at' => now(),
        ]);

        $this->actingAs($freelance)
            ->get(route('freelance.assignments.show', $assignment))
            ->assertOk()
            ->assertDontSee('Révision demandée sur la version');
    }
}
