<x-public-layout :title="$item->title . ' — Neroblanka'" :description="$item->excerpt">

    {{-- Hero --}}
    <section class="pt-24 md:pt-32 pb-16 px-6 border-b" style="border-color: var(--gris-bord-soft)">
        <div class="max-w-5xl mx-auto">
            <a href="/work" class="label-mono inline-flex items-center gap-2 mb-12 hover:opacity-60 transition-opacity" data-reveal>← Travaux</a>

            <div class="grid md:grid-cols-[1fr_auto] gap-12 items-end">
                <div data-reveal>
                    <p class="label-mono mb-5">{{ $item->client_name }} · {{ $item->published_at->format('Y') }}</p>
                    <h1 class="display text-4xl md:text-6xl lg:text-7xl leading-[1.02] mb-6">
                        {{ $item->title }}
                    </h1>
                    <p class="text-lg max-w-xl leading-relaxed text-gris">
                        {{ $item->excerpt }}
                    </p>
                </div>

                @if($item->service_type)
                    <div data-reveal>
                        <span class="label-pill">{{ $item->service_type->label() }}</span>
                    </div>
                @endif
            </div>

            @if($item->tags)
                <div class="flex flex-wrap gap-2 mt-10" data-reveal>
                    @foreach($item->tags as $tag)
                        <span class="text-[10px] uppercase tracking-wider px-3 py-1 rounded-full"
                              style="color: var(--gris-texte); border: 1px solid var(--gris-bord)">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Visuel principal --}}
    @if($item->cover_image)
        <section data-reveal>
            <div class="max-w-5xl mx-auto px-6 py-12">
                <div class="aspect-video overflow-hidden rounded-3xl" style="background: var(--perle); border: 1px solid var(--gris-bord-soft)">
                    <img src="{{ Storage::temporaryUrl($item->cover_image, now()->addMinutes(30)) }}"
                         alt="{{ $item->title }}" class="w-full h-full object-cover">
                </div>
            </div>
        </section>
    @endif

    {{-- Contenu --}}
    @if($item->content)
        <section class="py-16 px-6">
            <div class="max-w-2xl mx-auto">
                <div class="text-lg leading-relaxed space-y-6 text-gris" data-reveal>
                    {!! nl2br(e($item->content)) !!}
                </div>
            </div>
        </section>
    @endif

    {{-- Galerie --}}
    @if($item->gallery && count($item->gallery))
        <section class="py-12 px-6 border-t" style="border-color: var(--gris-bord-soft)">
            <div class="max-w-5xl mx-auto">
                <p class="label-mono mb-8" data-reveal>Galerie</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($item->gallery as $image)
                        <div class="aspect-video overflow-hidden rounded-2xl" data-reveal
                             style="background: var(--perle); border: 1px solid var(--gris-bord-soft)">
                            <img src="{{ Storage::temporaryUrl($image, now()->addMinutes(30)) }}" alt="" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="px-6 py-20">
        <div class="max-w-4xl mx-auto">
            <div class="rounded-3xl p-10 md:p-16 relative overflow-hidden bg-carbone" data-reveal>
                <div class="absolute inset-0 opacity-10 pointer-events-none"
                     style="background-image: linear-gradient(rgba(245,242,236,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(245,242,236,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="relative">
                    <p class="label-mono mb-4" style="color: rgba(245,242,236,0.5)">Prochaine étape</p>
                    <h2 class="display text-3xl md:text-4xl text-perle mb-4 leading-tight">
                        Un projet similaire en tête ?
                    </h2>
                    <p class="text-base mb-8 max-w-sm" style="color: rgba(245,242,236,0.7)">Partagez votre brief en 3 minutes. Réponse sous 48h.</p>
                    <a href="/brief" class="btn-primary px-7 py-3.5" style="background: var(--perle); color: var(--carbone); border-color: var(--perle);" data-magnetic="0.3">
                        Démarrer un projet
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
