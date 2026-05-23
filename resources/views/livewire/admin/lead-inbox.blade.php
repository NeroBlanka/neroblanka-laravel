<div>
    {{-- Filtres --}}
    <div class="flex flex-wrap items-center gap-3 mb-6">

        <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" wire:model.live="onlyHot" class="accent-green-500">
            <span class="text-green-600 font-medium">🔥 Hot leads (≥ 80)</span>
        </label>

        <select wire:model.live="filterStatus"
            class="text-sm border border-black/20 rounded px-3 py-1.5 bg-white">
            <option value="">Tous les statuts</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterService"
            class="text-sm border border-black/20 rounded px-3 py-1.5 bg-white">
            <option value="">Tous les services</option>
            @foreach($services as $service)
                <option value="{{ $service->value }}">{{ $service->label() }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterBudget"
            class="text-sm border border-black/20 rounded px-3 py-1.5 bg-white">
            <option value="">Tous les budgets</option>
            @foreach(['< 500$', '500$ – 2 000$', '2 000$ – 10 000$', '10 000$ – 30 000$', '> 30 000$'] as $b)
                <option value="{{ $b }}">{{ $b }}</option>
            @endforeach
        </select>

        @if($filterStatus || $filterService || $filterBudget || $onlyHot)
            <button wire:click="$set('filterStatus', ''); $set('filterService', ''); $set('filterBudget', ''); $set('onlyHot', false)"
                class="text-xs text-red-500 hover:text-red-700 underline">Réinitialiser</button>
        @endif

    </div>

    {{-- Table leads --}}
    <div class="overflow-x-auto rounded border border-black/10">
        <table class="w-full text-sm">
            <thead class="bg-[#0a0a0a] text-white text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Nom · Entreprise</th>
                    <th class="px-4 py-3 text-left font-medium">Service</th>
                    <th class="px-4 py-3 text-left font-medium">Budget</th>
                    <th class="px-4 py-3 text-left font-medium">Délai</th>
                    <th class="px-4 py-3 text-center font-medium">Score</th>
                    <th class="px-4 py-3 text-left font-medium">Statut</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/[0.06]">
                @forelse($leads as $lead)
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium text-[#0a0a0a]">{{ $lead->full_name }}</div>
                            @if($lead->company)
                                <div class="text-xs text-gray-400">{{ $lead->company }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $lead->service_type->label() }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $lead->budget_range }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $lead->deadline_range }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded text-white
                                {{ $lead->isHot() ? 'bg-green-600' : ($lead->isWarm() ? 'bg-yellow-500' : 'bg-red-500') }}">
                                {{ $lead->score }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <select wire:change="changeStatus('{{ $lead->id }}', $event.target.value)"
                                class="text-xs border border-black/20 rounded px-2 py-1 bg-white">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" @selected($lead->status === $status)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                            {{ $lead->created_at->format('d/m/y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.leads.show', $lead) }}"
                                class="text-xs text-[#0a0a0a] underline hover:no-underline">
                                Voir →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            Aucun lead correspondant aux filtres.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $leads->links() }}
    </div>

</div>
