{{--
    ============================================================
    VUE : Configurateur Coffret Industrie
    ============================================================
    Formulaire de devis pour les coffrets électriques industriels.
    Utilise les mêmes partials et composants que chantier.blade.php,
    avec des options adaptées au contexte industriel (IP plus élevés,
    prises lourdes, matériaux robustes).
    Note : partage chantier.js (qui lit le type actif depuis la nav).
    ============================================================
--}}
@extends('layouts.admin')

@section('title', 'Configurateur Coffret Industrie — BALS')

@section('content')
<div>

    @include('configurateur.partials.header')
    @include('configurateur.partials.nav-type', ['activeType' => 'industrie'])
    @include('configurateur.partials.progress-bar')

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{-- Section 1 : Identification --}}
            <x-configurateur.section id="s1" number="1" title="Identification">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Société / Distributeur</span>
                        <input id="societe" type="text" oninput="mettreAJour()" placeholder="Ex : ELECTRO DIST SUD"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</span>
                        <input id="contact" type="text" oninput="mettreAJour()" placeholder="Ex : Jean MARTIN"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Installateur</span>
                        <input id="installateur" type="text" oninput="mettreAJour()" placeholder="Ex : ELEC INDUSTRIE PRO"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Affaire / Référence</span>
                        <input id="affaire" type="text" oninput="mettreAJour()" placeholder="Ex : Usine Renault 2025"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email de contact</span>
                        <input id="email" type="email" oninput="mettreAJour()" placeholder="contact@societe.fr"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                </div>
            </x-configurateur.section>

            {{-- Section 2 : Configuration (options industrielles) --}}
            <x-configurateur.section id="s2" number="2" title="Configuration">
                <div class="space-y-5">
                    <x-configurateur.radio-group
                        label="Type de montage"
                        name="montage"
                        :options="['Fixe au sol', 'Fixe au mur', 'Sur châssis', 'Suspendu']"
                    />
                    <x-configurateur.radio-group
                        label="Matériau du coffret"
                        name="materiau"
                        :options="['Polyester', 'Acier inoxydable', 'Aluminium', 'Acier galvanisé']"
                    />
                    <x-configurateur.radio-group
                        label="Indice de protection (IP)"
                        name="ip"
                        :options="['IP55', 'IP65', 'IP66', 'IP67', 'IP68']"
                        :bold="true"
                    />
                </div>
            </x-configurateur.section>

            {{-- Section 3 : Prises industrielles lourdes --}}
            <x-configurateur.section id="s3" number="3" title="Prises industrielles">
                @php
                $prises = [
                    ['label' => '3P+N+T 16A (CEE)',   'type' => '3P+N+T 16A',  'brochage' => 'CEE'],
                    ['label' => '3P+N+T 32A (CEE)',   'type' => '3P+N+T 32A',  'brochage' => 'CEE'],
                    ['label' => '3P+N+T 63A (CEE)',   'type' => '3P+N+T 63A',  'brochage' => 'CEE'],
                    ['label' => '3P+N+T 125A (CEE)',  'type' => '3P+N+T 125A', 'brochage' => 'CEE'],
                    ['label' => '3P+N+T 200A (CEE)',  'type' => '3P+N+T 200A', 'brochage' => 'CEE'],
                    ['label' => '2P+T 16A (Schuko)',  'type' => '2P+T 16A',    'brochage' => 'Schuko'],
                ];
                @endphp
                @foreach($prises as $prise)
                <div class="flex items-center justify-between gap-4 border border-gray-100 rounded-xl px-4 py-3">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">{{ $prise['label'] }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select data-type="{{ $prise['type'] }}" data-brochage="{{ $prise['brochage'] }}" data-field="tension"
                                onchange="mettreAJour()"
                                class="rounded-lg border border-gray-200 text-xs px-2 py-1.5 text-gray-600">
                            <option value="">Tension</option>
                            <option value="230V">230V</option>
                            <option value="400V">400V</option>
                            <option value="690V">690V</option>
                        </select>
                        <div class="flex items-center gap-1">
                            <button onclick="changerQte(this, -1)" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-gray-600 flex items-center justify-center transition-colors">−</button>
                            <span data-type="{{ $prise['type'] }}" data-brochage="{{ $prise['brochage'] }}"
                                  class="w-8 text-center font-black text-sm text-gray-800">0</span>
                            <button onclick="changerQte(this, 1)" class="w-7 h-7 rounded-lg bg-bals-blue hover:opacity-90 font-bold text-white flex items-center justify-center transition-colors">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </x-configurateur.section>

            {{-- Section 4 : Protections industrielles --}}
            <x-configurateur.section id="s4" number="4" title="Protections">
                <div class="space-y-4">
                    <x-configurateur.checkbox-group
                        label="Protection en tête"
                        name="prot_tete"
                        :options="['Différentiel 300mA', 'Disjoncteur général', 'Sectionneur', 'Parafoudre', 'Sans']"
                    />
                    <x-configurateur.checkbox-group
                        label="Protection par prise"
                        name="prot_prises"
                        :options="['Disjoncteur 16A', 'Disjoncteur 32A', 'Disjoncteur 63A', 'Fusible', 'Sans']"
                    />
                </div>
            </x-configurateur.section>

            {{-- Section 5 : Observations (optionnelle) --}}
            <x-configurateur.section id="s5" number="5" title="Observations" :accent="false">
                <textarea id="observations" oninput="mettreAJour()" rows="4"
                          placeholder="Précisions complémentaires, contraintes particulières..."
                          class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue resize-none"></textarea>
                <p class="text-xs text-gray-400 mt-1 text-right"><span id="nb-caracteres">0</span> caractères</p>
            </x-configurateur.section>

        </div>

        @include('configurateur.partials.panneau-resume')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('configurateur/js/chantier.js') }}"></script>
@endsection
