<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'projects_total' => Project::withoutGlobalScopes()->count(),
            'projects_pending' => Project::withoutGlobalScopes()->where('status', 'pending')->count(),
            'projects_in_progress' => Project::withoutGlobalScopes()->whereIn('status', ['assigned', 'in_progress'])->count(),
            'freelances_available' => User::where('role', 'freelance')->where('is_available', true)->count(),
        ];

        $projects = Project::withoutGlobalScopes()
            ->with('client')
            ->latest()
            ->paginate(20);

        $freelances = User::where('role', 'freelance')
            ->orderBy('full_name')
            ->get();

        return view('admin.dashboard', compact('stats', 'projects', 'freelances'));
    }
}
