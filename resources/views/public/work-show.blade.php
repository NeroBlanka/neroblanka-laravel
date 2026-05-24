<x-public-layout :title="$item->title . ' — Neroblanka'" :description="$item->excerpt">

    {{-- Hero --}}
    <section class="pt-32 pb-20 border-b border-white/[0.06]">
        <div class="max-w-5xl mx-auto px-6">
            <a href="/work" class="label-mono inline-flex items-center gap-2 mb-12 hover:text-white transition-colors">
                ← Travaux
            </a>

            <div class="grid md:grid-cols-[1fr_auto] gap-12 items-end">
                <div>
                    <p class="label-mono mb-5 fade-in">{{ $item->client_name }} · {{ $item->published_at->format('Y') }}</p>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-semibold text-white leading-[1.05] tracking-tight mb-6 fade-up">
                        {{ $item->title }}
                    </h1>
                    <p class="text-[#888780] text-lg max-w-xl leading-relaxed fade-up" style="transition-delay:0.1s">
                        {{ $item->excerpt }}
                    </p>
                </div>

                @if($item->service_type)
                    <div class="fade-in" style="transition-delay:0.2s">
                        <span class="label-mono border border-white/10 px-4 py-2 rounded-sm inline-block">
                            {{ $item->service_type->label() }}
                        </span>
                    </div>
                @endif
            </div>

            @if($item->tags)
                <div class="flex flex-wrap gap-2 mt-10 fade-up" style="transition-delay:0.15s">
                    @foreach($item->tags as $tag)
                        <span class="text-xs text-[#555350] border border-white/[0.06] px-3 py-1">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Visuel principal --}}
    @if($item->cover_image)
        <section class="fade-in">
            <div class="max-w-5xl mx-auto px-6 py-12">
                <div class="aspect-video bg-white/[0.03] overflow-hidden">
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
                <div class="text-[#888780] leading-relaxed space-y-6 fade-up">
                    {!! nl2br(e($item->content)) !!}
                </div>
            </div>
        </section>
    @endif

    {{-- Galerie --}}
    @if($item->gallery && count($item->gallery))
        <section class="py-12 border-t border-white/[0.06]">
            <div class="max-w-5xl mx-auto px-6">
                <p class="label-mono mb-8 fade-in">Galerie</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($item->gallery as $image)
                        <div class="aspect-video bg-white/[0.03] overflow-hidden fade-up">
                            <img src="{{ Storage::temporaryUrl($image, now()->addMinutes(30)) }}"
                                 alt=""
                                 class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Navigation projet --}}
    <section class="border-t border-white/[0.06] py-16">
        <div class="max-w-5xl mx-auto px-6">
            <a href="/work" class="label-mono hover:text-white transition-colors">← Tous les projets</a>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-white/[0.06] py-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="fade-up">
                <p class="label-mono mb-4">Prochaine étape</p>
                <h2 class="text-3xl md:text-4xl font-semibold text-white mb-4 leading-tight">
                    Un projet similaire<br>en tête ?
                </h2>
                <p class="text-[#888780] text-sm mb-8 max-w-sm">Partagez votre brief en 3 minutes. Réponse sous 48h.</p>
                <a href="/brief" class="btn-primary">Démarrer un projet →</a>
            </div>
        </div>
    </section>

</x-public-layout>
