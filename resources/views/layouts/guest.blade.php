<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Neroblanka') }}</title>
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4 py-12" style="background: var(--perle)">

    <div class="mb-8">
        <a href="/" class="font-clash font-semibold text-2xl tracking-tight text-carbone hover:opacity-60 transition-opacity">
            Neroblanka<span style="color: var(--gris-texte-soft)">.</span>
        </a>
    </div>

    <div class="w-full max-w-md">
        <div class="card-on-perle rounded-3xl p-8">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
