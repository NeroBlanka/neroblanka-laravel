<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('assignments')
            ->latest()
            ->paginate(10);

        return view('client.dashboard', compact('projects'));
    }
}
