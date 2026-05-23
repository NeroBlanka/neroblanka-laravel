<x-app-layout>
    <x-slot:title>Lead — {{ $lead->full_name }}</x-slot:title>

    <div class="max-w-4xl mx-auto px-6 py-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-start justify-between mb-8">
            <div>
                <a href="{{ route('admin.leads') }}" class="text-sm text-gray-400 hover:text-gray-700 mb-3 inline-block">← Lead Inbox</a>
                <h1 class="text-2xl font-semibold text-[#0a0a0a]" style="font-family: 'Clash Grotesk', sans-serif;">
                    {{ $lead->full_name }}
                </h1>
                @if($lead->company)
                    <p class="text-gray-500 text-sm mt-1">{{ $lead->company }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-block px-3 py-1.5 text-sm font-semibold rounded text-white
                    {{ $lead->isHot() ? 'bg-green-600' : ($lead->isWarm() ? 'bg-yellow-500' : 'bg-red-500') }}">
                    Score {{ $lead->score }}/100
                </span>

                @if($lead->status === \App\Enums\LeadStatus::QUALIFIED && ! $lead->project)
                    <form method="POST" action="{{ route('admin.leads.convert', $lead) }}"
                          onsubmit="return confirm('Convertir ce lead en projet client ?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-[#0a0a0a] text-white text-sm font-medium rounded-sm hover:bg-[#333] transition-colors"
                                style="font-family: 'Clash Grotesk', sans-serif;">
                            → Convertir en projet
                        </button>
                    </form>
                @elseif($lead->project)
                    <a href="{{ route('admin.projects.show', $lead->project) }}"
                       class="px-4 py-2 border border-black/20 text-[#0a0a0a] text-sm font-medium rounded-sm hover:border-black/50 transition-colors">
                        Voir le projet →
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="col-span-2 space-y-6">

                {{-- Analyse IA --}}
                @if($lead->ai_summary)
                    <div class="border border-black/10 rounded p-5 bg-[#fafaf9]">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="font-semibold text-sm uppercase tracking-wider text-gray-400">Analyse IA</h2>
                            <span class="text-xs text-gray-300">{{ $lead->ai_analyzed_at?->format('d/m à H:i') }}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $lead->ai_summary }}</p>
                        @if($lead->ai_score_adjustment !== 0)
                            <p class="text-xs mt-2 {{ $lead->ai_score_adjustment > 0 ? 'text-green-600' : 'text-red-500' }}">
                                Ajustement score IA : {{ $lead->ai_score_adjustment > 0 ? '+' : '' }}{{ $lead->ai_score_adjustment }} pts
                            </p>
                        @endif
                    </div>
                @elseif(! $lead->ai_analyzed_at)
                    <div class="border border-dashed border-black/10 rounded p-4 text-xs text-gray-400 text-center">
                        Analyse IA en attente de traitement (queue:ai)
                    </div>
                @endif

                {{-- Brief --}}
                @if($lead->brief)
                    <div class="border border-black/10 rounded p-5">
                        <h2 class="font-semibold text-sm uppercase tracking-wider text-gray-400 mb-4">Brief</h2>
                        @foreach($lead->brief->answers as $key => $value)
                            @if($value)
                                <div class="mb-3">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">{{ str_replace('_', ' ', $key) }}</p>
                                    <p class="text-sm text-gray-800">{{ $value }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Fichiers --}}
                @if($lead->files->count())
                    <div class="border border-black/10 rounded p-5">
                        <h2 class="font-semibold text-sm uppercase tracking-wider text-gray-400 mb-4">Fichiers ({{ $lead->files->count() }})</h2>
                        <ul class="space-y-2">
                            @foreach($lead->files as $file)
                                <li>
                                    <a href="{{ $file->temporaryUrl(30) }}" target="_blank" rel="noopener"
                                        class="text-sm text-blue-600 hover:text-blue-800 underline">
                                        {{ $file->original_name }}
                                    </a>
                                    <span class="text-xs text-gray-400 ml-2">
                                        {{ number_format($file->size_bytes / 1024, 0) }} Ko
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Audit trail --}}
                @if($lead->events->count())
                    <div class="border border-black/10 rounded p-5">
                        <h2 class="font-semibold text-sm uppercase tracking-wider text-gray-400 mb-4">Historique</h2>
                        <ul class="space-y-3">
                            @foreach($lead->events as $event)
                                <li class="flex items-start gap-3 text-sm">
                                    <span class="text-gray-300 text-xs mt-0.5 whitespace-nowrap">{{ $event->created_at->format('d/m H:i') }}</span>
                                    <div>
                                        <span class="text-gray-700">{{ $event->type }}</span>
                                        @if($event->note)
                                            <p class="text-gray-500 mt-0.5">{{ $event->note }}</p>
                                        @endif
                                        @if($event->user)
                                            <span class="text-xs text-gray-300">par {{ $event->user->full_name }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>

            {{-- Sidebar infos --}}
            <div class="space-y-4">
                <div class="border border-black/10 rounded p-4">
                    <h2 class="font-semibold text-xs uppercase tracking-wider text-gray-400 mb-3">Informations</h2>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-400 text-xs">Email</dt><dd>{{ $lead->email }}</dd></div>
                        @if($lead->phone)
                            <div><dt class="text-gray-400 text-xs">Téléphone</dt><dd>{{ $lead->phone }}</dd></div>
                        @endif
                        <div><dt class="text-gray-400 text-xs">Service</dt><dd>{{ $lead->service_type->label() }}</dd></div>
                        <div><dt class="text-gray-400 text-xs">Budget</dt><dd>{{ $lead->budget_range }}</dd></div>
                        <div><dt class="text-gray-400 text-xs">Délai</dt><dd>{{ $lead->deadline_range }}</dd></div>
                        <div><dt class="text-gray-400 text-xs">Statut</dt><dd>{{ $lead->status->label() }}</dd></div>
                        <div><dt class="text-gray-400 text-xs">Reçu</dt><dd>{{ $lead->created_at->format('d/m/Y à H:i') }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
