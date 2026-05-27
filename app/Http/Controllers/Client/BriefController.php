<?php

namespace App\Http\Controllers\Client;

use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class BriefController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function create(): View
    {
        $services = ServiceType::options();

        return view('client.brief.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'service_type'   => ['nullable', new Enum(ServiceType::class)],
            'budget_da'      => ['nullable', 'integer', 'min:0'],
            'deadline'       => ['nullable', 'date', 'after:today'],
            'brief_file'     => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,zip', 'max:10240'],
            'reference_urls' => ['nullable', 'string'],
            'notes'          => ['nullable', 'string'],
        ]);

        $project = $this->projectService->create(
            auth()->user(),
            collect($validated)->except('brief_file')->toArray(),
            $request->file('brief_file'),
        );

        return redirect()->route('client.projects.show', $project)->with('success', 'Brief envoyé avec succès.');
    }
}
