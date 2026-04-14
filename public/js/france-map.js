/*
 * ╔══════════════════════════════════════════════════════════════════════╗
 * ║  france-map.js — Logique de la carte interactive Bals France        ║
 * ║                                                                      ║
 * ║  Ce script est responsable de :                                      ║
 * ║  1. Charger les données des agents depuis l'API Laravel              ║
 * ║  2. Charger le GeoJSON de France et dessiner la carte avec D3.js     ║
 * ║  3. Gérer les interactions (clic, survol, réinitialisation)          ║
 * ╚══════════════════════════════════════════════════════════════════════╝
 */

/*
 * TABLE DE CORRESPONDANCE — anciennes régions → nouvelles régions
 * Le fichier GeoJSON utilise les noms d'avant la réforme de 2016.
 * Cette table permet de faire la correspondance avec les noms actuels.
 */
const REGION_MAP = {
    'Alsace': 'Grand Est',
    'Champagne-Ardenne': 'Grand Est',
    'Lorraine': 'Grand Est',
    'Aquitaine': 'Nouvelle-Aquitaine',
    'Limousin': 'Nouvelle-Aquitaine',
    'Poitou-Charentes': 'Nouvelle-Aquitaine',
    'Auvergne': 'Auvergne-Rhône-Alpes',
    'Rhône-Alpes': 'Auvergne-Rhône-Alpes',
    'Bourgogne': 'Bourgogne-Franche-Comté',
    'Franche-Comté': 'Bourgogne-Franche-Comté',
    'Basse-Normandie': 'Normandie',
    'Haute-Normandie': 'Normandie',
    'Languedoc-Roussillon': 'Occitanie',
    'Midi-Pyrénées': 'Occitanie',
    'Nord-Pas-de-Calais': 'Hauts-de-France',
    'Picardie': 'Hauts-de-France',
    'Bretagne': 'Bretagne',
    'Centre': 'Centre-Val de Loire',
    'Corse': 'Corse',
    'Île-de-France': 'Île-de-France',
    'Pays de la Loire': 'Pays de la Loire',
    "Provence-Alpes-Côte-d'Azur": "Provence-Alpes-Côte d'Azur",
};

/*
 * COULEURS PAR RÉGION — palette visuelle de la carte
 * Chaque région a une couleur unique pour la distinguer visuellement.
 */
const COLORS = {
    'Auvergne-Rhône-Alpes':         '#7C3AED',
    'Bourgogne-Franche-Comté':      '#059669',
    'Bretagne':                     '#ED1C24',
    'Centre-Val de Loire':          '#CA8A04',
    'Corse':                        '#0E7490',
    'Grand Est':                    '#D97706',
    'Hauts-de-France':              '#4F46E5',
    'Île-de-France':                '#DC2626',
    'Normandie':                    '#16A34A',
    'Nouvelle-Aquitaine':           '#F97316',
    'Occitanie':                    '#EC4899',
    'Pays de la Loire':             '#A855F7',
    "Provence-Alpes-Côte d'Azur":  '#0E7490',
};
const DEFAULT_COLOR = '#94A3B8';

/*
 * Variables globales utilisées par plusieurs fonctions.
 * On les déclare ici pour qu'elles soient accessibles partout dans ce fichier.
 */
let CONTACTS      = {};   // Contiendra les données des agents chargées depuis l'API
let deptCounts    = {};   // Nombre de départements par région
let allPaths      = null; // Référence aux éléments SVG des départements
// AJOUT — dictionnaire code_dept → couleur, construit à partir des agents en base.
// Ex : { "14": "#16A34A", "27": "#16A34A", "75": "#DC2626", ... }
// Permet de colorier chaque département selon l'agent qui le couvre,
// et non plus selon la région géographique seule.
let deptColorMap  = {};


