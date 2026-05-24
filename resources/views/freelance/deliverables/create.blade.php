<x-app-layout>
    <x-slot name="title">Soumettre un livrable — Neroblanka</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-10">

        <a href="{{ route('freelance.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-[#888780] hover:text-[#0a0a0a] transition mb-8">
            &larr; Mes missions
        </a>

        <div class="mb-8">
            <p class="label-mono mb-2">Mission</p>
            <h1 class="text-3xl mb-3">Soumettre votre livrable</h1>
        </div>

        {{-- Assignment & project summary --}}
        <div class="card p-6 mb-8 space-y-4">
            <h2 class="text-base font-semibold">Détails de la mission</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="label-mono mb-1">Projet</p>
                    <p class="text-sm text-[#555350]">{{ $assignment->project->title ?? '—' }}</p>
                </div>
                <div>
                    <p class="label-mono mb-1">Client</p>
                    <p class="text-sm text-[#555350]">{{ $assignment->project->client->full_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="label-mono mb-1">Statut</p>
                    <x-status-badge :status="$assignment->status ?? 'assigned'" />
                </div>
                @if($assignment->project->deadline ?? null)
                    <div>
                        <p class="label-mono mb-1">Deadline</p>
                        <p class="text-sm text-[#555350]">{{ \Carbon\Carbon::parse($assignment->project->deadline)->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>
            @if($assignment->project->description ?? null)
                <div>
                    <p class="label-mono mb-1">Description</p>
                    <p class="text-sm text-[#555350] leading-relaxed">{{ $assignment->project->description }}</p>
                </div>
            @endif
        </div>

        {{-- Upload form --}}
        <form method="POST" action="{{ route('freelance.deliverables.store', $assignment) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="card p-6 space-y-5">
                <h2 class="text-base font-semibold">Nouveau livrable</h2>

                <div>
                    <label for="file" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                        Fichier <span class="text-red-500">*</span>
                    </label>
                    <input id="file" type="file" name="file" required
                           class="text-sm text-[#555350] file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-[#e8e7e2] file:text-[#0a0a0a] hover:file:bg-[#d8d7d2] cursor-pointer" />
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                        Message
                        <span class="text-[#888780] font-normal">(optionnel)</span>
                    </label>
                    <textarea id="message" name="message" rows="3"
                              class="input-base resize-none"
                              placeholder="Notes sur cette version, changements effectués…">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary w-full text-base py-4">
                Envoyer &rarr;
            </button>
        </form>

        {{-- Previous deliverables --}}
        @if(isset($deliverables) && $deliverables->isNotEmpty())
            <div class="mt-10 card overflow-hidden">
                <div class="px-6 py-4 border-b border-black/[0.08]">
                    <h2 class="text-base font-semibold">Historique des livrables</h2>
                </div>
                <div class="divide-y divide-black/[0.06]">
                    @foreach($deliverables as $deliverable)
                        <div class="px-6 py-4 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                <p class="text-xs text-[#888780] mt-0.5">
                                    {{ $deliverable->created_at->format('d/m/Y à H:i') }}
                                    @if($deliverable->message)
                                        — {{ $deliverable->message }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <x-status-badge :status="$deliverable->status ?? 'submitted'" />
                                @if(isset($deliverableUrls[$deliverable->id]))
                                    <a href="{{ $deliverableUrls[$deliverable->id] }}" target="_blank"
                                       class="btn-ghost text-xs px-3 py-1.5">
                                        Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
