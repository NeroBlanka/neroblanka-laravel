<x-app-layout>
    <x-slot:title>Configurer le MFA — Neroblanka Admin</x-slot:title>

    <div class="max-w-lg mx-auto px-6 py-16">

        <h1 class="text-2xl font-semibold text-[#0a0a0a] mb-2">
            Configurer l'authentification 2FA
        </h1>
        <p class="text-sm text-[#888780] mb-8">
            Scannez le QR code avec Google Authenticator, Authy ou toute autre app TOTP. Confirmez avec un code pour activer.
        </p>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="border border-black/10 rounded p-6 mb-6">
            <h2 class="font-semibold text-sm mb-4">1. Scannez ce QR code</h2>

            {{-- QR code via Google Charts API (pas de dépendance serveur) --}}
            <div class="flex justify-center mb-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($qrUrl) }}&size=200x200&bgcolor=ffffff"
                     alt="QR Code MFA"
                     class="border border-black/10 rounded p-2"
                     width="200" height="200">
            </div>

            <p class="text-xs text-[#888780] text-center mb-1">Ou entrez manuellement ce code secret :</p>
            <p class="text-center font-mono text-sm bg-[#f8f7f4] border border-black/10 rounded px-4 py-2 tracking-widest select-all">
                {{ $secret }}
            </p>
        </div>

        <div class="border border-black/10 rounded p-6 mb-6">
            <h2 class="font-semibold text-sm mb-4">2. Confirmez avec un code</h2>
            <form method="POST" action="{{ route('admin.mfa.enable') }}">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}"
                           maxlength="6" autocomplete="one-time-code"
                           class="flex-1 text-center text-xl tracking-[0.4em] border border-black/20 rounded px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-black/30 font-mono"
                           placeholder="000000">
                    <button type="submit"
                            class="px-5 py-2.5 bg-[#0a0a0a] text-white text-sm font-medium rounded-sm hover:bg-[#333] transition-colors whitespace-nowrap">
                        Activer le MFA
                    </button>
                </div>
                @error('code')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </form>
        </div>

        @if(auth()->user()->totp_enabled)
            <div class="border border-red-200 rounded p-6">
                <h2 class="font-semibold text-sm text-red-600 mb-3">Désactiver le MFA</h2>
                <form method="POST" action="{{ route('admin.mfa.disable') }}">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="code" inputmode="numeric" maxlength="6"
                               class="flex-1 text-center text-xl tracking-[0.4em] border border-red-200 rounded px-4 py-2.5 focus:outline-none font-mono"
                               placeholder="000000">
                        <button type="submit"
                                class="px-5 py-2.5 border border-red-300 text-red-600 text-sm rounded-sm hover:bg-red-50 transition-colors whitespace-nowrap">
                            Désactiver
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-block text-sm text-[#888780] hover:text-[#0a0a0a] transition-colors">
            ← Dashboard
        </a>
    </div>
</x-app-layout>
