<?php

namespace App\Livewire\Public;

use App\Enums\ServiceType;
use App\Services\FunnelTrackingService;
use App\Services\LeadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class BriefWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public int $totalSteps = 7;

    #[Url(as: 'service')]
    public string $urlService = '';

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

    // Step 6 — Fichiers + lien de transfert externe (pour > 400 Mo)
    #[Rule('nullable|array|max:5')]
    public array $uploaded_files = [];

    #[Rule('nullable|url|max:500')]
    public string $transfer_url = '';

    // Step 7 — Confirmation
    public bool $terms_accepted = false;

    public bool $submitted = false;

    public function mount(FunnelTrackingService $tracker): void
    {
        if ($this->urlService) {
            $serviceEnum = ServiceType::fromSlug($this->urlService);
            if ($serviceEnum) {
                $this->service_type = $serviceEnum->value;
                $this->step = 2;
            }
        }

        $tracker->track('brief_started', null, [
            'service' => $this->service_type ?: null,
            'prefilled' => (bool) $this->service_type,
        ]);
    }

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

    public function advance(LeadService $leadService, FunnelTrackingService $tracker): void
    {
        if ($this->step === $this->totalSteps) {
            $this->doSubmit($leadService, $tracker);
        } else {
            $this->validateCurrentStep();
            $this->step = min($this->step + 1, $this->totalSteps);
            $tracker->track('step_completed', null, ['step' => $this->step - 1, 'service' => $this->service_type]);
        }
    }

    public function prevStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    /** @internal kept for direct calls if ever needed */
    private function doSubmit(LeadService $leadService, FunnelTrackingService $tracker): void
    {
        $key = 'brief-submit:' . request()->ip();
        abort_if(RateLimiter::tooManyAttempts($key, 3), 429, 'Trop de soumissions. Réessayez dans quelques minutes.');
        RateLimiter::hit($key, 600);

        $this->validate(['terms_accepted' => 'accepted']);

        $answers = array_merge(
            ['project_description' => $this->project_description],
            ['inspirations' => $this->inspirations],
            ['competitors' => $this->competitors],
            $this->service_answers,
        );

        if ($this->transfer_url !== '') {
            $answers['transfer_url'] = $this->transfer_url;
        }

        $files = collect($this->uploaded_files)
            ->filter(fn($f) => $f instanceof UploadedFile)
            ->values()
            ->toArray();

        $lead = $leadService->submitBrief(
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

        $tracker->track('brief_submitted', $lead->id, [
            'service' => $this->service_type,
            'score' => $lead->score,
        ]);

        $this->submitted = true;
        $this->redirect(route('brief.merci'), navigate: true);
    }

    public function render()
    {
        return view('livewire.public.brief-wizard', [
            'services' => ServiceType::cases(),
            'currentServiceQuestions' => $this->getServiceQuestions(),
        ])->layout('layouts.brief');
    }

    public function getProgress(): int
    {
        return (int) (($this->step / $this->totalSteps) * 100);
    }

    private function validateCurrentStep(): void
    {
        // Livewire 3+ : validateOnly() prend un seul champ string. Pour valider plusieurs
        // champs d'un coup ET collecter toutes les erreurs en une passe (meilleure UX
        // qu'un validateOnly en boucle qui s'arrête au 1er échec), on utilise validate()
        // avec le sous-ensemble de rules de l'étape courante.
        $rulesByStep = [
            1 => [
                'service_type' => 'required|in:branding,event_stand_3d,product_rendering_3d,motion_design,social_campaign,website,ai_image_video,automation,mixed_project',
            ],
            2 => [
                'full_name'   => 'required|string|min:2|max:100',
                'email'       => 'required|email|max:255',
                'phone'       => 'nullable|string|max:30',
                'company'     => 'nullable|string|max:100',
                'client_type' => 'nullable|in:startup,pme,event,export,diaspora,autre',
            ],
            3 => [
                'budget_range'   => 'required|string',
                'deadline_range' => 'required|string',
            ],
            4 => [
                'project_description' => 'required|string|min:20|max:2000',
                'inspirations'        => 'nullable|string|max:1000',
                'competitors'         => 'nullable|string|max:500',
            ],
            5 => $this->serviceSpecificRules(),
            6 => [
                'uploaded_files.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,zip,rar,mp4,mov,webm,mkv|max:409600', // 400 Mo
                'transfer_url'     => 'nullable|url|max:500',
            ],
        ];

        if (isset($rulesByStep[$this->step])) {
            $this->validate($rulesByStep[$this->step]);
        }
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
