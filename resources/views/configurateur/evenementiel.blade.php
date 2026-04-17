@extends('layouts.admin')

@section('title', 'Configurateur Coffret Événementiel — BALS')

@section('content')
<div>

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-gray-900">Configurateur de devis</h1>
        <p class="text-gray-500 text-sm mt-1">Remplissez les sections pour obtenir votre devis personnalisé.</p>
    </div>

    {{-- Navigation types --}}
    @include('configurateur.partials.nav-type', ['activeType' => 'evenementiel'])

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
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Type de montage</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Mobile (roues)', 'Mobile (poignée)', 'Fixe temporaire', 'Suspendu'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="montage" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm font-medium">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Matériau</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Caoutchouc', 'Plastique ABS', 'Aluminium'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="materiau" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm font-medium">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Indice de protection (IP)</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['IP44', 'IP54', 'IP55', 'IP65'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="radio" name="ip" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm font-bold">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3 : Circuits --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s3')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-bals-blue text-white text-xs font-black flex items-center justify-center">3</span>
                        <span class="font-bold text-gray-800">Circuits & Prises</span>
                    </div>
                    <span id="arrow-s3" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s3" class="hidden px-5 pb-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Types de sorties</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['2P+T 16A (Schuko)', '3P+N+T 16A (CEE)', '3P+N+T 32A (CEE)', '3P+N+T 63A (CEE)', '3P+N+T 125A (CEE)'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-3 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="checkbox" name="prot_tete[]" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Protections</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Différentiel 30mA', 'Différentiel 300mA', 'Disjoncteur général', 'Parafoudre', 'Sectionneur'] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-3 py-2 hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
                                <input type="checkbox" name="prot_prises[]" value="{{ $opt }}" onchange="mettreAJour()" class="accent-[#009EE3]">
                                <span class="text-sm">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 4 : Observations --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button onclick="toggleSection('s4')" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-200 text-gray-600 text-xs font-black flex items-center justify-center">4</span>
                        <span class="font-bold text-gray-800">Observations</span>
                    </div>
                    <span id="arrow-s4" class="text-gray-400 text-sm">▼</span>
                </button>
                <div id="section-s4" class="hidden px-5 pb-5">
                    <textarea id="observations" oninput="mettreAJour()" rows="4"
                              placeholder="Type d'événement, puissance totale nécessaire, contraintes de lieu..."
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
<script>
// ================================================================
// CONFIGURATEUR COFFRET ÉVÉNEMENTIEL — BALS (JS inline)
// ================================================================

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

function mettreAJour() {
    var societe      = (document.getElementById('societe')      || {}).value || '';
    var contact      = (document.getElementById('contact')      || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire      = (document.getElementById('affaire')      || {}).value || '';
    var email        = (document.getElementById('email')        || {}).value || '';
    var observations = (document.getElementById('observations') || {}).value || '';

    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');
    var montage    = montageEl  ? montageEl.value  : '';
    var materiau   = materiauEl ? materiauEl.value : '';
    var ip         = ipEl       ? ipEl.value       : '';

    var sorties     = Array.from(document.querySelectorAll('input[name="prot_tete[]"]:checked')).map(function(el) { return el.value; });
    var protections = Array.from(document.querySelectorAll('input[name="prot_prises[]"]:checked')).map(function(el) { return el.value; });

    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = observations.length;

    var champs = [societe, email, montage, materiau, ip].map(function(v) { return v ? 1 : 0; });
    var remplis = champs.reduce(function(a, b) { return a + b; }, 0);
    var pct = Math.round(remplis / champs.length * 100);

    var barre = document.getElementById('progression-barre');
    var texte = document.getElementById('progression-texte');
    if (barre) barre.style.width = pct + '%';
    if (texte) texte.textContent = '(' + pct + '%)';

    var zone = document.getElementById('resume-zone');
    if (!zone) return;

    if (remplis === 0 && sorties.length === 0) {
        zone.innerHTML = '<p class="text-bals-blue font-bold text-sm opacity-40">Configurez votre coffret</p>'
                       + '<p class="text-gray-400 text-xs mt-1">Les informations apparaîtront ici</p>';
        var btns = document.getElementById('boutons-action');
        if (btns) btns.classList.add('hidden');
        return;
    }

    var html = '<div class="w-full text-left space-y-3">';
    html += '<div class="bg-bals-blue text-white rounded-lg px-3 py-2 text-sm font-bold text-center">Coffret Événementiel</div>';
    if (societe)      html += '<p class="text-xs"><span class="text-gray-400">Société :</span> <span class="font-bold text-gray-700">' + societe + '</span></p>';
    if (contact)      html += '<p class="text-xs"><span class="text-gray-400">Contact :</span> <span class="font-bold text-gray-700">' + contact + '</span></p>';
    if (installateur) html += '<p class="text-xs"><span class="text-gray-400">Installateur :</span> <span class="font-bold text-gray-700">' + installateur + '</span></p>';
    if (affaire)      html += '<p class="text-xs"><span class="text-gray-400">Affaire :</span> <span class="font-bold text-gray-700">' + affaire + '</span></p>';
    if (email)        html += '<p class="text-xs"><span class="text-gray-400">Email :</span> <span class="font-bold text-gray-700">' + email + '</span></p>';
    if (montage || materiau || ip) {
        html += '<div class="border-t border-gray-100 pt-2 space-y-1">';
        if (montage)  html += '<p class="text-xs"><span class="text-gray-400">Montage :</span> <span class="font-bold text-gray-700">' + montage + '</span></p>';
        if (materiau) html += '<p class="text-xs"><span class="text-gray-400">Matériau :</span> <span class="font-bold text-gray-700">' + materiau + '</span></p>';
        if (ip)       html += '<p class="text-xs"><span class="text-gray-400">IP :</span> <span class="font-black text-bals-blue">' + ip + '</span></p>';
        html += '</div>';
    }
    if (sorties.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Sorties :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + sorties.join(', ') + '</p></div>';
    }
    if (protections.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Protections :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + protections.join(', ') + '</p></div>';
    }
    html += '</div>';

    zone.innerHTML = html;
    var btns = document.getElementById('boutons-action');
    if (btns) btns.classList.remove('hidden');
}

function copierResume() {
    var societe = (document.getElementById('societe') || {}).value || 'N/A';
    var email   = (document.getElementById('email')   || {}).value || 'N/A';
    var texte   = 'DEVIS BALS — COFFRET ÉVÉNEMENTIEL\nSociété : ' + societe + '\nEmail : ' + email;
    navigator.clipboard.writeText(texte).then(function() { alert('Résumé copié !'); });
}

function reinitialiser() {
    ['societe','contact','installateur','affaire','email','observations'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(r) { r.checked = false; });
    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = '0';
    mettreAJour();
}

function envoyerDevis() {
    var societe      = (document.getElementById('societe')      || {}).value || '';
    var contact      = (document.getElementById('contact')      || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire      = (document.getElementById('affaire')      || {}).value || '';
    var email        = (document.getElementById('email')        || {}).value || '';
    var observations = (document.getElementById('observations') || {}).value || '';

    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');
    var sorties     = Array.from(document.querySelectorAll('input[name="prot_tete[]"]:checked')).map(function(el) { return el.value; });
    var protections = Array.from(document.querySelectorAll('input[name="prot_prises[]"]:checked')).map(function(el) { return el.value; });

    var payload = {
        type_coffret: 'Coffret Événementiel',
        distributeur: societe,
        contact:      contact,
        installateur: installateur,
        affaire:      affaire,
        email:        email,
        observations: observations,
        donnees: {
            montage:            montageEl  ? montageEl.value  : '',
            materiau:           materiauEl ? materiauEl.value : '',
            ip:                 ipEl       ? ipEl.value       : '',
            protections_tete:   sorties,
            protections_prises: protections
        }
    };

    var sujet = encodeURIComponent('Demande de devis Coffret Événementiel' + (societe ? ' — ' + societe : ''));
    var corps = encodeURIComponent('Bonjour,\n\nVeuillez trouver ci-joint ma demande de devis pour un coffret événementiel.\n\nSociété : ' + societe);
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

document.addEventListener('DOMContentLoaded', function() { mettreAJour(); });
</script>
@endsection
