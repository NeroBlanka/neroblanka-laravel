<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Services\AssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(private readonly AssignmentService $assignmentService) {}

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'uuid'],
            'freelance_id' => ['required', 'uuid', 'exists:users,id'],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $project = Project::withoutGlobalScopes()->findOrFail($validated['project_id']);
        $freelance = User::findOrFail($validated['freelance_id']);

        abort_unless($freelance->role === 'freelance', 422, 'L\'utilisateur sélectionné n\'est pas un freelance.');

        $this->assignmentService->create($project, $freelance, $validated['internal_notes'] ?? null);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Freelance assigné avec succès.');
    }
}
