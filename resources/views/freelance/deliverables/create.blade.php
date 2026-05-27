<x-app-layout>
    <x-slot name="title">Soumettre un livrable — Neroblanka</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-14">

        <a href="{{ route('freelance.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm mb-10 transition-opacity hover:opacity-60"
           style="color: var(--gris)">← Mes missions</a>

        <div class="mb-10">
            <p class="label-mono mb-3">Mission</p>
            <h1 class="text-4xl" style="color: var(--perle)">Soumettre votre livrable</h1>
        </div>

        {{-- Mission summary --}}
        <div class="card p-6 mb-5 neo-tilt-card">
            <p class="label-mono mb-5">Détails de la mission</p>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <p class="label-mono mb-1.5">Projet</p>
                    <p class="text-sm font-medium" style="color: var(--perle)">{{ $assignment->project->title ?? '—' }}</p>
                </div>
                <div>
                    <p class="label-mono mb-1.5">Client</p>
                    <p class="text-sm" style="color: var(--perle)">{{ $assignment->project->client->full_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="label-mono mb-1.5">Statut</p>
                    <x-status-badge :status="$assignment->status instanceof \BackedEnum ? $assignment->status->value : ($assignment->status ?? 'active')" />
                </div>
                @if($assignment->project->deadline ?? null)
                    <div>
                        <p class="label-mono mb-1.5">Deadline</p>
                        <p class="text-sm" style="color: var(--perle)">{{ \Carbon\Carbon::parse($assignment->project->deadline)->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>
            @if($assignment->project->description ?? null)
                <div class="mt-5 pt-5" style="border-top: 1px solid rgba(255,255,255,0.04)">
                    <p class="label-mono mb-1.5">Description</p>
                    <p class="text-sm leading-relaxed" style="color: var(--gris)">{{ $assignment->project->description }}</p>
                </div>
            @endif
        </div>

        {{-- Upload form --}}
        <form method="POST" action="{{ route('freelance.deliverables.store', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="card p-6">
                <p class="label-mono mb-5">Nouveau livrable</p>

                <div class="mb-5">
                    <label for="file" class="block text-sm font-medium mb-2" style="color: var(--perle)">
                        Fichier <span style="color: #f87171">*</span>
                        <span class="text-xs font-normal ml-1" style="color: var(--gris-mid)">pdf, png, jpg, zip — max 10 Mo</span>
                    </label>
                    <div class="neo-sunken rounded-xl px-4 py-3">
                        <input id="file" type="file" name="file" required
                               class="block w-full text-sm cursor-pointer
                                      file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                                      file:text-xs file:font-medium file:cursor-pointer file:transition-all"
                               style="color: var(--gris)"
                               onchange="this.style.cssText += '; --file-bg: rgba(255,255,255,0.06)'" />
                    </div>
                    @error('file')
                        <p class="text-xs mt-1.5" style="color: #f87171">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium mb-2" style="color: var(--perle)">
                        Message <span class="font-normal" style="color: var(--gris-mid)">(optionnel)</span>
                    </label>
                    <textarea id="message" name="message" rows="3"
                              class="input-base resize-none"
                              placeholder="Notes sur cette version, changements effectués…">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-xs mt-1.5" style="color: #f87171">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-4 text-base">
                Envoyer le livrable →
            </button>
        </form>

        {{-- History --}}
        @if(isset($deliverables) && $deliverables->isNotEmpty())
            <div class="mt-8 card overflow-hidden">
                <div class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.04)">
                    <p class="label-mono">Historique des livrables</p>
                </div>
                <div>
                    @foreach($deliverables as $deliverable)
                        <div class="px-6 py-4 flex items-center justify-between gap-4"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid rgba(255,255,255,0.03)' : '' }}">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--perle)">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                <p class="text-xs mt-0.5" style="color: var(--gris-mid)">
                                    {{ $deliverable->created_at?->format('d/m/Y à H:i') ?? '—' }}
                                    @if($deliverable->message)
                                        <span class="mx-1.5 opacity-40">·</span>{{ $deliverable->message }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <x-status-badge :status="$deliverable->status instanceof \BackedEnum ? $deliverable->status->value : ($deliverable->status ?? 'submitted')" />
                                @if(isset($deliverableUrls[$deliverable->id]))
                                    <a href="{{ $deliverableUrls[$deliverable->id] }}" target="_blank" class="btn-ghost text-xs px-3 py-1.5">↓</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
