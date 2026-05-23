<?php

namespace App\Http\Controllers;

use App\Models\Deliverable;
use App\Services\DeliverableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeliverableController extends Controller
{
    public function __construct(private readonly DeliverableService $deliverableService) {}

    public function approve(Deliverable $deliverable): RedirectResponse
    {
        $this->authorize('approve', $deliverable);

        $this->deliverableService->approve($deliverable);

        return back()->with('success', 'Livrable approuvé.');
    }

    public function revision(Request $request, Deliverable $deliverable): RedirectResponse
    {
        $this->authorize('revision', $deliverable);

        $request->validate([
            'revision_notes' => ['required', 'string', 'max:2000'],
        ]);

        $this->deliverableService->revision($deliverable, $request->input('revision_notes'));

        return back()->with('success', 'Révision demandée.');
    }
}
