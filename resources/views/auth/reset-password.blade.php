<x-guest-layout>
    <div class="mb-6">
        <p class="label-mono mb-3">Réinitialisation</p>
        <h1 class="font-clash text-2xl font-semibold text-carbone">Nouveau mot de passe</h1>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-5">
            <label class="block label-mono mb-2" for="email">Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email', $request->email) }}"
                   class="input-base w-full" required autofocus autocomplete="username">
            @error('email')
                <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block label-mono mb-2" for="password">Nouveau mot de passe</label>
            <input id="password" type="password" name="password"
                   class="input-base w-full" required autocomplete="new-password">
            @error('password')
                <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block label-mono mb-2" for="password_confirmation">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="input-base w-full" required autocomplete="new-password">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Réinitialiser le mot de passe
        </button>
    </form>
</x-guest-layout>
