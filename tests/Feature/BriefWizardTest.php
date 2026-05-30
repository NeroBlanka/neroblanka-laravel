<?php

namespace Tests\Feature;

use App\Livewire\Public\BriefWizard;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class BriefWizardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Queue::fake();
    }

    public function test_wizard_validates_step_2_contact_fields(): void
    {
        // Regression : validateOnly(array) crashait en Livewire 3+ (TypeError)
        Livewire::test(BriefWizard::class)
            ->set('service_type', 'branding')
            ->set('step', 2)
            ->call('advance')
            ->assertHasErrors(['full_name', 'email'])
            ->assertSet('step', 2); // reste sur l'étape, ne crash pas
    }

    public function test_wizard_advances_step_2_to_3_with_valid_contact(): void
    {
        Livewire::test(BriefWizard::class)
            ->set('service_type', 'branding')
            ->set('step', 2)
            ->set('full_name', 'Jean Dupont')
            ->set('email', 'jean@example.com')
            ->call('advance')
            ->assertHasNoErrors()
            ->assertSet('step', 3);
    }

    public function test_wizard_validates_step_3_budget_deadline(): void
    {
        Livewire::test(BriefWizard::class)
            ->set('service_type', 'branding')
            ->set('full_name', 'Jean Dupont')
            ->set('email', 'jean@example.com')
            ->set('step', 3)
            ->call('advance')
            ->assertHasErrors(['budget_range', 'deadline_range'])
            ->assertSet('step', 3);
    }

    public function test_wizard_validates_step_4_project_description(): void
    {
        // project_description: required|string|min:20
        Livewire::test(BriefWizard::class)
            ->set('service_type', 'branding')
            ->set('step', 4)
            ->set('project_description', 'court')
            ->call('advance')
            ->assertHasErrors(['project_description'])
            ->assertSet('step', 4);
    }

    public function test_full_wizard_submission_creates_lead(): void
    {
        Livewire::test(BriefWizard::class)
            ->set('service_type', 'branding')
            ->set('full_name', 'Jean Dupont')
            ->set('email', 'jean@example.com')
            ->set('budget_range', '2 000$ – 10 000$')
            ->set('deadline_range', '1 – 3 mois')
            ->set('project_description', 'Description longue suffisante pour le brief, plus de 20 caractères.')
            ->set('terms_accepted', true)
            ->set('step', 7)
            ->call('advance');

        $this->assertDatabaseHas('leads', [
            'email' => 'jean@example.com',
            'service_type' => 'branding',
        ]);
    }
}
