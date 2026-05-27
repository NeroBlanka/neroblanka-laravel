<x-app-layout>
    <x-slot name="title">Projets — Neroblanka Admin</x-slot>

    <div class="max-w-5xl mx-auto px-6 py-14">

        <div class="mb-12">
            <p class="label-mono mb-3">Admin · Production</p>
            <h1 class="text-4xl" style="color: var(--perle)">Projets</h1>
        </div>

        @if($projects->isEmpty())
            <div class="card p-12 text-center">
                <p class="text-sm" style="color: var(--gris-mid)">Aucun projet pour le moment.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($projects as $project)
                    @php
                        $statusValue = $project->status instanceof \BackedEnum ? $project->status->value : (string) $project->status;
                        $activeAssignment = $project->assignments->first();
                    @endphp
                    <a href="{{ route('admin.projects.show', $project) }}"
                       class="card px-6 py-5 flex items-center justify-between gap-6 hover:opacity-80 transition-opacity block">
                        <div class="min-w-0">
                            <p class="font-medium truncate" style="color: var(--perle)">{{ $project->title }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--gris-mid)">
                                {{ $project->client->full_name ?? '—' }}
                                @if($activeAssignment)
                                    <span class="mx-1.5 opacity-40">·</span>{{ $activeAssignment->freelance->full_name ?? '—' }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-4 shrink-0">
                            @if($project->deadline)
                                <p class="text-xs" style="color: var(--gris-mid)">
                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}
                                </p>
                            @endif
                            <x-status-badge :status="$statusValue" />
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
