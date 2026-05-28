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

    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-papier text-carbone antialiased">

    {{-- ─── Header ─── --}}
    <header
        x-data="{ open: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 12)"
        :class="scrolled ? 'border-b' : 'border-b-transparent'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-200"
        style="background: rgba(251,250,247,0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-color: var(--gris-bord-soft);">

        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="font-clash text-xl font-semibold tracking-tight text-carbone">
                Neroblanka<span style="color: var(--gris-texte-soft)">.</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="/work" class="nav-link {{ request()->is('work*') ? 'nav-link-active' : '' }}">Travaux</a>
                <a href="/services" class="nav-link {{ request()->is('services*') ? 'nav-link-active' : '' }}">Services</a>
                <a href="/#process" class="nav-link">Process</a>
                <a href="/#contact" class="nav-link">Contact</a>
                <a href="/brief" class="btn-primary ml-3 text-sm px-4 py-2">
                    Demander un diagnostic
                </a>
            </nav>

            {{-- Hamburger --}}
            <button
                @click="open = !open"
                class="md:hidden flex flex-col justify-center items-center w-10 h-10 gap-1.5 rounded-lg"
                :aria-expanded="open"
                aria-label="Menu">
                <span class="block w-5 h-px transition-all duration-300"
                      style="background: var(--carbone)"
                      :class="open ? 'rotate-45 translate-y-[7px]' : ''"></span>
                <span class="block w-5 h-px transition-all duration-300"
                      style="background: var(--carbone)"
                      :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-5 h-px transition-all duration-300"
                      style="background: var(--carbone)"
                      :class="open ? '-rotate-45 -translate-y-[7px]' : ''"></span>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div
            x-show="open"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            @click.outside="open = false"
            class="md:hidden px-6 py-6 flex flex-col gap-3 border-t"
            style="background: var(--papier); border-color: var(--gris-bord-soft);">
            <a href="/work" @click="open = false" class="text-base py-2 text-carbone">Travaux</a>
            <a href="/services" @click="open = false" class="text-base py-2 text-carbone">Services</a>
            <a href="/#process" @click="open = false" class="text-base py-2 text-carbone">Process</a>
            <a href="/#contact" @click="open = false" class="text-base py-2 text-carbone">Contact</a>
            <a href="/brief" @click="open = false" class="btn-primary mt-2 justify-center py-3">
                Demander un diagnostic
            </a>
        </div>
    </header>

    <main class="pt-16">{{ $slot }}</main>

    {{-- ─── Footer ─── --}}
    <footer class="border-t" style="border-color: var(--gris-bord-soft); background: var(--perle);">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">

                <div class="col-span-2 md:col-span-1">
                    <a href="/" class="font-clash text-xl font-semibold tracking-tight text-carbone block mb-3">
                        Neroblanka<span style="color: var(--gris-texte-soft)">.</span>
                    </a>
                    <p class="text-sm leading-relaxed text-gris max-w-xs">
                        Studio créatif premium basé à Blida, Algérie.
                    </p>
                    <p class="text-sm font-medium text-carbone mt-3">
                        Du contraste naît la clarté.
                    </p>
                </div>

                <div>
                    <p class="label-mono mb-4">Navigation</p>
                    <ul class="space-y-2.5">
                        <li><a href="/" class="text-sm text-gris hover:text-carbone transition-colors">Accueil</a></li>
                        <li><a href="/services" class="text-sm text-gris hover:text-carbone transition-colors">Services</a></li>
                        <li><a href="/work" class="text-sm text-gris hover:text-carbone transition-colors">Portfolio</a></li>
                        <li><a href="/brief" class="text-sm text-gris hover:text-carbone transition-colors">Brief</a></li>
                    </ul>
                </div>

                <div>
                    <p class="label-mono mb-4">Services</p>
                    <ul class="space-y-2.5">
                        <li><a href="/services" class="text-sm text-gris hover:text-carbone transition-colors">Branding</a></li>
                        <li><a href="/services" class="text-sm text-gris hover:text-carbone transition-colors">3D</a></li>
                        <li><a href="/services" class="text-sm text-gris hover:text-carbone transition-colors">Motion</a></li>
                        <li><a href="/services" class="text-sm text-gris hover:text-carbone transition-colors">Web</a></li>
                        <li><a href="/services" class="text-sm text-gris hover:text-carbone transition-colors">IA</a></li>
                    </ul>
                </div>

                <div>
                    <p class="label-mono mb-4">Social</p>
                    <ul class="space-y-2.5">
                        <li><a href="#" target="_blank" rel="noopener" class="text-sm text-gris hover:text-carbone transition-colors">LinkedIn</a></li>
                        <li><a href="#" target="_blank" rel="noopener" class="text-sm text-gris hover:text-carbone transition-colors">Behance</a></li>
                        <li><a href="#" target="_blank" rel="noopener" class="text-sm text-gris hover:text-carbone transition-colors">Instagram</a></li>
                        <li><a href="mailto:hello@neroblanka.com" class="text-sm text-gris hover:text-carbone transition-colors">Email</a></li>
                    </ul>
                </div>

            </div>

            <div class="pt-8 border-t flex flex-col md:flex-row items-start md:items-center justify-between gap-3"
                 style="border-color: var(--gris-bord-soft)">
                <p class="text-xs text-gris-soft">© {{ date('Y') }} Neroblanka Studio. Tous droits réservés.</p>
                <p class="text-xs text-gris-soft">Blida · Algérie</p>
            </div>
        </div>
    </footer>

    {{-- Scroll animation observer --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.querySelectorAll('.fade-up, .fade-in').forEach(el => el.classList.add('is-visible'));
                return;
            }
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

            document.querySelectorAll('.fade-up, .fade-in').forEach(function (el) {
                observer.observe(el);
            });
        });
    </script>

    @livewireScripts
</body>
</html>