/*
 * ════════════════════════════════════════════════════════════════════
 * FONCTION PRINCIPALE : chargerEtInitialiser()
 * ════════════════════════════════════════════════════════════════════
 *
 * C'est le point d'entrée de l'application. Elle est marquée "async"
 * parce qu'elle doit attendre deux opérations réseau :
 *   1. Le chargement des données agents depuis l'API Laravel
 *   2. Le chargement du fichier GeoJSON (géographie de la France)
 *
 * "async/await" est une syntaxe moderne qui permet d'écrire du code
 * asynchrone de façon lisible, comme si c'était du code synchrone.
 * Sans ça, on serait obligé d'enchaîner des .then().then().then()...
 * ce qui devient très vite difficile à lire (le fameux "callback hell").
 */
async function chargerEtInitialiser() {
    try {
        /*
         * ÉTAPE 1 : Charger les données des agents depuis l'API
         *
         * fetch() envoie une requête HTTP GET vers /api/agents.
         * C'est une route PUBLIQUE dans notre API, donc pas besoin
         * de s'authentifier. N'importe quel visiteur peut y accéder.
         *
         * Le "await" signifie : "attends que la requête soit terminée
         * avant de passer à la ligne suivante". Sans await, on passerait
         * à la suite immédiatement, avant d'avoir reçu les données.
         */
        const reponseAgents = await fetch('/api/agents');

        if (!reponseAgents.ok) {
            throw new Error('Erreur API : ' + reponseAgents.status);
        }

        const jsonAgents = await reponseAgents.json();

        /*
         * TRANSFORMATION DU FORMAT
         *
         * L'API retourne un tableau de régions : [{id, nom, zone, agents: [...]}, ...]
         * Mais notre carte attend un objet indexé par nom de région :
         * { "Normandie": { zone: "...", agents: [...] }, ... }
         *
         * On utilise reduce() pour transformer le tableau en objet.
         * reduce() parcourt chaque élément et construit un résultat accumulé.
         * Ici, l'accumulateur "acc" commence vide {} et on y ajoute une entrée
         * à chaque itération.
         */
        CONTACTS = jsonAgents.data.reduce((acc, region) => {
            acc[region.nom] = {
                zone: region.zone,
                agents: region.agents.map(agent => ({
                    agence: agent.agence,
                    nom:    agent.nom,
                    depts:  agent.departement,
                    tel:    agent.tel,
                    telRaw: agent.tel_raw,
                    email:  agent.email,
                    color:  agent.color,
                }))
            };
            return acc;
        }, {});

        /*
         * AJOUT — Construction du dictionnaire code_dept → couleur
         *
         * On parcourt chaque région et chaque agent pour extraire les numéros
         * de département couverts (ex : "Dépt. 14, 27, 50" → ["14","27","50"]).
         * Le regex \d{2,3} capture les codes à 2 ou 3 chiffres (01→95, 971…).
         * Chaque code reçoit la couleur de la région de l'agent.
         *
         * Le champ gn_a1_code du GeoJSON a la forme "FR.14" → on extrait "14"
         * pour l'utiliser comme clé dans ce dictionnaire (voir dessinerCarte).
         *
         * Ce bloc tourne AVANT dessinerCarte() afin que les couleurs soient
         * prêtes dès le premier tracé SVG.
         */
        deptColorMap = {};
        Object.entries(CONTACTS).forEach(([regionName, data]) => {
            const regionColor = COLORS[regionName] || DEFAULT_COLOR;
            data.agents.forEach(agent => {
                const codes = (agent.depts || '').match(/\d{2,3}/g) || [];
                codes.forEach(code => {
                    // Priorité à la couleur individuelle de l'agent (définie par l'admin).
                    // Si l'agent n'a pas de couleur (null), on retombe sur la couleur de région.
                    deptColorMap[code] = agent.color || regionColor;
                });
            });
        });
        // FIN AJOUT

        /*
         * ÉTAPE 2 : Charger le GeoJSON et dessiner la carte
         *
         * On attend que les données agents soient prêtes (étape 1)
         * avant de dessiner la carte, pour être sûr que les couleurs
         * et les contacts s'affichent correctement dès le premier rendu.
         */
        await dessinerCarte();

    } catch (erreur) {
        console.error('Erreur lors de l\'initialisation :', erreur);
        document.getElementById('map-container').innerHTML = `
            <div style="padding:32px;text-align:center;color:#ED1C24">
                <strong>⚠ Impossible de charger les données</strong><br>
                <small style="color:#6b7280">Vérifiez votre connexion et réessayez.</small>
            </div>
        `;
    }
}


