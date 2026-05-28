<x-app-layout>
    <x-slot name="title">Nouveau brief — Neroblanka</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-10">

        <div class="mb-10">
            <a href="{{ route('client.dashboard') }}" class="label-mono inline-flex items-center gap-2 mb-6 hover:opacity-70 transition-opacity">
                ← Mes projets
            </a>
            <p class="label-mono mb-2">Brief projet</p>
            <h1 class="text-3xl" style="color: var(--carbone)">Décrivez votre projet</h1>
        </div>

        <form method="POST" action="{{ route('client.brief.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Service type --}}
            <div class="card p-6">
                <p class="label-mono mb-5" style="border-bottom: 1px solid rgba(5,5,5,0.07); padding-bottom: 12px">Type de service</p>

                <div class="grid grid-cols-1 gap-3">
                    @foreach($services as $service)
                        @if($service['value'] !== \App\Enums\ServiceType::MIXED_PROJECT->value)
                        <label class="flex items-center justify-between rounded-xl px-5 py-4 cursor-pointer transition-all"
                               style="border: 1px solid rgba(5,5,5,0.10); background: var(--glass)"
                               x-data
                               :style="$el.querySelector('input').checked
                                   ? 'border-color: rgba(124,92,252,0.4); background: rgba(124,92,252,0.08)'
                                   : 'border-color: rgba(5,5,5,0.10); background: var(--glass)'">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="service_type" value="{{ $service['value'] }}"
                                       style="accent-color: var(--purple)"
                                       {{ old('service_type') === $service['value'] ? 'checked' : '' }}
                                       @change="$el.closest('label').style.borderColor = 'rgba(124,92,252,0.4)'; $el.closest('label').style.background = 'rgba(124,92,252,0.08)'" />
                                <div>
                                    <p class="font-medium text-sm" style="color: var(--carbone)">{{ $service['label'] }}</p>
                                </div>
                            </div>
                        </label>
                        @endif
                    @endforeach
                </div>
                @error('service_type')
                    <p class="text-xs mt-3" style="color: var(--status-danger)">{{ $message }}</p>
                @enderror
            </div>

            {{-- Détails projet --}}
            <div class="card p-6 space-y-5">
                <p class="label-mono" style="border-bottom: 1px solid rgba(5,5,5,0.07); padding-bottom: 12px">Détails du projet</p>

                <div>
                    <label for="title" class="block label-mono mb-2">Titre du projet</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}"
                           class="input-base w-full" placeholder="Ex : Refonte identité visuelle" required />
                    @error('title')
                        <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block label-mono mb-2">Description</label>
                    <textarea id="description" name="description" rows="5"
                              class="input-base w-full resize-none"
                              placeholder="Décrivez votre projet, vos objectifs, votre cible…" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block label-mono mb-2">Deadline souhaitée</label>
                    <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}"
                           class="input-base w-full" />
                    @error('deadline')
                        <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Références & notes --}}
            <div class="card p-6 space-y-5">
                <p class="label-mono" style="border-bottom: 1px solid rgba(5,5,5,0.07); padding-bottom: 12px">Références & notes</p>

                <div>
                    <label for="reference_urls" class="block label-mono mb-2">
                        URLs de référence
                        <span class="font-normal" style="color: var(--gris-mid)">(une par ligne)</span>
                    </label>
                    <textarea id="reference_urls" name="reference_urls" rows="3"
                              class="input-base w-full resize-none font-mono text-xs"
                              placeholder="https://example.com&#10;https://dribbble.com/...">{{ old('reference_urls') }}</textarea>
                    @error('reference_urls')
                        <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block label-mono mb-2">
                        Notes complémentaires
                        <span class="font-normal" style="color: var(--gris-mid)">(optionnel)</span>
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="input-base w-full resize-none"
                              placeholder="Contraintes techniques, budget, précisions…">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brief_file" class="block label-mono mb-2">
                        Fichier brief
                        <span class="font-normal" style="color: var(--gris-mid)">(PDF, DOC, ZIP — optionnel)</span>
                    </label>
                    <div class="neo-sunken rounded-xl px-4 py-3">
                        <input id="brief_file" type="file" name="brief_file"
                               class="w-full text-sm cursor-pointer file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:cursor-pointer"
                               style="color: var(--gris)" />
                    </div>
                    @error('brief_file')
                        <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary w-full text-base py-4">
                Envoyer mon brief →
            </button>
        </form>

    </div>
</x-app-layout>
