// ================================================================
// CONFIGURATEUR COFFRET D'ÉTAGE — BALS
// Extrait depuis etage.blade.php pour séparer JS et HTML.
// ================================================================

// ── Accordéon : ouvrir / fermer une section ─────────────────────
// Appelé via onclick="toggleSection('s1')" dans chaque carte section.
function toggleSection(id) {
    var section = document.getElementById('section-' + id);
    var arrow   = document.getElementById('arrow-' + id);
    if (!section) return;

    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');  // ouvre la section
        if (arrow) arrow.textContent = '▲';
    } else {
        section.classList.add('hidden');     // ferme la section
        if (arrow) arrow.textContent = '▼';
    }
}

// ── Mise à jour du panneau résumé et de la barre de progression ──
// Appelée à chaque modification d'un champ (oninput / onchange).
function mettreAJour() {

    // Lecture des champs texte d'identification
    var societe      = (document.getElementById('societe')      || {}).value || '';
    var contact      = (document.getElementById('contact')      || {}).value || '';
    var installateur = (document.getElementById('installateur') || {}).value || '';
    var affaire      = (document.getElementById('affaire')      || {}).value || '';
    var email        = (document.getElementById('email')        || {}).value || '';
    var observations = (document.getElementById('observations') || {}).value || '';

    // Lecture des boutons radio cochés
    var montageEl  = document.querySelector('input[name="montage"]:checked');
    var materiauEl = document.querySelector('input[name="materiau"]:checked');
    var ipEl       = document.querySelector('input[name="ip"]:checked');
    var montage    = montageEl  ? montageEl.value  : '';
    var materiau   = materiauEl ? materiauEl.value : '';
    var ip         = ipEl       ? ipEl.value       : '';

    // Lecture des cases à cocher (retourne un tableau de valeurs)
    var sorties     = Array.from(document.querySelectorAll('input[name="prot_tete[]"]:checked')).map(function(el) { return el.value; });
    var protections = Array.from(document.querySelectorAll('input[name="prot_prises[]"]:checked')).map(function(el) { return el.value; });

    // Compteur de caractères dans le textarea Observations
    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = observations.length;

    // Calcul du pourcentage de complétion (5 champs principaux)
    var champs  = [societe, email, montage, materiau, ip].map(function(v) { return v ? 1 : 0; });
    var remplis = champs.reduce(function(a, b) { return a + b; }, 0);
    var pct     = Math.round(remplis / champs.length * 100);

    // Mise à jour de la barre de progression
    var barre = document.getElementById('progression-barre');
    var texte = document.getElementById('progression-texte');
    if (barre) barre.style.width = pct + '%';
    if (texte) texte.textContent = '(' + pct + '%)';

    var zone = document.getElementById('resume-zone');
    if (!zone) return;

    // Si aucun champ rempli : afficher le message de départ et cacher les boutons
    if (remplis === 0 && sorties.length === 0) {
        zone.innerHTML = '<p class="text-bals-blue font-bold text-sm opacity-40">Configurez votre coffret</p>'
                       + '<p class="text-gray-400 text-xs mt-1">Les informations apparaîtront ici</p>';
        var btns = document.getElementById('boutons-action');
        if (btns) btns.classList.add('hidden');
        return;
    }

    // Construction du HTML du résumé
    var html = '<div class="w-full text-left space-y-3">';
    html += '<div class="bg-bals-blue text-white rounded-lg px-3 py-2 text-sm font-bold text-center">Coffret d\'Étage</div>';

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

    // Afficher les boutons d'action dès qu'il y a du contenu
    var btns = document.getElementById('boutons-action');
    if (btns) btns.classList.remove('hidden');
}

// ── Copier le résumé dans le presse-papiers ──────────────────────
function copierResume() {
    var societe = (document.getElementById('societe') || {}).value || 'N/A';
    var email   = (document.getElementById('email')   || {}).value || 'N/A';
    var texte   = "DEVIS BALS — COFFRET D'ÉTAGE\nSociété : " + societe + "\nEmail : " + email;
    navigator.clipboard.writeText(texte).then(function() { alert('Résumé copié !'); });
}

// ── Réinitialiser tous les champs du formulaire ──────────────────
function reinitialiser() {
    ['societe','contact','installateur','affaire','email','observations'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(r) {
        r.checked = false;
    });
    var nbCar = document.getElementById('nb-caracteres');
    if (nbCar) nbCar.textContent = '0';
    mettreAJour();
}

// ── Envoyer le devis via API + ouverture du client mail ──────────
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

    // Données envoyées à l'API /api/devis
    var payload = {
        type_coffret: "Coffret d'Étage",
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
            sorties:            sorties,
            protections_tete:   sorties,
            protections_prises: protections
        }
    };

    var sujet = encodeURIComponent("Demande de devis Coffret d'Étage" + (societe ? ' — ' + societe : ''));
    var corps = encodeURIComponent("Bonjour,\n\nVeuillez trouver ci-joint ma demande de devis pour un coffret d'étage.\n\nSociété : " + societe);
    var mailtoUrl = 'mailto:info@bals-france.fr?subject=' + sujet + '&body=' + corps;

    // POST API puis ouverture du client mail (même en cas d'erreur API)
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

// ── Initialisation au chargement de la page ──────────────────────
document.addEventListener('DOMContentLoaded', function() { mettreAJour(); });
