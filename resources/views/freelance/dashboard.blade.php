<x-app-layout>
    <x-slot name="title">Mes missions — Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-10">

        <div class="mb-10">
            <p class="label-mono mb-2">Espace freelance</p>
            <h1 class="text-3xl">Vos missions actives</h1>
        </div>

        @if($assignments->isEmpty())
            <div class="card px-6 py-16 text-center text-[#888780]">
                Aucune mission active pour le moment.
            </div>
        @else
            <div class="space-y-3">
                @foreach($assignments as $assignment)
                    <div class="card px-6 py-5 flex items-center justify-between gap-4 hover:border-black/20 transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <p class="font-medium truncate">{{ $assignment->project->title ?? '—' }}</p>
                                <x-status-badge :status="is_string($assignment->status) ? $assignment->status : ($assignment->status?->value ?? 'active')" />
                            </div>
                            <p class="text-xs text-[#888780]">
                                Client : {{ $assignment->project->client->full_name ?? '—' }}
                                @if($assignment->assigned_at)
                                    &middot; Assigné le {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('freelance.deliverables.create', $assignment->id) }}"
                           class="text-sm text-[#888780] hover:text-[#0a0a0a] transition shrink-0">
                            Voir la mission &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
