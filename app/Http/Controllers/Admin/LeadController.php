<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\Admin\LeadInbox;
use App\Models\Lead;

class LeadController extends Controller
{
    public function index()
    {
        return view('admin.leads.index');
    }

    public function show(Lead $lead)
    {
        $lead->load(['brief', 'files', 'events.user']);
        return view('admin.leads.show', compact('lead'));
    }
}
