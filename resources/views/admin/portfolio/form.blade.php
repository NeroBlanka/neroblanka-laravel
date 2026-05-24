<x-app-layout>
    <x-slot name="title">{{ $item ? 'Éditer — ' . $item->title : 'Nouveau projet' }} — Admin</x-slot>

    <div class="max-w-3xl mx-auto px-6 py-10">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.portfolio.index') }}" class="text-sm text-[#888780] hover:text-[#0a0a0a] transition">
                ← Portfolio
            </a>
            <span class="text-gray-200">/</span>
            <h1 class="text-xl font-semibold text-[#0a0a0a]">
                {{ $item ? 'Éditer le projet' : 'Nouveau projet' }}
            </h1>
        </div>

        <form method="POST"
              action="{{ $item ? route('admin.portfolio.update', $item) : route('admin.portfolio.store') }}"
              enctype="multipart/form-data"
              class="space-y-8">
            @csrf
            @if($item) @method('PUT') @endif

            {{-- Infos principales --}}
            <div class="card p-6 space-y-5">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400 border-b border-black/[0.06] pb-3">
                    Informations
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block label-mono mb-2">Titre *</label>
                        <input type="text" name="title" value="{{ old('title', $item?->title) }}"
                               class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-black/60 transition-colors"
                               placeholder="Refonte identité Startup Alger"
                               x-data
                               @input="$el.form.querySelector('[name=slug]').value = $el.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')">
                        @error('title') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block label-mono mb-2">Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug', $item?->slug) }}"
                               class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm font-mono text-[#888780] focus:outline-none focus:border-black/60 transition-colors"
                               placeholder="refonte-identite-startup-alger">
                        @error('slug') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block label-mono mb-2">Client *</label>
                        <input type="text" name="client_name" value="{{ old('client_name', $item?->client_name) }}"
                               class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-black/60 transition-colors"
                               placeholder="Startup SAS">
                        @error('client_name') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block label-mono mb-2">Service *</label>
                        <select name="service_type"
                                class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-black/60 transition-colors">
                            <option value="">Choisir...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->value }}"
                                    @selected(old('service_type', $item?->service_type?->value ?? $item?->service_type) === $service->value)>
                                    {{ $service->icon() }} {{ $service->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_type') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block label-mono mb-2">Extrait * <span class="text-gray-300 font-normal">(max 500 car.)</span></label>
                    <textarea name="excerpt" rows="2"
                              class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm resize-none focus:outline-none focus:border-black/60 transition-colors"
                              placeholder="Une phrase percutante qui décrit le projet.">{{ old('excerpt', $item?->excerpt) }}</textarea>
                    @error('excerpt') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block label-mono mb-2">Contenu <span class="text-gray-300 font-normal">(optionnel)</span></label>
                    <textarea name="content" rows="6"
                              class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm resize-y focus:outline-none focus:border-black/60 transition-colors"
                              placeholder="Description longue du projet, process, résultats...">{{ old('content', $item?->content) }}</textarea>
                </div>

                <div>
                    <label class="block label-mono mb-2">Tags <span class="text-gray-300 font-normal">(séparés par des virgules)</span></label>
                    <input type="text" name="tags"
                           value="{{ old('tags', $item?->tags ? implode(', ', $item->tags) : '') }}"
                           class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-black/60 transition-colors"
                           placeholder="Branding, Logo, Brand book">
                </div>
            </div>

            {{-- Publication --}}
            <div class="card p-6 space-y-5">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400 border-b border-black/[0.06] pb-3">
                    Publication
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block label-mono mb-2">Date de publication</label>
                        <input type="date" name="published_at"
                               value="{{ old('published_at', $item?->published_at?->format('Y-m-d')) }}"
                               class="w-full border border-black/20 rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-black/60 transition-colors">
                        <p class="text-xs text-gray-400 mt-1">Vide = brouillon</p>
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="featured" value="1"
                                   @checked(old('featured', $item?->featured))
                                   class="w-4 h-4 accent-[#0a0a0a]">
                            <div>
                                <span class="text-sm font-medium text-[#0a0a0a]">Mise en avant</span>
                                <p class="text-xs text-gray-400">Affiché en priorité sur la homepage</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Médias --}}
            <div class="card p-6 space-y-5">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400 border-b border-black/[0.06] pb-3">
                    Médias
                </h2>

                {{-- Cover --}}
                <div>
                    <label class="block label-mono mb-2">Image de couverture <span class="text-gray-300 font-normal">(JPEG/PNG/WebP, max 5 Mo)</span></label>

                    @if($item?->cover_image)
                        <div class="mb-3 flex items-start gap-4">
                            <img src="{{ Storage::temporaryUrl($item->cover_image, now()->addMinutes(10)) }}"
                                 alt="Couverture actuelle"
                                 class="w-40 h-24 object-cover rounded border border-black/10">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Image actuelle</p>
                                <p class="text-xs text-[#888780] font-mono break-all">{{ basename($item->cover_image) }}</p>
                                <p class="text-xs text-gray-400 mt-1">Sélectionner un nouveau fichier pour remplacer.</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="cover_image" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:border file:border-black/20 file:rounded-sm file:text-xs file:font-medium file:bg-white file:text-[#0a0a0a] hover:file:bg-gray-50 transition-colors">
                    @error('cover_image') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Gallery --}}
                <div>
                    <label class="block label-mono mb-2">Galerie <span class="text-gray-300 font-normal">(multiple, max 8 Mo chacune)</span></label>

                    @if($item?->gallery && count($item->gallery))
                        <div class="mb-3 grid grid-cols-4 gap-2">
                            @foreach($item->gallery as $img)
                                <div class="relative group" x-data="{ checked: false }">
                                    <img src="{{ Storage::temporaryUrl($img, now()->addMinutes(10)) }}"
                                         alt="" class="w-full aspect-video object-cover rounded border border-black/10">
                                    <label class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded">
                                        <input type="checkbox" name="remove_gallery[]" value="{{ $img }}"
                                               x-model="checked" class="sr-only">
                                        <span :class="checked ? 'bg-red-500 text-white' : 'bg-white text-[#0a0a0a]'"
                                              class="text-xs font-medium px-2 py-1 rounded transition-colors">
                                            <span x-text="checked ? '✕ Supprimer' : 'Cocher pour suppr.'"></span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mb-2">Survolez pour sélectionner les images à supprimer.</p>
                    @endif

                    <input type="file" name="gallery[]" accept="image/*" multiple
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:border file:border-black/20 file:rounded-sm file:text-xs file:font-medium file:bg-white file:text-[#0a0a0a] hover:file:bg-gray-50 transition-colors">
                    @error('gallery.*') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary text-sm">
                    Annuler
                </a>
                <button type="submit" class="btn-primary">
                    {{ $item ? 'Mettre à jour' : 'Créer le projet' }}
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
