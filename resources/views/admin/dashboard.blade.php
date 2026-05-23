<x-app-layout>
    <x-slot name="title">Dashboard Admin — Neroblanka</x-slot>

    {{-- Admin header bar --}}
    <div class="bg-[#0a0a0a] text-white">
        <div class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-xl text-white">Dashboard</h1>
                <span class="label-mono text-[#888780]">Admin</span>
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('admin.freelances') }}" class="text-sm text-[#b8b6b0] hover:text-white px-3 py-1.5 rounded hover:bg-white/10 transition">
                    Freelances
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-[#b8b6b0] hover:text-white px-3 py-1.5 rounded hover:bg-white/10 transition">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-10">

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div class="card p-6">
                <p class="label-mono mb-2">Total projets</p>
                <p class="text-3xl font-semibold" style="font-family: 'Clash Grotesk', system-ui, sans-serif;">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="card p-6">
                <p class="label-mono mb-2">En attente</p>
                <p class="text-3xl font-semibold" style="font-family: 'Clash Grotesk', system-ui, sans-serif;">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="card p-6">
                <p class="label-mono mb-2">Freelances actifs</p>
                <p class="text-3xl font-semibold" style="font-family: 'Clash Grotesk', system-ui, sans-serif;">{{ $stats['active_freelances'] ?? 0 }}</p>
            </div>
            <div class="card p-6">
                <p class="label-mono mb-2">Terminés ce mois</p>
                <p class="text-3xl font-semibold" style="font-family: 'Clash Grotesk', system-ui, sans-serif;">{{ $stats['completed_month'] ?? 0 }}</p>
            </div>
        </div>

        {{-- Projects table --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-black/[0.08]">
                <h2 class="text-lg">Projets</h2>
            </div>

            @if($projects->isEmpty())
                <div class="px-6 py-12 text-center text-[#888780]">
                    Aucun projet pour le moment.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-black/[0.08]">
                                <th class="text-left px-6 py-3 label-mono">Projet</th>
                                <th class="text-left px-6 py-3 label-mono">Client</th>
                                <th class="text-left px-6 py-3 label-mono">Date</th>
                                <th class="text-left px-6 py-3 label-mono">Statut</th>
                                <th class="text-left px-6 py-3 label-mono">Assigner</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.06]">
                            @foreach($projects as $project)
                                <tr class="hover:bg-[#e8e7e2]/30 transition-colors">
                                    <td class="px-6 py-4 font-medium">{{ $project->title }}</td>
                                    <td class="px-6 py-4 text-[#555350]">
                                        <span>{{ $project->client->full_name ?? '—' }}</span>
                                        @if($project->client->company ?? null)
                                            <span class="block text-xs text-[#888780]">{{ $project->client->company }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-[#888780]">{{ $project->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <x-status-badge :status="$project->status" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.assignments.store') }}" class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="project_id" value="{{ $project->id }}" />
                                            <select name="freelance_id" class="text-sm border border-black/20 rounded px-3 py-1.5 focus:outline-none focus:border-[#0a0a0a] bg-white">
                                                <option value="">Choisir…</option>
                                                @foreach($freelances as $freelance)
                                                    @if($freelance->is_available)
                                                        <option value="{{ $freelance->id }}">{{ $freelance->full_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Assigner</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.projects.show', $project->id) }}"
                                           class="text-sm text-[#888780] hover:text-[#0a0a0a] transition">
                                            Détail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($projects->hasPages())
                    <div class="px-6 py-4 border-t border-black/[0.08]">
                        {{ $projects->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</x-app-layout>
