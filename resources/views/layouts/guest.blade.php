<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Neroblanka') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@400,500,600,700&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap">
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4">

    <div class="mb-8">
        <a href="/" class="font-semibold text-2xl tracking-tight transition-opacity hover:opacity-60"
           style="font-family:'Clash Grotesk',sans-serif; color: var(--perle)">
            Neroblanka
        </a>
    </div>

    <div class="w-full max-w-md">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
