<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BriefController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function create(): View
    {
        $services = Service::where('is_active', true)->get();

        return view('client.brief.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'service_type' => ['nullable', 'in:identite_visuelle,direction_3d_ia,contenu_mensuel,marketing_digital,autre'],
            'budget_da' => ['nullable', 'integer', 'min:0'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'brief_file' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,zip', 'max:10240'],
            'reference_urls' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $project = $this->projectService->create(
            auth()->user(),
            collect($validated)->except('brief_file')->toArray(),
            $request->file('brief_file'),
        );

        return redirect()->route('client.projects.show', $project)->with('success', 'Brief envoyé avec succès.');
    }
}
