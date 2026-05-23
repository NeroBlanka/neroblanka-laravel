<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Project::withoutGlobalScopes()->count(),
            'pending' => Lead::where('status', 'new')->orWhere('status', 'in_review')->count(),
            'active_freelances' => User::withoutGlobalScopes()->where('role', 'freelance')->where('is_available', true)->count(),
            'completed_month' => Project::withoutGlobalScopes()->where('status', 'approved')->whereMonth('updated_at', now()->month)->count(),
            'leads_new' => Lead::where('status', 'new')->count(),
            'leads_qualified' => Lead::where('status', 'qualified')->count(),
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
