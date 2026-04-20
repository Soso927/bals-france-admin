{{--
    ============================================================
    VUE : Configurateur Coffret d'Étage
    ============================================================
    Formulaire de devis pour les coffrets de distribution d'étage
    (immeubles, bureaux). 4 sections au lieu de 5 car il n'y a pas
    de tableau de prises — les sorties sont choisies par cases à cocher.
    Le JavaScript est dans etage.js (extrait de cette vue).
    ============================================================
--}}
@extends('layouts.admin')

@section('title', "Configurateur Coffret d'Étage — BALS")

@section('content')
<div>

    @include('configurateur.partials.header')
    @include('configurateur.partials.nav-type', ['activeType' => 'etage'])
    @include('configurateur.partials.progress-bar')

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{-- Section 1 : Identification --}}
            <x-configurateur.section id="s1" number="1" title="Information de contact">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Société / Distributeur</span>
                        <input id="societe" type="text" oninput="mettreAJour()" placeholder="Ex : IMMO PARIS SUD"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</span>
                        <input id="contact" type="text" oninput="mettreAJour()" placeholder="Ex : Marie DUPONT"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Installateur</span>
                        <input id="installateur" type="text" oninput="mettreAJour()" placeholder="Ex : ELEC HABITAT"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Affaire / Référence</span>
                        <input id="affaire" type="text" oninput="mettreAJour()" placeholder="Ex : Résidence Les Pins"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email de contact</span>
                        <input id="email" type="email" oninput="mettreAJour()" placeholder="contact@societe.fr"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                </div>
            </x-configurateur.section>

            {{-- Section 2 : Configuration du coffret d'étage --}}
            <x-configurateur.section id="s2" number="2" title="Caractéristique technique général">
                <div class="space-y-5">
                    <x-configurateur.radio-group
                        label="Type de montage"
                        name="montage"
                        :options="['Encastré', 'En saillie', 'Encastré-saillie']"
                    />
                    <x-configurateur.radio-group
                        label="Matériau"
                        name="materiau"
                        :options="['Plastique ABS', 'Métal peint', 'Acier inoxydable']"
                    />
                    <x-configurateur.radio-group
                        label="Indice de protection (IP)"
                        name="ip"
                        :options="['IP40', 'IP43', 'IP44']"
                        :bold="true"
                    />
                </div>
            </x-configurateur.section>

            {{-- Section 3 : Circuits & Sorties --}}
            <x-configurateur.section id="s3" number="3" title="Caractéristiques techniques des prises">
                <div class="space-y-4">
                    <x-configurateur.checkbox-group
                        label="Types de sorties souhaités"
                        name="prot_tete"
                        :options="['Prises 2P+T 10/16A', 'Prises 3P+N+T 32A', 'Interrupteurs', 'Variateurs', 'RJ45 / Téléphone', 'USB']"
                    />
                    <x-configurateur.checkbox-group
                        label="Protections souhaitées"
                        name="prot_prises"
                        :options="['Différentiel 30mA', 'Disjoncteur par circuit', 'Coupe-circuit général', 'Parafoudre']"
                    />
                </div>
            </x-configurateur.section>


            {{-- Section 4 : Protections électriques --}}
            <x-configurateur.section id="s4" number="4" title="Protections">
                <div class="space-y-4">
                    <x-configurateur.checkbox-group
                        label="Protection en tête"
                        name="prot_tete"
                        :options="['Différentiel 30mA', 'Différentiel 300mA', 'Disjoncteur général', 'Parafoudre', 'Sans']"
                    />
                    <x-configurateur.checkbox-group
                        label="Protection par prise"
                        name="prot_prises"
                        :options="['Disjoncteur 16A', 'Disjoncteur 32A', 'Fusible 16A', 'Fusible 32A', 'Sans']"
                    />
                </div>
            </x-configurateur.section>

            {{-- Section 4 : Observations (badge gris = section optionnelle) --}}
            <x-configurateur.section id="s4" number="5" title="Observations" :accent="false">
                <textarea id="observations" oninput="mettreAJour()" rows="4"
                          placeholder="Nombre d'étages, particularités du bâtiment, contraintes techniques..."
                          class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue resize-none"></textarea>
                <p class="text-xs text-gray-400 mt-1 text-right"><span id="nb-caracteres">0</span> caractères</p>
            </x-configurateur.section>

        </div>

        @include('configurateur.partials.panneau-resume')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('configurateur/js/etage.js') }}"></script>
@endsection
