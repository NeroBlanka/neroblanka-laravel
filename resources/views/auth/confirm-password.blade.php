<x-guest-layout>
    <div class="mb-6">
        <p class="label-mono mb-3">Zone sécurisée</p>
        <h1 class="font-clash text-2xl font-semibold text-carbone mb-3">Confirmez votre mot de passe</h1>
        <p class="text-sm text-gris">
            Ceci est une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-6">
            <label for="password" class="block label-mono mb-2">Mot de passe</label>
            <input id="password" type="password" name="password"
                   class="input-base w-full" required autocomplete="current-password" autofocus>
            @error('password')
                <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Confirmer
        </button>
    </form>
</x-guest-layout>
