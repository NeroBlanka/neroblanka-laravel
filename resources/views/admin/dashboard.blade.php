<x-app-layout>
    <x-slot name="title">Dashboard — Neroblanka Admin</x-slot>

    <div class="max-w-6xl mx-auto px-6 py-14">

        <div class="mb-12">
            <p class="label-mono mb-3">Admin</p>
            <h1 class="text-4xl" style="color: var(--carbone)">Dashboard</h1>
        </div>

        {{-- Stats 3D --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            @foreach([
                ['Total projets',      $stats['total'] ?? 0,             null],
                ['En attente',         $stats['pending'] ?? 0,           '#fbbf24'],
                ['Freelances actifs',  $stats['active_freelances'] ?? 0, '#34d399'],
                ['Terminés ce mois',   $stats['completed_month'] ?? 0,   '#60a5fa'],
            ] as [$label, $value, $accent])
                <div class="stat-card neo-tilt-card relative overflow-hidden">
                    @if($accent)
                        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl"
                             style="background: {{ $accent }}; opacity: 0.5; box-shadow: 0 0 12px {{ $accent }}"></div>
                    @endif
                    <p class="label-mono mb-4">{{ $label }}</p>
                    <p class="text-5xl font-semibold tracking-tight stat-number">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        {{-- Projects table --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-5 flex items-center justify-between"
                 style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                <h2 class="text-sm font-semibold" style="color: var(--carbone)">Projets</h2>
                @if(($stats['leads_new'] ?? 0) > 0)
                    <a href="{{ route('admin.leads') }}" class="flex items-center gap-2 text-xs transition-opacity hover:opacity-70"
                       style="color: #15803d">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ $stats['leads_new'] }} nouveau{{ $stats['leads_new'] > 1 ? 'x' : '' }} lead{{ $stats['leads_new'] > 1 ? 's' : '' }}
                    </a>
                @endif
            </div>

            @if($projects->isEmpty())
                <div class="px-6 py-16 text-center text-sm" style="color: var(--gris-mid)">
                    Aucun projet pour le moment.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                                <th class="text-left px-6 py-3 label-mono">Projet</th>
                                <th class="text-left px-6 py-3 label-mono">Client</th>
                                <th class="text-left px-6 py-3 label-mono">Date</th>
                                <th class="text-left px-6 py-3 label-mono">Statut</th>
                                <th class="text-left px-6 py-3 label-mono">Assigner</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                @php $sv = $project->status instanceof \BackedEnum ? $project->status->value : (string) $project->status; @endphp
                                <tr class="transition-colors hover:bg-black/[0.03]"
                                    style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                                    <td class="px-6 py-4 font-medium" style="color: var(--carbone)">{{ $project->title }}</td>
                                    <td class="px-6 py-4">
                                        <span style="color: var(--gris)">{{ $project->client->full_name ?? '—' }}</span>
                                        @if($project->client->company ?? null)
                                            <span class="block text-xs" style="color: var(--gris-mid)">{{ $project->client->company }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs" style="color: var(--gris-mid)">{{ $project->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4"><x-status-badge :status="$sv" /></td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.assignments.store') }}" class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="project_id" value="{{ $project->id }}" />
                                            <select name="freelance_id" class="select-base text-xs">
                                                <option value="">Choisir…</option>
                                                @foreach($freelances as $f)
                                                    @if($f->is_available)
                                                        <option value="{{ $f->id }}">{{ $f->full_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn-primary text-xs px-3 py-2 whitespace-nowrap">Assigner</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.projects.show', $project->id) }}"
                                           class="text-xs transition-colors hover:opacity-70"
                                           style="color: var(--gris)">Détail →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($projects->hasPages())
                    <div class="px-6 py-4" style="border-top: 1px solid rgba(5,5,5,0.07)">
                        {{ $projects->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
