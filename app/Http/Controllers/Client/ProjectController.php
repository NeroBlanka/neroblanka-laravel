<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DeliverableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly DeliverableService $deliverableService) {}

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
                $d->id => Storage::disk('r2')->temporaryUrl($d->file_url, now()->addMinutes(30)),
            ]);

        return view('client.projects.show', compact('project', 'deliverables', 'deliverableUrls'));
    }

    public function approve(Project $project): RedirectResponse
    {
        $this->authorize('view', $project);

        $deliverable = $project->assignments()
            ->with('deliverables')
            ->get()
            ->flatMap(fn($a) => $a->deliverables)
            ->whereNull('approved_at')
            ->sortByDesc('version')
            ->first();

        abort_unless($deliverable, 404);

        $this->deliverableService->approve($deliverable);

        return back()->with('success', 'Livrable approuvé.');
    }

    public function revision(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('view', $project);

        $request->validate([
            'revision_notes' => ['required', 'string', 'max:2000'],
        ]);

        $deliverable = $project->assignments()
            ->with('deliverables')
            ->get()
            ->flatMap(fn($a) => $a->deliverables)
            ->whereNull('approved_at')
            ->sortByDesc('version')
            ->first();

        abort_unless($deliverable, 404);

        $this->deliverableService->revision($deliverable, $request->input('revision_notes'));

        return back()->with('success', 'Révision demandée.');
    }
}
