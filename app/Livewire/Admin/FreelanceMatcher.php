<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Services\AssignmentService;
use App\Services\FreelanceMatchingService;
use Livewire\Attributes\On;
use Livewire\Component;

class FreelanceMatcher extends Component
{
    public string $projectId;
    public string $message = '';
    public bool $success = false;

    public function mount(string $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function assign(string $freelanceId, FreelanceMatchingService $matcher, AssignmentService $assignmentService): void
    {
        $project = Project::withoutGlobalScopes()->findOrFail($this->projectId);
        $freelance = \App\Models\User::withoutGlobalScopes()->findOrFail($freelanceId);

        abort_unless($freelance->role === 'freelance', 422);

        $assignmentService->create($project, $freelance, null);

        $this->success = true;
        $this->message = $freelance->full_name . ' assigné avec succès.';
    }

    public function render(FreelanceMatchingService $matcher): \Illuminate\View\View
    {
        $project = Project::withoutGlobalScopes()->findOrFail($this->projectId);
        $ranked = $matcher->rankForProject($project);

        return view('livewire.admin.freelance-matcher', compact('ranked', 'project'));
    }
}
