<x-public-layout>

    {{-- HERO --}}
    <section class="min-h-screen flex flex-col justify-center px-6 pt-32 pb-24">
        <div class="max-w-6xl mx-auto w-full">

            <div class="max-w-3xl">
                <p class="text-[#888780] text-xs font-mono tracking-[0.2em] uppercase mb-8">Studio créatif · Alger</p>

                <h1 class="text-5xl md:text-7xl font-semibold leading-[1.05] tracking-tight text-white mb-8"
                    style="font-family: 'Clash Grotesk', sans-serif;">
                    Du contraste<br>naît la clarté.
                </h1>

                <p class="text-lg text-[#888780] max-w-xl leading-relaxed mb-12">
                    Branding premium, 3D, motion, web et systèmes IA pour les marques qui refusent l'ordinaire.
                </p>

                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <a href="/brief" id="hero-cta"
                        class="px-6 py-4 bg-white text-[#0a0a0a] text-sm font-medium rounded-sm hover:bg-[#e8e7e2] transition-colors"
                        style="font-family: 'Clash Grotesk', sans-serif;">
                        Demander un diagnostic créatif
                    </a>
                    <a href="/work"
                        class="px-6 py-4 border border-white/20 text-[#e8e7e2] text-sm font-medium rounded-sm hover:border-white/60 transition-colors">
                        Voir les travaux
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- SERVICES --}}
    <section class="py-24 border-t border-white/[0.06]">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-16">
                <h2 class="text-3xl font-semibold text-white" style="font-family: 'Clash Grotesk', sans-serif;">
                    7 pôles d'expertise
                </h2>
                <a href="/services" class="text-sm text-[#888780] hover:text-white transition-colors">Tout voir →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-white/[0.06]">
                @foreach(\App\Enums\ServiceType::cases() as $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        <a href="/services/{{ $service->value }}"
                            class="group p-8 bg-[#0a0a0a] hover:bg-white/[0.03] transition-colors flex flex-col gap-4">
                            <span class="text-2xl" aria-hidden="true">{{ $service->icon() }}</span>
                            <div>
                                <h3 class="text-sm font-medium text-white mb-2" style="font-family: 'Clash Grotesk', sans-serif;">
                                    {{ $service->label() }}
                                </h3>
                            </div>
                            <span class="text-xs text-[#888780] group-hover:text-white/60 transition-colors mt-auto">
                                Voir le service →
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>

        </div>
    </section>

    {{-- CTA STICKY (apparaît quand le hero-cta disparaît) --}}
    <div id="sticky-cta"
        class="fixed bottom-6 right-6 z-50 opacity-0 translate-y-4 transition-all duration-300 pointer-events-none"
        x-data="{ visible: false }"
        x-init="
            const hero = document.getElementById('hero-cta');
            const obs = new IntersectionObserver(([e]) => { visible = !e.isIntersecting; $el.style.opacity = visible ? '1' : '0'; $el.style.transform = visible ? 'translateY(0)' : 'translateY(1rem)'; $el.style.pointerEvents = visible ? 'auto' : 'none'; });
            obs.observe(hero);
        ">
        <a href="/brief"
            class="px-5 py-3 bg-white text-[#0a0a0a] text-sm font-medium rounded-sm shadow-xl hover:bg-[#e8e7e2] transition-colors"
            style="font-family: 'Clash Grotesk', sans-serif;">
            Diagnostic créatif →
        </a>
    </div>

</x-public-layout>
