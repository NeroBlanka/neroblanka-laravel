<x-guest-layout>
    <div class="mb-6">
        <p class="label-mono mb-3">Récupération</p>
        <h1 class="font-clash text-2xl font-semibold text-carbone mb-3">Mot de passe oublié ?</h1>
        <p class="text-sm text-gris">
            Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation.
        </p>
    </div>

    @if(session('status'))
        <div class="mb-5 text-sm px-4 py-3 rounded-xl" style="color: #15803d; background: rgba(31,157,85,0.08); border: 1px solid rgba(31,157,85,0.25)">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-5">
            <label class="block label-mono mb-2" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="input-base w-full" required autofocus>
            @error('email')
                <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gris">
        <a href="{{ route('login') }}" class="text-carbone font-medium hover:underline">← Retour à la connexion</a>
    </p>
</x-guest-layout>
