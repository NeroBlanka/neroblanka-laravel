<?php

namespace App\Livewire\Admin;

use App\Enums\LeadStatus;
use App\Enums\ServiceType;
use App\Models\Lead;
use App\Services\LeadService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class LeadInbox extends Component
{
    use WithPagination;

    #[Url]
    public string $filterStatus = '';

    #[Url]
    public string $filterService = '';

    #[Url]
    public string $filterBudget = '';

    #[Url]
    public bool $onlyHot = false;

    public ?string $selectedLeadId = null;
    public string $statusNote = '';
    public string $newNote = '';

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterService(): void { $this->resetPage(); }
    public function updatingOnlyHot(): void { $this->resetPage(); }

    public function changeStatus(string $leadId, string $status, LeadService $leadService): void
    {
        $lead = Lead::withoutGlobalScopes()->findOrFail($leadId);
        $leadService->changeStatus($lead, LeadStatus::from($status), $this->statusNote ?: null, auth()->id());
        $this->statusNote = '';
    }

    public function addNote(string $leadId, LeadService $leadService): void
    {
        $this->validate(['newNote' => 'required|string|min:2|max:2000']);
        $lead = Lead::withoutGlobalScopes()->findOrFail($leadId);
        $leadService->addNote($lead, $this->newNote, auth()->id());
        $this->newNote = '';
    }

    public function render()
    {
        $query = Lead::withoutGlobalScopes()
            ->with(['brief', 'events'])
            ->latest();

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterService) {
            $query->where('service_type', $this->filterService);
        }

        if ($this->filterBudget) {
            $query->where('budget_range', $this->filterBudget);
        }

        if ($this->onlyHot) {
            $query->where('score', '>=', 80);
        }

        return view('livewire.admin.lead-inbox', [
            'leads' => $query->paginate(25),
            'statuses' => LeadStatus::cases(),
            'services' => ServiceType::cases(),
        ]);
    }
}
