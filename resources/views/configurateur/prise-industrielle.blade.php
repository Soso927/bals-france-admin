{{--
    ============================================================
    VUE : Configurateur Prise Industrielle
    ============================================================
    Formulaire de devis pour les prises et connecteurs industriels.
    Ce formulaire a une structure différente des autres :
        - Les boutons de section utilisent data-action="toggle-section"
          (gestion par délégation d'événements dans prise-industrielle.js)
        - La section 2 contient une zone conditionnelle (#zone-montage)
          affichée uniquement pour "Socle de prise" et "Socle connecteur"
        - La section 4 combine quantité + observations

    Les 3 partials communs (header, progress-bar, panneau-resume)
    sont réutilisés — les fonctions globales envoyerDevis(),
    copierResume() et reinitialiser() sont définies dans prise-industrielle.js.
    ============================================================
--}}
@extends('layouts.admin')

@section('title', 'Configurateur Prise Industrielle — BALS')

@section('content')
<div>

    @include('configurateur.partials.header')
    @include('configurateur.partials.nav-type', ['activeType' => 'prise-industrielle'])
    @include('configurateur.partials.progress-bar')

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{--
                Les boutons de section utilisent data-action="toggle-section"
                au lieu de onclick="toggleSection()" — c'est prise-industrielle.js
                qui écoute ces clics via document.addEventListener('click', ...).
            --}}

            {{-- Section 1 : Identification --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button data-action="toggle-section" data-section-id="s1"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
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
                            <input id="societe" type="text" data-action="mettre-a-jour" placeholder="Ex : ELECTRO DIST SUD"
                                   class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</span>
                            <input id="contact" type="text" data-action="mettre-a-jour" placeholder="Ex : Jean MARTIN"
                                   class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Installateur</span>
                            <input id="installateur" type="text" data-action="mettre-a-jour" placeholder="Ex : ELEC PRO"
                                   class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Affaire / Référence</span>
                            <input id="affaire" type="text" data-action="mettre-a-jour" placeholder="Ex : Usine Lyon 2025"
                                   class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                        </label>
                        <label class="block sm:col-span-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email de contact</span>
                            <input id="email" type="email" data-action="mettre-a-jour" placeholder="contact@societe.fr"
                                   class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue">
                        </label>
                    </div>
                </div>
            </div>

            {{-- Section 2 : Type de produit (avec zone montage conditionnelle) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button data-action="toggle-section" data-section-id="s2"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">2</span>
                        <span class="font-bold text-gray-800">Type de produit</span>
                    </div>
                    <span id="arrow-s2" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s2" class="hidden px-5 pb-5 space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Produit</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Socle de prise', 'Socle connecteur', 'Fiche mobile', 'Prolongateur'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="produit" value="{{ $opt }}" data-action="mettre-a-jour" class="accent-[#009EE3]">
                                <span class="text-sm font-medium">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    {{--
                        Zone montage conditionnelle : masquée par défaut.
                        prise-industrielle.js l'affiche uniquement si "Socle de prise"
                        ou "Socle connecteur" est sélectionné.
                    --}}
                    <div id="zone-montage" class="hidden">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Type de montage</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Encastré', 'En saillie', 'Panneau'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="montage_type" value="{{ $opt }}" data-action="mettre-a-jour" class="accent-[#009EE3]">
                                <span class="text-sm font-medium">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3 : Caractéristiques électriques --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button data-action="toggle-section" data-section-id="s3"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">3</span>
                        <span class="font-bold text-gray-800">Caractéristiques électriques</span>
                    </div>
                    <span id="arrow-s3" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s3" class="hidden px-5 pb-5 space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tension</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['110V', '230V', '400V', '690V'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="tension" value="{{ $opt }}" data-action="mettre-a-jour" class="accent-[#009EE3]">
                                <span class="text-sm font-bold">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Intensité</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['16A', '32A', '63A', '125A'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="amp" value="{{ $opt }}" data-action="mettre-a-jour" class="accent-[#009EE3]">
                                <span class="text-sm font-bold">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Polarité</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['2P', '2P+T', '3P', '3P+N+T'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="pol" value="{{ $opt }}" data-action="mettre-a-jour" class="accent-[#009EE3]">
                                <span class="text-sm font-bold">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Indice de protection (IP)</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['IP44', 'IP54', 'IP55', 'IP66', 'IP67', 'IP68'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="ip" value="{{ $opt }}" data-action="mettre-a-jour" class="accent-[#009EE3]">
                                <span class="text-sm font-bold">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 4 : Quantité & Observations (badge gris = section optionnelle) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button data-action="toggle-section" data-section-id="s4"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-200 text-gray-600 text-xs font-black flex items-center justify-center">4</span>
                        <span class="font-bold text-gray-800">Quantité &amp; Observations</span>
                    </div>
                    <span id="arrow-s4" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s4" class="hidden px-5 pb-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Quantité</p>
                        <div class="flex items-center gap-3">
                            <button data-action="changer-qte" data-delta="-1"
                                    class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 font-bold text-gray-700 flex items-center justify-center text-lg transition-colors">−</button>
                            <input id="quantite" type="number" value="1" min="1" data-action="mettre-a-jour"
                                   class="w-20 rounded-xl border border-gray-200 text-center font-black text-lg py-2 focus:outline-none focus:ring-2 focus:ring-bals-blue">
                            <button data-action="changer-qte" data-delta="1"
                                    class="w-9 h-9 rounded-xl bg-bals-blue hover:opacity-90 font-bold text-white flex items-center justify-center text-lg transition-colors">+</button>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Observations</p>
                        <textarea id="observations" data-action="mettre-a-jour" rows="4"
                                  placeholder="Précisions complémentaires..."
                                  class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue resize-none"></textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Panneau résumé partagé — les fonctions onclick sont dans prise-industrielle.js --}}
        @include('configurateur.partials.panneau-resume')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('configurateur/js/prise-industrielle.js') }}"></script>
@endsection
