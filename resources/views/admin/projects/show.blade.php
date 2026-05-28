<x-app-layout>
    <x-slot name="title">{{ $project->title }} — Admin Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-14">

        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm mb-10 transition-opacity hover:opacity-60"
           style="color: var(--gris)">← Retour au dashboard</a>

        @if(session('success'))
            <div class="mb-8 rounded-xl px-5 py-4 text-sm flex items-center gap-3"
                 style="background: rgba(31,157,85,0.08); color:#15803d; border:1px solid rgba(31,157,85,0.25)">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @php
            $statusValue = $project->status instanceof \BackedEnum ? $project->status->value : (string) $project->status;
            $serviceLabel = $project->service_type instanceof \BackedEnum ? $project->service_type->label() : ($project->service_type ?? 'Projet');
        @endphp

        <div class="mb-10">
            <p class="label-mono mb-3">{{ $serviceLabel }}</p>
            <h1 class="text-4xl mb-4" style="color: var(--carbone)">{{ $project->title }}</h1>
            <x-status-badge :status="$statusValue" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">

                <div class="card p-6 neo-tilt-card">
                    <p class="label-mono mb-3">Description</p>
                    <p class="text-sm leading-relaxed" style="color: var(--gris)">{{ $project->description }}</p>
                </div>

                @if($project->notes)
                    <div class="card p-6 neo-tilt-card">
                        <p class="label-mono mb-3">Notes</p>
                        <p class="text-sm leading-relaxed" style="color: var(--gris)">{{ $project->notes }}</p>
                    </div>
                @endif

                <div class="card overflow-hidden">
                    <div class="px-6 py-4" style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                        <p class="text-sm font-semibold" style="color: var(--carbone)">Livrables</p>
                    </div>
                    @if($deliverables->isEmpty())
                        <div class="px-6 py-10 text-sm text-center" style="color: var(--gris-mid)">Aucun livrable soumis.</div>
                    @else
                        @foreach($deliverables as $deliverable)
                            <div class="px-6 py-4"
                                 style="{{ !$loop->last ? 'border-bottom: 1px solid rgba(5,5,5,0.07)' : '' }}">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium" style="color: var(--carbone)">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                        <p class="text-xs mt-0.5" style="color: var(--gris-mid)">
                                            {{ $deliverable->submitted_at?->format('d/m/Y à H:i') ?? '—' }}
                                            @if($deliverable->message)<span class="mx-1.5 opacity-40">·</span>{{ $deliverable->message }}@endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if(isset($deliverableUrls[$deliverable->id]))
                                            <a href="{{ $deliverableUrls[$deliverable->id] }}" target="_blank" class="btn-ghost text-xs px-3 py-1.5">↓ Télécharger</a>
                                        @endif
                                        @if($deliverable->status === 'submitted')
                                            <form method="POST" action="{{ route('admin.deliverables.approve', $deliverable->id) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary text-xs px-3 py-1.5">Approuver</button>
                                            </form>
                                        @else
                                            <x-status-badge :status="$deliverable->status" />
                                        @endif
                                    </div>
                                </div>
                                @if($deliverable->status === 'submitted')
                                    <form method="POST" action="{{ route('admin.deliverables.revision', $deliverable->id) }}"
                                          class="mt-3 flex gap-2">
                                        @csrf
                                        <textarea name="revision_notes" rows="2" required
                                                  class="input-base text-xs resize-none flex-1"
                                                  placeholder="Décrire les révisions attendues…"></textarea>
                                        <button type="submit" class="btn-secondary text-xs px-3 py-2 self-end whitespace-nowrap">Demander révision</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <livewire:admin.freelance-matcher :project-id="$project->id" />
            </div>

            {{-- Sidebar --}}
            <div class="space-y-3">
                <div class="card p-6 neo-tilt-card space-y-5">
                    <p class="label-mono">Informations</p>
                    <div>
                        <p class="label-mono mb-1.5">Client</p>
                        <p class="text-sm font-medium" style="color: var(--carbone)">{{ $project->client->full_name ?? '—' }}</p>
                        @if($project->client->company ?? null)
                            <p class="text-xs mt-0.5" style="color: var(--gris-mid)">{{ $project->client->company }}</p>
                        @endif
                    </div>
                    @if($project->deadline)
                        <div>
                            <p class="label-mono mb-1.5">Deadline</p>
                            <p class="text-sm" style="color: var(--carbone)">{{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="label-mono mb-1.5">Créé le</p>
                        <p class="text-sm" style="color: var(--carbone)">{{ $project->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                @if($assignment)
                    <div class="card p-6 neo-tilt-card space-y-3">
                        <p class="label-mono">Freelance assigné</p>
                        <p class="text-sm font-medium" style="color: var(--carbone)">{{ $assignment->freelance->full_name ?? '—' }}</p>
                        <x-status-badge :status="$assignment->status instanceof \BackedEnum ? $assignment->status->value : ($assignment->status ?? 'active')" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
