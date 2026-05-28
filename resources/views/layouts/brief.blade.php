<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Brief — Neroblanka' }}</title>
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-papier text-carbone antialiased" style="min-height: 100vh">

    <header class="fixed top-0 left-0 right-0 z-50 px-6 h-16 flex items-center border-b"
            style="background: rgba(251,250,247,0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-color: var(--gris-bord-soft)">
        <a href="/" class="font-clash font-semibold text-xl tracking-tight text-carbone hover:opacity-70 transition-opacity">
            Neroblanka<span style="color: var(--gris-texte-soft)">.</span>
        </a>
    </header>

    {{ $slot }}

    @livewireScripts
</body>
</html>
