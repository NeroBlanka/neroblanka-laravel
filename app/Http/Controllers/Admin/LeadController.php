<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Enums\NoFitReason;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        return view('admin.leads.index');
    }

    public function show(Lead $lead)
    {
        $lead->load(['brief', 'files', 'events.user', 'project']);
        return view('admin.leads.show', compact('lead'));
    }

    public function convert(Lead $lead, LeadService $leadService): RedirectResponse
    {
        $project = $leadService->convertToProject($lead, auth()->id());

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Lead converti en projet avec succès.');
    }

    public function markNoFit(Request $request, Lead $lead, LeadService $leadService): RedirectResponse
    {
        $validated = $request->validate([
            'no_fit_reason' => 'required|in:' . implode(',', array_column(NoFitReason::cases(), 'value')),
            'note' => 'nullable|string|max:500',
        ]);

        $leadService->changeStatus(
            $lead,
            LeadStatus::NO_FIT,
            $validated['note'] ?? null,
            auth()->id(),
            NoFitReason::from($validated['no_fit_reason']),
        );

        return back()->with('success', 'Lead marqué comme non compatible.');
    }
}
