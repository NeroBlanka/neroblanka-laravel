<section class="space-y-5">
    <header>
        <h2 class="font-clash text-lg font-semibold text-carbone">Supprimer le compte</h2>
        <p class="mt-1 text-sm text-gris">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Téléchargez au préalable toute donnée que vous souhaitez conserver.</p>
    </header>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-medium transition-colors cursor-pointer"
            style="color: var(--status-danger); background: rgba(185,28,28,0.06); border: 1px solid rgba(185,28,28,0.25)">
        Supprimer le compte
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-clash text-lg font-semibold text-carbone">
                Êtes-vous sûr de vouloir supprimer votre compte ?
            </h2>

            <p class="mt-1 text-sm text-gris">
                Une fois supprimé, toutes les ressources et données seront définitivement effacées. Entrez votre mot de passe pour confirmer.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Mot de passe</label>
                <input id="password" name="password" type="password" class="input-base w-3/4" placeholder="Mot de passe" />
                @error('password', 'userDeletion') <p class="mt-2 text-xs" style="color: var(--status-danger)">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary px-5 py-2.5 text-sm">Annuler</button>
                <button type="submit" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-medium cursor-pointer text-white" style="background: var(--status-danger); border: 1px solid var(--status-danger)">
                    Supprimer le compte
                </button>
            </div>
        </form>
    </x-modal>
</section>
