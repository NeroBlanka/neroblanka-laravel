<?php

namespace App\Services;

use App\Jobs\SendAssignmentCreated;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;
use App\Scopes\FreelanceOwnedScope;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function create(Project $project, User $freelance, ?string $internalNotes = null): Assignment
    {
        $assignment = DB::transaction(function () use ($project, $freelance, $internalNotes) {
            $alreadyAssigned = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)
                ->where('project_id', $project->id)
                ->where('freelance_id', $freelance->id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->exists();

            abort_if($alreadyAssigned, 422, 'Ce freelance est déjà actif sur ce projet.');

            $assignment = Assignment::withoutGlobalScope(FreelanceOwnedScope::class)->create([
                'project_id' => $project->id,
                'freelance_id' => $freelance->id,
                'status' => 'active',
                'internal_notes' => $internalNotes,
            ]);

            $project->update(['status' => 'assigned']);

            return $assignment;
        });

        SendAssignmentCreated::dispatch($freelance, $assignment)->onQueue('emails')->afterCommit();

        return $assignment;
    }
}
