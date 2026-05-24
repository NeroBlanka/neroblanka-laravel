<div class="min-h-screen bg-[#0a0a0a] pt-24 pb-16 px-4">

    {{-- Barre de progression --}}
    <div class="fixed top-16 left-0 right-0 z-40 h-px bg-white/10" role="progressbar" aria-valuenow="{{ $this->getProgress() }}" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full bg-white transition-all duration-500" style="width: {{ $this->getProgress() }}%"></div>
    </div>

    <div class="max-w-xl mx-auto">

        {{-- Header étape --}}
        <div class="mb-10">
            <p class="label-mono mb-3">Étape {{ $step }} / {{ $totalSteps }}</p>
            <h1 class="text-2xl font-semibold text-white">
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

        <form wire:submit.prevent="{{ $step === $totalSteps ? 'submit' : 'nextStep' }}">

            {{-- STEP 1 — Service --}}
            @if($step === 1)
                <div class="grid grid-cols-1 gap-3">
                    @foreach($services as $service)
                        <label class="flex items-center gap-4 p-4 border rounded-sm cursor-pointer transition-all
                            {{ $service_type === $service->value ? 'border-white bg-white/[0.06]' : 'border-white/[0.12] hover:border-white/30' }}">
                            <input type="radio" wire:model="service_type" value="{{ $service->value }}" class="sr-only">
                            <span class="text-xl w-8 text-center" aria-hidden="true">{{ $service->icon() }}</span>
                            <span class="text-sm text-[#e8e7e2]">{{ $service->label() }}</span>
                        </label>
                    @endforeach
                </div>
                @error('service_type')
                    <p class="mt-3 text-red-400 text-sm">{{ $message }}</p>
                @enderror

            {{-- STEP 2 — Contact --}}
            @elseif($step === 2)
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Nom complet *</label>
                        <input wire:model="full_name" type="text" autocomplete="name"
                            class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20"
                            placeholder="Sarah Amrani">
                        @error('full_name') <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Email *</label>
                        <input wire:model="email" type="email" autocomplete="email"
                            class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20"
                            placeholder="sarah@startup.dz">
                        @error('email') <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Téléphone</label>
                            <input wire:model="phone" type="tel" autocomplete="tel"
                                class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20"
                                placeholder="+213 5xx xx xx xx">
                        </div>
                        <div>
                            <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Entreprise</label>
                            <input wire:model="company" type="text" autocomplete="organization"
                                class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20"
                                placeholder="Ma Startup SAS">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Type de structure</label>
                        <select wire:model="client_type"
                            class="w-full bg-[#0a0a0a] border border-white/20 rounded-sm px-4 py-3 text-sm text-[#e8e7e2] focus:outline-none focus:border-white/60 transition-colors">
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
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-4">Budget estimé *</label>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach(['< 500$', '500$ – 2 000$', '2 000$ – 10 000$', '10 000$ – 30 000$', '> 30 000$'] as $range)
                                <label class="flex items-center gap-4 p-3.5 border rounded-sm cursor-pointer transition-all
                                    {{ $budget_range === $range ? 'border-white bg-white/[0.06]' : 'border-white/[0.12] hover:border-white/30' }}">
                                    <input type="radio" wire:model="budget_range" value="{{ $range }}" class="sr-only">
                                    <span class="text-sm text-[#e8e7e2]">{{ $range }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('budget_range') <p class="mt-2 text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-4">Délai idéal *</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['< 2 semaines', '2 – 4 semaines', '1 – 3 mois', '> 3 mois'] as $range)
                                <label class="flex items-center gap-3 p-3.5 border rounded-sm cursor-pointer transition-all
                                    {{ $deadline_range === $range ? 'border-white bg-white/[0.06]' : 'border-white/[0.12] hover:border-white/30' }}">
                                    <input type="radio" wire:model="deadline_range" value="{{ $range }}" class="sr-only">
                                    <span class="text-sm text-[#e8e7e2]">{{ $range }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('deadline_range') <p class="mt-2 text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

            {{-- STEP 4 — Description projet --}}
            @elseif($step === 4)
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Décrivez votre projet *</label>
                        <textarea wire:model="project_description" rows="5"
                            class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20 resize-none"
                            placeholder="Décrivez votre projet, vos objectifs, le contexte..."></textarea>
                        @error('project_description') <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Inspirations / références</label>
                        <textarea wire:model="inspirations" rows="3"
                            class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20 resize-none"
                            placeholder="Marques, sites, visuels que vous aimez..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">Concurrents à connaître</label>
                        <input wire:model="competitors" type="text"
                            class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20"
                            placeholder="Noms ou URLs de vos concurrents principaux">
                    </div>
                </div>

            {{-- STEP 5 — Questions spécifiques au service --}}
            @elseif($step === 5)
                <div class="space-y-5">
                    @foreach($currentServiceQuestions as $question)
                        <div>
                            <label class="block text-xs text-[#888780] uppercase tracking-widest mb-2">{{ $question['label'] }}</label>
                            @if($question['type'] === 'textarea')
                                <textarea wire:model="service_answers.{{ $question['key'] }}" rows="3"
                                    class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20 resize-none"></textarea>
                            @else
                                <input wire:model="service_answers.{{ $question['key'] }}" type="text"
                                    class="w-full bg-white/[0.04] border border-white/20 rounded-sm px-4 py-3 text-white text-sm focus:outline-none focus:border-white/60 transition-colors placeholder-white/20">
                            @endif
                        </div>
                    @endforeach
                </div>

            {{-- STEP 6 — Fichiers --}}
            @elseif($step === 6)
                <div>
                    <p class="text-sm text-[#888780] mb-6">Formats acceptés: PDF, PNG, JPEG — 10 Mo max par fichier, 5 fichiers max.</p>
                    <label class="flex flex-col items-center justify-center w-full h-40 border border-dashed border-white/20 rounded-sm cursor-pointer hover:border-white/40 transition-colors"
                        x-data="{ dragging: false }"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false">
                        <input type="file" wire:model="uploaded_files" multiple accept=".pdf,.png,.jpg,.jpeg" class="sr-only">
                        <span class="text-2xl mb-2 text-[#888780]">↑</span>
                        <span class="text-sm text-[#888780]">Glissez vos fichiers ici ou <span class="text-white underline">parcourez</span></span>
                    </label>
                    @error('uploaded_files.*')
                        <p class="mt-3 text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                    @if(count($uploaded_files) > 0)
                        <ul class="mt-4 space-y-2">
                            @foreach($uploaded_files as $file)
                                <li class="flex items-center gap-3 text-sm text-[#888780]">
                                    <span class="text-white">✓</span>
                                    {{ is_object($file) ? $file->getClientOriginalName() : $file }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            {{-- STEP 7 — Confirmation --}}
            @elseif($step === 7)
                <div class="space-y-8">
                    <div class="border border-white/[0.12] rounded-sm p-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#888780]">Service</span>
                            <span class="text-white">{{ collect(\App\Enums\ServiceType::cases())->firstWhere('value', $service_type)?->label() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#888780]">Contact</span>
                            <span class="text-white">{{ $full_name }} · {{ $email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#888780]">Budget</span>
                            <span class="text-white">{{ $budget_range }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#888780]">Délai</span>
                            <span class="text-white">{{ $deadline_range }}</span>
                        </div>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input wire:model="terms_accepted" type="checkbox" class="mt-0.5 w-4 h-4 accent-white">
                        <span class="text-sm text-[#888780]">
                            Je confirme que les informations fournies sont exactes et j'accepte que Neroblanka me contacte pour étudier mon projet.
                        </span>
                    </label>
                    @error('terms_accepted') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Navigation --}}
            <div class="flex items-center justify-between mt-10">
                @if($step > 1)
                    <button type="button" wire:click="prevStep"
                        class="text-sm text-[#888780] hover:text-white transition-colors">
                        ← Retour
                    </button>
                @else
                    <div></div>
                @endif

                <button type="submit"
                    class="btn-primary disabled:opacity-40"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        {{ $step === $totalSteps ? 'Envoyer mon diagnostic' : 'Continuer →' }}
                    </span>
                    <span wire:loading>Envoi en cours...</span>
                </button>
            </div>

        </form>
    </div>
</div>
