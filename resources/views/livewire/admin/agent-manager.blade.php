<section class="grid gap-6 lg:grid-cols-[380px_minmax(0,1fr)]">

    {{-- ════════════════════════════════════════
         PANNEAU GAUCHE : Formulaire ajout/édition
         ════════════════════════════════════════ --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">
                    {{ $isEditing ? 'Modifier un agent' : 'Nouvel agent' }}
                </h2>
                <p class="mt-1 text-sm text-stone-600">
                    Création et mise à jour via Livewire → MySQL.
                </p>
            </div>

            @if ($showForm)
                {{-- openAddForm() réinitialise le formulaire et le cache --}}
                <button wire:click="openAddForm" type="button"
                        class="rounded-2xl border border-stone-300 px-4 py-2 text-sm">
                    Annuler
                </button>
            @endif
        </div>

        {{-- Message de succès après une action --}}
        @if (session('success'))
            <div class="mt-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($showForm)
            {{-- wire:submit appelle la méthode save() du composant --}}
            <form wire:submit="save" class="mt-6 space-y-4">

                {{-- Région — wire:model doit correspondre exactement
                     au nom de la propriété dans AgentManager.php --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Région *</span>
                    <select wire:model="selectedRegionId"
                            class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                        <option value="">Sélectionner une région</option>
                        @foreach ($regions as $region)
                            {{-- $region->nom car la colonne s'appelle 'nom' dans MySQL --}}
                            <option value="{{ $region->id }}">{{ $region->nom }}</option>
                        @endforeach
                    </select>
                    @error('selectedRegionId')
                        <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Agence (optionnelle) --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Agence</span>
                    <input wire:model="agence" type="text"
                           placeholder="Ex: AGENCE DUMAS"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                </label>

                {{-- Nom — propriété $nom dans AgentManager --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Nom complet *</span>
                    <input wire:model="nom" type="text"
                           placeholder="Ex: Vincent CARPENTIER"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                    @error('nom')
                        <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Départements — propriété $departement, colonne 'depts' --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Départements</span>
                    <input wire:model="departement" type="text"
                           placeholder="Ex: Dépt. 75, 78, 92"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                </label>

                {{-- Téléphone affiché — propriété $tel --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Téléphone</span>
                    <input wire:model="tel" type="text"
                           placeholder="Ex: 06 12 34 56 78"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                </label>

                {{-- Téléphone brut pour le lien tel: — propriété $telRaw --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Téléphone brut</span>
                    <input wire:model="telRaw" type="text"
                           placeholder="Ex: +33612345678"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                </label>

                {{-- Email — propriété $email --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Email *</span>
                    <input wire:model="email" type="email"
                           placeholder="agent@exemple.fr"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                    @error('email')
                        <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <button type="submit"
                        class="w-full rounded-2xl bg-stone-900 px-4 py-3 font-medium text-white">
                    {{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </form>
        @else
            {{-- Bouton pour ouvrir le formulaire d'ajout --}}
            <button wire:click="openAddForm" type="button"
                    class="mt-6 w-full rounded-2xl bg-stone-900 px-4 py-3 font-medium text-white">
                + Ajouter un agent
            </button>
        @endif
    </div>

    {{-- ════════════════════════════════════════
         PANNEAU DROIT : Liste des agents
         On boucle sur les régions, puis sur leurs agents
         ════════════════════════════════════════ --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Agents commerciaux</h2>
                <p class="mt-1 text-sm text-stone-600">
                    Liste des agents rattachés aux régions.
                </p>
            </div>
            <span class="rounded-full bg-stone-100 px-3 py-1 text-sm text-stone-600">
                {{-- On compte le total d'agents sur toutes les régions --}}
                {{ $regions->sum(fn($r) => $r->agents->count()) }} agents
            </span>
        </div>

        <div class="mt-6 space-y-6">
            @forelse ($regions as $region)
                {{-- Titre de la région --}}
                <div>
                    <h3 class="mb-2 text-sm font-semibold uppercase tracking-wider text-stone-400">
                        {{-- $region->nom correspond à la colonne 'nom' dans MySQL --}}
                        {{ $region->nom }}
                        <span class="ml-2 text-xs font-normal">{{ $region->zone }}</span>
                    </h3>

                    <table class="min-w-full divide-y divide-stone-200 text-sm">
                        <thead>
                            <tr class="text-left text-stone-500">
                                <th class="pb-2 pr-4 font-medium">Agent</th>
                                <th class="pb-2 pr-4 font-medium">Départements</th>
                                <th class="pb-2 pr-4 font-medium">Contact</th>
                                <th class="pb-2 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($region->agents as $agent)
                                <tr>
                                    <td class="py-3 pr-4">
                                        @if ($agent->agence)
                                            <div class="text-xs font-bold uppercase text-blue-600">
                                                {{ $agent->agence }}
                                            </div>
                                        @endif
                                        {{-- $agent->nom car la colonne s'appelle 'nom' --}}
                                        <div class="font-medium">{{ $agent->nom }}</div>
                                    </td>
                                    <td class="py-3 pr-4 text-stone-500">
                                        {{-- $agent->departements car la colonne s'appelle 'departements' --}}
                                        {{ $agent->departements }}
                                    </td>
                                    <td class="py-3 pr-4">
                                        <div>{{ $agent->tel }}</div>
                                        <div class="text-stone-500">{{ $agent->email }}</div>
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            {{-- wire:click doit correspondre exactement
                                                 aux noms des méthodes dans AgentManager.php --}}
                                            <button wire:click="editAgent({{ $agent->id }})"
                                                    type="button"
                                                    class="rounded-2xl border border-stone-300 px-3 py-1.5 text-sm">
                                                Modifier
                                            </button>
                                            <button wire:click="deleteAgent({{ $agent->id }})"
                                                    wire:confirm="Supprimer cet agent ?"
                                                    type="button"
                                                    class="rounded-2xl border border-red-200 px-3 py-1.5 text-sm text-red-700">
                                                Supprimer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-stone-400">
                                        Aucun agent pour cette région.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @empty
                <p class="py-10 text-center text-stone-500">Aucune région en base de données.</p>
            @endforelse
        </div>
    </div>
</section>