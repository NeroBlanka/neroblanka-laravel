@props(['title' => null, 'description' => null])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Neroblanka — Studio créatif premium' }}</title>
    <meta name="description" content="{{ $description ?? 'Studio de branding, 3D, motion design, web et systèmes IA. Du contraste naît la clarté.' }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Neroblanka — Studio créatif premium' }}">
    <meta property="og:description" content="{{ $description ?? 'Studio de branding, 3D, motion design, web et systèmes IA. Du contraste naît la clarté.' }}">
    <meta property="og:site_name" content="Neroblanka">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Neroblanka — Studio créatif premium' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Studio de branding, 3D, motion design, web et systèmes IA. Du contraste naît la clarté.' }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@400,500,600,700&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@400;500&display=swap">
    @livewireStyles
</head>
<body class="bg-[#0a0a0a] text-[#e8e7e2]" style="font-family: 'DM Sans', system-ui, sans-serif;">

    {{-- Header with mobile menu --}}
    <header
        x-data="{ open: false }"
        class="fixed top-0 left-0 right-0 z-50 border-b border-white/[0.06]"
        style="background: rgba(10,10,10,0.92); backdrop-filter: blur(12px);">

        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="font-clash text-white font-semibold text-lg tracking-tight">
                Neroblanka
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-6 text-sm text-[#888780]">
                <a href="/work" class="hover:text-white transition-colors">Travaux</a>
                <a href="/services" class="hover:text-white transition-colors">Services</a>
                <a href="/brief" class="ml-4 px-4 py-2 bg-white text-[#0a0a0a] text-sm font-medium rounded-sm hover:bg-[#e8e7e2] transition-colors">
                    Diagnostic créatif
                </a>
            </nav>

            {{-- Hamburger button (mobile only) --}}
            <button
                @click="open = !open"
                class="md:hidden flex flex-col justify-center items-center w-10 h-10 gap-1.5"
                :aria-expanded="open"
                aria-label="Menu">
                <span class="block w-5 h-px bg-white transition-all duration-300"
                      :class="open ? 'rotate-45 translate-y-[7px]' : ''"></span>
                <span class="block w-5 h-px bg-white transition-all duration-300"
                      :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-5 h-px bg-white transition-all duration-300"
                      :class="open ? '-rotate-45 -translate-y-[7px]' : ''"></span>
            </button>
        </div>

        {{-- Mobile menu panel --}}
        <div
            x-show="open"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            @click.outside="open = false"
            class="md:hidden border-t border-white/[0.06] px-6 py-6 flex flex-col gap-5 text-sm text-[#888780]"
            style="background: rgba(10,10,10,0.97);">
            <a href="/work" @click="open = false" class="hover:text-white transition-colors py-1">Travaux</a>
            <a href="/services" @click="open = false" class="hover:text-white transition-colors py-1">Services</a>
            <a href="/brief" @click="open = false"
               class="mt-2 px-5 py-3 bg-white text-[#0a0a0a] text-sm font-medium rounded-sm hover:bg-[#e8e7e2] transition-colors text-center">
                Diagnostic créatif
            </a>
        </div>
    </header>

    <main>{{ $slot }}</main>

    <footer class="border-t border-white/[0.08] mt-32 py-12">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 text-sm text-[#888780]">
            <span class="text-white font-semibold">Neroblanka</span>
            <span>Du contraste naît la clarté.</span>
            <div class="flex items-center gap-6">
                <a href="/services" class="hover:text-white transition-colors">Services</a>
                <a href="/work" class="hover:text-white transition-colors">Travaux</a>
                <a href="/brief" class="hover:text-white transition-colors">Contact</a>
            </div>
        </div>
    </footer>

    {{-- Scroll animation observer --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.fade-up, .fade-in').forEach(function (el) {
                observer.observe(el);
            });
        });
    </script>

    @livewireScripts
</body>
</html>
