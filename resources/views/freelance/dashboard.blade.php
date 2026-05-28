<x-app-layout>
    <x-slot name="title">Mes missions — Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-14">
        <div class="mb-12">
            <p class="label-mono mb-3">Espace freelance</p>
            <h1 class="font-clash text-4xl md:text-5xl font-semibold text-carbone">Vos missions actives</h1>
        </div>

        @if($assignments->isEmpty())
            <div class="card-on-perle rounded-3xl p-16 flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 neo-sunken">
                    <svg class="w-6 h-6" style="color: var(--gris-texte-soft)" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                </div>
                <p class="text-base text-gris">Aucune mission active pour le moment.</p>
                <p class="text-sm text-gris-soft mt-1">Les missions assignées par Neroblanka apparaîtront ici.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($assignments as $assignment)
                    @php $sv = $assignment->status instanceof \BackedEnum ? $assignment->status->value : ($assignment->status ?? 'active'); @endphp
                    <a href="{{ route('freelance.assignments.show', $assignment->id) }}"
                       class="card-interactive rounded-3xl flex items-center justify-between gap-4 px-6 py-5 group">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="font-clash text-lg font-semibold truncate text-carbone">{{ $assignment->project->title ?? '—' }}</p>
                                <x-status-badge :status="$sv" />
                            </div>
                            <p class="text-xs text-gris">
                                Client : {{ $assignment->project->client->full_name ?? '—' }}
                                @if($assignment->assigned_at)
                                    <span class="mx-1.5 opacity-40">·</span>
                                    Assigné le {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <span class="arrow-circle shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
