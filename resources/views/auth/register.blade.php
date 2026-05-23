<x-guest-layout>
    <div class="card p-8">
        <div class="mb-8">
            <p class="label-mono mb-3">Nouveau compte</p>
            <h1 class="text-2xl">Créez votre compte client</h1>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="role" value="client" />

            <div>
                <label for="full_name" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Nom complet</label>
                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}"
                       class="input-base" required autofocus autocomplete="name" />
                @error('full_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="input-base" required autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Mot de passe</label>
                <input id="password" type="password" name="password"
                       class="input-base" required autocomplete="new-password" />
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="input-base" required autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary w-full">
                Créer mon compte
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#888780]">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-[#0a0a0a] font-medium hover:underline">Se connecter</a>
        </p>
    </div>
</x-guest-layout>
