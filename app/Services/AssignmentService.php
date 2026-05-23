<?php

namespace App\Services;

use App\Jobs\SendAssignmentCreated;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function create(Project $project, User $freelance, ?string $internalNotes = null): Assignment
    {
        $assignment = DB::transaction(function () use ($project, $freelance, $internalNotes) {
            $assignment = Assignment::withoutGlobalScopes()->create([
                'project_id' => $project->id,
                'freelance_id' => $freelance->id,
                'status' => 'active',
                'internal_notes' => $internalNotes,
            ]);

            $project->update(['status' => 'assigned']);

            return $assignment;
        });

        SendAssignmentCreated::dispatch($freelance, $assignment)->onQueue('emails');

        return $assignment;
    }
}
