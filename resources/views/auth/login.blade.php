<x-guest-layout>
    <div class="card p-8">
        <div class="mb-8">
            <p class="label-mono mb-3">Espace client</p>
            <h1 class="text-2xl">Connectez-vous à votre espace</h1>
        </div>

        @if(session('status'))
            <div class="mb-6 text-sm text-[#0a0a0a] bg-[#e8e7e2] px-4 py-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="input-base" required autofocus autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Mot de passe</label>
                <input id="password" type="password" name="password"
                       class="input-base" required autocomplete="current-password" />
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-black/20 text-[#0a0a0a] focus:ring-[#0a0a0a]" />
                <label for="remember_me" class="text-sm text-[#888780]">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-primary w-full">
                Connexion
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#888780]">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-[#0a0a0a] font-medium hover:underline">Créer un compte</a>
        </p>
    </div>
</x-guest-layout>
