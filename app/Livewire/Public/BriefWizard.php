<?php

namespace App\Livewire\Public;

use App\Enums\ServiceType;
use App\Services\LeadService;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class BriefWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public int $totalSteps = 7;

    // Step 1 — Service
    #[Rule('required|in:branding,event_stand_3d,product_rendering_3d,motion_design,social_campaign,website,ai_image_video,automation,mixed_project')]
    public string $service_type = '';

    // Step 2 — Contact
    #[Rule('required|string|min:2|max:100')]
    public string $full_name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('nullable|string|max:30')]
    public string $phone = '';

    #[Rule('nullable|string|max:100')]
    public string $company = '';

    #[Rule('nullable|in:startup,pme,event,export,diaspora,autre')]
    public string $client_type = '';

    // Step 3 — Budget & deadline
    #[Rule('required|string')]
    public string $budget_range = '';

    #[Rule('required|string')]
    public string $deadline_range = '';

    // Step 4 — Brief textuel
    #[Rule('required|string|min:20|max:2000')]
    public string $project_description = '';

    #[Rule('nullable|string|max:1000')]
    public string $inspirations = '';

    #[Rule('nullable|string|max:500')]
    public string $competitors = '';

    // Step 5 — Questions spécifiques au service (dynamique)
    public array $service_answers = [];

    // Step 6 — Fichiers
    #[Rule('nullable|array|max:5')]
    public array $uploaded_files = [];

    // Step 7 — Confirmation
    public bool $terms_accepted = false;

    public bool $submitted = false;

    public function rules(): array
    {
        $rules = [
            'uploaded_files.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:10240',
        ];

        // Questions spécifiques selon le service
        if ($this->step === 5) {
            $rules = array_merge($rules, $this->serviceSpecificRules());
        }

        return $rules;
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        $this->step = min($this->step + 1, $this->totalSteps);
    }

    public function prevStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    public function submit(LeadService $leadService): void
    {
        $this->validate(['terms_accepted' => 'accepted']);

        $answers = array_merge(
            ['project_description' => $this->project_description],
            ['inspirations' => $this->inspirations],
            ['competitors' => $this->competitors],
            $this->service_answers,
        );

        $files = collect($this->uploaded_files)
            ->filter(fn($f) => $f instanceof UploadedFile)
            ->values()
            ->toArray();

        $leadService->submitBrief(
            data: [
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'company' => $this->company,
                'service_type' => $this->service_type,
                'budget_range' => $this->budget_range,
                'deadline_range' => $this->deadline_range,
                'client_type' => $this->client_type,
                'source' => request()->header('referer'),
                'utm' => session('utm'),
            ],
            answers: $answers,
            files: $files,
        );

        $this->submitted = true;
        $this->redirect(route('brief.merci'), navigate: true);
    }

    public function render()
    {
        return view('livewire.public.brief-wizard', [
            'services' => ServiceType::cases(),
            'currentServiceQuestions' => $this->getServiceQuestions(),
        ])->layout('layouts.guest');
    }

    public function getProgress(): int
    {
        return (int) (($this->step / $this->totalSteps) * 100);
    }

    private function validateCurrentStep(): void
    {
        match($this->step) {
            1 => $this->validateOnly('service_type'),
            2 => $this->validateOnly(['full_name', 'email', 'phone', 'company', 'client_type']),
            3 => $this->validateOnly(['budget_range', 'deadline_range']),
            4 => $this->validateOnly(['project_description', 'inspirations', 'competitors']),
            5 => $this->validate($this->serviceSpecificRules()),
            6 => $this->validate(['uploaded_files.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:10240']),
            default => null,
        };
    }

    private function getServiceQuestions(): array
    {
        return match($this->service_type) {
            'branding' => [
                ['key' => 'brand_values', 'label' => 'Quelles sont les valeurs de votre marque ?', 'type' => 'textarea'],
                ['key' => 'target_audience', 'label' => 'Qui est votre cible principale ?', 'type' => 'text'],
                ['key' => 'brand_tone', 'label' => 'Quel ton pour votre marque ? (ex: premium, chaleureux, tech)', 'type' => 'text'],
            ],
            'event_stand_3d' => [
                ['key' => 'event_name', 'label' => 'Nom et date de l\'événement', 'type' => 'text'],
                ['key' => 'stand_dimensions', 'label' => 'Dimensions du stand (m²) et type d\'espace', 'type' => 'text'],
                ['key' => 'products_to_display', 'label' => 'Produits ou services à mettre en avant', 'type' => 'textarea'],
            ],
            'product_rendering_3d' => [
                ['key' => 'product_type', 'label' => 'Type de produit (packaging, objet, architecture...)', 'type' => 'text'],
                ['key' => 'render_usage', 'label' => 'Usage des rendus (web, print, catalogue, pub)', 'type' => 'text'],
                ['key' => 'quantity', 'label' => 'Nombre de produits et d\'angles souhaités', 'type' => 'text'],
            ],
            'motion_design' => [
                ['key' => 'format', 'label' => 'Format final (reel Instagram, YouTube, TikTok, displaypub...)', 'type' => 'text'],
                ['key' => 'duration', 'label' => 'Durée souhaitée (en secondes)', 'type' => 'text'],
                ['key' => 'mood', 'label' => 'Ambiance / mood (dynamique, épuré, storytelling...)', 'type' => 'textarea'],
            ],
            'social_campaign' => [
                ['key' => 'platforms', 'label' => 'Plateformes cibles (Instagram, LinkedIn, TikTok...)', 'type' => 'text'],
                ['key' => 'campaign_objective', 'label' => 'Objectif de la campagne', 'type' => 'textarea'],
                ['key' => 'post_quantity', 'label' => 'Nombre de posts et formats', 'type' => 'text'],
            ],
            'website' => [
                ['key' => 'site_type', 'label' => 'Type de site (vitrine, portfolio, e-commerce, landing)', 'type' => 'text'],
                ['key' => 'pages', 'label' => 'Pages souhaitées', 'type' => 'textarea'],
                ['key' => 'existing_brand', 'label' => 'Avez-vous une charte graphique existante ?', 'type' => 'text'],
            ],
            'ai_image_video' => [
                ['key' => 'ai_usage', 'label' => 'Usage des visuels IA (pub, social, portfolio, interne)', 'type' => 'text'],
                ['key' => 'style_references', 'label' => 'Références de style (artistes, films, marques)', 'type' => 'textarea'],
                ['key' => 'quantity_format', 'label' => 'Quantité et formats souhaités', 'type' => 'text'],
            ],
            'automation' => [
                ['key' => 'current_workflow', 'label' => 'Décrivez le workflow actuel que vous voulez automatiser', 'type' => 'textarea'],
                ['key' => 'tools_used', 'label' => 'Outils utilisés (Notion, Airtable, Make, n8n, autre...)', 'type' => 'text'],
                ['key' => 'expected_output', 'label' => 'Quel résultat attendez-vous ?', 'type' => 'textarea'],
            ],
            default => [
                ['key' => 'mixed_description', 'label' => 'Décrivez la combinaison de services dont vous avez besoin', 'type' => 'textarea'],
            ],
        };
    }

    private function serviceSpecificRules(): array
    {
        return [
            'service_answers' => 'array',
            'service_answers.*' => 'nullable|string|max:1000',
        ];
    }
}
