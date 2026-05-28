<x-app-layout>
    <x-slot name="title">Services — Neroblanka Admin</x-slot>

    <div class="max-w-5xl mx-auto px-6 py-14">

        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="label-mono mb-3">Admin · Catalogue</p>
                <h1 class="text-4xl" style="color: var(--carbone)">Services</h1>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn-primary px-5 py-3">
                + Nouveau service
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 px-5 py-3 rounded-xl text-sm" style="background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.2); color: #34d399">
                {{ session('success') }}
            </div>
        @endif

        @if($services->isEmpty())
            <div class="card p-12 text-center">
                <p class="text-sm" style="color: var(--gris-mid)">Aucun service configuré.</p>
                <a href="{{ route('admin.services.create') }}" class="btn-primary inline-block mt-4 px-5 py-2.5 text-sm">Créer le premier</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($services as $service)
                    <div class="card px-6 py-5 flex items-center justify-between gap-6">
                        <div class="flex items-center gap-5 min-w-0">
                            <div class="w-2 h-2 rounded-full shrink-0" style="background: {{ $service->is_active ? '#34d399' : 'rgba(5,5,5,0.15)' }}"></div>
                            <div class="min-w-0">
                                <p class="font-medium truncate" style="color: var(--carbone)">{{ $service->name }}</p>
                                @if($service->type)
                                    <p class="text-xs mt-0.5" style="color: var(--gris-mid)">
                                        {{ \App\Enums\ServiceType::tryFrom($service->type)?->label() ?? $service->type }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-6 shrink-0">
                            <div class="text-right">
                                <p class="text-sm font-medium" style="color: var(--carbone)">{{ number_format($service->price_da) }} DA</p>
                                <p class="text-xs" style="color: var(--gris-mid)">{{ $service->delivery_days }}j</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.services.toggle-active', $service) }}">
                                    @csrf
                                    <button type="submit" class="btn-ghost text-xs px-3 py-1.5">
                                        {{ $service->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn-ghost text-xs px-3 py-1.5">Modifier</a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                      onsubmit="return confirm('Supprimer « {{ $service->name }} » ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs px-3 py-1.5 rounded-lg transition-colors"
                                            style="color: var(--status-danger); background: rgba(248,113,113,0.08)">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
