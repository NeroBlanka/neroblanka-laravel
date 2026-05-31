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
    <style>
        @keyframes toast-in {
            from { opacity: 0; transform: translateY(-12px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .toast { animation: toast-in 0.25s cubic-bezier(0.16,1,0.3,1); }
    </style>
</head>
<body class="min-h-screen" style="background: var(--papier)">

    {{-- Toasts --}}
    @if(session('success'))
        <div class="toast fixed top-5 right-5 z-[100] flex items-center gap-3 rounded-2xl px-5 py-3.5 text-sm font-medium"
             style="background: var(--papier); color: #15803d; box-shadow: 0 12px 32px -12px rgba(5,5,5,0.2); border: 1px solid rgba(31,157,85,0.25)">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 opacity-40 hover:opacity-100 transition text-base leading-none cursor-pointer">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="toast fixed top-5 right-5 z-[100] flex items-center gap-3 rounded-2xl px-5 py-3.5 text-sm"
             style="background: var(--papier); color: var(--status-danger); box-shadow: 0 12px 32px -12px rgba(5,5,5,0.2); border: 1px solid rgba(185,28,28,0.25)">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 opacity-40 hover:opacity-100 transition text-base leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b" style="background: rgba(251,250,247,0.9); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-color: var(--gris-bord-soft)">
        <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between gap-8">

            <a href="{{ auth()->check() ? route(auth()->user()->role . '.dashboard') : '/' }}"
               class="font-clash font-semibold text-base tracking-tight shrink-0 text-carbone hover:opacity-60 transition-opacity">
                Neroblanka<span style="color: var(--gris-texte-soft)">.</span>
            </a>

            @auth
                @php $role = auth()->user()->role; @endphp
                <nav class="flex items-center gap-1 flex-1 overflow-x-auto no-scrollbar">
                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.leads') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.leads*') ? 'nav-link-active' : '' }}">
                            Leads
                            @if(($leadsCount ?? 0) > 0)
                                <span class="ml-1.5 text-[10px] font-bold rounded-full px-1.5 py-0.5 leading-none"
                                      style="background:rgba(31,157,85,0.12); color:#15803d">{{ $leadsCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.projects*') ? 'nav-link-active' : '' }}">Projets</a>
                        <a href="{{ route('admin.freelances') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.freelances*') ? 'nav-link-active' : '' }}">Freelances</a>
                        <a href="{{ route('admin.services.index') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.services*') ? 'nav-link-active' : '' }}">Services</a>
                        <a href="{{ route('admin.portfolio.index') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.portfolio*') ? 'nav-link-active' : '' }}">Portfolio</a>
                        <a href="{{ route('admin.funnel') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.funnel') ? 'nav-link-active' : '' }}">Funnel</a>
                        <a href="{{ route('admin.mfa.setup') }}" class="nav-link whitespace-nowrap flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ auth()->user()->totp_enabled ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>MFA
                        </a>
                    @elseif($role === 'client')
                        <a href="{{ route('client.dashboard') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('client.dashboard') ? 'nav-link-active' : '' }}">Mes projets</a>
                        <a href="{{ route('client.brief.create') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('client.brief*') ? 'nav-link-active' : '' }}">Nouveau brief</a>
                    @elseif($role === 'freelance')
                        <a href="{{ route('freelance.dashboard') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('freelance.dashboard') ? 'nav-link-active' : '' }}">Mes missions</a>
                    @endif
                </nav>

                <div class="flex items-center gap-3 shrink-0">
                    <span class="label-mono hidden sm:block">{{ ucfirst($role) }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs hover:text-carbone transition-colors cursor-pointer text-gris">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    {{-- Banner config prod manquante (admin uniquement) --}}
    <x-admin-env-warnings />

    <main>{{ $slot }}</main>

    @livewireScripts
</body>
</html>
