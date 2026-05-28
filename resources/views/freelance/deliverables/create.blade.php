<x-app-layout>
    <x-slot name="title">Soumettre un livrable — Neroblanka</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-14">

        <a href="{{ route('freelance.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm mb-10 transition-opacity hover:opacity-60"
           style="color: var(--gris)">← Mes missions</a>

        <div class="mb-10">
            <p class="label-mono mb-3">Mission</p>
            <h1 class="font-clash text-4xl md:text-5xl font-semibold" style="color: var(--carbone)">Soumettre votre livrable</h1>
        </div>

        {{-- Bannière de révision : le dernier livrable a été renvoyé pour correction --}}
        @php $latest = ($deliverables ?? collect())->first(); @endphp
        @if($latest && $latest->status === 'revision_requested' && filled($latest->revision_notes))
            <div class="rounded-2xl p-6 mb-5"
                 style="background: rgba(234,88,12,0.06); border: 1px solid rgba(234,88,12,0.25)">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="#9a3412" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    <div>
                        <p class="text-sm font-semibold" style="color: #9a3412">Révision demandée sur la version {{ $latest->version }}</p>
                        <p class="text-sm leading-relaxed mt-1.5" style="color: var(--gris-texte)">{{ $latest->revision_notes }}</p>
                        <p class="text-xs mt-3" style="color: var(--gris-texte-soft)">Corrigez puis soumettez une nouvelle version ci-dessous.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mission summary --}}
        <div class="card p-6 mb-5">
            <p class="label-mono mb-5">Détails de la mission</p>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <p class="label-mono mb-1.5">Projet</p>
                    <p class="text-sm font-medium" style="color: var(--carbone)">{{ $assignment->project->title ?? '—' }}</p>
                </div>
                <div>
                    <p class="label-mono mb-1.5">Client</p>
                    <p class="text-sm" style="color: var(--carbone)">{{ $assignment->project->client->full_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="label-mono mb-1.5">Statut</p>
                    <x-status-badge :status="$assignment->status instanceof \BackedEnum ? $assignment->status->value : ($assignment->status ?? 'active')" />
                </div>
                @if($assignment->project->deadline ?? null)
                    <div>
                        <p class="label-mono mb-1.5">Deadline</p>
                        <p class="text-sm" style="color: var(--carbone)">{{ \Carbon\Carbon::parse($assignment->project->deadline)->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>
            @if($assignment->project->description ?? null)
                <div class="mt-5 pt-5" style="border-top: 1px solid rgba(5,5,5,0.07)">
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
                    <label for="file" class="block text-sm font-medium mb-2" style="color: var(--carbone)">
                        Fichier <span style="color: var(--status-danger)">*</span>
                        <span class="text-xs font-normal ml-1" style="color: var(--gris-mid)">pdf, png, jpg, zip — max 10 Mo</span>
                    </label>
                    <div class="neo-sunken rounded-xl px-4 py-3">
                        <input id="file" type="file" name="file" required
                               class="block w-full text-sm cursor-pointer
                                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-xs file:font-medium file:cursor-pointer file:transition-colors
                                      file:bg-[#050505] file:text-[#F5F2EC] hover:file:bg-[#1c1c1c]"
                               style="color: var(--gris-texte)" />
                    </div>
                    @error('file')
                        <p class="text-xs mt-1.5" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium mb-2" style="color: var(--carbone)">
                        Message <span class="font-normal" style="color: var(--gris-mid)">(optionnel)</span>
                    </label>
                    <textarea id="message" name="message" rows="3"
                              class="input-base resize-none"
                              placeholder="Notes sur cette version, changements effectués…">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-xs mt-1.5" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-4 text-base">
                Envoyer le livrable
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </form>

        {{-- History --}}
        @if(isset($deliverables) && $deliverables->isNotEmpty())
            <div class="mt-8 card overflow-hidden">
                <div class="px-6 py-4" style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                    <p class="label-mono">Historique des livrables</p>
                </div>
                <div>
                    @foreach($deliverables as $deliverable)
                        <div class="px-6 py-4"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid rgba(5,5,5,0.07)' : '' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium" style="color: var(--carbone)">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                    <p class="text-xs mt-0.5" style="color: var(--gris-mid)">
                                        {{ $deliverable->submitted_at?->format('d/m/Y à H:i') ?? '—' }}
                                        @if($deliverable->message)
                                            <span class="mx-1.5 opacity-40">·</span>{{ $deliverable->message }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <x-status-badge :status="$deliverable->status" />
                                    @if(isset($deliverableUrls[$deliverable->id]))
                                        <a href="{{ $deliverableUrls[$deliverable->id] }}" target="_blank" class="btn-ghost text-xs px-3 py-1.5">↓</a>
                                    @endif
                                </div>
                            </div>
                            @if($deliverable->status === 'revision_requested' && filled($deliverable->revision_notes))
                                <div class="mt-3 rounded-xl px-4 py-3" style="background: rgba(234,88,12,0.05); border: 1px solid rgba(234,88,12,0.2)">
                                    <p class="text-[10px] uppercase tracking-[0.14em] font-medium mb-1" style="color: #9a3412">Note de révision</p>
                                    <p class="text-sm leading-relaxed" style="color: var(--gris-texte)">{{ $deliverable->revision_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
