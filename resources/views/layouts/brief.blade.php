<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Brief — Neroblanka' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@400,500,600,700&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap">
    @livewireStyles
</head>
<body class="min-h-screen bg-[#0a0a0a] text-[#e8e7e2]">

    <header class="fixed top-0 left-0 right-0 z-50 px-6 h-16 flex items-center">
        <a href="/" class="font-clash font-semibold text-lg tracking-tight text-white hover:opacity-70 transition-opacity">
            Neroblanka
        </a>
    </header>

    {{ $slot }}

    @livewireScripts
</body>
</html>
