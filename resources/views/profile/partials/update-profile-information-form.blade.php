<section>
    <header>
        <h2 class="font-clash text-lg font-semibold text-carbone">Informations du profil</h2>
        <p class="mt-1 text-sm text-gris">Mettez à jour les informations et l'adresse email de votre compte.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="full_name" class="block label-mono mb-2">Nom complet</label>
            <input id="full_name" name="full_name" type="text" class="input-base w-full"
                   value="{{ old('full_name', $user->full_name) }}" required autofocus autocomplete="name" />
            @error('full_name') <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block label-mono mb-2">Email</label>
            <input id="email" name="email" type="email" class="input-base w-full"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gris">
                        Votre adresse email n'est pas vérifiée.
                        <button form="send-verification" class="underline text-carbone hover:opacity-70">Cliquez ici pour renvoyer l'email de vérification.</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm" style="color: #15803d">Un nouveau lien de vérification a été envoyé.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary px-5 py-2.5 text-sm">Enregistrer</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gris">Enregistré.</p>
            @endif
        </div>
    </form>
</section>
