<x-guest-layout>
    <div class="mb-6">
        <p class="label-mono mb-3">Vérification</p>
        <h1 class="font-clash text-2xl font-semibold text-carbone mb-3">Vérifiez votre email</h1>
        <p class="text-sm leading-relaxed text-gris">
            Merci de votre inscription. Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Vous ne l'avez pas reçu ? Nous vous en renverrons un volontiers.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 text-sm px-4 py-3 rounded-xl" style="color: #15803d; background: rgba(31,157,85,0.08); border: 1px solid rgba(31,157,85,0.25)">
            Un nouveau lien de vérification a été envoyé à votre adresse email.
        </div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary px-5 py-3">
                Renvoyer l'email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-ghost text-sm">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
