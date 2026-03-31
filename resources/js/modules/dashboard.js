/**
 * ═══════════════════════════════════════════════════════════════════
 * DASHBOARD MODULE — Tableau de bord admin Bals France
 * ═══════════════════════════════════════════════════════════════════
 *
 * Ce module remplace l'ancien code JavaScript qui était directement
 * dans la balise <script> de dashboard.blade.php.
 *
 * CHANGEMENT FONDAMENTAL :
 *   Avant → les données venaient de localStorage (stockage navigateur)
 *   Après → les données viennent de MySQL via l'API Laravel (fetch)
 *
 * Cela signifie que toute modification faite par l'admin est
 * maintenant sauvegardée côté serveur et visible sur tous les
 * appareils, pas seulement sur la machine de l'administrateur.
 * ═══════════════════════════════════════════════════════════════════
 */

export const DashboardModule = {

    /**
     * ─────────────────────────────────────────────────────────────
     * INIT — Point d'entrée du module
     * ─────────────────────────────────────────────────────────────
     * Cette méthode est appelée automatiquement par app.js quand
     * la page détectée est "dashboard" (via l'attribut data-page).
     * Elle lance le premier chargement des données depuis l'API.
     */
    init() {
        console.log('Dashboard module initialisé');
        // On charge immédiatement les données depuis le serveur
        // au lieu de lire localStorage au chargement de la page
        this.chargerEtAfficher();
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * CHARGER ET AFFICHER — Cœur du module
     * ─────────────────────────────────────────────────────────────
     * Envoie une requête GET vers /admin/regions qui appelle
     * RegionController@index dans Laravel.
     * Laravel interroge MySQL et retourne un tableau JSON de régions,
     * chacune contenant ses agents dans un sous-tableau "agents".
     *
     * Structure de la réponse JSON attendue :
     * [
     *   {
     *     "id": 1,
     *     "nom": "Île-de-France",
     *     "zone": "ÎLE-DE-FRANCE",
     *     "agents": [
     *       { "id": 1, "nom": "Alexis ANDRADE SILVA", "email": "...", ... },
     *       { "id": 2, "nom": "Arnaud JOUSSELIN", ... }
     *     ]
     *   },
     *   ...
     * ]
     */
    async chargerEtAfficher() {
        try {
            const reponse = await fetch('/admin/regions');

            // Si le serveur répond avec une erreur HTTP (401, 403, 500...),
            // on lève une exception pour tomber dans le catch
            if (!reponse.ok) {
                throw new Error('Erreur serveur : ' + reponse.status);
            }

            // .json() transforme la réponse texte brute en tableau JavaScript
            const regions = await reponse.json();

            // On met à jour les 3 cartes de statistiques en haut du dashboard
            this.mettreAJourStats(regions);

            // On construit et injecte le HTML de toutes les régions et agents
            this.afficherRegions(regions);

        } catch (erreur) {
            console.error('Impossible de charger les données :', erreur);
        }
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * METTRE À JOUR LES STATISTIQUES
     * ─────────────────────────────────────────────────────────────
     * Calcule les chiffres affichés dans les 3 cartes du haut :
     * total d'agents, nombre de régions, agences uniques.
     *
     * @param {Array} regions — tableau de régions retourné par l'API
     */
    mettreAJourStats(regions) {
        // Le nombre de régions est simplement la longueur du tableau
        const nbRegions = regions.length;

        let nbAgents = 0;
        // Un Set est une collection qui refuse les doublons,
        // idéal pour compter les agences uniques
        const agencesUniques = new Set();

        regions.forEach(region => {
            nbAgents += region.agents.length;
            region.agents.forEach(agent => {
                if (agent.agence) agencesUniques.add(agent.agence);
            });
        });

        document.getElementById('stat-agents').textContent  = nbAgents;
        document.getElementById('stat-regions').textContent = nbRegions;
        document.getElementById('stat-agences').textContent = agencesUniques.size;
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * AFFICHER LES RÉGIONS ET LEURS AGENTS
     * ─────────────────────────────────────────────────────────────
     * Construit dynamiquement le HTML de la liste des agents.
     *
     * DIFFÉRENCE IMPORTANTE avec l'ancienne version :
     * Chaque agent possède maintenant un vrai "id" fourni par MySQL.
     * On passe cet id aux fonctions sauvegarder() et supprimer()
     * pour que les requêtes PUT et DELETE sachent quel agent cibler.
     * Avant, on utilisait la position dans le tableau (index),
     * ce qui était fragile car l'index change si on supprime un agent.
     *
     * @param {Array} regions — tableau de régions retourné par l'API
     */
    afficherRegions(regions) {
        const conteneur = document.getElementById('liste-regions');
        conteneur.innerHTML = '';

        // On trie les régions par nom alphabétique
        const regionsTriees = [...regions].sort((a, b) => a.nom.localeCompare(b.nom));

        // On remplit aussi le menu déroulant du formulaire d'ajout
        const select = document.getElementById('new-region');
        select.innerHTML = '';

        regionsTriees.forEach(region => {
            // Ajout de l'option dans le select du formulaire d'ajout
            const opt = document.createElement('option');
            opt.value       = region.id; // On utilise l'ID MySQL, pas le nom
            opt.textContent = region.nom;
            select.appendChild(opt);

            // Construction du HTML de la section région
            let html = `
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold" style="color: var(--bals-black);">
                            ${region.nom}
                            <span class="text-xs font-semibold uppercase tracking-wider ml-2"
                                  style="color: var(--bals-blue);">
                                ${region.zone}
                            </span>
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full"
                              style="background: rgba(0,149,218,0.08); color: var(--bals-blue);">
                            ${region.agents.length} agent${region.agents.length > 1 ? 's' : ''}
                        </span>
                    </div>
            `;

            region.agents.forEach((agent, index) => {
                // On utilise agent.id (ID MySQL) comme identifiant unique.
                // C'est crucial : c'est cet ID qu'on enverra à l'API
                // dans les requêtes PUT /admin/agents/{id} et DELETE.
                html += `
                    <div class="agent-card">

                        <!-- MODE LECTURE -->
                        <div id="view-${agent.id}">
                            ${agent.agence
                                ? `<div class="text-xs font-bold uppercase tracking-wider mb-1"
                                        style="color: var(--bals-blue);">${agent.agence}</div>`
                                : ''
                            }
                            <div class="text-sm font-bold">${agent.nom}</div>
                            <div class="text-xs mt-1" style="color: var(--text-muted);">${agent.depts || ''}</div>
                            <div class="text-xs mt-1" style="color: var(--bals-blue);">${agent.tel || ''} · ${agent.email}</div>
                            <div class="flex gap-2 mt-3">
                                <button onclick="window.dashboardEditer(${agent.id})"
                                        class="text-xs font-semibold px-3 py-1.5 rounded-md"
                                        style="background: rgba(0,149,218,0.08); color: var(--bals-blue);">
                                    Modifier
                                </button>
                                <button onclick="window.dashboardSupprimer(${agent.id})"
                                        class="text-xs font-semibold px-3 py-1.5 rounded-md"
                                        style="background: rgba(237,28,36,0.08); color: var(--bals-red);">
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <!-- MODE ÉDITION (caché par défaut) -->
                        <div id="edit-${agent.id}" class="hidden">
                            <input type="text"   id="f-agence-${agent.id}"  class="edit-input" value="${agent.agence || ''}" placeholder="Agence (optionnel)">
                            <input type="text"   id="f-nom-${agent.id}"     class="edit-input" value="${agent.nom}"           placeholder="Nom complet *">
                            <input type="text"   id="f-depts-${agent.id}"   class="edit-input" value="${agent.depts || ''}"   placeholder="Départements">
                            <input type="text"   id="f-tel-${agent.id}"     class="edit-input" value="${agent.tel || ''}"     placeholder="Téléphone">
                            <input type="text"   id="f-telRaw-${agent.id}"  class="edit-input" value="${agent.tel_raw || ''}" placeholder="Tél. brut (+33...)">
                            <input type="email"  id="f-email-${agent.id}"   class="edit-input" value="${agent.email}"         placeholder="Email *">
                            <div class="flex gap-2 mt-2">
                                <button onclick="window.dashboardSauvegarder(${agent.id})"
                                        class="text-white text-xs font-bold px-4 py-2 rounded-md"
                                        style="background: #059669;">
                                    Sauvegarder
                                </button>
                                <button onclick="window.dashboardAnnuler(${agent.id})"
                                        class="text-xs font-bold px-4 py-2 rounded-md border"
                                        style="color: var(--text-muted); border-color: var(--border);">
                                    Annuler
                                </button>
                            </div>
                        </div>

                    </div>
                `;
            });

            html += `</div>`;
            conteneur.innerHTML += html;
        });
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * ÉDITER / ANNULER — Bascule entre mode lecture et mode édition
     * ─────────────────────────────────────────────────────────────
     */
    editer(agentId) {
        document.getElementById('view-' + agentId).classList.add('hidden');
        document.getElementById('edit-' + agentId).classList.remove('hidden');
    },

    annuler(agentId) {
        document.getElementById('edit-' + agentId).classList.add('hidden');
        document.getElementById('view-' + agentId).classList.remove('hidden');
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * SAUVEGARDER — Requête PUT vers /admin/agents/{id}
     * ─────────────────────────────────────────────────────────────
     * PUT est la méthode HTTP standard pour une mise à jour complète.
     * Laravel appelle AgentController@update avec l'ID dans l'URL.
     *
     * Le token CSRF est obligatoire pour toute requête qui modifie
     * des données (POST, PUT, DELETE). Sans lui, Laravel rejette
     * la requête avec une erreur 419 "Page Expired".
     */
    async sauvegarder(agentId) {
        const nom   = document.getElementById('f-nom-' + agentId).value.trim();
        const email = document.getElementById('f-email-' + agentId).value.trim();

        if (!nom || !email) {
            alert('Le nom et l\'email sont obligatoires.');
            return;
        }

        try {
            const reponse = await fetch('/admin/agents/' + agentId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    // Ce token est généré par Laravel et placé dans
                    // la balise <meta name="csrf-token"> du Blade
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    agence:  document.getElementById('f-agence-' + agentId).value.trim() || null,
                    nom:     nom,
                    depts:   document.getElementById('f-depts-' + agentId).value.trim(),
                    tel:     document.getElementById('f-tel-' + agentId).value.trim(),
                    tel_raw: document.getElementById('f-telRaw-' + agentId).value.trim(),
                    email:   email,
                })
            });

            if (reponse.ok) {
                // On recharge tout depuis le serveur pour
                // s'assurer que l'affichage reflète la BDD
                await this.chargerEtAfficher();
                this.toast('Modifications enregistrées !');
            }

        } catch (erreur) {
            console.error('Erreur lors de la sauvegarde :', erreur);
        }
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * SUPPRIMER — Requête DELETE vers /admin/agents/{id}
     * ─────────────────────────────────────────────────────────────
     */
    async supprimer(agentId) {
        if (!confirm('Supprimer cet agent ?')) return;

        try {
            const reponse = await fetch('/admin/agents/' + agentId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });

            if (reponse.ok) {
                await this.chargerEtAfficher();
                this.toast('Agent supprimé.');
            }

        } catch (erreur) {
            console.error('Erreur lors de la suppression :', erreur);
        }
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * AJOUTER UN AGENT — Requête POST vers /admin/agents
     * ─────────────────────────────────────────────────────────────
     * Laravel appelle AgentController@store.
     * On envoie region_id (l'ID MySQL de la région) plutôt que
     * le nom de la région comme c'était le cas avec localStorage.
     */
    async ajouterAgent() {
        const nom   = document.getElementById('new-nom').value.trim();
        const email = document.getElementById('new-email').value.trim();

        if (!nom || !email) {
            alert('Le nom et l\'email sont obligatoires.');
            return;
        }

        try {
            const reponse = await fetch('/admin/agents', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    region_id: document.getElementById('new-region').value,
                    agence:    document.getElementById('new-agence').value.trim()  || null,
                    nom:       nom,
                    depts:     document.getElementById('new-depts').value.trim(),
                    tel:       document.getElementById('new-tel').value.trim(),
                    tel_raw:   document.getElementById('new-telRaw').value.trim(),
                    email:     email,
                })
            });

            if (reponse.ok) {
                this.cacherFormulaireAjout();
                await this.chargerEtAfficher();
                this.toast('Nouvel agent ajouté !');
            }

        } catch (erreur) {
            console.error('Erreur lors de l\'ajout :', erreur);
        }
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * FORMULAIRE D'AJOUT — Afficher / Cacher
     * ─────────────────────────────────────────────────────────────
     */
    afficherFormulaireAjout() {
        document.getElementById('formulaire-ajout').classList.remove('hidden');
    },

    cacherFormulaireAjout() {
        document.getElementById('formulaire-ajout').classList.add('hidden');
        ['new-agence','new-nom','new-depts','new-tel','new-telRaw','new-email'].forEach(id => {
            document.getElementById(id).value = '';
        });
    },


    /**
     * ─────────────────────────────────────────────────────────────
     * TOAST — Message de confirmation
     * ─────────────────────────────────────────────────────────────
     */
    toast(message) {
        const el = document.getElementById('toast');
        el.textContent = message;
        el.classList.add('visible');
        setTimeout(() => el.classList.remove('visible'), 2000);
    }
};


/**
 * ═══════════════════════════════════════════════════════════════════
 * EXPOSITION GLOBALE DES FONCTIONS
 * ═══════════════════════════════════════════════════════════════════
 *
 * Les boutons dans le HTML généré dynamiquement appellent des fonctions
 * via onclick="window.dashboardSauvegarder(id)".
 * Or, les modules ES6 (import/export) ne sont pas accessibles
 * directement depuis le HTML — ils vivent dans leur propre scope.
 * On doit donc exposer ces méthodes sur l'objet window pour
 * que les onclick puissent les atteindre.
 */
window.dashboardEditer           = (id) => DashboardModule.editer(id);
window.dashboardAnnuler          = (id) => DashboardModule.annuler(id);
window.dashboardSauvegarder      = (id) => DashboardModule.sauvegarder(id);
window.dashboardSupprimer        = (id) => DashboardModule.supprimer(id);
window.afficherFormulaireAjout   = ()   => DashboardModule.afficherFormulaireAjout();
window.cacherFormulaireAjout     = ()   => DashboardModule.cacherFormulaireAjout();
window.ajouterAgent              = ()   => DashboardModule.ajouterAgent();

export default DashboardModule;