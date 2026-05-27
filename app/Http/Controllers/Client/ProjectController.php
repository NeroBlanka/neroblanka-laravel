<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load([
            'assignments.freelance',
            'assignments.deliverables' => fn($q) => $q->orderByDesc('version'),
        ]);

        $deliverables = $project->assignments
            ->flatMap(fn($a) => $a->deliverables)
            ->sortByDesc('version')
            ->values();

        $deliverableUrls = $deliverables
            ->filter(fn($d) => $d->file_url)
            ->mapWithKeys(fn($d) => [
                $d->id => Storage::disk('s3')->temporaryUrl($d->file_url, now()->addMinutes(30)),
            ]);

        return view('client.projects.show', compact('project', 'deliverables', 'deliverableUrls'));
    }
}
