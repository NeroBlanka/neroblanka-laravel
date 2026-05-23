<?php

namespace App\Http\Controllers\Freelance;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Services\DeliverableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliverableController extends Controller
{
    public function __construct(private readonly DeliverableService $deliverableService) {}

    public function create(Assignment $assignment): View
    {
        $this->authorize('view', $assignment);

        $assignment->load('project');

        return view('freelance.deliverables.create', compact('assignment'));
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
