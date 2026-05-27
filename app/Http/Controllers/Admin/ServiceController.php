<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('name')->get();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.form', [
            'service'      => new Service(),
            'serviceTypes' => ServiceType::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service créé.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', [
            'service'      => $service,
            'serviceTypes' => ServiceType::options(),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $this->validated($request);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service supprimé.');
    }

    public function toggleActive(Service $service): RedirectResponse
    {
        $service->update(['is_active' => ! $service->is_active]);

        return back();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'price_da'      => ['required', 'integer', 'min:0'],
            'delivery_days' => ['required', 'integer', 'min:1'],
            'type'          => ['nullable', new Enum(ServiceType::class)],
            'is_active'     => ['boolean'],
        ]);
    }
}
