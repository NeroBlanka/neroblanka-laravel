<x-app-layout>
    <x-slot name="title">Freelances — Admin Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="label-mono mb-2">Administration</p>
                <h1 class="text-3xl" style="color: var(--carbone)">Freelances</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-ghost text-sm">← Dashboard</a>
        </div>

        @if($freelances->isEmpty())
            <div class="card px-6 py-14 text-center text-sm" style="color: var(--gris-mid)">
                Aucun freelance enregistré.
            </div>
        @else
            <div class="card overflow-hidden">
                @foreach($freelances as $freelance)
                    <div class="px-6 py-5 flex items-center justify-between gap-4"
                         style="{{ !$loop->last ? 'border-bottom: 1px solid rgba(5,5,5,0.07)' : '' }}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <p class="font-medium" style="color: var(--carbone)">{{ $freelance->full_name }}</p>
                                <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-lg font-mono uppercase tracking-wider"
                                      style="{{ $freelance->is_available
                                        ? 'color:#6ee7b7; background:rgba(52,211,153,0.08); border:1px solid rgba(52,211,153,0.3)'
                                        : 'color:var(--gris-mid); background:rgba(122,120,117,0.06); border:1px solid rgba(122,120,117,0.2)' }}">
                                    {{ $freelance->is_available ? 'Disponible' : 'Indisponible' }}
                                </span>
                            </div>
                            <p class="text-sm" style="color: var(--gris)">{{ $freelance->email }}</p>

                            @if(!empty($freelance->specialties))
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    @foreach((array) $freelance->specialties as $spec)
                                        <span class="label-mono px-2 py-0.5 rounded-md"
                                              style="background: rgba(124,92,252,0.08); border: 1px solid rgba(124,92,252,0.2); color: var(--gris)">
                                            {{ $spec }}
                                        </span>
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
        @endif
    </div>
</x-app-layout>
