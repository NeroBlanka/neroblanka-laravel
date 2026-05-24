<x-app-layout>
    <x-slot:title>Vérification MFA — Neroblanka Admin</x-slot:title>

    <div class="min-h-screen flex items-center justify-center bg-[#f8f7f4] px-4">
        <div class="w-full max-w-sm">

            <div class="text-center mb-8">
                <p class="font-semibold text-[#0a0a0a] text-xl mb-1">Neroblanka</p>
                <p class="text-sm text-[#888780]">Authentification à deux facteurs</p>
            </div>

            <div class="bg-white border border-black/[0.08] rounded p-8">
                <h1 class="font-semibold text-[#0a0a0a] mb-1">Code d'authentification</h1>
                <p class="text-sm text-[#888780] mb-6">Saisissez le code à 6 chiffres généré par votre application d'authentification.</p>

                <form method="POST" action="{{ route('admin.mfa.check') }}">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}"
                               maxlength="6" autocomplete="one-time-code" autofocus
                               class="w-full text-center text-2xl tracking-[0.5em] border border-black/20 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-black/30 font-mono"
                               placeholder="000000">
                        @error('code')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-[#0a0a0a] text-white text-sm font-medium rounded-sm hover:bg-[#333] transition-colors">
                        Vérifier
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="text-center mt-6">
                @csrf
                <button type="submit" class="text-xs text-[#888780] hover:text-[#0a0a0a] transition-colors">
                    Se déconnecter
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
