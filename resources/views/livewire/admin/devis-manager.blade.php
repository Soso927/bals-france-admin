<div>

    {{-- Message succès --}}
    @if (session('success'))
        <div class="mb-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="{{ $devisDetail ? 'grid gap-6 lg:grid-cols-[minmax(0,1fr)_420px]' : '' }}">

        {{-- ════════════════════════════════════════
             PANNEAU PRINCIPAL : Filtres + Tableau
             ════════════════════════════════════════ --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">

            {{-- Barre de filtres --}}
            <div class="flex flex-wrap gap-3 mb-6">
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="Rechercher (société, email, affaire…)"
                       class="flex-1 min-w-[200px] rounded-2xl border border-stone-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-400">

                <select wire:model.live="filtreType"
                        class="rounded-2xl border border-stone-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-400">
                    <option value="">Tous les types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filtreStatut"
                        class="rounded-2xl border border-stone-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-400">
                    <option value="">Tous les statuts</option>
                    <option value="nouveau">Nouveau</option>
                    <option value="lu">Lu</option>
                    <option value="traité">Traité</option>
                </select>
            </div>

            {{-- Tableau --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <thead>
                        <tr class="text-left text-stone-500">
                            <th class="pb-3 pr-4 font-medium">Date</th>
                            <th class="pb-3 pr-4 font-medium">Type</th>
                            <th class="pb-3 pr-4 font-medium">Société</th>
                            <th class="pb-3 pr-4 font-medium">Email</th>
                            <th class="pb-3 pr-4 font-medium">Statut</th>
                            <th class="pb-3 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($devis as $d)
                            @php
                                $badgeClass = match($d->statut) {
                                    'lu'     => 'bg-stone-100 text-stone-600',
                                    'traité' => 'bg-emerald-100 text-emerald-700',
                                    default  => 'bg-orange-100 text-orange-700',
                                };
                            @endphp
                            <tr class="{{ $devisSelectionne === $d->id ? 'bg-stone-50' : '' }} hover:bg-stone-50 transition-colors">
                                <td class="py-3 pr-4 text-stone-500 whitespace-nowrap">
                                    {{ $d->created_at->format('d/m/Y') }}<br>
                                    <span class="text-xs">{{ $d->created_at->format('H:i') }}</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="rounded-xl bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-700">
                                        {{ $d->type_coffret }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4">
                                    <div class="font-medium text-stone-900">{{ $d->distributeur ?: '—' }}</div>
                                    @if ($d->contact)
                                        <div class="text-xs text-stone-400">{{ $d->contact }}</div>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-stone-600">{{ $d->email ?: '—' }}</td>
                                <td class="py-3 pr-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ ucfirst($d->statut) }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="voirDetail({{ $d->id }})"
                                                type="button"
                                                class="rounded-2xl border border-stone-300 px-3 py-1.5 text-xs font-medium hover:bg-stone-50 transition-colors">
                                            Voir
                                        </button>
                                        @if ($d->reference)
                                        <a href="{{ route('admin.devis.pdf', $d) }}"
                                           target="_blank"
                                           class="rounded-2xl border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 transition-colors">
                                            PDF
                                        </a>
                                        @endif
                                        <button wire:click="supprimer({{ $d->id }})"
                                                wire:confirm="Supprimer ce devis ?"
                                                type="button"
                                                class="rounded-2xl border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 transition-colors">
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-stone-400">
                                    Aucune demande de devis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($devis->hasPages())
                <div class="mt-6">
                    {{ $devis->links() }}
                </div>
            @endif

        </div>

        {{-- ════════════════════════════════════════
             PANNEAU DÉTAIL (conditionnel)
             ════════════════════════════════════════ --}}
        @if ($devisDetail)
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">

            {{-- Header panel --}}
            <div class="flex items-start justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-stone-900">Devis #{{ $devisDetail->id }}</h2>
                    @if ($devisDetail->reference)
                        <p class="text-xs font-mono text-stone-500 mt-0.5">{{ $devisDetail->reference }}</p>
                    @endif
                    <p class="text-xs text-stone-400 mt-0.5">{{ $devisDetail->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    @if ($devisDetail->reference)
                    <a href="{{ route('admin.devis.pdf', $devisDetail) }}"
                       target="_blank"
                       class="flex-shrink-0 inline-flex items-center gap-1 rounded-2xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF
                    </a>
                    @endif
                    <button wire:click="fermerDetail" type="button"
                            class="flex-shrink-0 rounded-2xl border border-stone-300 px-3 py-1.5 text-sm hover:bg-stone-50 transition-colors">
                        Fermer
                    </button>
                </div>
            </div>

            {{-- Type coffret --}}
            <div class="mb-4">
                <span class="rounded-xl bg-stone-900 px-3 py-1.5 text-xs font-bold text-white">
                    {{ $devisDetail->type_coffret }}
                </span>
            </div>

            {{-- Changement de statut --}}
            <div class="mb-5">
                <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">Statut</p>
                <div class="flex gap-2">
                    @foreach(['nouveau' => 'orange', 'lu' => 'stone', 'traité' => 'emerald'] as $s => $color)
                        <button wire:click="changerStatut({{ $devisDetail->id }}, '{{ $s }}')"
                                type="button"
                                class="rounded-2xl px-3 py-1.5 text-xs font-semibold border transition-colors
                                       {{ $devisDetail->statut === $s
                                           ? 'bg-stone-900 text-white border-stone-900'
                                           : 'border-stone-300 text-stone-600 hover:bg-stone-50' }}">
                            {{ ucfirst($s) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <hr class="border-stone-100 mb-5">

            {{-- Informations contact --}}
            <div class="mb-5 space-y-2">
                <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">Contact</p>
                @foreach([
                    'Société'      => $devisDetail->distributeur,
                    'Contact'      => $devisDetail->contact,
                    'Installateur' => $devisDetail->installateur,
                    'Affaire'      => $devisDetail->affaire,
                    'Email'        => $devisDetail->email,
                    'Téléphone'    => $devisDetail->telephone,
                ] as $label => $valeur)
                    @if ($valeur)
                    <div class="flex gap-2 text-sm">
                        <dt class="w-28 flex-shrink-0 text-stone-400">{{ $label }}</dt>
                        <dd class="font-medium text-stone-800 break-all">{{ $valeur }}</dd>
                    </div>
                    @endif
                @endforeach
            </div>

            {{-- Données techniques (JSON) --}}
            @if ($devisDetail->donnees)
            <hr class="border-stone-100 mb-5">
            <div class="mb-5">
                <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">Données techniques</p>
                <dl class="space-y-1.5">
                    @foreach ($devisDetail->donnees as $cle => $valeur)
                        @if ($valeur !== '' && $valeur !== null && $valeur !== [])
                        <div class="flex gap-2 text-sm">
                            <dt class="w-28 flex-shrink-0 text-stone-400 capitalize">{{ str_replace('_', ' ', $cle) }}</dt>
                            <dd class="font-medium text-stone-800">
                                @if (is_array($valeur))
                                    @if (count($valeur) > 0)
                                        @foreach ($valeur as $item)
                                            @if (is_array($item))
                                                <span class="block text-xs">{{ implode(' / ', array_map(fn($v, $k) => "$k: $v", $item, array_keys($item))) }}</span>
                                            @else
                                                <span class="block">{{ $item }}</span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-stone-300">—</span>
                                    @endif
                                @else
                                    {{ $valeur }}
                                @endif
                            </dd>
                        </div>
                        @endif
                    @endforeach
                </dl>
            </div>
            @endif

            {{-- Observations --}}
            @if ($devisDetail->observations)
            <hr class="border-stone-100 mb-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">Observations</p>
                <p class="text-sm text-stone-700 leading-relaxed whitespace-pre-wrap">{{ $devisDetail->observations }}</p>
            </div>
            @endif

        </div>
        @endif

    </div>
</div>