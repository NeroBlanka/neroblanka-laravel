<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\RedirectResponse;

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
}
