<x-app-layout>
    <x-slot name="title">{{ $project->title }} — Neroblanka</x-slot>

    <div class="max-w-3xl mx-auto px-6 py-10">

        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-[#888780] hover:text-[#0a0a0a] transition mb-8">
            &larr; Mes projets
        </a>

        <div class="flex items-start justify-between gap-4 mb-8">
            <div>
                <p class="label-mono mb-2">{{ is_string($project->service_type) ? $project->service_type : ($project->service_type?->label() ?? 'Projet') }}</p>
                <h1 class="text-3xl mb-3">{{ $project->title }}</h1>
                <x-status-badge :status="is_string($project->status) ? $project->status : $project->status?->value" />
            </div>
        </div>

        <div class="space-y-6">

            {{-- Description --}}
            <div class="card p-6">
                <h2 class="text-base font-semibold mb-3">Description</h2>
                <p class="text-[#555350] leading-relaxed">{{ $project->description }}</p>
            </div>

            {{-- Meta --}}
            <div class="card p-6 grid grid-cols-2 gap-6">
                @if($project->deadline)
                    <div>
                        <p class="label-mono mb-1">Deadline</p>
                        <p class="text-sm text-[#555350]">{{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}</p>
                    </div>
                @endif
                <div>
                    <p class="label-mono mb-1">Créé le</p>
                    <p class="text-sm text-[#555350]">{{ $project->created_at->format('d/m/Y') }}</p>
                </div>
                @if($project->notes)
                    <div class="col-span-2">
                        <p class="label-mono mb-1">Notes</p>
                        <p class="text-sm text-[#555350]">{{ $project->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Deliverables --}}
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-black/[0.08]">
                    <h2 class="text-base font-semibold">Livrables</h2>
                </div>

                @if($deliverables->isEmpty())
                    <div class="px-6 py-8 text-sm text-[#888780]">
                        Aucun livrable disponible pour le moment.
                    </div>
                @else
                    <div class="divide-y divide-black/[0.06]">
                        @foreach($deliverables as $deliverable)
                            <div class="px-6 py-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                        <p class="text-xs text-[#888780] mt-0.5">{{ $deliverable->created_at->format('d/m/Y à H:i') }}</p>
                                        @if($deliverable->message)
                                            <p class="text-sm text-[#555350] mt-2">{{ $deliverable->message }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if(isset($deliverableUrls[$deliverable->id]))
                                            <a href="{{ $deliverableUrls[$deliverable->id] }}" target="_blank" rel="noopener"
                                               class="btn-ghost text-xs px-3 py-1.5">
                                                Télécharger
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @php $statusValue = is_string($project->status) ? $project->status : $project->status?->value; @endphp
                                @if($statusValue === 'submitted')
                                    <div class="mt-4 flex items-start gap-3">
                                        <form method="POST" action="{{ route('client.projects.approve', $project->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-primary text-xs px-4 py-2">Approuver</button>
                                        </form>

                                        <form method="POST" action="{{ route('client.projects.revision', $project->id) }}" class="flex-1">
                                            @csrf
                                            <div class="flex gap-2">
                                                <textarea name="revision_notes" rows="2"
                                                          class="input-base text-xs resize-none flex-1"
                                                          placeholder="Décrivez les modifications souhaitées…"></textarea>
                                                <button type="submit" class="btn-secondary text-xs px-4 py-2 self-end">
                                                    Demander une révision
                                                </button>
                                            </div>
                                            @error('revision_notes')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
