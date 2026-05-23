<?php

namespace App\Http\Controllers\Freelance;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $assignments = Assignment::with(['project.client', 'deliverables'])
            ->where('status', 'active')
            ->latest('assigned_at')
            ->get();

        return view('freelance.dashboard', compact('assignments'));
    }
}
