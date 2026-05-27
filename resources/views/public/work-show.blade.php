<x-public-layout :title="$item->title . ' — Neroblanka'" :description="$item->excerpt">

    {{-- Hero --}}
    <section class="pt-32 pb-20" style="border-bottom: 1px solid rgba(255,255,255,0.06)">
        <div class="max-w-5xl mx-auto px-6">
            <a href="/work" class="label-mono inline-flex items-center gap-2 mb-12 transition-opacity hover:opacity-60">
                ← Travaux
            </a>

            <div class="grid md:grid-cols-[1fr_auto] gap-12 items-end">
                <div>
                    <p class="label-mono mb-5 fade-in">{{ $item->client_name }} · {{ $item->published_at->format('Y') }}</p>
                    <h1 class="font-clash text-5xl md:text-6xl lg:text-7xl font-semibold leading-[1.05] tracking-tight mb-6 fade-up" style="color: var(--perle)">
                        {{ $item->title }}
                    </h1>
                    <p class="text-lg max-w-xl leading-relaxed fade-up" style="color: var(--gris); transition-delay: 0.1s">
                        {{ $item->excerpt }}
                    </p>
                </div>

                @if($item->service_type)
                    <div class="fade-in" style="transition-delay: 0.2s">
                        <span class="label-mono px-4 py-2 rounded-full inline-block"
                              style="border: 1px solid rgba(124,92,252,0.3); background: rgba(124,92,252,0.08); color: var(--purple)">
                            {{ $item->service_type->label() }}
                        </span>
                    </div>
                @endif
            </div>

            @if($item->tags)
                <div class="flex flex-wrap gap-2 mt-10 fade-up" style="transition-delay: 0.15s">
                    @foreach($item->tags as $tag)
                        <span class="label-mono px-3 py-1 rounded-full text-xs"
                              style="color: var(--gris-mid); border: 1px solid rgba(255,255,255,0.08)">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Visuel principal --}}
    @if($item->cover_image)
        <section class="fade-in">
            <div class="max-w-5xl mx-auto px-6 py-12">
                <div class="aspect-video overflow-hidden rounded-2xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06)">
                    <img src="{{ Storage::temporaryUrl($item->cover_image, now()->addMinutes(30)) }}"
                         alt="{{ $item->title }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </section>
    @endif

    {{-- Contenu --}}
    @if($item->content)
        <section class="py-20">
            <div class="max-w-2xl mx-auto px-6">
                <div class="leading-relaxed space-y-6 fade-up" style="color: var(--gris)">
                    {!! nl2br(e($item->content)) !!}
                </div>
            </div>
        </section>
    @endif

    {{-- Galerie --}}
    @if($item->gallery && count($item->gallery))
        <section class="py-12" style="border-top: 1px solid rgba(255,255,255,0.06)">
            <div class="max-w-5xl mx-auto px-6">
                <p class="label-mono mb-8 fade-in">Galerie</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($item->gallery as $image)
                        <div class="aspect-video overflow-hidden rounded-xl fade-up"
                             style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06)">
                            <img src="{{ Storage::temporaryUrl($image, now()->addMinutes(30)) }}"
                                 alt="" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Navigation --}}
    <section class="py-12" style="border-top: 1px solid rgba(255,255,255,0.06)">
        <div class="max-w-5xl mx-auto px-6">
            <a href="/work" class="label-mono transition-opacity hover:opacity-60">← Tous les projets</a>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6" style="border-top: 1px solid rgba(255,255,255,0.06)">
        <div class="max-w-4xl mx-auto">
            <div class="rounded-2xl p-10 md:p-14 relative overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(124,92,252,0.12) 0%, rgba(240,89,218,0.06) 100%);
                        border: 1px solid rgba(124,92,252,0.2)">
                <div class="absolute inset-0 opacity-20 pointer-events-none"
                     style="background: radial-gradient(circle at 20% 50%, rgba(124,92,252,0.4) 0%, transparent 60%)"></div>
                <div class="relative fade-up">
                    <p class="label-mono mb-4" style="color: var(--purple)">Prochaine étape</p>
                    <h2 class="font-clash text-3xl md:text-4xl font-semibold mb-4 leading-tight" style="color: var(--perle)">
                        Un projet similaire<br>en tête ?
                    </h2>
                    <p class="text-sm mb-8 max-w-sm" style="color: var(--gris)">Partagez votre brief en 3 minutes. Réponse sous 48h.</p>
                    <a href="/brief" class="btn-primary">Démarrer un projet →</a>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
