<x-guest-layout>
    <div class="mb-8">
        <p class="label-mono mb-3">Espace privé</p>
        <h1 class="font-clash text-2xl font-semibold text-carbone">Connectez-vous à votre espace</h1>
    </div>

    @if(session('status'))
        <div class="mb-6 text-sm px-4 py-3 rounded-xl" style="color: var(--carbone); background: var(--perle); border: 1px solid var(--gris-bord)">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium mb-1.5 text-carbone">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="input-base" required autofocus autocomplete="username" />
            @error('email')
                <p class="text-xs mt-1.5" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1.5 text-carbone">Mot de passe</label>
            <input id="password" type="password" name="password"
                   class="input-base" required autocomplete="current-password" />
            @error('password')
                <p class="text-xs mt-1.5" style="color: var(--status-danger)">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded" style="accent-color: var(--carbone)" />
                <label for="remember_me" class="text-sm text-gris">Se souvenir de moi</label>
            </div>
            <a href="{{ route('password.request') }}"
               class="text-xs hover:opacity-70 transition-opacity text-gris">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Connexion
        </button>
    </form>
</x-guest-layout>
