<?php

namespace App\Http\Controllers\Freelance;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Services\DeliverableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DeliverableController extends Controller
{
    public function __construct(private readonly DeliverableService $deliverableService) {}

    public function create(Assignment $assignment): View
    {
        $this->authorize('view', $assignment);

        $assignment->load(['project', 'deliverables' => fn($q) => $q->orderByDesc('version')]);

        $deliverables = $assignment->deliverables;

        $deliverableUrls = $deliverables
            ->filter(fn($d) => $d->file_url)
            ->mapWithKeys(fn($d) => [
                $d->id => rescue(fn() => Storage::disk('s3')->temporaryUrl($d->file_url, now()->addMinutes(30)), null, false),
            ])
            ->filter();

        return view('freelance.deliverables.create', compact('assignment', 'deliverables', 'deliverableUrls'));
    }

    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorize('view', $assignment);

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg,zip', 'max:10240'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->deliverableService->submit(
            $assignment,
            $request->file('file'),
            $request->input('message'),
        );

        return redirect()->route('freelance.dashboard')->with('success', 'Livrable soumis avec succès.');
    }
}
