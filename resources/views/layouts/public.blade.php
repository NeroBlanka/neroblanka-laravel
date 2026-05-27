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
<body style="background: var(--bg); color: var(--perle); font-family: 'DM Sans', sans-serif;">

    <header class="fixed top-0 left-0 right-0 z-50"
            style="background: rgba(7,8,15,0.75); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.06)">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="font-clash font-semibold text-lg tracking-tight" style="color: var(--perle)">
                Neroblanka
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="/work" class="transition-opacity hover:opacity-60" style="color: var(--gris)">Travaux</a>
                <a href="/services" class="transition-opacity hover:opacity-60" style="color: var(--gris)">Services</a>
                <a href="/brief" class="btn-primary text-sm px-5 py-2">
                    Diagnostic créatif
                </a>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer style="border-top: 1px solid rgba(255,255,255,0.06); margin-top: 128px; padding: 48px 0">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 text-sm">
            <span class="font-clash font-semibold" style="color: var(--perle)">Neroblanka</span>
            <span style="color: var(--gris-mid)">Du contraste naît la clarté.</span>
            <div class="flex items-center gap-6" style="color: var(--gris)">
                <a href="/services" class="transition-opacity hover:opacity-60">Services</a>
                <a href="/work" class="transition-opacity hover:opacity-60">Travaux</a>
                <a href="/brief" class="transition-opacity hover:opacity-60">Contact</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
