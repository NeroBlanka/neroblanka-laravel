<x-app-layout>
    <x-slot:title>Configurer le MFA — Neroblanka Admin</x-slot:title>

    <div class="max-w-lg mx-auto px-6 py-16">

        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}" class="label-mono inline-flex items-center gap-2 mb-6 hover:opacity-70 transition-opacity">
                ← Dashboard
            </a>
            <h1 class="text-2xl font-semibold mb-2" style="color: var(--carbone)">
                Authentification 2FA
            </h1>
            <p class="text-sm" style="color: var(--gris)">
                Scannez le QR code avec Google Authenticator, Authy ou toute autre app TOTP. Confirmez avec un code pour activer.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 text-sm px-4 py-3 rounded-xl" style="color: #6ee7b7; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.25)">
                {{ session('success') }}
            </div>
        @endif

        {{-- QR Code --}}
        <div class="card p-6 mb-4">
            <p class="label-mono mb-4">1. Scannez ce QR code</p>

            <div class="flex justify-center mb-5">
                <div class="p-3 rounded-xl" style="background: #fff; display: inline-block;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($qrUrl) }}&size=180x180&bgcolor=ffffff"
                         alt="QR Code MFA"
                         width="180" height="180">
                </div>
            </div>

            <p class="text-xs text-center mb-2" style="color: var(--gris-mid)">Ou entrez manuellement ce code secret :</p>
            <p class="text-center font-mono text-sm px-4 py-2.5 rounded-xl tracking-widest select-all"
               style="background: rgba(0,0,0,0.3); color: var(--carbone); border: 1px solid rgba(5,5,5,0.10); letter-spacing: 0.25em">
                {{ $secret }}
            </p>
        </div>

        {{-- Confirmation --}}
        <div class="card p-6 mb-4">
            <p class="label-mono mb-4">2. Confirmez avec un code</p>
            <form method="POST" action="{{ route('admin.mfa.enable') }}">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}"
                           maxlength="6" autocomplete="one-time-code"
                           class="input-base flex-1 text-center text-xl tracking-[0.4em] font-mono"
                           placeholder="000000">
                    <button type="submit" class="btn-primary whitespace-nowrap">
                        Activer
                    </button>
                </div>
                @error('code')
                    <p class="text-xs mt-2" style="color: var(--status-danger)">{{ $message }}</p>
                @enderror
            </form>
        </div>

        @if(auth()->user()->totp_enabled)
            <div class="card p-6" style="border-color: rgba(248,113,113,0.2)">
                <p class="label-mono mb-4" style="color: #fca5a5">Désactiver le MFA</p>
                <form method="POST" action="{{ route('admin.mfa.disable') }}">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="code" inputmode="numeric" maxlength="6"
                               class="input-base flex-1 text-center text-xl tracking-[0.4em] font-mono"
                               placeholder="000000">
                        <button type="submit"
                                class="btn-secondary whitespace-nowrap text-sm"
                                style="color: #fca5a5; border-color: rgba(248,113,113,0.3)">
                            Désactiver
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>
