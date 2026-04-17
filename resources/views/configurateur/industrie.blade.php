@extends('layouts.admin')

@section('title', 'Configurateur Coffret Industrie — BALS')

@section('content')
<div>

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-gray-900">Configurateur de devis</h1>
        <p class="text-gray-500 text-sm mt-1">Remplissez les sections pour obtenir votre devis personnalisé.</p>
    </div>

    {{-- Navigation types --}}
    @include('configurateur.partials.nav-type', ['activeType' => 'industrie'])

    {{-- Progression --}}
    <div class="mt-6 bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Complétion</span>
            <span id="progression-texte" class="text-xs font-bold text-bals-blue">(0%)</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            <div id="progression-barre" class="bg-bals-blue h-2 rounded-full transition-all" style="width:0%"></div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Colonne gauche : Formulaire ── --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Section 1 : Identification --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s1')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">1</span>
                        <span class="font-bold text-gray-800">Identification</span>
                    </div>
                    <span id="arrow-s1" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s1" class="hidden px-5 pb-5 space-y-3">
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
                </div>
            </div>

            {{-- Section 2 : Configuration --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s2')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">2</span>
                        <span class="font-bold text-gray-800">Configuration</span>
                    </div>
                    <span id="arrow-s2" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s2" class="hidden px-5 pb-5 space-y-5">
                    {{-- Montage --}}
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Type de montage</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Fixe au sol', 'Fixe au mur', 'Sur châssis', 'Suspendu'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="montage" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm font-medium">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    {{-- Matériau --}}
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Matériau du coffret</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Polyester', 'Acier inoxydable', 'Aluminium', 'Acier galvanisé'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="materiau" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm font-medium">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    {{-- IP --}}
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Indice de protection (IP)</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['IP55', 'IP65', 'IP66', 'IP67', 'IP68'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="ip" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm font-bold">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3 : Prises --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s3')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">3</span>
                        <span class="font-bold text-gray-800">Prises industrielles</span>
                    </div>
                    <span id="arrow-s3" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s3" class="hidden px-5 pb-5 space-y-3">
                    @php
                    $prises = [
                        ['label' => '3P+N+T 16A (CEE)',      'type' => '3P+N+T 16A',  'brochage' => 'CEE'],
                        ['label' => '3P+N+T 32A (CEE)',      'type' => '3P+N+T 32A',  'brochage' => 'CEE'],
                        ['label' => '3P+N+T 63A (CEE)',      'type' => '3P+N+T 63A',  'brochage' => 'CEE'],
                        ['label' => '3P+N+T 125A (CEE)',     'type' => '3P+N+T 125A', 'brochage' => 'CEE'],
                        ['label' => '3P+N+T 200A (CEE)',     'type' => '3P+N+T 200A', 'brochage' => 'CEE'],
                        ['label' => '2P+T 16A (Schuko)',     'type' => '2P+T 16A',    'brochage' => 'Schuko'],
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
                </div>
            </div>

            {{-- Section 4 : Protections --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s4')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">4</span>
                        <span class="font-bold text-gray-800">Protections</span>
                    </div>
                    <span id="arrow-s4" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s4" class="hidden px-5 pb-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Protection en tête</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Différentiel 300mA', 'Disjoncteur général', 'Sectionneur', 'Parafoudre', 'Sans'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-3 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="checkbox" name="prot_tete[]" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Protection par prise</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Disjoncteur 16A', 'Disjoncteur 32A', 'Disjoncteur 63A', 'Fusible', 'Sans'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-3 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="checkbox" name="prot_prises[]" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 5 : Observations --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s5')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-200 text-gray-600 text-xs font-black flex items-center justify-center">5</span>
                        <span class="font-bold text-gray-800">Observations</span>
                    </div>
                    <span id="arrow-s5" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s5" class="hidden px-5 pb-5">
                    <textarea id="observations" oninput="mettreAJour()" rows="4"
                              placeholder="Précisions complémentaires, contraintes particulières..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue resize-none"></textarea>
                    <p class="text-xs text-gray-400 mt-1 text-right"><span id="nb-caracteres">0</span> caractères</p>
                </div>
            </div>

        </div>

        {{-- ── Colonne droite : Résumé --}}
        <div class="lg:col-span-1">
            <div class="sticky top-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-3">Résumé de la demande</p>

                <div id="resume-zone" class="min-h-[120px] flex flex-col items-center justify-center text-center">
                    <p class="text-bals-blue font-bold text-sm opacity-40">Configurez votre coffret</p>
                    <p class="text-gray-400 text-xs mt-1">Les informations apparaîtront ici</p>
                </div>

                <div id="boutons-action" class="hidden mt-4 space-y-2 border-t border-gray-100 pt-4">
                    <button onclick="envoyerDevis()"
                            class="w-full rounded-xl bg-bals-blue text-white font-bold py-3 text-sm hover:opacity-90 transition-opacity">
                        ✉ Envoyer le devis
                    </button>
                    <button onclick="copierResume()"
                            class="w-full rounded-xl border border-gray-200 text-gray-700 font-bold py-2.5 text-sm hover:bg-gray-50 transition-colors">
                        Copier le résumé
                    </button>
                    <button onclick="reinitialiser()"
                            class="w-full rounded-xl border border-red-100 text-red-500 font-bold py-2.5 text-sm hover:bg-red-50 transition-colors">
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('configurateur/js/chantier.js') }}"></script>
@endsection
