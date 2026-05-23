<x-public-layout title="Services — Neroblanka">
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-6xl mx-auto">
            <p class="text-[#888780] text-xs font-mono tracking-[0.2em] uppercase mb-6">Expertise</p>
            <h1 class="text-4xl md:text-5xl font-semibold text-white mb-16" style="font-family: 'Clash Grotesk', sans-serif;">
                7 pôles de création
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-white/[0.06]">
                @foreach(\App\Enums\ServiceType::cases() as $service)
                    @if($service !== \App\Enums\ServiceType::MIXED_PROJECT)
                        <a href="/services/{{ $service->value }}"
                            class="group p-8 bg-[#0a0a0a] hover:bg-white/[0.03] transition-colors flex flex-col gap-4">
                            <span class="text-3xl" aria-hidden="true">{{ $service->icon() }}</span>
                            <h2 class="text-lg font-semibold text-white" style="font-family: 'Clash Grotesk', sans-serif;">
                                {{ $service->label() }}
                            </h2>
                            <span class="text-xs text-[#888780] group-hover:text-white/60 transition-colors mt-auto">
                                En savoir plus →
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-public-layout>
