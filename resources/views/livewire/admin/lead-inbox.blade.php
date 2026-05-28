<div>
    {{-- Filtres --}}
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <label class="flex items-center gap-2 text-sm cursor-pointer" style="color: #6ee7b7">
            <input type="checkbox" wire:model.live="onlyHot" style="accent-color: #6ee7b7">
            <span class="font-medium">Hot leads (≥ 80)</span>
        </label>

        <select wire:model.live="filterStatus" class="select-base text-xs" style="width:auto">
            <option value="">Tous les statuts</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterService" class="select-base text-xs" style="width:auto">
            <option value="">Tous les services</option>
            @foreach($services as $service)
                <option value="{{ $service->value }}">{{ $service->label() }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterBudget" class="select-base text-xs" style="width:auto">
            <option value="">Tous les budgets</option>
            @foreach(['< 500$', '500$ – 2 000$', '2 000$ – 10 000$', '10 000$ – 30 000$', '> 30 000$'] as $b)
                <option value="{{ $b }}">{{ $b }}</option>
            @endforeach
        </select>

        @if($filterStatus || $filterService || $filterBudget || $onlyHot)
            <button wire:click="$set('filterStatus', ''); $set('filterService', ''); $set('filterBudget', ''); $set('onlyHot', false)"
                class="text-xs transition-opacity hover:opacity-60" style="color: var(--status-danger)">
                Réinitialiser
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                        <th class="text-left px-5 py-3 label-mono">Nom · Entreprise</th>
                        <th class="text-left px-5 py-3 label-mono">Service</th>
                        <th class="text-left px-5 py-3 label-mono">Budget</th>
                        <th class="text-left px-5 py-3 label-mono">Délai</th>
                        <th class="text-center px-5 py-3 label-mono">Score</th>
                        <th class="text-left px-5 py-3 label-mono">Statut</th>
                        <th class="text-left px-5 py-3 label-mono">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr class="transition-colors hover:bg-white/[0.025]"
                            style="border-bottom: 1px solid rgba(5,5,5,0.07)">
                            <td class="px-5 py-3.5">
                                <div class="font-medium" style="color: var(--carbone)">{{ $lead->full_name }}</div>
                                @if($lead->company)
                                    <div class="text-xs mt-0.5" style="color: var(--gris-mid)">{{ $lead->company }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5" style="color: var(--gris)">{{ $lead->service_type->label() }}</td>
                            <td class="px-5 py-3.5 whitespace-nowrap" style="color: var(--gris)">{{ $lead->budget_range }}</td>
                            <td class="px-5 py-3.5 whitespace-nowrap" style="color: var(--gris)">{{ $lead->deadline_range }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-lg text-white"
                                      style="background: {{ $lead->isHot() ? 'rgba(52,211,153,0.3)' : ($lead->isWarm() ? 'rgba(251,191,36,0.3)' : 'rgba(248,113,113,0.3)') }};
                                             color: {{ $lead->isHot() ? '#6ee7b7' : ($lead->isWarm() ? '#fbbf24' : '#fca5a5') }};
                                             border: 1px solid {{ $lead->isHot() ? 'rgba(52,211,153,0.4)' : ($lead->isWarm() ? 'rgba(251,191,36,0.4)' : 'rgba(248,113,113,0.4)') }}">
                                    {{ $lead->score }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <select wire:change="changeStatus('{{ $lead->id }}', $event.target.value)"
                                    class="select-base text-xs" style="width:auto">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->value }}" @selected($lead->status === $status)>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-5 py-3.5 text-xs whitespace-nowrap" style="color: var(--gris-mid)">
                                {{ $lead->created_at->format('d/m/y H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.leads.show', $lead) }}"
                                    class="text-xs transition-opacity hover:opacity-60" style="color: var(--gris)">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center text-sm" style="color: var(--gris-mid)">
                                Aucun lead correspondant aux filtres.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $leads->links() }}
    </div>
</div>
