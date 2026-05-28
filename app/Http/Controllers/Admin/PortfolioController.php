<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $items = PortfolioItem::latest()->paginate(20);

        return view('admin.portfolio.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.portfolio.form', [
            'item'     => null,
            'services' => ServiceType::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $item = PortfolioItem::create($data);

        $this->handleFiles($request, $item);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Projet créé.');
    }

    public function edit(PortfolioItem $portfolio): View
    {
        return view('admin.portfolio.form', [
            'item'     => $portfolio,
            'services' => ServiceType::cases(),
        ]);
    }

    public function update(Request $request, PortfolioItem $portfolio): RedirectResponse
    {
        $data = $this->validated($request, $portfolio);

        $portfolio->update($data);

        $this->handleFiles($request, $portfolio);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Projet mis à jour.');
    }

    public function destroy(PortfolioItem $portfolio): RedirectResponse
    {
        if ($portfolio->cover_image) {
            Storage::disk('s3_public')->delete($portfolio->cover_image);
        }

        foreach ($portfolio->gallery ?? [] as $path) {
            Storage::disk('s3_public')->delete($path);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Projet supprimé.');
    }

    public function togglePublished(PortfolioItem $portfolio): RedirectResponse
    {
        $portfolio->update([
            'published_at' => $portfolio->published_at ? null : today(),
        ]);

        return back()->with('success', $portfolio->published_at ? 'Projet publié.' : 'Projet dépublié.');
    }

    private function validated(Request $request, ?PortfolioItem $existing = null): array
    {
        $slugRule = 'required|string|max:200|unique:portfolio_items,slug'
            . ($existing ? ",{$existing->id},id" : '');

        $data = $request->validate([
            'title'        => 'required|string|max:200',
            'slug'         => $slugRule,
            'client_name'  => 'required|string|max:200',
            'service_type' => 'required|string|in:' . implode(',', array_column(ServiceType::cases(), 'value')),
            'excerpt'      => 'required|string|max:500',
            'content'      => 'nullable|string',
            'tags'         => 'nullable|string|max:500',
            'featured'     => 'boolean',
            'is_concept'   => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Tags: comma-separated string → array
        $data['tags'] = $data['tags']
            ? array_values(array_filter(array_map('trim', explode(',', $data['tags']))))
            : null;

        $data['featured'] = $request->boolean('featured');
        $data['is_concept'] = $request->boolean('is_concept');

        return $data;
    }

    private function handleFiles(Request $request, PortfolioItem $item): void
    {
        if ($request->hasFile('cover_image')) {
            $request->validate(['cover_image' => 'image|max:5120']);

            if ($item->cover_image) {
                Storage::disk('s3_public')->delete($item->cover_image);
            }

            $path = $request->file('cover_image')->store("portfolio/{$item->id}/cover", 's3_public');
            $item->update(['cover_image' => $path]);
        }

        if ($request->hasFile('gallery')) {
            $request->validate(['gallery.*' => 'image|max:8192']);

            $existing = $item->gallery ?? [];

            foreach ($request->file('gallery') as $file) {
                $existing[] = $file->store("portfolio/{$item->id}/gallery", 's3_public');
            }

            $item->update(['gallery' => $existing]);
        }

        // Remove individual gallery images
        if ($request->filled('remove_gallery')) {
            $toRemove = $request->input('remove_gallery', []);
            $gallery  = array_values(array_filter(
                $item->gallery ?? [],
                fn($p) => ! in_array($p, $toRemove)
            ));

            foreach ($toRemove as $path) {
                Storage::disk('s3_public')->delete($path);
            }

            $item->update(['gallery' => $gallery ?: null]);
        }
    }
}
