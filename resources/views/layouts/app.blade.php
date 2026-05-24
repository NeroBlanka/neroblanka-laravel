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
<body class="min-h-screen bg-white">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="fixed top-4 right-4 z-50 bg-[#0a0a0a] text-white text-sm px-5 py-3 rounded shadow-lg flex items-center gap-3">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 leading-none text-base">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-600 text-white text-sm px-5 py-3 rounded shadow-lg flex items-center gap-3">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 leading-none text-base">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <header class="border-b border-black/[0.08] bg-white sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ auth()->check() ? route(auth()->user()->role . '.dashboard') : '/' }}"
               class="font-clash font-semibold text-lg tracking-tight text-[#0a0a0a]">
                Neroblanka
            </a>

            @auth
                <nav class="flex items-center gap-1">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost text-sm">Dashboard</a>
                        <a href="{{ route('admin.freelances') }}" class="btn-ghost text-sm">Freelances</a>
                    @elseif(auth()->user()->role === 'client')
                        <a href="{{ route('client.dashboard') }}" class="btn-ghost text-sm">Mes projets</a>
                        <a href="{{ route('client.brief.create') }}" class="btn-ghost text-sm">Nouveau brief</a>
                    @elseif(auth()->user()->role === 'freelance')
                        <a href="{{ route('freelance.dashboard') }}" class="btn-ghost text-sm">Mes missions</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" class="btn-ghost text-sm">Déconnexion</button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    {{-- Main content --}}
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