/*
 * ════════════════════════════════════════════════════════════════════
 * dessinerCarte() — Charge le GeoJSON et crée la carte SVG avec D3
 * ════════════════════════════════════════════════════════════════════
 *
 * Cette fonction est séparée de chargerEtInitialiser() pour respecter
 * le principe de "responsabilité unique" : chaque fonction ne fait
 * qu'une seule chose. C'est une bonne pratique de POO/programmation
 * que ton jury appréciera.
 */
async function dessinerCarte() {
    const reponseGeo = await fetch('/data/france.json');
    const geojson    = await reponseGeo.json();

    // On filtre pour ne garder que la métropole (latitude > 40°)
    const features = geojson.features.filter(f => f.properties.latitude > 40);

    // On convertit les anciens noms de régions vers les nouveaux
    features.forEach(f => {
        const ancien = f.properties.region;
        f.properties.region = REGION_MAP[ancien] || ancien;
    });

    // On compte le nombre de départements par région
    features.forEach(f => {
        const r = f.properties.region;
        deptCounts[r] = (deptCounts[r] || 0) + 1;
    });

    const container = document.getElementById('map-container');
    const W = container.clientWidth || 720;
    const H = Math.round(W * 0.88);

    // Création de l'élément SVG avec D3
    const svg = d3.select('#map-container')
        .append('svg')
        .attr('width', '100%')
        .attr('height', H)
        .attr('viewBox', `0 0 ${W} ${H}`)
        .attr('preserveAspectRatio', 'xMidYMid meet');

    // Projection cartographique Mercator
    const projection = d3.geoMercator().fitSize([W, H], { type: 'FeatureCollection', features });
    const path       = d3.geoPath().projection(projection);
    const tooltip    = document.getElementById('tooltip');

    // Dessin de chaque département comme un chemin SVG
    allPaths = svg.selectAll('path')
        .data(features)
        .enter()
        .append('path')
        .attr('class', 'departement')
        .attr('d', path)
        // MODIFIÉ — Priorité au dictionnaire deptColorMap (code agent → couleur).
        // On extrait le code numérique depuis gn_a1_code ("FR.14" → "14"),
        // puis on cherche dans deptColorMap. Si le département n'est couvert
        // par aucun agent, on retombe sur la couleur de la région géographique,
        // et enfin sur la couleur par défaut (gris) si la région n'est pas listée.
        .attr('fill', d => {
            const code = (d.properties.gn_a1_code || '').replace('FR.', '');
            return deptColorMap[code] || COLORS[d.properties.region] || DEFAULT_COLOR;
        })
        .on('mousemove', (event, d) => {
            tooltip.style.opacity = '1';
            tooltip.style.left    = (event.clientX + 16) + 'px';
            tooltip.style.top     = (event.clientY - 12) + 'px';
            const zone = CONTACTS[d.properties.region]?.zone || d.properties.region;
            tooltip.innerHTML = `<strong>${d.properties.name}</strong><small>${zone}</small>`;
        })
        .on('mouseleave', () => { tooltip.style.opacity = '0'; })
        .on('click', (event, d) => {
            event.stopPropagation();
            selectRegion(d.properties.region, d.properties.name);
        });

    svg.on('dblclick', () => resetMap());

    // Construction de la liste des régions dans le panneau latéral
    const list    = document.getElementById('region-list');
    const regions = [...new Set(features.map(f => f.properties.region))].sort();

    document.getElementById('region-count').textContent = regions.length + ' régions';

    regions.forEach(region => {
        const color = COLORS[region] || DEFAULT_COLOR;
        const li    = document.createElement('li');
        li.className = 'region-item';
        li.id        = 'li-' + slugify(region);
        li.innerHTML = `
            <span class="region-dot" style="background:${color}"></span>
            <span class="region-item-name">${region}</span>
            <span class="region-item-count">${deptCounts[region] || 0}</span>
        `;
        li.addEventListener('click', () => selectRegion(region, null));
        list.appendChild(li);
    });

    // Gestion du redimensionnement de la fenêtre
    window.addEventListener('resize', () => {
        const newW = container.clientWidth || 720;
        const newH = Math.round(newW * 0.88);
        svg.attr('height', newH).attr('viewBox', `0 0 ${newW} ${newH}`);
        projection.fitSize([newW, newH], { type: 'FeatureCollection', features });
        svg.selectAll('path').attr('d', path);
    });
}


