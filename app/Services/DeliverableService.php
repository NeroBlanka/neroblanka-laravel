<?php

namespace App\Services;

use App\Jobs\SendDeliverableApproved;
use App\Jobs\SendDeliverableRevision;
use App\Jobs\SendDeliverableSubmitted;
use App\Models\Assignment;
use App\Models\Deliverable;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeliverableService
{
    public function submit(Assignment $assignment, UploadedFile $file, ?string $message = null): Deliverable
    {
        $deliverable = DB::transaction(function () use ($assignment, $file, $message) {
            $path = Storage::disk('s3')->put('deliverables', $file);

            $lastVersion = Deliverable::where('assignment_id', $assignment->id)->max('version') ?? 0;

            $deliverable = Deliverable::create([
                'assignment_id' => $assignment->id,
                'file_url' => $path,
                'file_name' => $file->getClientOriginalName(),
                'message' => $message,
                'version' => $lastVersion + 1,
            ]);

            $assignment->project->update(['status' => 'submitted']);

            return $deliverable;
        });

        SendDeliverableSubmitted::dispatch($assignment->project->client, $deliverable)->onQueue('emails');

        return $deliverable;
    }

    public function approve(Deliverable $deliverable): Deliverable
    {
        $locked = DB::transaction(function () use ($deliverable) {
            $locked = Deliverable::lockForUpdate()->findOrFail($deliverable->id);
            $locked->update(['approved_at' => now()]);

            $locked->assignment->update(['status' => 'completed']);
            $locked->assignment->project->update(['status' => 'approved']);

            return $locked;
        });

        SendDeliverableApproved::dispatch($locked->assignment->freelance, $locked)->onQueue('emails');

        return $locked;
    }

    public function revision(Deliverable $deliverable, string $revisionNotes): Deliverable
    {
        $locked = DB::transaction(function () use ($deliverable, $revisionNotes) {
            $locked = Deliverable::lockForUpdate()->findOrFail($deliverable->id);
            $locked->update(['revision_notes' => $revisionNotes]);

            $locked->assignment->project->update(['status' => 'revision']);

            return $locked;
        });

        SendDeliverableRevision::dispatch($locked->assignment->freelance, $locked)->onQueue('emails');

        return $locked;
    }
}
