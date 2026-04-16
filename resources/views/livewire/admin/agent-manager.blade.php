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
                        @foreach ($allRegions as $region)
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

                {{-- format de numéro de téléphone international pour le lien tel: — propriété $telRaw --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Téléphone international</span>
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

                {{-- Teinte de couleur — wire:model synchronise la valeur avec $color dans AgentManager --}}
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Teinte de couleur</span>
                    <div class="flex items-center gap-3">
                        <input wire:model="color"
                               type="color"
                               class="h-10 w-14 cursor-pointer rounded-xl border border-stone-300 p-1">
                        <span class="text-sm text-stone-500 font-mono">{{ $color }}</span>
                    </div>
                    @error('color')
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
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Agents commerciaux</h2>
                <p class="mt-1 text-sm text-stone-600">
                    Liste des agents rattachés aux régions.
                </p>
            </div>
            <span class="rounded-full bg-stone-100 px-3 py-1 text-sm text-stone-600">
                {{-- total() retourne le COUNT(*) du paginateur — précis même avec un filtre actif --}}
                {{ $agents->total() }} agents
            </span>
        </div>

        {{-- Barre de recherche + sélecteur de résultats par page --}}
        <div class="mt-4 flex flex-wrap gap-3">
            {{-- debounce.300ms : attend 300ms après la dernière frappe avant d'envoyer la requête AJAX --}}
            <input wire:model.live.debounce.300ms="search"
                   type="search"
                   placeholder="Rechercher par nom, agence, email, département…"
                   class="flex-1 min-w-[200px] rounded-2xl border border-stone-300 px-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-stone-400">

            {{-- Pas de debounce sur le select : un clic = un choix, pas de frappes en rafale --}}
            <select wire:model.live="perPage"
                    class="rounded-2xl border border-stone-300 px-4 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-stone-400">
                <option value="5">5 par page</option>
                <option value="10">10 par page</option>
                <option value="25">25 par page</option>
                <option value="50">50 par page</option>
            </select>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead>
                    <tr class="text-left text-stone-500">
                        <th class="pb-2 pr-4 font-medium">Agent</th>
                        <th class="pb-2 pr-4 font-medium">Région</th>
                        <th class="pb-2 pr-4 font-medium">Départements</th>
                        <th class="pb-2 pr-4 font-medium">Contact</th>
                        <th class="pb-2 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($agents as $agent)
                        <tr>
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-2">
                                    {{-- Pastille colorée — style inline obligatoire : Tailwind JIT ne génère pas de classes hex dynamiques --}}
                                    <span class="inline-block h-3 w-3 flex-shrink-0 rounded-full"
                                          style="background-color: {{ $agent->color ?? '#94A3B8' }}"
                                          title="{{ $agent->color ?? '#94A3B8' }}"></span>
                                    <div>
                                        @if ($agent->agence)
                                            <div class="text-xs font-bold uppercase text-blue-600">
                                                {{ $agent->agence }}
                                            </div>
                                        @endif
                                        <div class="font-medium">{{ $agent->nom }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 pr-4 text-stone-500">
                                {{-- $agent->region chargé par with('region') dans render() — zéro requête supplémentaire --}}
                                {{ $agent->region->nom ?? '—' }}
                            </td>
                            <td class="py-3 pr-4 text-stone-500">
                                {{ $agent->departement }}
                            </td>
                            <td class="py-3 pr-4">
                                <div>{{ $agent->tel }}</div>
                                <div class="text-stone-500">{{ $agent->email }}</div>
                            </td>
                            <td class="py-3 text-right">
                                <div class="flex justify-end gap-2">
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
                            <td colspan="5" class="py-10 text-center text-stone-400">
                                Aucun agent trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Contrôles de pagination — masqués s'il n'y a qu'une seule page --}}
        @if ($agents->hasPages())
            <div class="mt-6">
                {{ $agents->links() }}
            </div>
        @endif

    </div>
</section>