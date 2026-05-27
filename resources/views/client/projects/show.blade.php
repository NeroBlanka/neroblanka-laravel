<x-app-layout>
    <x-slot name="title">{{ $project->title }} — Neroblanka</x-slot>

    <div class="max-w-3xl mx-auto px-6 py-14">

        <a href="{{ route('client.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm mb-10 transition-opacity hover:opacity-60"
           style="color: var(--gris)">← Mes projets</a>

        @php
            $statusValue = $project->status instanceof \BackedEnum ? $project->status->value : (string) $project->status;
            $serviceLabel = $project->service_type instanceof \BackedEnum ? $project->service_type->label() : ($project->service_type ?? 'Projet');
        @endphp

        <div class="mb-10">
            <p class="label-mono mb-3">{{ $serviceLabel }}</p>
            <h1 class="text-4xl mb-4" style="color: var(--perle)">{{ $project->title }}</h1>
            <x-status-badge :status="$statusValue" />
        </div>

        <div class="space-y-4">
            <div class="card p-6 neo-tilt-card">
                <p class="label-mono mb-3">Description</p>
                <p class="text-sm leading-relaxed" style="color: var(--gris)">{{ $project->description }}</p>
            </div>

            <div class="card p-6 neo-tilt-card grid grid-cols-2 gap-6">
                <div>
                    <p class="label-mono mb-1.5">Créé le</p>
                    <p class="text-sm" style="color: var(--perle)">{{ $project->created_at->format('d/m/Y') }}</p>
                </div>
                @if($project->deadline)
                    <div>
                        <p class="label-mono mb-1.5">Deadline</p>
                        <p class="text-sm" style="color: var(--perle)">{{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}</p>
                    </div>
                @endif
                @if($project->notes)
                    <div class="col-span-2 pt-4" style="border-top: 1px solid rgba(255,255,255,0.04)">
                        <p class="label-mono mb-1.5">Notes</p>
                        <p class="text-sm" style="color: var(--gris)">{{ $project->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Deliverables --}}
            <div class="card overflow-hidden">
                <div class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.04)">
                    <p class="text-sm font-semibold" style="color: var(--perle)">Livrables</p>
                </div>

                @if($deliverables->isEmpty())
                    <div class="px-6 py-10 text-sm text-center" style="color: var(--gris-mid)">
                        Aucun livrable disponible pour le moment.
                    </div>
                @else
                    @foreach($deliverables as $deliverable)
                        <div class="px-6 py-5" style="{{ !$loop->last ? 'border-bottom: 1px solid rgba(255,255,255,0.03)' : '' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium" style="color: var(--perle)">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                    <p class="text-xs mt-0.5" style="color: var(--gris-mid)">{{ $deliverable->created_at?->format('d/m/Y à H:i') ?? '—' }}</p>
                                    @if($deliverable->message)
                                        <p class="text-sm mt-2" style="color: var(--gris)">{{ $deliverable->message }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <x-status-badge :status="$deliverable->status instanceof \BackedEnum ? $deliverable->status->value : ($deliverable->status ?? $statusValue)" />
                                    @if(isset($deliverableUrls[$deliverable->id]))
                                        <a href="{{ $deliverableUrls[$deliverable->id] }}" target="_blank" rel="noopener" class="btn-ghost text-xs px-3 py-1.5">↓ Télécharger</a>
                                    @endif
                                </div>
                            </div>

                            @if($statusValue === 'submitted')
                                <div class="mt-5 pt-4 flex items-start gap-3" style="border-top: 1px solid rgba(255,255,255,0.04)">
                                    <form method="POST" action="{{ route('client.deliverables.approve', $deliverable->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary text-xs px-4 py-2">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('client.deliverables.revision', $deliverable->id) }}" class="flex-1">
                                        @csrf
                                        <div class="flex gap-2">
                                            <textarea name="revision_notes" rows="2" class="input-base text-xs resize-none flex-1"
                                                      placeholder="Décrivez les modifications souhaitées…"></textarea>
                                            <button type="submit" class="btn-secondary text-xs px-4 py-2 self-end whitespace-nowrap">Demander révision</button>
                                        </div>
                                        @error('revision_notes')
                                            <p class="text-xs mt-1" style="color: #f87171">{{ $message }}</p>
                                        @enderror
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
