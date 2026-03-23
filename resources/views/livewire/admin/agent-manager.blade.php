<section class="grid gap-6 lg:grid-cols-[380px_minmax(0,1fr)]">
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">{{ $agentId ? 'Modifier un agent' : 'Nouvel agent' }}</h2>
                <p class="mt-1 text-sm text-stone-600">Creation et mise a jour en Livewire.</p>
            </div>

            @if ($agentId)
                <button wire:click="resetForm" type="button" class="rounded-2xl border border-stone-300 px-4 py-2 text-sm">
                    Annuler
                </button>
            @endif
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="save" class="mt-6 space-y-4">
            <label class="block">
                <span class="mb-1 block text-sm font-medium">Nom</span>
                <input wire:model.defer="name" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                @error('name') <span class="mt-1 block text-sm text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium">Email</span>
                <input wire:model.defer="email" type="email" class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                @error('email') <span class="mt-1 block text-sm text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium">Telephone</span>
                <input wire:model.defer="phone" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                @error('phone') <span class="mt-1 block text-sm text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium">Ville</span>
                <input wire:model.defer="city" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                @error('city') <span class="mt-1 block text-sm text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium">Region</span>
                <select wire:model.defer="region_id" class="w-full rounded-2xl border border-stone-300 px-4 py-3">
                    <option value="">Selectionner une region</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
                @error('region_id') <span class="mt-1 block text-sm text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-2 text-sm text-stone-700">
                <input wire:model.defer="is_active" type="checkbox" class="rounded border-stone-300">
                <span>Agent actif</span>
            </label>

            <button type="submit" class="w-full rounded-2xl bg-stone-900 px-4 py-3 font-medium text-white">
                {{ $agentId ? 'Mettre a jour' : 'Enregistrer' }}
            </button>
        </form>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Agents</h2>
                <p class="mt-1 text-sm text-stone-600">Liste des agents rattaches aux regions.</p>
            </div>
            <span class="rounded-full bg-stone-100 px-3 py-1 text-sm text-stone-600">{{ $agents->count() }} total</span>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead>
                    <tr class="text-left text-stone-500">
                        <th class="pb-3 pr-4 font-medium">Nom</th>
                        <th class="pb-3 pr-4 font-medium">Region</th>
                        <th class="pb-3 pr-4 font-medium">Ville</th>
                        <th class="pb-3 pr-4 font-medium">Statut</th>
                        <th class="pb-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($agents as $agent)
                        <tr>
                            <td class="py-4 pr-4">
                                <div class="font-medium">{{ $agent->name }}</div>
                                <div class="text-stone-500">{{ $agent->email ?: 'Sans email' }}</div>
                            </td>
                            <td class="py-4 pr-4">{{ $agent->region?->name ?: 'Non assignee' }}</td>
                            <td class="py-4 pr-4">{{ $agent->city ?: '-' }}</td>
                            <td class="py-4 pr-4">
                                <span class="rounded-full px-3 py-1 text-xs {{ $agent->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                                    {{ $agent->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $agent->id }})" type="button" class="rounded-2xl border border-stone-300 px-3 py-2">Modifier</button>
                                    <button wire:click="delete({{ $agent->id }})" wire:confirm="Supprimer cet agent ?" type="button" class="rounded-2xl border border-red-200 px-3 py-2 text-red-700">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-stone-500">Aucun agent pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>