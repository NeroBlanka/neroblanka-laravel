<x-app-layout>
    <x-slot name="title">Mes projets — Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-10">

        @if(request('submitted'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm px-5 py-4 rounded flex items-start gap-3">
                <span class="mt-0.5">&#10003;</span>
                <span>Votre brief a bien été envoyé. Nous vous contacterons rapidement.</span>
            </div>
        @endif

        <div class="flex items-start justify-between gap-4 mb-10">
            <div>
                <p class="label-mono mb-2">Espace client</p>
                <h1 class="text-3xl">Bonjour, {{ auth()->user()->full_name }}</h1>
            </div>
            <a href="{{ route('client.brief.create') }}" class="btn-primary text-sm shrink-0">
                Nouveau brief
            </a>
        </div>

        @if($projects->isEmpty())
            <div class="card px-6 py-16 flex flex-col items-center text-center">
                <p class="text-[#888780] mb-6">Vous n'avez pas encore de projets.</p>
                <a href="{{ route('client.brief.create') }}" class="btn-primary">
                    Déposez votre premier brief &rarr;
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($projects as $project)
                    <div class="card px-6 py-5 flex items-center justify-between gap-4 hover:border-black/20 transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <p class="font-medium truncate">{{ $project->title }}</p>
                                <x-status-badge :status="$project->status" />
                            </div>
                            <p class="text-xs text-[#888780]">
                                {{ $project->service_type ?? 'Projet' }}
                                &middot;
                                {{ $project->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('client.projects.show', $project->id) }}"
                           class="text-sm text-[#888780] hover:text-[#0a0a0a] transition shrink-0">
                            Voir &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
