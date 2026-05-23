<x-app-layout>
    <x-slot name="title">Nouveau brief — Neroblanka</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-10">

        <div class="mb-10">
            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-[#888780] hover:text-[#0a0a0a] transition mb-6">
                &larr; Mes projets
            </a>
            <p class="label-mono mb-2">Brief projet</p>
            <h1 class="text-3xl">Décrivez votre projet</h1>
        </div>

        <form method="POST" action="{{ route('client.brief.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Step 1: Service type --}}
            <div class="card p-6">
                <h2 class="text-base font-semibold mb-5">Type de service</h2>

                <div class="grid grid-cols-1 gap-3">
                    @php
                    $services = [
                        ['value' => 'identite_visuelle', 'label' => 'Identité Visuelle Premium',      'description' => 'Logo, charte graphique, système visuel complet', 'price' => '80 000 DA'],
                        ['value' => 'direction_3d_ia',   'label' => 'Direction Artistique 3D + IA',   'description' => 'Visuels produit photoréalistes, rendus 3D, assets IA', 'price' => '150 000 DA'],
                        ['value' => 'contenu_mensuel',   'label' => 'Contenu Visuel Mensuel',         'description' => 'Direction artistique fixée une fois, production IA chaque mois', 'price' => '35 000 DA/mois'],
                        ['value' => 'marketing_digital', 'label' => 'Marketing Digital',              'description' => 'Stratégie visuelle, campagnes, contenus multi-canaux', 'price' => 'Sur devis'],
                        ['value' => 'autre',             'label' => 'Autre / Je ne sais pas encore',  'description' => 'Décrivez votre besoin, on définit ensemble', 'price' => ''],
                    ];
                    @endphp

                    @foreach($services as $service)
                        <label class="flex items-center justify-between border border-black/[0.08] rounded-lg px-5 py-4 cursor-pointer hover:border-[#0a0a0a] transition has-[:checked]:border-[#0a0a0a] has-[:checked]:bg-[#0a0a0a]/[0.02]">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="service_type" value="{{ $service['value'] }}"
                                       class="accent-[#0a0a0a]"
                                       {{ old('service_type') === $service['value'] ? 'checked' : '' }} />
                                <div>
                                    <p class="font-medium text-sm">{{ $service['label'] }}</p>
                                    <p class="text-xs text-[#888780] mt-0.5">{{ $service['description'] }}</p>
                                </div>
                            </div>
                            <span class="label-mono text-[10px] shrink-0">{{ $service['price'] }}</span>
                        </label>
                    @endforeach
                </div>
                @error('service_type')
                    <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                @enderror
            </div>

            {{-- Step 2: Project details --}}
            <div class="card p-6 space-y-5">
                <h2 class="text-base font-semibold">Détails du projet</h2>

                <div>
                    <label for="title" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Titre du projet</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}"
                           class="input-base" placeholder="Ex : Refonte identité visuelle" required />
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="5"
                              class="input-base resize-none"
                              placeholder="Décrivez votre projet, vos objectifs, votre cible…" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Deadline souhaitée</label>
                    <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}"
                           class="input-base" />
                    @error('deadline')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Step 3: References & notes --}}
            <div class="card p-6 space-y-5">
                <h2 class="text-base font-semibold">Références & notes</h2>

                <div>
                    <label for="reference_urls" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                        URLs de référence
                        <span class="text-[#888780] font-normal">(une par ligne)</span>
                    </label>
                    <textarea id="reference_urls" name="reference_urls" rows="3"
                              class="input-base resize-none font-mono text-xs"
                              placeholder="https://example.com&#10;https://dribbble.com/...">{{ old('reference_urls') }}</textarea>
                    @error('reference_urls')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                        Notes complémentaires
                        <span class="text-[#888780] font-normal">(optionnel)</span>
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="input-base resize-none"
                              placeholder="Contraintes techniques, budget, précisions…">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brief_file" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                        Fichier brief
                        <span class="text-[#888780] font-normal">(PDF, DOC, ZIP — optionnel)</span>
                    </label>
                    <input id="brief_file" type="file" name="brief_file"
                           class="text-sm text-[#555350] file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-[#e8e7e2] file:text-[#0a0a0a] hover:file:bg-[#d8d7d2] cursor-pointer" />
                    @error('brief_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary w-full text-base py-4">
                Envoyer mon brief &rarr;
            </button>
        </form>

    </div>
</x-app-layout>
