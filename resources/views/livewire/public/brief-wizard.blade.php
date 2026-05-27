<div class="min-h-screen pt-24 pb-16 px-4" style="background: var(--bg)">

    {{-- Barre de progression --}}
    <div class="fixed top-16 left-0 right-0 z-40 h-px" style="background: rgba(255,255,255,0.08)"
         role="progressbar" aria-valuenow="{{ $this->getProgress() }}" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full transition-all duration-500"
             style="width: {{ $this->getProgress() }}%; background: linear-gradient(90deg, var(--purple), var(--pink))"></div>
    </div>

    <div class="max-w-xl mx-auto">

        {{-- Header étape --}}
        <div class="mb-10">
            <p class="label-mono mb-3">Étape {{ $step }} / {{ $totalSteps }}</p>
            <h1 class="text-2xl font-semibold" style="color: var(--perle)">
                @switch($step)
                    @case(1) Quel est votre besoin ? @break
                    @case(2) Qui êtes-vous ? @break
                    @case(3) Budget & calendrier @break
                    @case(4) Votre projet @break
                    @case(5) Questions spécifiques @break
                    @case(6) Fichiers de référence @break
                    @case(7) Confirmation @break
                @endswitch
            </h1>
        </div>

        <form wire:submit="advance">

            {{-- STEP 1 — Service --}}
            @if($step === 1)
                <div class="grid grid-cols-1 gap-3">
                    @foreach($services as $service)
                        <label class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all"
                               style="{{ $service_type === $service->value
                                   ? 'background: rgba(124,92,252,0.1); border: 1px solid rgba(124,92,252,0.4)'
                                   : 'background: var(--glass); border: 1px solid rgba(255,255,255,0.08)' }}
                               backdrop-filter: blur(16px);">
                            <input type="radio" wire:model="service_type" value="{{ $service->value }}" class="sr-only">
                            <span class="text-xl w-8 text-center" aria-hidden="true">{{ $service->icon() }}</span>
                            <span class="text-sm" style="color: var(--perle)">{{ $service->label() }}</span>
                        </label>
                    @endforeach
                </div>
                @error('service_type')
                    <p class="mt-3 text-sm" style="color: #f87171">{{ $message }}</p>
                @enderror

            {{-- STEP 2 — Contact --}}
            @elseif($step === 2)
                <div class="space-y-5">
                    <div>
                        <label class="block label-mono mb-2">Nom complet *</label>
                        <input wire:model="full_name" type="text" autocomplete="name"
                            class="input-base w-full" placeholder="Sarah Amrani">
                        @error('full_name') <p class="mt-1.5 text-xs" style="color: #f87171">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block label-mono mb-2">Email *</label>
                        <input wire:model="email" type="email" autocomplete="email"
                            class="input-base w-full" placeholder="sarah@startup.dz">
                        @error('email') <p class="mt-1.5 text-xs" style="color: #f87171">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block label-mono mb-2">Téléphone</label>
                            <input wire:model="phone" type="tel" autocomplete="tel"
                                class="input-base w-full" placeholder="+213 5xx xx xx xx">
                        </div>
                        <div>
                            <label class="block label-mono mb-2">Entreprise</label>
                            <input wire:model="company" type="text" autocomplete="organization"
                                class="input-base w-full" placeholder="Ma Startup SAS">
                        </div>
                    </div>
                    <div>
                        <label class="block label-mono mb-2">Type de structure</label>
                        <select wire:model="client_type" class="select-dark w-full">
                            <option value="">Choisir...</option>
                            <option value="startup">Startup / Scaleup</option>
                            <option value="pme">PME / ETI</option>
                            <option value="event">Organisateur d'événements</option>
                            <option value="export">Marque export</option>
                            <option value="diaspora">Diaspora FR / CA / UAE</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                </div>

            {{-- STEP 3 — Budget & Deadline --}}
            @elseif($step === 3)
                <div class="space-y-8">
                    <div>
                        <label class="block label-mono mb-4">Budget estimé *</label>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach(['< 500$', '500$ – 2 000$', '2 000$ – 10 000$', '10 000$ – 30 000$', '> 30 000$'] as $range)
                                <label class="flex items-center gap-4 p-3.5 rounded-xl cursor-pointer transition-all"
                                       style="{{ $budget_range === $range
                                           ? 'background: rgba(124,92,252,0.1); border: 1px solid rgba(124,92,252,0.4)'
                                           : 'background: var(--glass); border: 1px solid rgba(255,255,255,0.08)' }}">
                                    <input type="radio" wire:model="budget_range" value="{{ $range }}" class="sr-only">
                                    <span class="text-sm" style="color: var(--perle)">{{ $range }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('budget_range') <p class="mt-2 text-xs" style="color: #f87171">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block label-mono mb-4">Délai idéal *</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['< 2 semaines', '2 – 4 semaines', '1 – 3 mois', '> 3 mois'] as $range)
                                <label class="flex items-center gap-3 p-3.5 rounded-xl cursor-pointer transition-all"
                                       style="{{ $deadline_range === $range
                                           ? 'background: rgba(124,92,252,0.1); border: 1px solid rgba(124,92,252,0.4)'
                                           : 'background: var(--glass); border: 1px solid rgba(255,255,255,0.08)' }}">
                                    <input type="radio" wire:model="deadline_range" value="{{ $range }}" class="sr-only">
                                    <span class="text-sm" style="color: var(--perle)">{{ $range }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('deadline_range') <p class="mt-2 text-xs" style="color: #f87171">{{ $message }}</p> @enderror
                    </div>
                </div>

            {{-- STEP 4 — Description projet --}}
            @elseif($step === 4)
                <div class="space-y-5">
                    <div>
                        <label class="block label-mono mb-2">Décrivez votre projet *</label>
                        <textarea wire:model="project_description" rows="5"
                            class="input-base w-full resize-none"
                            placeholder="Décrivez votre projet, vos objectifs, le contexte..."></textarea>
                        @error('project_description') <p class="mt-1.5 text-xs" style="color: #f87171">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block label-mono mb-2">Inspirations / références</label>
                        <textarea wire:model="inspirations" rows="3"
                            class="input-base w-full resize-none"
                            placeholder="Marques, sites, visuels que vous aimez..."></textarea>
                    </div>
                    <div>
                        <label class="block label-mono mb-2">Concurrents à connaître</label>
                        <input wire:model="competitors" type="text"
                            class="input-base w-full"
                            placeholder="Noms ou URLs de vos concurrents principaux">
                    </div>
                </div>

            {{-- STEP 5 — Questions spécifiques --}}
            @elseif($step === 5)
                <div class="space-y-5">
                    @foreach($currentServiceQuestions as $question)
                        <div>
                            <label class="block label-mono mb-2">{{ $question['label'] }}</label>
                            @if($question['type'] === 'textarea')
                                <textarea wire:model="service_answers.{{ $question['key'] }}" rows="3"
                                    class="input-base w-full resize-none"></textarea>
                            @else
                                <input wire:model="service_answers.{{ $question['key'] }}" type="text"
                                    class="input-base w-full">
                            @endif
                        </div>
                    @endforeach
                </div>

            {{-- STEP 6 — Fichiers --}}
            @elseif($step === 6)
                <div>
                    <p class="text-sm mb-6" style="color: var(--gris)">Formats acceptés: PDF, PNG, JPEG — 10 Mo max par fichier, 5 fichiers max.</p>
                    <label class="flex flex-col items-center justify-center w-full h-40 rounded-xl cursor-pointer transition-all"
                        style="border: 1px dashed rgba(255,255,255,0.15); background: var(--glass)"
                        x-data="{ dragging: false }"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false"
                        :style="dragging ? 'border-color: rgba(124,92,252,0.5); background: rgba(124,92,252,0.05)' : ''">
                        <input type="file" wire:model="uploaded_files" multiple accept=".pdf,.png,.jpg,.jpeg" class="sr-only">
                        <span class="text-2xl mb-2" style="color: var(--gris)">↑</span>
                        <span class="text-sm" style="color: var(--gris)">
                            Glissez vos fichiers ici ou <span style="color: var(--perle); text-decoration: underline">parcourez</span>
                        </span>
                    </label>
                    @error('uploaded_files.*')
                        <p class="mt-3 text-sm" style="color: #f87171">{{ $message }}</p>
                    @enderror
                    @if(count($uploaded_files) > 0)
                        <ul class="mt-4 space-y-2">
                            @foreach($uploaded_files as $file)
                                <li class="flex items-center gap-3 text-sm" style="color: var(--gris)">
                                    <span style="color: #6ee7b7">✓</span>
                                    {{ is_object($file) ? $file->getClientOriginalName() : $file }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            {{-- STEP 7 — Confirmation --}}
            @elseif($step === 7)
                <div class="space-y-8">
                    <div class="card p-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span style="color: var(--gris)">Service</span>
                            <span style="color: var(--perle)">{{ collect(\App\Enums\ServiceType::cases())->firstWhere('value', $service_type)?->label() }}</span>
                        </div>
                        <div class="flex justify-between" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px">
                            <span style="color: var(--gris)">Contact</span>
                            <span style="color: var(--perle)">{{ $full_name }} · {{ $email }}</span>
                        </div>
                        <div class="flex justify-between" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px">
                            <span style="color: var(--gris)">Budget</span>
                            <span style="color: var(--perle)">{{ $budget_range }}</span>
                        </div>
                        <div class="flex justify-between" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px">
                            <span style="color: var(--gris)">Délai</span>
                            <span style="color: var(--perle)">{{ $deadline_range }}</span>
                        </div>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input wire:model="terms_accepted" type="checkbox"
                               class="mt-0.5 w-4 h-4" style="accent-color: var(--purple)">
                        <span class="text-sm" style="color: var(--gris)">
                            Je confirme que les informations fournies sont exactes et j'accepte que Neroblanka me contacte pour étudier mon projet.
                        </span>
                    </label>
                    @error('terms_accepted') <p class="text-xs" style="color: #f87171">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Navigation --}}
            <div class="flex items-center justify-between mt-10">
                @if($step > 1)
                    <button type="button" wire:click="prevStep"
                        class="text-sm transition-opacity hover:opacity-60" style="color: var(--gris)">
                        ← Retour
                    </button>
                @else
                    <div></div>
                @endif

                <button type="submit" class="btn-primary disabled:opacity-40"
                        wire:loading.attr="disabled" wire:target="advance,prevStep">
                    <span wire:loading.remove wire:target="advance">
                        {{ $step === $totalSteps ? 'Envoyer mon diagnostic' : 'Continuer →' }}
                    </span>
                    <span wire:loading wire:target="advance">Envoi en cours...</span>
                </button>
            </div>

        </form>
    </div>
</div>
