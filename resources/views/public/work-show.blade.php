<x-public-layout :title="$item->title . ' — Neroblanka'" :description="$item->excerpt">

    {{-- Hero --}}
    <section class="pt-32 pb-16 border-b border-white/[0.06]">
        <div class="max-w-4xl mx-auto px-6">
            <a href="/work" class="text-sm text-[#888780] hover:text-white transition-colors mb-8 inline-block">← Travaux</a>

            <p class="text-[#888780] text-xs font-mono tracking-[0.2em] uppercase mb-4">
                {{ $item->client_name }} · {{ $item->published_at->format('Y') }}
            </p>
            <h1 class="text-4xl md:text-5xl font-semibold text-white mb-6" style="font-family: 'Clash Grotesk', sans-serif;">
                {{ $item->title }}
            </h1>
            <p class="text-[#888780] text-lg max-w-2xl leading-relaxed">{{ $item->excerpt }}</p>

            @if($item->tags)
                <div class="flex flex-wrap gap-2 mt-6">
                    @foreach($item->tags as $tag)
                        <span class="text-xs text-[#888780] border border-white/10 px-3 py-1 rounded-full">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Contenu --}}
    @if($item->content)
        <section class="py-16">
            <div class="max-w-3xl mx-auto px-6">
                <div class="prose prose-invert prose-lg max-w-none text-[#888780] leading-relaxed">
                    {!! nl2br(e($item->content)) !!}
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="border-t border-white/[0.06] py-16">
        <div class="max-w-4xl mx-auto px-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-semibold text-white mb-2" style="font-family: 'Clash Grotesk', sans-serif;">
                    Un projet similaire en tête ?
                </h2>
                <p class="text-[#888780] text-sm">Partagez votre brief en 3 minutes.</p>
            </div>
            <a href="/brief"
               class="shrink-0 px-6 py-3 bg-white text-[#0a0a0a] font-medium text-sm rounded-sm hover:bg-[#e8e7e2] transition-colors"
               style="font-family: 'Clash Grotesk', sans-serif;">
                Démarrer un projet →
            </a>
        </div>
    </section>

</x-public-layout>
