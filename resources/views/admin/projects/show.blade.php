<x-app-layout>
    <x-slot name="title">{{ $project->title }} — Admin Neroblanka</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-10">

        {{-- Back --}}
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-[#888780] hover:text-[#0a0a0a] transition mb-8">
            &larr; Retour au dashboard
        </a>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Project header --}}
        @php $statusValue = is_string($project->status) ? $project->status : $project->status?->value; @endphp
        <div class="flex items-start justify-between gap-4 mb-8">
            <div>
                <p class="label-mono mb-2">{{ is_string($project->service_type) ? $project->service_type : ($project->service_type?->label() ?? 'Projet') }}</p>
                <h1 class="text-3xl mb-3">{{ $project->title }}</h1>
                <x-status-badge :status="$statusValue" />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main details --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="text-base font-semibold mb-4">Description</h2>
                    <p class="text-[#555350] leading-relaxed">{{ $project->description }}</p>
                </div>

                @if($project->notes)
                    <div class="card p-6">
                        <h2 class="text-base font-semibold mb-4">Notes</h2>
                        <p class="text-[#555350] leading-relaxed">{{ $project->notes }}</p>
                    </div>
                @endif

                {{-- Deliverables --}}
                <div class="card overflow-hidden">
                    <div class="px-6 py-4 border-b border-black/[0.08]">
                        <h2 class="text-base font-semibold">Livrables</h2>
                    </div>

                    @if($deliverables->isEmpty())
                        <div class="px-6 py-8 text-sm text-[#888780]">Aucun livrable soumis.</div>
                    @else
                        <div class="divide-y divide-black/[0.06]">
                            @foreach($deliverables as $deliverable)
                                <div class="px-6 py-4 flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium">Version {{ $deliverable->version ?? $loop->iteration }}</p>
                                        <p class="text-xs text-[#888780] mt-0.5">
                                            {{ $deliverable->created_at->format('d/m/Y à H:i') }}
                                            @if($deliverable->message)
                                                — {{ $deliverable->message }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($deliverable->file_url)
                                            <a href="{{ $deliverable->file_url }}" target="_blank"
                                               class="btn-ghost text-xs px-3 py-1.5">
                                                Télécharger
                                            </a>
                                        @endif

                                        @if($statusValue === 'submitted')
                                            <form method="POST" action="{{ route('admin.deliverables.approve', $deliverable->id) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary text-xs px-3 py-1.5">Approuver</button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.deliverables.revision', $deliverable->id) }}">
                                                @csrf
                                                <input type="hidden" name="revision_notes" value="Révision demandée par l'admin">
                                                <button type="submit" class="btn-secondary text-xs px-3 py-1.5">Révision</button>
                                            </form>
                                        @else
                                            <x-status-badge :status="is_string($deliverable->status) ? $deliverable->status : ($deliverable->status?->value ?? $statusValue)" />
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                <div class="card p-6 space-y-4">
                    <h2 class="text-base font-semibold">Informations</h2>

                    <div>
                        <p class="label-mono mb-1">Client</p>
                        <p class="text-sm text-[#555350]">{{ $project->client->full_name ?? '—' }}</p>
                        @if($project->client->company ?? null)
                            <p class="text-xs text-[#888780]">{{ $project->client->company }}</p>
                        @endif
                    </div>

                    @if($project->deadline)
                        <div>
                            <p class="label-mono mb-1">Deadline</p>
                            <p class="text-sm text-[#555350]">{{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="label-mono mb-1">Créé le</p>
                        <p class="text-sm text-[#555350]">{{ $project->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                {{-- Assignment actif --}}
                @if($assignment)
                    <div class="card p-6 space-y-3">
                        <h2 class="text-base font-semibold">Freelance assigné</h2>
                        <p class="text-sm text-[#555350]">{{ $assignment->freelance->full_name ?? '—' }}</p>
                        <x-status-badge :status="is_string($assignment->status) ? $assignment->status : ($assignment->status?->value ?? 'active')" />
                    </div>
                @endif

                {{-- Assigner un freelance --}}
                @if($availableFreelances->isNotEmpty())
                    <div class="card p-6">
                        <h2 class="text-base font-semibold mb-4">{{ $assignment ? 'Réassigner' : 'Assigner un freelance' }}</h2>
                        <form method="POST" action="{{ route('admin.assignments.store') }}">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="space-y-3">
                                <select name="freelance_id" required
                                        class="w-full text-sm border border-black/10 rounded px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-black/20">
                                    <option value="">Choisir un freelance…</option>
                                    @foreach($availableFreelances as $freelance)
                                        <option value="{{ $freelance->id }}">{{ $freelance->full_name }}</option>
                                    @endforeach
                                </select>
                                <textarea name="internal_notes" rows="2"
                                          class="w-full text-sm border border-black/10 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-black/20 resize-none"
                                          placeholder="Notes internes (optionnel)"></textarea>
                                <button type="submit"
                                        class="w-full px-4 py-2 bg-[#0a0a0a] text-white text-sm font-medium rounded-sm hover:bg-[#333] transition-colors">
                                    Assigner
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
