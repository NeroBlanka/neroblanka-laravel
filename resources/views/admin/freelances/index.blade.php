<x-app-layout>
    <x-slot name="title">Freelances — Admin Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="label-mono mb-2">Administration</p>
                <h1 class="text-3xl">Freelances</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-ghost text-sm">&larr; Dashboard</a>
        </div>

        @if($freelances->isEmpty())
            <div class="card px-6 py-12 text-center text-[#888780]">
                Aucun freelance enregistré.
            </div>
        @else
            <div class="card overflow-hidden">
                <div class="divide-y divide-black/[0.06]">
                    @foreach($freelances as $freelance)
                        <div class="px-6 py-5 flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <p class="font-medium">{{ $freelance->full_name }}</p>
                                    <span class="inline-flex items-center text-xs px-2 py-0.5 rounded
                                        {{ $freelance->is_available ? 'bg-green-50 text-green-700' : 'bg-[#e8e7e2] text-[#888780]' }}">
                                        {{ $freelance->is_available ? 'Disponible' : 'Indisponible' }}
                                    </span>
                                </div>
                                <p class="text-sm text-[#888780]">{{ $freelance->email }}</p>

                                @if(!empty($freelance->specialities))
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach((array) $freelance->specialities as $spec)
                                            <span class="label-mono bg-[#e8e7e2] px-2 py-0.5 rounded text-[10px]">{{ $spec }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('admin.freelances.toggle-availability', $freelance->id) }}" class="shrink-0">
                                @csrf
                                <button type="submit" class="{{ $freelance->is_available ? 'btn-secondary' : 'btn-primary' }} text-xs px-3 py-1.5">
                                    {{ $freelance->is_available ? 'Marquer indisponible' : 'Marquer disponible' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
