<x-guest-layout>

    <p class="text-sm mb-6" style="color: var(--gris)">
        Mot de passe oublié ? Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation.
    </p>

    @if(session('status'))
        <div class="mb-5 text-sm px-4 py-3 rounded-xl" style="color: #6ee7b7; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.25)">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label class="block label-mono mb-2" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="input-base w-full" required autofocus>
            @error('email')
                <p class="mt-1.5 text-xs" style="color: #f87171">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">
            Envoyer le lien de réinitialisation
        </button>
    </form>

</x-guest-layout>
