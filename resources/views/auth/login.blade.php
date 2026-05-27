<x-guest-layout>
    <div class="card p-8">
        <div class="mb-8">
            <p class="label-mono mb-3">Espace privé</p>
            <h1 class="text-2xl" style="color: var(--perle)">Connectez-vous à votre espace</h1>
        </div>

        @if(session('status'))
            <div class="mb-6 text-sm px-4 py-3 rounded-xl" style="color: var(--perle); background: rgba(124,92,252,0.1); border: 1px solid rgba(124,92,252,0.3)">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium mb-1.5" style="color: var(--perle)">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="input-base" required autofocus autocomplete="username" />
                @error('email')
                    <p class="text-xs mt-1.5" style="color: #f87171">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1.5" style="color: var(--perle)">Mot de passe</label>
                <input id="password" type="password" name="password"
                       class="input-base" required autocomplete="current-password" />
                @error('password')
                    <p class="text-xs mt-1.5" style="color: #f87171">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded" style="accent-color: var(--purple)" />
                    <label for="remember_me" class="text-sm" style="color: var(--gris)">Se souvenir de moi</label>
                </div>
                <a href="{{ route('password.request') }}"
                   class="text-xs transition-opacity hover:opacity-70"
                   style="color: var(--gris-mid)">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-primary w-full">
                Connexion
            </button>
        </form>
    </div>
</x-guest-layout>
