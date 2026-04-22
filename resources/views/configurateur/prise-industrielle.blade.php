{{--
    ============================================================
    VUE : Configurateur Prise Industrielle
    ============================================================
    Formulaire de devis pour les prises industrielles BALS
    (NF EN 60 309-1/2) : socles, fiches, prolongateurs.
    ============================================================
--}}
@extends('layouts.admin')

@section('title', 'Configurateur Prises Industrielles — BALS')

@section('content')
<div>

    @include('configurateur.partials.header')
    @include('configurateur.partials.nav-type', ['activeType' => 'prise-industrielle'])
    @include('configurateur.partials.progress-bar')

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{-- ====================================================== --}}
            {{-- SECTION 01 : INFORMATIONS DE CONTACT                   --}}
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
                        <label class="block text-sm font-bold text-gray-700 mb-2">Société / Distributeur</label>
                        <input type="text" id="societe" placeholder="Nom de la société"
                               oninput="mettreAJour()"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Contact</label>
                        <input type="text" id="contact" placeholder="Nom du contact"
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
                        <label class="block text-sm font-bold text-gray-700 mb-2">Référence Affaire</label>
                        <input type="text" id="affaire" placeholder="Référence de l'affaire"
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
            {{-- SECTION 02 : TYPE DE PRODUIT                           --}}
            {{-- ====================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                     onclick="toggleSection('s2')">
                    <div class="flex items-center gap-3">
                        <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">02</span>
                        <span class="font-bold text-lg">Type de Produit</span>
                    </div>
                    <span id="arrow-s2" class="text-white text-lg transition-transform duration-300">▼</span>
                </div>

                <div id="section-s2" class="hidden p-6 flex flex-col gap-6">

                    {{-- Famille de produit --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Famille de produit <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['Socle de prise', 'Socle connecteur', 'Fiche mobile', 'Prolongateur'] as $prod)
                            <label class="cursor-pointer group">
                                <input type="radio" name="produit" value="{{ $prod }}" class="peer sr-only"
                                       onchange="gererTypeProduit()">
                                <div class="h-full border-2 border-gray-200 rounded-xl p-4 text-center peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue transition-all cursor-pointer">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg mx-auto mb-2 text-gray-400 flex items-center justify-center group-hover:bg-blue-50 group-hover:text-bals-blue peer-checked:bg-white peer-checked:text-bals-blue transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-black uppercase leading-tight text-gray-700">{{ $prod }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Type de montage (uniquement pour Socle de prise / Socle connecteur) --}}
                    <div id="zone-montage" class="hidden">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Type de montage</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['Encastré', 'En saillie', 'Panneau'] as $montage)
                            <label class="cursor-pointer">
                                <input type="radio" name="montage_type" value="{{ $montage }}" class="peer sr-only"
                                       onchange="mettreAJour()">
                                <div class="border-2 border-gray-200 rounded-xl p-3 text-center peer-checked:border-bals-blue peer-checked:bg-blue-50 hover:border-bals-blue transition-all cursor-pointer">
                                    <span class="text-xs font-bold text-gray-700">{{ $montage }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====================================================== --}}
            {{-- SECTION 03 : TENSION & FRÉQUENCE                       --}}
            {{-- ====================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                     onclick="toggleSection('s3')">
                    <div class="flex items-center gap-3">
                        <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">03</span>
                        <span class="font-bold text-lg">Tension & Fréquence</span>
                    </div>
                    <span id="arrow-s3" class="text-white text-lg transition-transform duration-300">▼</span>
                </div>

                <div id="section-s3" class="hidden p-6 space-y-6">

                    {{-- Très Basse Tension (TBT) < 50V --}}
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400 mb-3">Très Basse Tension (&lt; 50V)</p>
                        <div class="grid grid-cols-3 gap-2">
                            @php
                                $tbt = [
                                    ['v' => '24V', 'c' => 'bg-purple-600', 'hz' => '50-60Hz'],
                                    ['v' => '42V', 'c' => 'bg-white border-2 border-gray-300', 'hz' => '50-60Hz'],
                                    ['v' => 'CC',  'c' => 'bg-white border-2 border-gray-300', 'hz' => 'Courant Continu'],
                                ];
                            @endphp
                            @foreach($tbt as $item)
                            <label class="cursor-pointer">
                                <input type="radio" name="tension" value="{{ $item['v'] }}" class="peer sr-only"
                                       onchange="mettreAJour()">
                                <div class="border border-gray-200 rounded-lg overflow-hidden peer-checked:ring-2 peer-checked:ring-bals-blue transition-all hover:shadow-md">
                                    <div class="{{ $item['c'] }} h-3 w-full"></div>
                                    <div class="p-2 text-center">
                                        <div class="font-black text-sm text-gray-800">{{ $item['v'] }}</div>
                                        <div class="text-[9px] text-gray-400 leading-tight">{{ $item['hz'] }}</div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Basse Tension (BT) > 50V --}}
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400 mb-3">Basse Tension (&gt; 50V)</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @php
                                $bt = [
                                    ['v' => '110V', 'c' => 'bg-yellow-400', 'hz' => '50-60Hz'],
                                    ['v' => '230V', 'c' => 'bg-blue-500',   'hz' => '50-60Hz'],
                                    ['v' => '400V', 'c' => 'bg-red-600',    'hz' => '50-60Hz'],
                                    ['v' => '690V', 'c' => 'bg-black',      'hz' => '50-60Hz'],
                                    ['v' => '>50V', 'c' => 'bg-green-600',  'hz' => '100-300Hz'],
                                    ['v' => '>50V', 'c' => 'bg-green-600',  'hz' => '300-500Hz'],
                                ];
                            @endphp
                            @foreach($bt as $item)
                            <label class="cursor-pointer">
                                <input type="radio" name="tension" value="{{ $item['v'] }} ({{ $item['hz'] }})" class="peer sr-only"
                                       onchange="mettreAJour()">
                                <div class="border border-gray-200 rounded-lg overflow-hidden peer-checked:ring-2 peer-checked:ring-bals-blue transition-all hover:shadow-md">
                                    <div class="{{ $item['c'] }} h-3 w-full"></div>
                                    <div class="p-2 text-center">
                                        <div class="font-black text-sm text-gray-800">{{ $item['v'] }}</div>
                                        <div class="text-[9px] text-gray-400 leading-tight">{{ $item['hz'] }}</div>
                                    </div>
                                </div>
                            </label>
                            @endforeach

                            {{-- Tension libre --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="tension" value="Tension libre" class="peer sr-only"
                                       onchange="mettreAJour()">
                                <div class="h-full border border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center p-2 peer-checked:bg-gray-50 peer-checked:border-bals-blue peer-checked:border-solid transition-all">
                                    <span class="text-[10px] font-black uppercase text-gray-500 text-center leading-tight">Autre / Libre</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====================================================== --}}
            {{-- SECTION 04 : INTENSITÉ & POLARITÉ                      --}}
            {{-- ====================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                     onclick="toggleSection('s4')">
                    <div class="flex items-center gap-3">
                        <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">04</span>
                        <span class="font-bold text-lg">Intensité & Polarité</span>
                    </div>
                    <span id="arrow-s4" class="text-white text-lg transition-transform duration-300">▼</span>
                </div>

                <div id="section-s4" class="hidden p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Intensité --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Intensité <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['16A', '32A', '63A', '125A'] as $amp)
                            <label class="cursor-pointer">
                                <input type="radio" name="amp" value="{{ $amp }}" class="peer sr-only"
                                       onchange="mettreAJour()">
                                <div class="border-2 border-gray-200 rounded-xl p-3 text-center peer-checked:border-bals-blue peer-checked:bg-blue-50 peer-checked:text-bals-blue font-bold text-gray-600 transition-all hover:border-bals-blue cursor-pointer">
                                    {{ $amp }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Polarité --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Polarité <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['2P', '2P+T', '3P', '3P+N+T'] as $pol)
                            <label class="cursor-pointer">
                                <input type="radio" name="pol" value="{{ $pol }}" class="peer sr-only"
                                       onchange="mettreAJour()">
                                <div class="border-2 border-gray-200 rounded-xl p-3 text-center peer-checked:border-bals-blue peer-checked:bg-blue-50 peer-checked:text-bals-blue font-bold text-gray-600 text-xs transition-all hover:border-bals-blue cursor-pointer">
                                    {{ $pol }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====================================================== --}}
            {{-- SECTION 05 : INDICE DE PROTECTION (IP)                 --}}
            {{-- ====================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                     onclick="toggleSection('s5')">
                    <div class="flex items-center gap-3">
                        <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">05</span>
                        <span class="font-bold text-lg">Indice de Protection</span>
                    </div>
                    <span id="arrow-s5" class="text-white text-lg transition-transform duration-300">▼</span>
                </div>

                <div id="section-s5" class="hidden p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach([
                            'IP44' => 'Corps > 1mm & projections d\'eau.',
                            'IP54' => 'Poussières & projections d\'eau.',
                            'IP55' => 'Poussières & jets d\'eau.',
                            'IP66' => 'Étanche aux poussières & jets puissants.',
                            'IP67' => 'Étanche & immersion temporaire.',
                            'IP68' => 'Étanche & immersion prolongée.',
                        ] as $val => $desc)
                        <label class="cursor-pointer h-full">
                            <input type="radio" name="ip" value="{{ $val }}" class="peer sr-only"
                                   onchange="mettreAJour()">
                            <div class="h-full border-2 border-gray-200 rounded-xl p-4 flex flex-col peer-checked:border-bals-blue peer-checked:bg-blue-50 transition-all hover:border-gray-300 cursor-pointer">
                                <span class="block text-xl font-black text-bals-blue mb-1">{{ $val }}</span>
                                <span class="text-xs text-gray-500 block leading-snug">{{ $desc }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ====================================================== --}}
            {{-- SECTION 06 : QUANTITÉ ET OBSERVATIONS                  --}}
            {{-- ====================================================== --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="bg-bals-blue text-white px-6 py-4 flex items-center justify-between cursor-pointer"
                     onclick="toggleSection('s6')">
                    <div class="flex items-center gap-3">
                        <span class="bg-white text-bals-blue font-black text-sm w-8 h-8 rounded-lg flex items-center justify-center">06</span>
                        <span class="font-bold text-lg">Quantité & Observations</span>
                    </div>
                    <span id="arrow-s6" class="text-white text-lg transition-transform duration-300">▼</span>
                </div>

                <div id="section-s6" class="hidden p-6 space-y-5">

                    {{-- Quantité --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Quantité souhaitée</label>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="changerQte(-1)"
                                    class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 font-bold text-lg transition-all">−</button>
                            <input type="number" id="quantite" value="1" min="1"
                                   oninput="mettreAJour()"
                                   class="w-20 text-center border-2 border-gray-200 rounded-xl py-2 font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-bals-blue">
                            <button type="button" onclick="changerQte(1)"
                                    class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 font-bold text-lg transition-all">+</button>
                        </div>
                    </div>

                    {{-- Observations --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Observations / Commentaires</label>
                        <textarea
                            id="observations"
                            name="observations"
                            rows="5"
                            placeholder="Précisions supplémentaires (environnement d'utilisation, contraintes particulières...)"
                            oninput="mettreAJour()"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bals-blue focus:border-transparent transition-all bg-gray-50 resize-y">
                        </textarea>
                        <p class="text-xs text-gray-400 mt-2 text-right">
                            <span id="nb-caracteres">0</span> caractère(s)
                        </p>
                    </div>
                </div>
            </div>

        </div>{{-- fin lg:col-span-2 --}}

        @include('configurateur.partials.panneau-resume')

    </div>{{-- fin grid --}}
</div>

@endsection

@section('scripts')
<script>
    window.COFFRET = { nom: 'Prise Industrielle', type: 'prise' };
</script>
<script src="{{ asset('configurateur/js/configurateur.js') }}"></script>
@endsection
