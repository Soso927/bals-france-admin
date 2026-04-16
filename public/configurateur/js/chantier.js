// ================================================================
// CONFIGURATEUR CHANTIER / INDUSTRIE — BALS
// Fonctions partagées par chantier.blade.php et industrie.blade.php
// ================================================================

// ── Accordéons ──────────────────────────────────────────────────
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

// ── Contrôle des quantités ───────────────────────────────────────
function changerQte(btn, direction) {
    var span   = btn.parentElement.querySelector('span');
    var valeur = parseInt(span.textContent) + direction;
    if (valeur < 0) valeur = 0;
    span.textContent = valeur;
    mettreAJour();
}

// ── Lecture des prises ───────────────────────────────────────────
function lirePrises() {
    var prises = [];
    document.querySelectorAll('#section-s3 span[data-type]').forEach(function(span) {
        var qte = parseInt(span.textContent);
        if (qte > 0) {
            var type     = span.dataset.type;
            var brochage = span.dataset.brochage || '';
            var selectTension = document.querySelector(
                '#section-s3 select[data-type="' + type + '"]'
                + (brochage ? '[data-brochage="' + brochage + '"]' : '')
                + '[data-field="tension"]'
            );
            prises.push({
                type:     type,
                brochage: brochage,
                qte:      qte,
                tension:  selectTension ? selectTension.value : ''
            });
        }
    });
    return prises;
}

// ── Mise à jour du résumé ────────────────────────────────────────
function mettreAJour() {
    var societe     = (document.getElementById('societe')     || {}).value || '';
    var contact     = (document.getElementById('contact')     || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire     = (document.getElementById('affaire')     || {}).value || '';
    var email       = (document.getElementById('email')       || {}).value || '';

    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');

    var montage  = montageEl  ? montageEl.value  : '';
    var materiau = materiauEl ? materiauEl.value : '';
    var ip       = ipEl       ? ipEl.value       : '';

    var prises = lirePrises();

    var protTeteCoches = Array.from(
        document.querySelectorAll('input[name="prot_tete[]"]:checked')
    ).map(function(el) { return el.value; });

    var protPrisesCoches = Array.from(
        document.querySelectorAll('input[name="prot_prises[]"]:checked')
    ).map(function(el) { return el.value; });

    var observations = (document.getElementById('observations') || {}).value || '';
    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = observations.length;

    // Type de coffret actif
    var typeBouton = document.querySelector('.btn-type.bg-bals-blue');
    var typeCoffret = typeBouton ? typeBouton.dataset.type : '';

    // Calcul progression
    var champs = [
        societe     ? 1 : 0,
        contact     ? 1 : 0,
        installateur ? 1 : 0,
        email       ? 1 : 0,
        typeCoffret ? 1 : 0,
        montage     ? 1 : 0,
        materiau    ? 1 : 0,
        ip          ? 1 : 0,
        protTeteCoches.length   > 0 ? 1 : 0,
        protPrisesCoches.length > 0 ? 1 : 0,
    ];
    var champsRemplis = champs.reduce(function(a, b) { return a + b; }, 0);
    var pourcentage   = Math.round(champsRemplis / champs.length * 100);

    var barre = document.getElementById('progression-barre');
    var texte = document.getElementById('progression-texte');
    if (barre) barre.style.width = pourcentage + '%';
    if (texte) texte.textContent = '(' + pourcentage + '%)';

    // Résumé
    var zoneResume = document.getElementById('resume-zone');
    if (!zoneResume) return;

    if (champsRemplis === 0 && prises.length === 0) {
        zoneResume.innerHTML = '<p class="text-bals-blue font-bold text-sm opacity-40">Configurez votre coffret</p>'
                             + '<p class="text-gray-400 text-xs mt-1">Les informations apparaîtront ici</p>';
        var btns = document.getElementById('boutons-action');
        if (btns) btns.classList.add('hidden');
        return;
    }

    var html = '<div class="w-full text-left space-y-3">';
    if (typeCoffret) {
        html += '<div class="bg-bals-blue text-white rounded-lg px-3 py-2 text-sm font-bold text-center">' + typeCoffret + '</div>';
    }
    if (societe || contact || installateur || affaire || email) {
        html += '<div class="space-y-1">';
        if (societe)      html += '<p class="text-xs"><span class="text-gray-400">Société :</span> <span class="font-bold text-gray-700">' + societe + '</span></p>';
        if (contact)      html += '<p class="text-xs"><span class="text-gray-400">Contact :</span> <span class="font-bold text-gray-700">' + contact + '</span></p>';
        if (installateur) html += '<p class="text-xs"><span class="text-gray-400">Installateur :</span> <span class="font-bold text-gray-700">' + installateur + '</span></p>';
        if (affaire)      html += '<p class="text-xs"><span class="text-gray-400">Affaire :</span> <span class="font-bold text-gray-700">' + affaire + '</span></p>';
        if (email)        html += '<p class="text-xs"><span class="text-gray-400">Email :</span> <span class="font-bold text-gray-700">' + email + '</span></p>';
        html += '</div>';
    }
    if (montage || materiau || ip) {
        html += '<div class="border-t border-gray-100 pt-2 space-y-1">';
        if (montage)  html += '<p class="text-xs"><span class="text-gray-400">Montage :</span> <span class="font-bold text-gray-700">' + montage + '</span></p>';
        if (materiau) html += '<p class="text-xs"><span class="text-gray-400">Matériau :</span> <span class="font-bold text-gray-700">' + materiau + '</span></p>';
        if (ip)       html += '<p class="text-xs"><span class="text-gray-400">IP :</span> <span class="font-black text-bals-blue">' + ip + '</span></p>';
        html += '</div>';
    }
    if (prises.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Prises :</p>';
        prises.forEach(function(p) {
            html += '<p class="text-xs font-bold text-gray-700">• ' + p.qte + 'x ' + p.type
                  + (p.brochage ? ' / ' + p.brochage : '')
                  + (p.tension  ? ' → ' + p.tension  : '') + '</p>';
        });
        html += '</div>';
    }
    if (protTeteCoches.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Protection tête :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + protTeteCoches.join(', ') + '</p></div>';
    }
    if (protPrisesCoches.length > 0) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Protection prises :</p>'
              + '<p class="text-xs font-bold text-gray-700">' + protPrisesCoches.join(', ') + '</p></div>';
    }
    if (observations.trim()) {
        html += '<div class="border-t border-gray-100 pt-2"><p class="text-xs text-gray-400 font-bold mb-1">Observations :</p>'
              + '<p class="text-xs text-gray-600 italic">' + observations.substring(0, 80) + (observations.length > 80 ? '…' : '') + '</p></div>';
    }
    html += '</div>';

    zoneResume.innerHTML = html;
    var btns = document.getElementById('boutons-action');
    if (btns) btns.classList.remove('hidden');
}

