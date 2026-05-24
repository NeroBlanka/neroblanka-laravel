<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Neroblanka — Studio créatif premium' }}</title>
    <meta name="description" content="{{ $description ?? 'Studio de branding, 3D, motion design, web et systèmes IA. Du contraste naît la clarté.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@400,500,600,700&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap">
    @livewireStyles
</head>
<body class="bg-[#0a0a0a] text-[#e8e7e2] font-['DM_Sans']">

    <header class="fixed top-0 left-0 right-0 z-50 border-b border-white/[0.06]" style="background: rgba(10,10,10,0.92); backdrop-filter: blur(12px);">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="font-clash text-[#ffffff] font-semibold text-lg tracking-tight">
                Neroblanka
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm text-[#888780]">
                <a href="/work" class="hover:text-white transition-colors">Travaux</a>
                <a href="/services" class="hover:text-white transition-colors">Services</a>
                <a href="/brief" class="ml-4 px-4 py-2 bg-white text-[#0a0a0a] text-sm font-medium rounded-sm hover:bg-[#e8e7e2] transition-colors">
                    Diagnostic créatif
                </a>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-white/[0.08] mt-32 py-12">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 text-sm text-[#888780]">
            <span class="font-clash text-white font-semibold">Neroblanka</span>
            <span>Du contraste naît la clarté.</span>
            <div class="flex items-center gap-6">
                <a href="/services" class="hover:text-white transition-colors">Services</a>
                <a href="/work" class="hover:text-white transition-colors">Travaux</a>
                <a href="/brief" class="hover:text-white transition-colors">Contact</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
