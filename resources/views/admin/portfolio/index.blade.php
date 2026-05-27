<x-app-layout>
    <x-slot name="title">Portfolio — Admin Neroblanka</x-slot>

    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="label-mono mb-1">Admin</p>
                <h1 class="text-3xl" style="color: var(--perle)">Portfolio</h1>
            </div>
            <a href="{{ route('admin.portfolio.create') }}" class="btn-primary text-sm">+ Nouveau projet</a>
        </div>

        @if($items->isEmpty())
            <div class="card px-6 py-16 text-center text-sm" style="color: var(--gris-mid)">
                Aucun projet dans le portfolio.
            </div>
        @else
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.06)">
                                <th class="text-left px-5 py-3 label-mono">Titre · Client</th>
                                <th class="text-left px-5 py-3 label-mono">Service</th>
                                <th class="text-center px-5 py-3 label-mono">Mise en avant</th>
                                <th class="text-left px-5 py-3 label-mono">Statut</th>
                                <th class="text-left px-5 py-3 label-mono">Date publi.</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="transition-colors hover:bg-white/[0.025]"
                                    style="border-bottom: 1px solid rgba(255,255,255,0.04)">
                                    <td class="px-5 py-3.5">
                                        <p class="font-medium" style="color: var(--perle)">{{ $item->title }}</p>
                                        <p class="text-xs mt-0.5" style="color: var(--gris-mid)">{{ $item->client_name }}</p>
                                    </td>
                                    <td class="px-5 py-3.5" style="color: var(--gris)">{{ $item->service_type->label() }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if($item->featured)
                                            <span style="color: var(--accent-warm, #c8a76a)">★</span>
                                        @else
                                            <span style="color: var(--gris-mid)">★</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($item->published_at && $item->published_at <= today())
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-lg"
                                                  style="color:#6ee7b7; background:rgba(52,211,153,0.08); border:1px solid rgba(52,211,153,0.25)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Publié
                                            </span>
                                        @elseif($item->published_at)
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-lg"
                                                  style="color:#60a5fa; background:rgba(96,165,250,0.08); border:1px solid rgba(96,165,250,0.25)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Planifié
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-lg"
                                                  style="color:var(--gris); background:rgba(122,120,117,0.06); border:1px solid rgba(122,120,117,0.2)">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background:var(--gris-mid)"></span> Brouillon
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-xs" style="color: var(--gris-mid)">
                                        {{ $item->published_at?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('work.show', $item->slug) }}" target="_blank"
                                               class="text-xs transition-opacity hover:opacity-60" style="color: var(--gris)">↗</a>
                                            <a href="{{ route('admin.portfolio.edit', $item) }}"
                                               class="text-xs transition-opacity hover:opacity-60" style="color: var(--perle)">Éditer</a>
                                            <form method="POST" action="{{ route('admin.portfolio.toggle-published', $item) }}">
                                                @csrf
                                                <button type="submit" class="text-xs transition-opacity hover:opacity-60 cursor-pointer"
                                                        style="color: {{ $item->published_at ? '#fca5a5' : '#6ee7b7' }}">
                                                    {{ $item->published_at ? 'Dépublier' : 'Publier' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.portfolio.destroy', $item) }}"
                                                  onsubmit="return confirm('Supprimer ce projet ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs transition-opacity hover:opacity-60 cursor-pointer"
                                                        style="color: #fca5a5">✕</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $items->links() }}</div>
        @endif
    </div>
</x-app-layout>
