<?php

namespace App\Livewire\Admin;

use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;
use App\Scopes\ClientOwnedScope;
use App\Scopes\FreelanceOwnedScope;
use App\Services\AssignmentService;
use App\Services\FreelanceMatchingService;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FreelanceMatcher extends Component
{
    public string $projectId;
    public string $message = '';
    public bool $success = false;

    public function mount(string $projectId): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->projectId = $projectId;
    }

    public function assign(string $freelanceId, AssignmentService $assignmentService): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->findOrFail($this->projectId);
        $freelance = User::findOrFail($freelanceId);

        abort_unless($freelance->role === 'freelance', 422);

        try {
            $assignmentService->create($project, $freelance, null);
        } catch (HttpException $e) {
            $this->success = false;
            $this->message = $e->getMessage() ?: "Impossible d'assigner ce freelance.";
            return;
        }

        $this->success = true;
        $this->message = $freelance->full_name . ' assigné avec succès.';
    }

    public function render(FreelanceMatchingService $matcher): \Illuminate\View\View
    {
        $project = Project::withoutGlobalScope(ClientOwnedScope::class)->findOrFail($this->projectId);
        $ranked = $matcher->rankForProject($project);

        $assignedIds = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)
            ->where('project_id', $this->projectId)
            ->where('status', 'active')
            ->pluck('freelance_id')
            ->all();

        return view('livewire.admin.freelance-matcher', compact('ranked', 'project', 'assignedIds'));
    }
}
