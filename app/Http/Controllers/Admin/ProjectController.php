<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Scopes\ClientOwnedScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::withoutGlobalScope(ClientOwnedScope::class)
            ->with(['client', 'assignments' => fn($q) => $q->where('status', 'active')->with('freelance')])
            ->latest()
            ->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $project->load([
            'client',
            'assignments.freelance',
            'assignments.deliverables' => fn($q) => $q->orderByDesc('version'),
        ]);

        $deliverables = $project->assignments
            ->flatMap(fn($a) => $a->deliverables)
            ->sortByDesc('version')
            ->values();

        $assignment = $project->assignments->sortByDesc('created_at')->first();

        $deliverableUrls = $deliverables
            ->filter(fn($d) => $d->file_url)
            ->mapWithKeys(fn($d) => [
                $d->id => rescue(fn() => Storage::disk('s3')->temporaryUrl($d->file_url, now()->addMinutes(30)), null),
            ])
            ->filter();

        return view('admin.projects.show', compact('project', 'deliverables', 'deliverableUrls', 'assignment'));
    }
}
