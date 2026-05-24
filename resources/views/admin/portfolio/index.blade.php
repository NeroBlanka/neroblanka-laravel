<x-app-layout>
    <x-slot name="title">Portfolio — Admin Neroblanka</x-slot>

    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="label-mono mb-1">Admin</p>
                <h1 class="text-2xl font-semibold text-[#0a0a0a]">Portfolio</h1>
            </div>
            <a href="{{ route('admin.portfolio.create') }}" class="btn-primary text-sm">
                + Nouveau projet
            </a>
        </div>

        @if($items->isEmpty())
            <div class="card px-6 py-16 text-center text-[#888780]">
                Aucun projet dans le portfolio.
            </div>
        @else
            <div class="card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-[#0a0a0a] text-white text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Titre · Client</th>
                            <th class="px-4 py-3 text-left font-medium">Service</th>
                            <th class="px-4 py-3 text-center font-medium">Mise en avant</th>
                            <th class="px-4 py-3 text-left font-medium">Statut</th>
                            <th class="px-4 py-3 text-left font-medium">Date publi.</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.06]">
                        @foreach($items as $item)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-[#0a0a0a]">{{ $item->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->client_name }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $item->service_type instanceof \App\Enums\ServiceType
                                        ? $item->service_type->label()
                                        : $item->service_type }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($item->featured)
                                        <span class="text-yellow-500 text-base">★</span>
                                    @else
                                        <span class="text-gray-200">★</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($item->published_at && $item->published_at <= today())
                                        <span class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-50 px-2 py-0.5 rounded">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Publié
                                        </span>
                                    @elseif($item->published_at)
                                        <span class="inline-flex items-center gap-1 text-xs text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                                            <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span> Planifié
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Brouillon
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-400 text-xs">
                                    {{ $item->published_at?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('work.show', $item->slug) }}" target="_blank"
                                           class="text-xs text-gray-400 hover:text-gray-700 transition">
                                            ↗
                                        </a>
                                        <a href="{{ route('admin.portfolio.edit', $item) }}"
                                           class="text-xs text-[#0a0a0a] underline hover:no-underline">
                                            Éditer
                                        </a>
                                        <form method="POST" action="{{ route('admin.portfolio.toggle-published', $item) }}">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs {{ $item->published_at ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }} transition">
                                                {{ $item->published_at ? 'Dépublier' : 'Publier' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.portfolio.destroy', $item) }}"
                                              onsubmit="return confirm('Supprimer ce projet ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
