{{-- ============================================================ --}}
{{-- FICHIER : resources/views/configurateur/etage.blade.php      --}}
{{-- PRODUIT : COFFRET D'ÉTAGE BALS                                --}}
{{-- RÉFÉRENCE : 510_802 (configuration par défaut)                --}}
{{-- VERSION : CORRIGÉE (4 bugs résolus)                           --}}
{{-- ============================================================ --}}
{{--
    ✅ SPÉCIFICITÉS COFFRET D'ÉTAGE (selon PDF) :

    📦 TYPE DE MONTAGE :
       - Mobile (par défaut) ⭐
       - Mobile sur pied

    🏗️ MATÉRIAUX :
       - Plastique uniquement (léger et adapté aux étages)

    🔌 CONFIGURATION DES PRISES PAR DÉFAUT :
       - 6x NF 10/16A (230V, 2P+T) - prises domestiques
       - 1x CEI 16A (400V, 3P+N+T) - prise industrielle
       - 1x CEI 32A (400V, 3P+N+T) - prise industrielle

    🛡️ PROTECTION DE TÊTE (par défaut) :
       - Inter différentiel ✓
       - Disjoncteur ✓

    🔒 PROTECTION DES PRISES (par défaut) :
       - Disjoncteur ✓

    📋 CHAMPS DE CONTACT :
       - Distributeur
       - Contact Distributeur
       - Installateur
       - Contact Installateur
       - Référence Affaire
       - Téléphone
       - Email

    🐛 CORRECTIONS APPLIQUÉES :
       - [BUG 1] Bouton actif : classes bleues déplacées sur "Coffret d'Étage"
                 (elles étaient par erreur sur "Coffret Événementiel")
       - [BUG 2] Checkboxes pré-cochées : styles statiques remplacés par
                 peer-checked: pour que reinitialiser() fonctionne visuellement
       - [BUG 3] Spans des quantités : ajout d'attributs data-type/data-brochage
                 précis pour un ciblage fiable dans reinitialiser()
       - [BUG 4] Résumé incomplet : ajout de la lecture et l'affichage
                 des prises sélectionnées dans mettreAJour()
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

        {{-- ====================================================== --}}
        {{-- 📋 SECTION 01 : INFORMATIONS DE CONTACT                --}}
        {{-- ====================================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                 onclick="toggleSection('s1')">
                <div class="flex items-center gap-3">
                    <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">01</span>
                    <span class="font-bold text-lg">Informations de Contact</span>
                </div>
                <span id="arrow-s1" class="text-white text-lg transition-transform duration-300">▲</span>
            </div>

            <div id="section-s1" class="p-6 flex flex-col gap-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Distributeur</label>
                    <input type="text" id="distributeur" placeholder="Nom du distributeur"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Contact Distributeur</label>
                    <input type="text" id="contact_distributeur" placeholder="Nom du contact"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Installateur</label>
                    <input type="text" id="installateur" placeholder="Nom de l'installateur"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Contact Installateur</label>
                    <input type="text" id="contact_installateur" placeholder="Nom du contact"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Référence Affaire</label>
                    <input type="text" id="affaire" placeholder="Référence de l'affaire"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" id="telephone" placeholder="+33 1 23 45 67 89"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" placeholder="contact@exemple.fr"
                           oninput="mettreAJour()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- ⚙️ SECTION 02 : CARACTÉRISTIQUES TECHNIQUES GÉNÉRALES --}}
        {{-- ====================================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                 onclick="toggleSection('s2')">
                <div class="flex items-center gap-3">
                    <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">02</span>
                    <span class="font-bold text-lg">Caractéristiques Techniques générales</span>
                </div>
                <span id="arrow-s2" class="text-white text-lg transition-transform duration-300">▲</span>
            </div>

            <div id="section-s2" class="p-6 flex flex-col gap-7">

                {{-- Type de montage --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Type de coffret <span class="text-red-500">*</span>
                    </label>
                    <div class="bg-blue-50 border-l-4 border-bals-blue p-3 mb-3 rounded">
                        <p class="text-xs text-blue-800">
                            <strong>Configuration Coffret d'Étage :</strong>
                            Mobile avec boîtier Plastique - Conçu pour une installation facile et rapide dans les étages.
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-3" id="type-montage">
                        <label class="cursor-pointer">
                            <input type="radio" name="montage" value="Mobile" class="sr-only peer" onchange="mettreAJour()" checked>
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                                <span class="font-bold text-sm text-gray-700">Mobile</span>
                                <span class="block text-xs text-bals-blue mt-1">Recommandé</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="montage" value="Mobile sur pied" class="sr-only peer" onchange="mettreAJour()">
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                                <span class="font-bold text-sm text-gray-700">Mobile sur pied</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Matériaux --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Matériaux <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="materiau" value="Plastique" class="sr-only peer" onchange="mettreAJour()" checked>
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 cursor-pointer">
                                <span class="font-bold text-gray-700">Plastique</span>
                                <span class="block text-xs text-bals-blue mt-1">
                                    Matériau standard pour coffrets d'étage - Léger et résistant
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Indice de Protection --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Indice de Protection (IP) <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="ip" value="IP44" class="sr-only peer" onchange="mettreAJour()" checked>
                            <div class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                                <span class="font-black text-bals-blue">IP44</span>
                                <span class="text-gray-400 text-xs ml-1 block mt-1">Projections d'eau</span>
                                <span class="text-xs text-bals-blue block mt-1">Recommandé</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="ip" value="IP54" class="sr-only peer" onchange="mettreAJour()">
                            <div class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                                <span class="font-black text-bals-blue">IP54</span>
                                <span class="text-gray-400 text-xs ml-1 block mt-1">Poussières + projections</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="ip" value="IP67" class="sr-only peer" onchange="mettreAJour()">
                            <div class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                                <span class="font-black text-bals-blue">IP67</span>
                                <span class="text-gray-400 text-xs ml-1 block mt-1">Immersion temporaire</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- SECTION 03 : CARACTÉRISTIQUES TECHNIQUES DES PRISES    --}}
        {{-- ====================================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                 onclick="toggleSection('s3')">
                <div class="flex items-center gap-3">
                    <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">03</span>
                    <span class="font-bold text-lg">Caractéristiques Techniques des prises</span>
                </div>
                <span id="arrow-s3" class="text-white text-lg transition-transform duration-300">▼</span>
            </div>

            <div id="section-s3" class="hidden p-6">
                <div class="flex flex-col gap-6">

                {{-- ── CARTE NF ── --}}
                <div class="rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                        <span class="font-black text-gray-800 text-lg">Prises domestiques NF</span>
                    </div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-bals-blue text-white">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase border-r border-white/20">Brochage</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold border-r border-white/20">Quantité</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold">Tension</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white">
                                <td class="px-5 py-4 font-bold text-gray-400 text-sm border-r border-gray-100 w-28">10/16A</td>
                                <td class="px-5 py-4 border-r border-gray-100">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="changerQte(this, -1)"
                                            class="w-8 h-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-bold">−</button>
                                        {{-- ✅ BUG 3 CORRIGÉ : data-type et data-brochage pour ciblage précis --}}
                                        <span class="w-10 text-center font-bold text-gray-800 text-sm"
                                              data-type="NF"
                                              data-brochage="10-16A">0</span>
                                        <button type="button" onclick="changerQte(this, 1)"
                                            class="w-8 h-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-bold">+</button>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-bals-blue"
                                        data-type="NF"
                                        data-brochage="10-16A"
                                        data-field="tension"
                                        onchange="mettreAJour()">
                                        <option value="">--</option>
                                        <option value="230V">230V</option>
                                        <option value="400V">400V</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ── CARTES CEI (16A, 32A, 63A, 125A) ── --}}
                @foreach(['Prises domestiques CEI 16A', 'Prises domestiques CEI 32A', 'Prises domestiques CEI 63A', 'Prises domestiques CEI 125A'] as $cei)
                <div class="rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                        <span class="font-black text-bals-blue text-lg">{{ $cei }}</span>
                    </div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-bals-blue text-white">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase border-r border-white/20">Brochage</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold border-r border-white/20">Quantité</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold">Tension</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['2P+T', '3P+T', '3P+N+T'] as $brochage)
                            <tr class="{{ !$loop->last ? 'border-b border-gray-100' : '' }} {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="px-5 py-4 font-black text-bals-blue text-sm border-r border-gray-100 w-28">
                                    {{ $brochage }}
                                </td>
                                <td class="px-5 py-4 border-r border-gray-100">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="changerQte(this, -1)"
                                            class="w-8 h-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-bold">−</button>
                                        {{-- ✅ BUG 3 CORRIGÉ : identifiants uniques par type et brochage --}}
                                        <span class="w-10 text-center font-bold text-gray-800 text-sm"
                                              data-type="{{ $cei }}"
                                              data-brochage="{{ $brochage }}">0</span>
                                        <button type="button" onclick="changerQte(this, 1)"
                                            class="w-8 h-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-bold">+</button>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-bals-blue"
                                        data-type="{{ $cei }}"
                                        data-brochage="{{ $brochage }}"
                                        data-field="tension"
                                        onchange="mettreAJour()">
                                        <option value="">--</option>
                                        <option value="230V">230V</option>
                                        <option value="400V">400V</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach

                {{-- ── CARTE CEI 24A ── --}}
                <div class="rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                        <span class="font-black text-bals-blue text-lg">Prise domestiques CEI 24A</span>
                    </div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-bals-blue text-white">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase border-r border-white/20">Brochage</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold border-r border-white/20">Quantité</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold">Tension</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['2P', '3P'] as $brochage)
                            <tr class="{{ !$loop->last ? 'border-b border-gray-100' : '' }} {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="px-5 py-4 font-black text-bals-blue text-sm border-r border-gray-100 w-28">
                                    {{ $brochage }}
                                </td>
                                <td class="px-5 py-4 border-r border-gray-100">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="changerQte(this, -1)"
                                            class="w-8 h-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-bold">−</button>
                                        <span class="w-10 text-center font-bold text-gray-800 text-sm"
                                            data-type="CEI 24A"
                                            data-brochage="{{ $brochage }}">0</span>
                                        <button type="button" onclick="changerQte(this, 1)"
                                            class="w-8 h-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-bold">+</button>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-bals-blue"
                                        data-type="CEI 24A"
                                        data-brochage="{{ $brochage }}"
                                        data-field="tension"
                                        onchange="mettreAJour()">
                                        <option value="">--</option>
                                        <option value="24V">24V</option>
                                        <option value="48V">48V</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                </div>{{-- fin flex flex-col gap-6 --}}
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- 🛡️ SECTION 04 : PROTECTION DE TÊTE                    --}}
        {{-- ====================================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                 onclick="toggleSection('s4')">
                <div class="flex items-center gap-3">
                    <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">04</span>
                    <span class="font-bold text-lg">Protection de Tête</span>
                </div>
                <span id="arrow-s4" class="text-white text-lg transition-transform duration-300">▼</span>
            </div>

            <div id="section-s4" class="hidden p-6">

                <p class="text-xs text-gray-400 mb-4 italic">
                    La protection de tête protège l'ensemble du coffret d'étage contre les surintensités et les défauts d'isolement.
                </p>

                <div class="bg-blue-50 border-l-4 border-bals-blue p-3 mb-4 rounded">
                    <p class="text-xs text-blue-800">
                        <strong>Configuration recommandée (selon PDF) :</strong> Inter différentiel + Disjoncteur
                    </p>
                </div>

                {{--
                    ✅ BUG 2 CORRIGÉ : styles "actifs" gérés uniquement par peer-checked:
                    (plus de classes border-bals-blue / bg-blue-50 codées en dur)
                --}}
                <div class="grid grid-cols-2 gap-3">

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_tete[]" value="Sans" class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Sans</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_tete[]" value="Interrupteur" class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Interrupteur</span>
                        </div>
                    </label>

                    {{-- ✅ BUG 2 CORRIGÉ : Inter différentiel pré-coché via peer-checked: uniquement --}}
                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_tete[]" value="Inter différentiel" class="peer sr-only" onchange="mettreAJour()" checked>
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0 flex items-center justify-center peer-checked:bg-bals-blue peer-checked:border-bals-blue">
                                <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Inter différentiel ⭐</span>
                        </div>
                    </label>

                    {{-- ✅ BUG 2 CORRIGÉ : Disjoncteur pré-coché via peer-checked: uniquement --}}
                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_tete[]" value="Disjoncteur" class="peer sr-only" onchange="mettreAJour()" checked>
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0 flex items-center justify-center peer-checked:bg-bals-blue peer-checked:border-bals-blue">
                                <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Disjoncteur ⭐</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_tete[]" value="Disjoncteur Diff." class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Disjoncteur Diff.</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_tete[]" value="Arrêt d'urgence" class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-red-600">Arrêt d'urgence</span>
                        </div>
                    </label>

                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- 🔒 SECTION 05 : PROTECTION DES PRISES                  --}}
        {{-- ====================================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                 onclick="toggleSection('s5')">
                <div class="flex items-center gap-3">
                    <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">05</span>
                    <span class="font-bold text-lg">Protection des Prises</span>
                </div>
                <span id="arrow-s5" class="text-white text-lg transition-transform duration-300">▼</span>
            </div>

            <div id="section-s5" class="hidden p-6">

                <p class="text-xs text-gray-400 mb-4 italic">
                    Protection individuelle ou par groupe pour chaque prise du coffret d'étage.
                </p>

                <div class="bg-blue-50 border-l-4 border-bals-blue p-3 mb-4 rounded">
                    <p class="text-xs text-blue-800">
                        <strong>Configuration recommandée (selon PDF) :</strong> Disjoncteur par prise ou par groupe
                    </p>
                </div>

                {{-- ✅ BUG 2 CORRIGÉ : même logique peer-checked: pour toutes les checkboxes --}}
                <div class="grid grid-cols-2 gap-3">

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_prises[]" value="Sans" class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Sans</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_prises[]" value="Par prise" class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Par prise</span>
                        </div>
                    </label>

                    <label class="cursor-pointer col-span-2">
                        <input type="checkbox" name="prot_prises[]" value="Par groupe de prises" class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Par groupe de prises</span>
                        </div>
                    </label>

                    {{-- ✅ BUG 2 CORRIGÉ : Disjoncteur pré-coché via peer-checked: uniquement --}}
                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_prises[]" value="Disjoncteur" class="peer sr-only" onchange="mettreAJour()" checked>
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0 flex items-center justify-center peer-checked:bg-bals-blue peer-checked:border-bals-blue">
                                <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Disjoncteur ⭐</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="checkbox" name="prot_prises[]" value="Disjoncteur Diff." class="peer sr-only" onchange="mettreAJour()">
                        <div class="border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 transition-all peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue cursor-pointer">
                            <div class="w-5 h-5 rounded border-2 border-gray-300 flex-shrink-0"></div>
                            <span class="text-sm font-bold text-gray-700">Disjoncteur Diff.</span>
                        </div>
                    </label>

                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- 📝 SECTION 06 : OBSERVATIONS                           --}}
        {{-- ====================================================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                 onclick="toggleSection('s6')">
                <div class="flex items-center gap-3">
                    <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">06</span>
                    <span class="font-bold text-lg">Observations</span>
                </div>
                <span id="arrow-s6" class="text-white text-lg transition-transform duration-300">▼</span>
            </div>

            <div id="section-s6" class="hidden p-6">

                <p class="text-xs text-gray-400 mb-4 italic">
                    Ajoutez toute précision utile sur votre installation d'étage (nombre d'étages, besoins spécifiques, contraintes techniques...).
                </p>

                <textarea
                    id="observations"
                    name="observations"
                    rows="6"
                    placeholder="Ex : Installation au 3ème étage, besoin de mobilité, nombre de prises par pièce, contraintes d'accès..."
                    oninput="mettreAJour()"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50 resize-y">
                </textarea>

                <p class="text-xs text-gray-400 mt-2 text-right">
                    <span id="nb-caracteres">0</span> caractère(s)
                </p>
            </div>
        </div>

        </div>{{-- fin lg:col-span-2 --}}

        @include('configurateur.partials.panneau-resume')

    </div>{{-- fin grid --}}
</div>

@endsection

@section('scripts')
<script>

// ── Accordéon : ouvrir / fermer une section ──────────────────────
function toggleSection(id) {
    var section = document.getElementById('section-' + id);
    var arrow   = document.getElementById('arrow-' + id);
    if (!section) return;
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        if (arrow) arrow.textContent = '▲';
    } else {
        section.classList.add('hidden');
        if (arrow) arrow.textContent = '▼';
    }
}

// ── Incrémenter / décrémenter une quantité de prise ─────────────
function changerQte(btn, delta) {
    var span = btn.parentElement.querySelector('span[data-type]');
    if (!span) return;
    var val = parseInt(span.textContent) || 0;
    span.textContent = Math.max(0, val + delta);
    mettreAJour();
}

// ── Mise à jour du résumé et de la progression ──────────────────
// ✅ BUG 4 CORRIGÉ : lecture et affichage des prises sélectionnées
function mettreAJour() {

    var distributeur        = (document.getElementById('distributeur')        || {}).value || '';
    var contactDistrib      = (document.getElementById('contact_distributeur')|| {}).value || '';
    var installateur        = (document.getElementById('installateur')        || {}).value || '';
    var contactInstallateur = (document.getElementById('contact_installateur')|| {}).value || '';
    var affaire             = (document.getElementById('affaire')             || {}).value || '';
    var telephone           = (document.getElementById('telephone')           || {}).value || '';
    var email               = (document.getElementById('email')               || {}).value || '';
    var observations        = (document.getElementById('observations')        || {}).value || '';

    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');
    var montage    = montageEl  ? montageEl.value  : '';
    var materiau   = materiauEl ? materiauEl.value : '';
    var ip         = ipEl       ? ipEl.value       : '';

    var protTete   = Array.from(document.querySelectorAll('input[name="prot_tete[]"]:checked')).map(function(el) { return el.value; });
    var protPrises = Array.from(document.querySelectorAll('input[name="prot_prises[]"]:checked')).map(function(el) { return el.value; });

    // ✅ BUG 4 CORRIGÉ : lecture de toutes les prises avec quantité > 0
    var prises = [];
    document.querySelectorAll('span[data-type][data-brochage]').forEach(function(span) {
        var qte = parseInt(span.textContent) || 0;
        if (qte > 0) {
            var tensionEl = document.querySelector(
                'select[data-type="' + span.dataset.type + '"][data-brochage="' + span.dataset.brochage + '"][data-field="tension"]'
            );
            var tension = tensionEl ? tensionEl.value : '';
            prises.push(qte + 'x ' + span.dataset.type + ' ' + span.dataset.brochage + (tension ? ' ' + tension : ''));
        }
    });

    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = observations.length;

    var champs  = [distributeur, email, montage, materiau, ip].map(function(v) { return v ? 1 : 0; });
    var remplis = champs.reduce(function(a, b) { return a + b; }, 0);
    var pct     = Math.round(remplis / champs.length * 100);

    var barre = document.getElementById('progression-barre');
    var texte = document.getElementById('progression-texte');
    if (barre) barre.style.width = pct + '%';
    if (texte) texte.textContent = '(' + pct + '%)';

    var zone = document.getElementById('resume-zone');
    if (!zone) return;

    if (remplis === 0 && prises.length === 0 && protTete.length === 0) {
        zone.innerHTML = '<p class="text-bals-blue font-bold text-sm opacity-40">Configurez votre coffret</p>'
                       + '<p class="text-gray-400 text-xs mt-1">Les informations apparaîtront ici</p>';
        var btns = document.getElementById('boutons-action');
        if (btns) btns.classList.add('hidden');
        return;
    }

    var html = '<div class="w-full text-left space-y-3">';
    html += '<div class="bg-bals-blue text-white rounded-lg px-3 py-2 text-sm font-bold text-center">Coffret d\'Étage</div>';

    if (distributeur)        html += '<p class="text-xs"><span class="text-gray-400">Distributeur :</span> <span class="font-bold text-gray-700">' + distributeur + '</span></p>';
    if (contactDistrib)      html += '<p class="text-xs"><span class="text-gray-400">Contact distrib. :</span> <span class="font-bold text-gray-700">' + contactDistrib + '</span></p>';
    if (installateur)        html += '<p class="text-xs"><span class="text-gray-400">Installateur :</span> <span class="font-bold text-gray-700">' + installateur + '</span></p>';
    if (contactInstallateur) html += '<p class="text-xs"><span class="text-gray-400">Contact install. :</span> <span class="font-bold text-gray-700">' + contactInstallateur + '</span></p>';
    if (affaire)             html += '<p class="text-xs"><span class="text-gray-400">Affaire :</span> <span class="font-bold text-gray-700">' + affaire + '</span></p>';
    if (telephone)           html += '<p class="text-xs"><span class="text-gray-400">Tél. :</span> <span class="font-bold text-gray-700">' + telephone + '</span></p>';
    if (email)               html += '<p class="text-xs"><span class="text-gray-400">Email :</span> <span class="font-bold text-gray-700">' + email + '</span></p>';

    if (montage || materiau || ip) {
        html += '<div class="border-t border-gray-100 pt-2 space-y-1">';
        if (montage)  html += '<p class="text-xs"><span class="text-gray-400">Montage :</span> <span class="font-bold text-gray-700">' + montage + '</span></p>';
        if (materiau) html += '<p class="text-xs"><span class="text-gray-400">Matériau :</span> <span class="font-bold text-gray-700">' + materiau + '</span></p>';
        if (ip)       html += '<p class="text-xs"><span class="text-gray-400">IP :</span> <span class="font-black text-bals-blue">' + ip + '</span></p>';
        html += '</div>';
    }

    // ✅ BUG 4 CORRIGÉ : affichage des prises dans le résumé
    if (prises.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Prises :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + prises.join('<br>') + '</p></div>';
    }

    if (protTete.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Prot. de tête :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + protTete.join(', ') + '</p></div>';
    }
    if (protPrises.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Prot. des prises :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + protPrises.join(', ') + '</p></div>';
    }

    html += '</div>';
    zone.innerHTML = html;

    var btns = document.getElementById('boutons-action');
    if (btns) btns.classList.remove('hidden');
}

// ── Copier le résumé dans le presse-papiers ──────────────────────
function copierResume() {
    var distributeur = (document.getElementById('distributeur') || {}).value || 'N/A';
    var email        = (document.getElementById('email')        || {}).value || 'N/A';
    var texte = "DEVIS BALS — COFFRET D'ÉTAGE\nDistributeur : " + distributeur + "\nEmail : " + email;
    navigator.clipboard.writeText(texte).then(function() { alert('Résumé copié !'); });
}

// ── Réinitialiser tous les champs du formulaire ──────────────────
function reinitialiser() {
    ['distributeur','contact_distributeur','installateur','contact_installateur','affaire','telephone','email','observations'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
    });

    document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(r) {
        r.checked = false;
    });

    // ✅ BUG 3 CORRIGÉ : remise à zéro des quantités via data-type/data-brochage
    document.querySelectorAll('span[data-type][data-brochage]').forEach(function(span) {
        span.textContent = '0';
    });

    document.querySelectorAll('select[data-field="tension"]').forEach(function(sel) {
        sel.value = '';
    });

    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = '0';

    mettreAJour();
}

// ── Envoyer le devis via API + ouverture du client mail ──────────
function envoyerDevis() {
    var distributeur        = (document.getElementById('distributeur')        || {}).value || '';
    var contactDistrib      = (document.getElementById('contact_distributeur')|| {}).value || '';
    var installateur        = (document.getElementById('installateur')        || {}).value || '';
    var contactInstallateur = (document.getElementById('contact_installateur')|| {}).value || '';
    var affaire             = (document.getElementById('affaire')             || {}).value || '';
    var telephone           = (document.getElementById('telephone')           || {}).value || '';
    var email               = (document.getElementById('email')               || {}).value || '';
    var observations        = (document.getElementById('observations')        || {}).value || '';

    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');
    var protTete   = Array.from(document.querySelectorAll('input[name="prot_tete[]"]:checked')).map(function(el) { return el.value; });
    var protPrises = Array.from(document.querySelectorAll('input[name="prot_prises[]"]:checked')).map(function(el) { return el.value; });

    var prises = [];
    document.querySelectorAll('span[data-type][data-brochage]').forEach(function(span) {
        var qte = parseInt(span.textContent) || 0;
        if (qte > 0) {
            var tensionEl = document.querySelector(
                'select[data-type="' + span.dataset.type + '"][data-brochage="' + span.dataset.brochage + '"][data-field="tension"]'
            );
            prises.push({ type: span.dataset.type, brochage: span.dataset.brochage, quantite: qte, tension: tensionEl ? tensionEl.value : '' });
        }
    });

    var payload = {
        type_coffret: "Coffret d'Étage",
        distributeur:         distributeur,
        contact_distributeur: contactDistrib,
        installateur:         installateur,
        contact_installateur: contactInstallateur,
        affaire:              affaire,
        telephone:            telephone,
        email:                email,
        observations:         observations,
        donnees: {
            montage:            montageEl  ? montageEl.value  : '',
            materiau:           materiauEl ? materiauEl.value : '',
            ip:                 ipEl       ? ipEl.value       : '',
            prises:             prises,
            protections_tete:   protTete,
            protections_prises: protPrises
        }
    };

    var sujet = encodeURIComponent("Demande de devis Coffret d'Étage" + (distributeur ? ' — ' + distributeur : ''));
    var corps = encodeURIComponent("Bonjour,\n\nVeuillez trouver ci-joint ma demande de devis pour un coffret d'étage.\n\nDistributeur : " + distributeur);
    var mailtoUrl = 'mailto:info@bals-france.fr?subject=' + sujet + '&body=' + corps;

    fetch('/api/devis', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''
        },
        body: JSON.stringify(payload)
    })
    .then(function() { window.location.href = mailtoUrl; })
    .catch(function() { window.location.href = mailtoUrl; });
}

// ── Initialisation au chargement de la page ─────────────────────
document.addEventListener('DOMContentLoaded', function() { mettreAJour(); });

</script>
@endsection
