<x-app-layout>
    <x-slot name="title">{{ $service->exists ? 'Modifier' : 'Nouveau' }} service — Neroblanka Admin</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-14">

        <div class="mb-10">
            <a href="{{ route('admin.services.index') }}" class="label-mono inline-flex items-center gap-2 mb-6 hover:opacity-70 transition-opacity">
                ← Services
            </a>
            <p class="label-mono mb-2">Admin · Catalogue</p>
            <h1 class="text-3xl" style="color: var(--carbone)">
                {{ $service->exists ? 'Modifier le service' : 'Nouveau service' }}
            </h1>
        </div>

        <form method="POST"
              action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
              class="space-y-5">
            @csrf
            @if($service->exists)
                @method('PUT')
            @endif

            <div class="card p-6 space-y-5">

                <div>
                    <label for="name" class="block label-mono mb-2">Nom du service</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $service->name) }}"
                           class="input-base w-full" placeholder="Ex : Identité Visuelle Premium" required />
                    @error('name') <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block label-mono mb-2">
                        Type enum
                        <span class="font-normal" style="color: var(--gris-mid)">(optionnel — lie ce service à un type du wizard)</span>
                    </label>
                    <select id="type" name="type" class="input-base w-full">
                        <option value="">— Aucun —</option>
                        @foreach($serviceTypes as $option)
                            <option value="{{ $option['value'] }}" {{ old('type', $service->type) === $option['value'] ? 'selected' : '' }}>
                                {{ $option['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block label-mono mb-2">Description <span class="font-normal" style="color: var(--gris-mid)">(optionnel)</span></label>
                    <textarea id="description" name="description" rows="3"
                              class="input-base w-full resize-none">{{ old('description', $service->description) }}</textarea>
                    @error('description') <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price_da" class="block label-mono mb-2">Prix (DA)</label>
                        <input id="price_da" type="number" name="price_da" min="0"
                               value="{{ old('price_da', $service->price_da) }}"
                               class="input-base w-full" placeholder="80000" required />
                        @error('price_da') <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="delivery_days" class="block label-mono mb-2">Délai (jours)</label>
                        <input id="delivery_days" type="number" name="delivery_days" min="1"
                               value="{{ old('delivery_days', $service->delivery_days) }}"
                               class="input-base w-full" placeholder="21" required />
                        @error('delivery_days') <p class="text-xs mt-1" style="color: var(--status-danger)">{{ $message }}</p> @enderror
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
                           style="accent-color: var(--purple); width: 16px; height: 16px" />
                    <span class="text-sm" style="color: var(--carbone)">Service actif (visible dans le portail client)</span>
                </label>

            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-3">
                    {{ $service->exists ? 'Enregistrer' : 'Créer le service' }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn-ghost px-6 py-3">Annuler</a>
            </div>

        </form>

    </div>
</x-app-layout>
