<?php

namespace Tests\Feature;

use App\Enums\ServiceType;
use App\Livewire\Admin\FreelanceMatcher;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;
use App\Scopes\ClientOwnedScope;
use App\Scopes\FreelanceOwnedScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class FreelanceMatcherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function makeProject(): Project
    {
        $client = User::factory()->create(['role' => 'client']);

        return Project::withoutGlobalScope(ClientOwnedScope::class)->create([
            'client_id' => $client->id,
            'title' => 'Projet Matcher',
            'description' => 'desc',
            'service_type' => ServiceType::BRANDING->value,
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_assign_freelance(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);
        $freelance = User::factory()->create(['role' => 'freelance', 'is_available' => true]);
        $project = $this->makeProject();

        $this->actingAs($admin);

        Livewire::test(FreelanceMatcher::class, ['projectId' => $project->id])
            ->call('assign', $freelance->id)
            ->assertSet('success', true);

        $this->assertDatabaseHas('assignments', [
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
            'status' => 'active',
        ]);
    }

    public function test_reassigning_same_freelance_is_graceful(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'totp_enabled' => false]);
        $freelance = User::factory()->create(['role' => 'freelance', 'is_available' => true]);
        $project = $this->makeProject();

        Assignment::withoutGlobalScope(FreelanceOwnedScope::class)->create([
            'project_id' => $project->id,
            'freelance_id' => $freelance->id,
            'status' => 'active',
        ]);

        $this->actingAs($admin);

        // Re-assigning the same active freelance must not throw — graceful error state
        Livewire::test(FreelanceMatcher::class, ['projectId' => $project->id])
            ->call('assign', $freelance->id)
            ->assertSet('success', false)
            ->assertStatus(200);

        // Still exactly one active assignment
        $count = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)
            ->where('project_id', $project->id)
            ->where('freelance_id', $freelance->id)
            ->count();

        $this->assertSame(1, $count);
    }

}
