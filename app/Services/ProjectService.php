<?php

namespace App\Services;

use App\Jobs\SendProjectBriefReceived;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    public function create(User $client, array $data, ?UploadedFile $briefFile = null): Project
    {
        if ($briefFile) {
            $data['brief_file_url'] = Storage::disk('s3')->put('briefs', $briefFile);
        }

        $project = Project::create(array_merge($data, ['client_id' => $client->id]));

        $admin = User::withoutGlobalScopes()->where('role', 'admin')->first();
        if ($admin) {
            SendProjectBriefReceived::dispatch($admin, $project)->onQueue('emails');
        }

        return $project;
    }

    public function updateStatus(Project $project, string $status): Project
    {
        $project->update(['status' => $status]);
        return $project;
    }
}
