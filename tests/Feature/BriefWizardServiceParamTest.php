<?php

namespace Tests\Feature;

use App\Enums\ServiceType;
use App\Livewire\Public\BriefWizard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class BriefWizardServiceParamTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_valid_service_slug_prefills_and_skips_to_step_2(): void
    {
        Livewire::test(BriefWizard::class, ['urlService' => 'branding'])
            ->assertSet('service_type', ServiceType::BRANDING->value)
            ->assertSet('step', 2);
    }

    public function test_seo_slug_with_hyphens_prefills_correctly(): void
    {
        Livewire::test(BriefWizard::class, ['urlService' => '3d-stand-event'])
            ->assertSet('service_type', ServiceType::EVENT_STAND_3D->value)
            ->assertSet('step', 2);
    }

    public function test_invalid_service_slug_does_not_prefill(): void
    {
        Livewire::test(BriefWizard::class, ['urlService' => 'nonexistent-service'])
            ->assertSet('service_type', '')
            ->assertSet('step', 1);
    }

    public function test_empty_service_param_stays_on_step_1(): void
    {
        Livewire::test(BriefWizard::class, ['urlService' => ''])
            ->assertSet('service_type', '')
            ->assertSet('step', 1);
    }

    public function test_all_service_slugs_resolve_correctly(): void
    {
        foreach (ServiceType::cases() as $service) {
            $result = Livewire::test(BriefWizard::class, ['urlService' => $service->slug()]);
            $result->assertSet('service_type', $service->value);
        }
    }
}
