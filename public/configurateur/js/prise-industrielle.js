// ================================================================
// CONFIGURATEUR PRISE INDUSTRIELLE — BALS
// ================================================================

// ── Accordéons (via data-action) ────────────────────────────────
// Accordéon : ouvrir/fermer via data-action="toggle-section" (prise-industrielle conserve ce pattern)
document.addEventListener('click', function(e) {
    var toggleBtn = e.target.closest('[data-action="toggle-section"]');
    if (toggleBtn) {
        var id      = toggleBtn.dataset.sectionId;
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
});

// ── Type de produit : afficher/masquer la zone montage ──────────
document.addEventListener('change', function(e) {
    if (e.target.matches('input[name="produit"]')) {
        var zoneMontage = document.getElementById('zone-montage');
        var val = e.target.value;
        if (zoneMontage) {
            if (val === 'Socle de prise' || val === 'Socle connecteur') {
                zoneMontage.classList.remove('hidden');
            } else {
                zoneMontage.classList.add('hidden');
            }
        }
        mettreAJour();
    }
    if (e.target.matches('[data-action="mettre-a-jour"]')) {
        mettreAJour();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.matches('[data-action="mettre-a-jour"]')) {
        mettreAJour();
    }
});

// Contrôle de la quantité via data-action="changer-qte"
// Les boutons d'action (Envoyer, Copier, Réinitialiser) utilisent maintenant
// onclick directement via le partial panneau-resume.blade.php.
document.addEventListener('click', function(e) {
    var deltaBtn = e.target.closest('[data-action="changer-qte"]');
    if (deltaBtn) {
        var delta = parseInt(deltaBtn.dataset.delta || '0');
        var input = document.getElementById('quantite');
        if (input) {
            var val = parseInt(input.value || 1) + delta;
            input.value = Math.max(1, val);
            mettreAJour();
        }
    }
});

// ── Mise à jour du résumé ────────────────────────────────────────
function mettreAJour() {
    var societe      = (document.getElementById('societe')      || {}).value || '';
    var contact      = (document.getElementById('contact')      || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire      = (document.getElementById('affaire')      || {}).value || '';
    var email        = (document.getElementById('email')        || {}).value || '';
    var observations = (document.getElementById('observations') || {}).value || '';
    var quantite     = parseInt((document.getElementById('quantite') || {}).value || 1);

    var produitEl    = document.querySelector('input[name="produit"]:checked');
    var montageEl    = document.querySelector('input[name="montage_type"]:checked');
    var tensionEl    = document.querySelector('input[name="tension"]:checked');
    var ampEl        = document.querySelector('input[name="amp"]:checked');
    var polEl        = document.querySelector('input[name="pol"]:checked');
    var ipEl         = document.querySelector('input[name="ip"]:checked');

    var produit  = produitEl  ? produitEl.value  : '';
    var montage  = montageEl  ? montageEl.value  : '';
    var tension  = tensionEl  ? tensionEl.value  : '';
    var amp      = ampEl      ? ampEl.value      : '';
    var pol      = polEl      ? polEl.value      : '';
    var ip       = ipEl       ? ipEl.value       : '';

    var champs = [societe, email, produit, tension, amp, pol, ip].map(function(v) { return v ? 1 : 0; });
    var remplis = champs.reduce(function(a, b) { return a + b; }, 0);
    var pct = Math.round(remplis / champs.length * 100);

    var barre = document.getElementById('progression-barre');
    var texte = document.getElementById('progression-texte');
    if (barre) barre.style.width = pct + '%';
    if (texte) texte.textContent = '(' + pct + '%)';

    var zone = document.getElementById('resume-zone');
    if (!zone) return;

    if (remplis === 0) {
        zone.innerHTML = '<p class="text-bals-blue font-bold text-sm opacity-40">En attente de saisie...</p>';
        var btns = document.getElementById('boutons-action');
        if (btns) btns.classList.add('hidden');
        return;
    }

    var html = '<div class="w-full text-left space-y-2 text-xs">';
    html += '<div class="bg-bals-blue text-white rounded-lg px-3 py-2 text-sm font-bold text-center">Prise Industrielle</div>';
    if (societe)  html += '<p><span class="text-gray-400">Société :</span> <span class="font-bold">' + societe + '</span></p>';
    if (contact)  html += '<p><span class="text-gray-400">Contact :</span> <span class="font-bold">' + contact + '</span></p>';
    if (email)    html += '<p><span class="text-gray-400">Email :</span> <span class="font-bold">' + email + '</span></p>';
    if (produit)  html += '<p class="border-t border-gray-100 pt-2"><span class="text-gray-400">Produit :</span> <span class="font-bold">' + produit + '</span></p>';
    if (montage)  html += '<p><span class="text-gray-400">Montage :</span> <span class="font-bold">' + montage + '</span></p>';
    if (tension)  html += '<p><span class="text-gray-400">Tension :</span> <span class="font-bold text-bals-blue">' + tension + '</span></p>';
    if (amp)      html += '<p><span class="text-gray-400">Intensité :</span> <span class="font-bold">' + amp + '</span></p>';
    if (pol)      html += '<p><span class="text-gray-400">Polarité :</span> <span class="font-bold">' + pol + '</span></p>';
    if (ip)       html += '<p><span class="text-gray-400">IP :</span> <span class="font-black text-bals-blue">' + ip + '</span></p>';
    html += '<p class="border-t border-gray-100 pt-2"><span class="text-gray-400">Quantité :</span> <span class="font-black text-xl text-gray-800">' + quantite + '</span></p>';
    if (observations.trim()) {
        html += '<p class="border-t border-gray-100 pt-2 text-gray-600 italic">' + observations.substring(0, 80) + (observations.length > 80 ? '…' : '') + '</p>';
    }
    html += '</div>';

    zone.innerHTML = html;
    var btns = document.getElementById('boutons-action');
    if (btns) btns.classList.remove('hidden');
}

// ── Copier résumé ────────────────────────────────────────────────
function copierResume() {
    var societe  = (document.getElementById('societe') || {}).value || 'N/A';
    var email    = (document.getElementById('email')   || {}).value || 'N/A';
    var ampEl    = document.querySelector('input[name="amp"]:checked');
    var polEl    = document.querySelector('input[name="pol"]:checked');
    var tensionEl = document.querySelector('input[name="tension"]:checked');

    var texte = 'DEVIS BALS — PRISE INDUSTRIELLE\n'
              + 'Société : '   + societe + '\n'
              + 'Email : '     + email + '\n'
              + 'Tension : '   + (tensionEl  ? tensionEl.value  : 'N/A') + '\n'
              + 'Intensité : ' + (ampEl      ? ampEl.value      : 'N/A') + '\n'
              + 'Polarité : '  + (polEl      ? polEl.value      : 'N/A');

    navigator.clipboard.writeText(texte).then(function() {
        alert('Résumé copié dans le presse-papiers !');
    }).catch(function() {
        alert('Impossible de copier automatiquement.');
    });
}

// ── Envoyer le devis (POST API + mailto) ────────────────────────
function envoyerDevis() {
    var societe      = (document.getElementById('societe')      || {}).value || '';
    var contact      = (document.getElementById('contact')      || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire      = (document.getElementById('affaire')      || {}).value || '';
    var email        = (document.getElementById('email')        || {}).value || '';
    var observations = (document.getElementById('observations') || {}).value || '';
    var quantite     = parseInt((document.getElementById('quantite') || {}).value || 1);

    var produitEl  = document.querySelector('input[name="produit"]:checked');
    var montageEl  = document.querySelector('input[name="montage_type"]:checked');
    var tensionEl  = document.querySelector('input[name="tension"]:checked');
    var ampEl      = document.querySelector('input[name="amp"]:checked');
    var polEl      = document.querySelector('input[name="pol"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');

    var payload = {
        type_coffret: 'Prise industrielle',
        distributeur: societe,
        contact:      contact,
        installateur: installateur,
        affaire:      affaire,
        email:        email,
        observations: observations,
        donnees: {
            produit:  produitEl  ? produitEl.value  : '',
            montage:  montageEl  ? montageEl.value  : '',
            tension:  tensionEl  ? tensionEl.value  : '',
            amp:      ampEl      ? ampEl.value      : '',
            polarite: polEl      ? polEl.value      : '',
            ip:       ipEl       ? ipEl.value       : '',
            quantite: quantite
        }
    };

    var sujet = encodeURIComponent('Demande de devis Prise Industrielle' + (societe ? ' — ' + societe : ''));
    var corps = encodeURIComponent('Bonjour,\n\nVeuillez trouver ci-joint ma demande de devis pour une prise industrielle.\n\nSociété : ' + societe);
    var mailtoUrl = 'mailto:info@bals-france.fr?subject=' + sujet + '&body=' + corps;

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
        window.location.href = mailtoUrl;
    });
}

// ── Réinitialiser ────────────────────────────────────────────────
function reinitialiser() {
    ['societe','contact','installateur','affaire','email','observations'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
    });
    var qte = document.getElementById('quantite');
    if (qte) qte.value = 1;
    document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(r) {
        r.checked = false;
    });
    var zoneMontage = document.getElementById('zone-montage');
    if (zoneMontage) zoneMontage.classList.add('hidden');
    mettreAJour();
}

// ── Init ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    mettreAJour();
});