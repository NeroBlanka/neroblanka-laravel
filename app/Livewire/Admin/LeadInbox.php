<?php

namespace App\Livewire\Admin;

use App\Enums\LeadStatus;
use App\Enums\NoFitReason;
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
    public string $noFitReason = '';

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterService(): void { $this->resetPage(); }
    public function updatingOnlyHot(): void { $this->resetPage(); }

    public function changeStatus(string $leadId, string $status, LeadService $leadService): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $lead = Lead::findOrFail($leadId);
        $newStatus = LeadStatus::from($status);

        $noFitReason = ($newStatus === LeadStatus::NO_FIT && $this->noFitReason)
            ? NoFitReason::from($this->noFitReason)
            : null;

        $leadService->changeStatus($lead, $newStatus, $this->statusNote ?: null, auth()->id(), $noFitReason);
        $this->statusNote = '';
        $this->noFitReason = '';
    }

    public function addNote(string $leadId, LeadService $leadService): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $this->validate(['newNote' => 'required|string|min:2|max:2000']);
        $lead = Lead::findOrFail($leadId);
        $leadService->addNote($lead, $this->newNote, auth()->id());
        $this->newNote = '';
    }

    public function render()
    {
        $query = Lead::query()
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
            'noFitReasons' => NoFitReason::cases(),
        ]);
    }
}
