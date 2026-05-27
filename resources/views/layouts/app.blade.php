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
    <style>
        /* Glassmorphisme nav header */
        .neo-header {
            background: rgba(7,8,15,0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 4px 30px rgba(0,0,0,0.4);
        }
        /* Toast slide-in */
        @keyframes toast-in {
            from { opacity: 0; transform: translateY(-12px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .toast { animation: toast-in 0.25s cubic-bezier(0.16,1,0.3,1); }

        /* 3D tilt effect */
        .neo-tilt-card {
            transition: transform 0.25s cubic-bezier(0.23,1,0.32,1);
            transform-style: preserve-3d;
        }
    </style>
</head>
<body class="min-h-screen">

    {{-- Toasts --}}
    @if(session('success'))
        <div class="toast fixed top-5 right-5 z-[100] flex items-center gap-3 rounded-xl px-5 py-3.5 text-sm font-medium"
             style="background:rgba(7,8,15,0.85); backdrop-filter:blur(20px); color:#6ee7b7; box-shadow:0 8px 32px rgba(0,0,0,0.5); border:1px solid rgba(110,231,183,0.2)">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 opacity-40 hover:opacity-100 transition text-base leading-none">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="toast fixed top-5 right-5 z-[100] flex items-center gap-3 rounded-xl px-5 py-3.5 text-sm"
             style="background:rgba(7,8,15,0.85); backdrop-filter:blur(20px); color:#fca5a5; box-shadow:0 8px 32px rgba(0,0,0,0.5); border:1px solid rgba(252,165,165,0.2)">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 opacity-40 hover:opacity-100 transition text-base leading-none">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <header class="neo-header sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between gap-8">

            <a href="{{ auth()->check() ? route(auth()->user()->role . '.dashboard') : '/' }}"
               class="font-clash font-semibold text-base tracking-tight shrink-0 transition-opacity hover:opacity-60"
               style="color: var(--perle)">
                Neroblanka
            </a>

            @auth
                @php $role = auth()->user()->role; @endphp
                <nav class="flex items-center gap-1 flex-1">
                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.leads') }}" class="nav-link {{ request()->routeIs('admin.leads*') ? 'nav-link-active' : '' }}">
                            Leads
                            @if(($leadsCount ?? 0) > 0)
                                <span class="ml-1.5 text-[10px] font-mono font-bold rounded-full px-1.5 py-0.5 leading-none"
                                      style="background:rgba(110,231,183,0.15); color:#6ee7b7">{{ $leadsCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.freelances') }}" class="nav-link {{ request()->routeIs('admin.freelances*') ? 'nav-link-active' : '' }}">Freelances</a>
                        <a href="{{ route('admin.portfolio.index') }}" class="nav-link {{ request()->routeIs('admin.portfolio*') ? 'nav-link-active' : '' }}">Portfolio</a>
                        <a href="{{ route('admin.mfa.setup') }}" class="nav-link flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ auth()->user()->totp_enabled ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>MFA
                        </a>
                    @elseif($role === 'client')
                        <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'nav-link-active' : '' }}">Mes projets</a>
                        <a href="{{ route('client.brief.create') }}" class="nav-link {{ request()->routeIs('client.brief*') ? 'nav-link-active' : '' }}">Nouveau brief</a>
                    @elseif($role === 'freelance')
                        <a href="{{ route('freelance.dashboard') }}" class="nav-link {{ request()->routeIs('freelance.dashboard') ? 'nav-link-active' : '' }}">Mes missions</a>
                    @endif
                </nav>

                <div class="flex items-center gap-3 shrink-0">
                    <span class="label-mono hidden sm:block">{{ ucfirst($role) }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs transition-colors cursor-pointer" style="color: var(--gris-mid)">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main>{{ $slot }}</main>

    @livewireScripts

    {{-- 3D tilt effect on neo-tilt-card elements --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.neo-tilt-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const r = card.getBoundingClientRect();
                const x = e.clientX - r.left - r.width / 2;
                const y = e.clientY - r.top - r.height / 2;
                const rx = -(y / r.height) * 6;
                const ry =  (x / r.width)  * 6;
                card.style.transform = `perspective(600px) rotateX(${rx}deg) rotateY(${ry}deg) translateZ(4px)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(600px) rotateX(0deg) rotateY(0deg) translateZ(0)';
            });
        });
    });
    </script>
</body>
</html>