// ── Copier résumé ────────────────────────────────────────────────
function copierResume() {
    var typeBouton = document.querySelector('.btn-type.bg-bals-blue');
    var typeCoffret = typeBouton ? typeBouton.dataset.type : 'Coffret BALS';

    var societe      = (document.getElementById('societe')      || {}).value || 'N/A';
    var contact      = (document.getElementById('contact')      || {}).value || 'N/A';
    var installateur = (document.getElementById('installateur') || {}).value || 'N/A';
    var affaire      = (document.getElementById('affaire')      || {}).value || 'N/A';
    var email        = (document.getElementById('email')        || {}).value || 'N/A';
    var montageEl    = document.querySelector('input[name="montage"]:checked');
    var ipEl         = document.querySelector('input[name="ip"]:checked');

    var texte = 'DEVIS BALS — ' + typeCoffret.toUpperCase() + '\n'
              + 'Société : '     + societe      + '\n'
              + 'Contact : '     + contact      + '\n'
              + 'Installateur : '+ installateur + '\n'
              + 'Affaire : '     + affaire      + '\n'
              + 'Email : '       + email        + '\n'
              + 'Montage : '     + (montageEl ? montageEl.value : 'N/A') + '\n'
              + 'IP : '          + (ipEl ? ipEl.value : 'N/A');

    navigator.clipboard.writeText(texte).then(function() {
        alert('Résumé copié dans le presse-papiers !');
    });
}

// ── Envoyer le devis (POST API + mailto) ────────────────────────
function envoyerDevis() {
    var typeBouton = document.querySelector('.btn-type.bg-bals-blue');
    var typeCoffret = typeBouton ? typeBouton.dataset.type : 'Coffret BALS';

    var societe      = (document.getElementById('societe')      || {}).value || '';
    var contact      = (document.getElementById('contact')      || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire      = (document.getElementById('affaire')      || {}).value || '';
    var email        = (document.getElementById('email')        || {}).value || '';
    var observations = (document.getElementById('observations') || {}).value || '';

    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');

    var protTeteCoches = Array.from(
        document.querySelectorAll('input[name="prot_tete[]"]:checked')
    ).map(function(el) { return el.value; });

    var protPrisesCoches = Array.from(
        document.querySelectorAll('input[name="prot_prises[]"]:checked')
    ).map(function(el) { return el.value; });

    var prises = lirePrises();

    var payload = {
        type_coffret: typeCoffret,
        distributeur: societe,
        contact:      contact,
        installateur: installateur,
        affaire:      affaire,
        email:        email,
        observations: observations,
        donnees: {
            montage:           montageEl  ? montageEl.value  : '',
            materiau:          materiauEl ? materiauEl.value : '',
            ip:                ipEl       ? ipEl.value       : '',
            prises:            prises,
            protections_tete:  protTeteCoches,
            protections_prises: protPrisesCoches
        }
    };

    var sujet = encodeURIComponent('Demande de devis ' + typeCoffret + (societe ? ' — ' + societe : ''));
    var corps = encodeURIComponent('Bonjour,\n\nVeuillez trouver ci-joint ma demande de devis.\n\nSociété : ' + societe);
    var mailtoUrl = 'mailto:info@bals-france.fr?subject=' + sujet + '&body=' + corps;

    // Enregistrement en base puis ouverture du mailto
    fetch('/api/devis', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?
                            document.querySelector('meta[name="csrf-token"]').content : ''
        },
        body: JSON.stringify(payload)
    })
    .then(function() {
        window.location.href = mailtoUrl;
    })
    .catch(function() {
        // Même en cas d'erreur API, on ouvre quand même le mailto
        window.location.href = mailtoUrl;
    });
}

// ── Réinitialiser ────────────────────────────────────────────────
function reinitialiser() {
    ['societe','contact','installateur','affaire','email','observations'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(r) {
        r.checked = false;
    });
    document.querySelectorAll('#section-s3 span[data-type]').forEach(function(span) {
        span.textContent = '0';
    });
    document.querySelectorAll('#section-s3 select[data-field="tension"]').forEach(function(sel) {
        sel.value = '';
    });
    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = '0';
    mettreAJour();
}

// ── Init ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    mettreAJour();
});