<x-app-layout>
    <x-slot name="title">Mon profil — Neroblanka</x-slot>

    <div class="max-w-2xl mx-auto px-6 py-14">
        <div class="mb-10">
            <p class="label-mono mb-3">Compte</p>
            <h1 class="font-clash text-4xl font-semibold text-carbone">Mon profil</h1>
        </div>

        <div class="space-y-5">
            <div class="card-on-perle rounded-3xl p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="card-on-perle rounded-3xl p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div class="card-on-perle rounded-3xl p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
