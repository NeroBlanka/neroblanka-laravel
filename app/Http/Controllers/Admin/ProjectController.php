<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        $project->load([
            'client',
            'assignments.freelance',
            'assignments.deliverables',
        ]);

        $availableFreelances = User::where('role', 'freelance')
            ->where('is_available', true)
            ->orderBy('full_name')
            ->get();

        return view('admin.projects.show', compact('project', 'availableFreelances'));
    }
}
