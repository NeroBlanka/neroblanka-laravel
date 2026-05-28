<x-app-layout>
    <x-slot:title>Vérification MFA — Neroblanka Admin</x-slot:title>

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm">

            <div class="text-center mb-8">
                <p class="font-clash font-semibold text-xl mb-1" style="color: var(--carbone)">Neroblanka</p>
                <p class="text-sm" style="color: var(--gris)">Authentification à deux facteurs</p>
            </div>

            <div class="card p-8">
                <h1 class="font-semibold mb-1" style="color: var(--carbone)">Code d'authentification</h1>
                <p class="text-sm mb-6" style="color: var(--gris)">Saisissez le code à 6 chiffres généré par votre application d'authentification.</p>

                <form method="POST" action="{{ route('admin.mfa.check') }}">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}"
                               maxlength="6" autocomplete="one-time-code" autofocus
                               class="input-base w-full text-center text-2xl tracking-[0.5em] font-mono"
                               placeholder="000000">
                        @error('code')
                            <p class="text-xs mt-2" style="color: var(--status-danger)">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        Vérifier
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="text-center mt-6">
                @csrf
                <button type="submit" class="text-xs transition-opacity hover:opacity-60" style="color: var(--gris)">
                    Se déconnecter
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