/*
 * selectRegion() — Affiche les contacts d'une région dans le panneau latéral
 * Appelée au clic sur un département ou sur un élément de la liste.
 */
function selectRegion(regionName, deptName) {
    if (!allPaths) return;

    allPaths
        .classed('dimmed', d => d.properties.region !== regionName)
        .classed('active',  d => d.properties.region === regionName);

    document.querySelectorAll('.region-item').forEach(el => el.classList.remove('active'));
    const li = document.getElementById('li-' + slugify(regionName));
    if (li) { li.classList.add('active'); li.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }

    const color  = COLORS[regionName] || DEFAULT_COLOR;
    const data   = CONTACTS[regionName];
    const zone   = data?.zone || regionName;
    const agents = data?.agents || [];
    const box    = document.getElementById('info-box');
    box.classList.add('has-selection');

    const agentsHTML = agents.map(a => `
        <div class="contact-card">
            ${a.agence ? `<div class="contact-agence">${a.agence}</div>` : ''}
            <div class="contact-nom">${a.nom}</div>
            <div class="contact-depts">${a.depts || ''}</div>
            <div class="contact-links">
                <a href="tel:${a.telRaw}" class="contact-link">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.46 19.46 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    ${a.tel}
                </a>
                <a href="mailto:${a.email}" class="contact-link">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    ${a.email}
                </a>
            </div>
        </div>
    `).join('');

    box.innerHTML = `
        <div style="animation: fadeUp .25s ease both">
            <div class="info-zone-label">
                <span class="info-zone-dot" style="background:${color}"></span>
                Zone commerciale
            </div>
            <div class="info-region-name">${zone}</div>
            ${deptName ? `<div class="info-dept-name">Département sélectionné : ${deptName}</div>` : '<div style="height:14px"></div>'}
            <div class="info-depts-covered">
                ${deptCounts[regionName] || 0} département${(deptCounts[regionName] || 0) > 1 ? 's' : ''}
                · ${agents.length} agent${agents.length > 1 ? 's' : ''}
            </div>
            ${agentsHTML || `<p style="font-size:13px;color:#6b7280">Contactez le siège au <a href="tel:+33164786080" style="color:#0095DA">01 64 78 60 80</a></p>`}
        </div>
    `;

    document.getElementById('reset-btn').style.display = 'block';
}


/*
 * resetMap() — Remet la carte dans son état initial
 */
function resetMap() {
    if (!allPaths) return;
    allPaths.classed('dimmed', false).classed('active', false);
    document.querySelectorAll('.region-item').forEach(el => el.classList.remove('active'));

    const box = document.getElementById('info-box');
    box.classList.remove('has-selection');
    box.innerHTML = `
        <div class="placeholder">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <span>Cliquez sur un département<br>pour afficher l'agent local</span>
        </div>
    `;
    document.getElementById('reset-btn').style.display = 'none';
}


/*
 * slugify() — Transforme un texte en identifiant HTML valide
 * Exemple : "Île-de-France" → "ile-de-france"
 */
function slugify(str) {
    return str.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]/g, '-');
}


/*
 * Point d'entrée : on lance tout au chargement de la page.
 * Le "DOMContentLoaded" garantit que le HTML est prêt avant
 * qu'on essaie de manipuler des éléments du DOM.
 */
document.addEventListener('DOMContentLoaded', chargerEtInitialiser);