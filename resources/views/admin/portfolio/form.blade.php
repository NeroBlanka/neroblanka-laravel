<x-app-layout>
    <x-slot name="title">{{ $item ? 'Éditer — ' . $item->title : 'Nouveau projet' }} — Admin</x-slot>

    <div class="max-w-3xl mx-auto px-6 py-10">

        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin.portfolio.index') }}" class="text-sm transition-opacity hover:opacity-60" style="color: var(--gris)">← Portfolio</a>
            <span style="color: var(--gris-mid)">/</span>
            <h1 class="text-xl" style="color: var(--carbone)">{{ $item ? 'Éditer le projet' : 'Nouveau projet' }}</h1>
        </div>

        <form method="POST"
              action="{{ $item ? route('admin.portfolio.update', $item) : route('admin.portfolio.store') }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf
            @if($item) @method('PUT') @endif

            <div class="card p-6 space-y-5">
                <p class="label-mono" style="border-bottom: 1px solid rgba(5,5,5,0.07); padding-bottom: 12px">Informations</p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block label-mono mb-2">Titre *</label>
                        <input type="text" name="title" value="{{ old('title', $item?->title) }}"
                               class="input-base" placeholder="Refonte identité Startup Alger"
                               x-data
                               @input="$el.form.querySelector('[name=slug]').value = $el.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')">
                        @error('title') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block label-mono mb-2">Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug', $item?->slug) }}"
                               class="input-base font-mono" placeholder="refonte-identite-startup-alger">
                        @error('slug') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block label-mono mb-2">Client *</label>
                        <input type="text" name="client_name" value="{{ old('client_name', $item?->client_name) }}"
                               class="input-base" placeholder="Startup SAS">
                        @error('client_name') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block label-mono mb-2">Service *</label>
                        <select name="service_type" class="select-base">
                            <option value="">Choisir...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->value }}"
                                    @selected(old('service_type', $item?->service_type?->value ?? $item?->service_type) === $service->value)>
                                    {{ $service->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_type') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block label-mono mb-2">Extrait * <span class="font-normal" style="color:var(--gris-mid)">(max 500 car.)</span></label>
                    <textarea name="excerpt" rows="2" class="input-base resize-none"
                              placeholder="Une phrase percutante qui décrit le projet.">{{ old('excerpt', $item?->excerpt) }}</textarea>
                    @error('excerpt') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block label-mono mb-2">Contenu <span class="font-normal" style="color:var(--gris-mid)">(optionnel)</span></label>
                    <textarea name="content" rows="6" class="input-base resize-y"
                              placeholder="Description longue du projet, process, résultats...">{{ old('content', $item?->content) }}</textarea>
                </div>

                <div>
                    <label class="block label-mono mb-2">Tags <span class="font-normal" style="color:var(--gris-mid)">(séparés par des virgules)</span></label>
                    <input type="text" name="tags"
                           value="{{ old('tags', $item?->tags ? implode(', ', $item->tags) : '') }}"
                           class="input-base" placeholder="Branding, Logo, Brand book">
                </div>
            </div>

            <div class="card p-6 space-y-5">
                <p class="label-mono" style="border-bottom: 1px solid rgba(5,5,5,0.07); padding-bottom: 12px">Publication</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block label-mono mb-2">Date de publication</label>
                        <input type="date" name="published_at"
                               value="{{ old('published_at', $item?->published_at?->format('Y-m-d')) }}"
                               class="input-base">
                        <p class="text-xs mt-1" style="color:var(--gris-mid)">Vide = brouillon</p>
                    </div>

                    <div class="flex flex-col gap-4 pt-5">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="featured" value="1"
                                   @checked(old('featured', $item?->featured))
                                   class="w-4 h-4" style="accent-color: var(--carbone)">
                            <div>
                                <span class="text-sm font-medium" style="color:var(--carbone)">Mise en avant</span>
                                <p class="text-xs" style="color:var(--gris-mid)">Affiché en priorité sur la homepage</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_concept" value="1"
                                   @checked(old('is_concept', $item?->is_concept))
                                   class="w-4 h-4" style="accent-color: var(--carbone)">
                            <div>
                                <span class="text-sm font-medium" style="color:var(--carbone)">Projet concept</span>
                                <p class="text-xs" style="color:var(--gris-mid)">Badge « Concept » — projet non commandé / étude visuelle</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card p-6 space-y-5">
                <p class="label-mono" style="border-bottom: 1px solid rgba(5,5,5,0.07); padding-bottom: 12px">Médias</p>

                <div>
                    <label class="block label-mono mb-2">Image de couverture <span class="font-normal" style="color:var(--gris-mid)">(JPEG/PNG/WebP, max 5 Mo)</span></label>
                    @if($item?->cover_image)
                        <div class="mb-3 flex items-start gap-4">
                            <img src="{{ Storage::temporaryUrl($item->cover_image, now()->addMinutes(10)) }}"
                                 alt="Couverture actuelle" class="w-40 h-24 object-cover rounded-xl" style="border:1px solid rgba(5,5,5,0.10)">
                            <div>
                                <p class="text-xs mb-1" style="color:var(--gris-mid)">Image actuelle</p>
                                <p class="text-xs font-mono break-all" style="color:var(--gris)">{{ basename($item->cover_image) }}</p>
                                <p class="text-xs mt-1" style="color:var(--gris-mid)">Sélectionner un nouveau fichier pour remplacer.</p>
                            </div>
                        </div>
                    @endif
                    <div class="neo-sunken rounded-xl px-4 py-3">
                        <input type="file" name="cover_image" accept="image/*"
                               class="w-full text-sm cursor-pointer file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:cursor-pointer"
                               style="color:var(--gris)">
                    </div>
                    @error('cover_image') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block label-mono mb-2">Galerie <span class="font-normal" style="color:var(--gris-mid)">(multiple, max 8 Mo chacune)</span></label>
                    @if($item?->gallery && count($item->gallery))
                        <div class="mb-3 grid grid-cols-4 gap-2">
                            @foreach($item->gallery as $img)
                                <div class="relative group" x-data="{ checked: false }">
                                    <img src="{{ Storage::temporaryUrl($img, now()->addMinutes(10)) }}"
                                         alt="" class="w-full aspect-video object-cover rounded-lg" style="border:1px solid rgba(5,5,5,0.10)">
                                    <label class="absolute inset-0 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                                           style="background:rgba(0,0,0,0.6)">
                                        <input type="checkbox" name="remove_gallery[]" value="{{ $img }}"
                                               x-model="checked" class="sr-only">
                                        <span :class="checked ? 'bg-red-500/80 text-white' : 'bg-white/10 text-white'"
                                              class="text-xs font-medium px-2 py-1 rounded transition-colors border border-white/20">
                                            <span x-text="checked ? '✕ Supprimer' : 'Sélectionner'"></span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="neo-sunken rounded-xl px-4 py-3">
                        <input type="file" name="gallery[]" accept="image/*" multiple
                               class="w-full text-sm cursor-pointer file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:cursor-pointer"
                               style="color:var(--gris)">
                    </div>
                    @error('gallery.*') <p class="mt-1 text-xs" style="color:var(--status-danger)">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary text-sm">Annuler</a>
                <button type="submit" class="btn-primary">{{ $item ? 'Mettre à jour' : 'Créer le projet' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
