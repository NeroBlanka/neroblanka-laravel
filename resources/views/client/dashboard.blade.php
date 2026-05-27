<x-app-layout>
    <x-slot name="title">Mes projets — Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-14">

        @if(request('submitted'))
            <div class="mb-8 rounded-xl px-5 py-4 text-sm flex items-start gap-3"
                 style="background:#161719; color:#34d399; box-shadow: 6px 6px 16px #060607, -3px -3px 8px #1a1c1f; border:1px solid rgba(52,211,153,0.12)">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span style="color: var(--perle)">Votre brief a bien été envoyé. Nous vous contacterons rapidement.</span>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-14">
            <div>
                <p class="label-mono mb-3">Espace client</p>
                <h1 class="text-4xl" style="color: var(--perle)">Bonjour,<br>{{ auth()->user()->full_name }}</h1>
            </div>
            <a href="{{ route('client.brief.create') }}" class="btn-primary text-sm shrink-0">
                Nouveau brief →
            </a>
        </div>

        @if($projects->isEmpty())
            <div class="card p-16 flex flex-col items-center text-center neo-tilt-card">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 neo-sunken">
                    <svg class="w-6 h-6" style="color: var(--gris-mid)" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <p class="text-sm mb-8" style="color: var(--gris)">Vous n'avez pas encore de projets.</p>
                <a href="{{ route('client.brief.create') }}" class="btn-primary text-sm">
                    Déposez votre premier brief →
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($projects as $project)
                    @php
                        $statusValue = $project->status instanceof \BackedEnum ? $project->status->value : (string) $project->status;
                        $serviceLabel = $project->service_type instanceof \BackedEnum ? $project->service_type->label() : ($project->service_type ?? 'Projet');
                    @endphp
                    <a href="{{ route('client.projects.show', $project->id) }}"
                       class="card-interactive neo-tilt-card flex items-center justify-between gap-4 px-6 py-5 block">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="font-medium truncate" style="color: var(--perle)">{{ $project->title }}</p>
                                <x-status-badge :status="$statusValue" />
                            </div>
                            <p class="text-xs" style="color: var(--gris-mid)">
                                {{ $serviceLabel }}
                                <span class="mx-1.5 opacity-40">·</span>
                                {{ $project->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="text-sm shrink-0" style="color: var(--gris-mid)">→</span>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
