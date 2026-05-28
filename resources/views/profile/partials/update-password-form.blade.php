<section>
    <header>
        <h2 class="font-clash text-lg font-semibold text-carbone">Mot de passe</h2>
        <p class="mt-1 text-sm text-gris">Assurez-vous d'utiliser un mot de passe long et aléatoire pour rester en sécurité.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block label-mono mb-2">Mot de passe actuel</label>
            <input id="update_password_current_password" name="current_password" type="password" class="input-base w-full" autocomplete="current-password" />
            @error('current_password', 'updatePassword') <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password" class="block label-mono mb-2">Nouveau mot de passe</label>
            <input id="update_password_password" name="password" type="password" class="input-base w-full" autocomplete="new-password" />
            @error('password', 'updatePassword') <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block label-mono mb-2">Confirmer le mot de passe</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input-base w-full" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword') <p class="mt-1.5 text-xs" style="color: var(--status-danger)">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary px-5 py-2.5 text-sm">Enregistrer</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gris">Enregistré.</p>
            @endif
        </div>
    </form>
</section>
