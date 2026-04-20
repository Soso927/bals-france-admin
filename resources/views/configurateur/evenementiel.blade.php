{{--
    ============================================================
    VUE : Configurateur Coffret Événementiel
    ============================================================
    Formulaire de devis pour les coffrets de distribution
    événementiels (festivals, spectacles, tournages).
    Structure identique à etage.blade.php mais avec des options
    adaptées au contexte événementiel (mobilité, puissance).
    Le JavaScript est dans evenementiel.js (extrait de cette vue).
    ============================================================
--}}
@extends('layouts.admin')

@section('title', 'Configurateur Coffret Événementiel — BALS')

@section('content')
<div>

    @include('configurateur.partials.header')
    @include('configurateur.partials.nav-type', ['activeType' => 'evenementiel'])
    @include('configurateur.partials.progress-bar')

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{-- Section 1 : Informations de contact --}}
            <x-configurateur.section id="s1" number="1" title="Informations de contact">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Société / Distributeur</span>
                        <input id="societe" type="text" oninput="mettreAJour()" placeholder="Ex : EVENT PRO LYON"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</span>
                        <input id="contact" type="text" oninput="mettreAJour()" placeholder="Ex : Paul BERNARD"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Installateur</span>
                        <input id="installateur" type="text" oninput="mettreAJour()" placeholder="Ex : SCENE ELEC"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Affaire / Référence</span>
                        <input id="affaire" type="text" oninput="mettreAJour()" placeholder="Ex : Festival Vieilles Charrues"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email de contact</span>
                        <input id="email" type="email" oninput="mettreAJour()" placeholder="contact@societe.fr"
                               class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                    </label>
                </div>
            </x-configurateur.section>

            {{-- Section 2 : Configuration événementielle --}}
            <x-configurateur.section id="s2" number="2" title="Caractéristique technique général">
                <div class="space-y-5">
                    <x-configurateur.radio-group
                        label="Type de montage"
                        name="montage"
                        :options="['Mobile (roues)', 'Mobile (poignée)', 'Fixe temporaire', 'Suspendu']"
                    />
                    <x-configurateur.radio-group
                        label="Matériau"
                        name="materiau"
                        :options="['Caoutchouc', 'Plastique ABS', 'Aluminium']"
                    />
                    <x-configurateur.radio-group
                        label="Indice de protection (IP)"
                        name="ip"
                        :options="['IP44', 'IP54', 'IP55', 'IP65']"
                        :bold="true"
                    />
                </div>
            </x-configurateur.section>

            {{-- Section 3 : Circuits & Prises --}}
            <x-configurateur.section id="s3" number="3" title="Caractéristiques techniques des prises">
                <div class="space-y-4">
                    <x-configurateur.checkbox-group
                        label="Types de sorties"
                        name="prot_tete"
                        :options="['2P+T 16A ', '3P+N+T 16A (CEE)', '3P+N+T 32A (CEE)', '3P+N+T 63A (CEE)', '3P+N+T 125A (CEE)']"
                    />
                    <x-configurateur.checkbox-group
                        label="Protections"
                        name="prot_prises"
                        :options="['Différentiel 30mA', 'Différentiel 300mA', 'Disjoncteur général', 'Parafoudre', 'Sectionneur']"
                    />
                </div>
            </x-configurateur.section>

            {{-- Section 4 : Observations (badge gris = section optionnelle) --}}
            <x-configurateur.section id="s4" number="4" title="Observations" :accent="false">
                <textarea id="observations" oninput="mettreAJour()" rows="4"
                          placeholder="Type d'événement, puissance totale nécessaire, contraintes de lieu..."
                          class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue resize-none"></textarea>
                <p class="text-xs text-gray-400 mt-1 text-right"><span id="nb-caracteres">0</span> caractères</p>
            </x-configurateur.section>

        </div>

        @include('configurateur.partials.panneau-resume')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('configurateur/js/evenementiel.js') }}"></script>
@endsection
