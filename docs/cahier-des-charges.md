# CAHIER DES CHARGES
## Application Web d'Administration — Bals France
### Titre professionnel : Développeur Web et Web Mobile (RNCP 37674)

---

## TABLE DES MATIÈRES

1. [Présentation du projet](#1-présentation-du-projet)
2. [Contexte et enjeux](#2-contexte-et-enjeux)
3. [Périmètre fonctionnel](#3-périmètre-fonctionnel)
4. [Architecture technique](#4-architecture-technique)
5. [Modèle de données](#5-modèle-de-données)
6. [Spécifications fonctionnelles — Module Agents](#6-spécifications-fonctionnelles--module-agents)
7. [Spécifications fonctionnelles — Carte interactive France](#7-spécifications-fonctionnelles--carte-interactive-france)
8. [Spécifications fonctionnelles — Authentification et sécurité](#8-spécifications-fonctionnelles--authentification-et-sécurité)
9. [Spécifications fonctionnelles — Module Devis (à développer)](#9-spécifications-fonctionnelles--module-devis-à-développer)
10. [API REST](#10-api-rest)
11. [Contraintes techniques et non-fonctionnelles](#11-contraintes-techniques-et-non-fonctionnelles)
12. [Charte graphique](#12-charte-graphique)
13. [Plan de tests](#13-plan-de-tests)
14. [Livrables attendus](#14-livrables-attendus)

---

## 1. Présentation du projet

### 1.1 Identification du projet

| Champ | Valeur |
|-------|--------|
| **Nom du projet** | Bals France Admin |
| **Type d'application** | Application web d'administration (SPA/MPA hybride) |
| **Technologies principales** | Laravel 12, Livewire 3, Tailwind CSS 4, D3.js 7 |
| **Base de données** | MySQL |
| **Environnement de développement** | Laragon (Windows), PHP 8.2+, Node.js 20+ |
| **Dépôt de code** | Git (branche `main`) |

### 1.2 Commanditaire

L'application est développée pour **Bals France**, fabricant et distributeur de coffrets et bornes électriques industriels, dans le but de centraliser la gestion de son réseau commercial national.

### 1.3 Objectif général

Fournir à l'équipe administrative de Bals France un outil back-office permettant de :

1. **Gérer le réseau d'agents commerciaux** — Créer, modifier et supprimer les fiches des agents couvrant les 13 régions de France métropolitaine.
2. **Visualiser le réseau sur une carte interactive** — Afficher dynamiquement les agents par région sur une carte SVG de la France.
3. **Gérer les demandes de devis** — Centraliser et traiter les formulaires de configuration de coffrets électriques soumis via le site public (module à venir).

---

## 2. Contexte et enjeux

### 2.1 Contexte métier

Bals France commercialise ses produits (coffrets de chantier, coffrets d'étage, coffrets industriels, coffrets événementiels, prises industrielles) par l'intermédiaire d'un réseau d'**agents commerciaux régionaux** et d'**installateurs partenaires**. Chaque agent est rattaché à une région administrative et couvre un ou plusieurs départements.

Avant ce projet, la gestion des contacts agents était réalisée sur des fichiers statiques (JSON, Excel), ce qui rendait les mises à jour longues et exposées aux erreurs humaines.

### 2.2 Problèmes identifiés

- Données agents stockées dans un fichier `agent.json` non versionné, mis à jour manuellement.
- Aucune interface d'administration : toute modification nécessitait une intervention technique directe.
- La carte France consommait les données statiques, sans lien avec une base de données.
- Les formulaires de configuration de coffrets (configurateur produit) n'avaient aucune destination de traitement côté serveur.

### 2.3 Enjeux du projet

| Enjeu | Description |
|-------|-------------|
| **Maintenabilité** | Remplacer les données statiques par une base de données relationnelle |
| **Autonomie** | Donner aux administrateurs un back-office sans compétences techniques |
| **Cohérence** | Un seul référentiel de données alimentant à la fois la carte et l'interface admin |
| **Évolutivité** | Prévoir l'ajout du module de gestion des devis sans refonte de l'architecture |
| **Sécurité** | Protéger les données sensibles (contacts, emails, téléphones) par une authentification |

---

## 3. Périmètre fonctionnel

### 3.1 Modules existants (développés)

| Module | Statut | Description |
|--------|--------|-------------|
| Authentification | ✅ Développé | Connexion, déconnexion, protection des routes admin |
| Gestion des agents | ✅ Développé | CRUD complet via composant Livewire |
| Carte interactive France | ✅ Développé | Visualisation D3.js avec données API |
| Tableau de bord | ✅ Développé | Statistiques globales + accès rapide |
| API REST agents | ✅ Développé | Endpoints publics et protégés |
| Page d'accueil publique | ✅ Développé | Hero + stats + liens navigation |

### 3.2 Modules à développer

| Module | Priorité | Description |
|--------|----------|-------------|
| Gestion des demandes de devis | 🔴 Haute | Réception, consultation et traitement des devis configurateur |
| Formulaires configurateur (public) | 🟡 Moyenne | 5 types de coffrets avec génération PDF/Excel |
| Notifications email | 🟢 Basse | Alertes à la réception d'un nouveau devis |

---

## 4. Architecture technique

### 4.1 Stack technologique

```
┌─────────────────────────────────────────────────────────┐
│                     NAVIGATEUR CLIENT                    │
│  Livewire (réactivité)  │  D3.js (carte)  │  Tailwind   │
└────────────────┬────────────────────────────────────────┘
                 │ HTTP / WebSocket (Livewire)
┌────────────────▼────────────────────────────────────────┐
│                    SERVEUR WEB (Laravel 12)              │
│                                                         │
│  ┌──────────────┐  ┌──────────────┐  ┌───────────────┐  │
│  │  Routes web  │  │  Routes API  │  │  Middleware   │  │
│  │  (web.php)   │  │  (api.php)   │  │  auth/admin   │  │
│  └──────┬───────┘  └──────┬───────┘  └───────────────┘  │
│         │                 │                              │
│  ┌──────▼───────┐  ┌──────▼──────────────────────────┐  │
│  │   Livewire   │  │     Controllers API (REST)      │  │
│  │  Components  │  │  AgentController / Region...    │  │
│  │ AgentManager │  └─────────────────────────────────┘  │
│  └──────┬───────┘                                        │
│         │                                                │
│  ┌──────▼─────────────────────────────────────────────┐  │
│  │              Modèles Eloquent                       │  │
│  │         Agent  │  Region  │  User                  │  │
│  └──────┬──────────────────────────────────────────── ┘  │
└─────────┼───────────────────────────────────────────────┘
          │
┌─────────▼───────────┐
│   BASE DE DONNÉES   │
│       MySQL         │
│  users / regions /  │
│  agents / devis*    │
└─────────────────────┘
```

### 4.2 Patterns d'architecture

| Pattern | Usage |
|---------|-------|
| **MVC (Model-View-Controller)** | Structure Laravel standard |
| **Composant Livewire** | Interface réactive sans JavaScript personnalisé pour l'admin |
| **Repository implicite (Eloquent)** | Accès données via modèles avec relations |
| **API RESTful** | Exposition des données agents pour la carte et les clients tiers |
| **Middleware** | Contrôle d'accès par rôle (admin) |
| **Seeder / Migration** | Gestion reproductible du schéma et des données initiales |

### 4.3 Dépendances principales

| Dépendance | Version | Rôle |
|-----------|---------|------|
| Laravel Framework | 12.x | Framework PHP backend |
| Livewire | 3.x | Composants réactifs full-stack |
| Livewire Volt | 1.x | Composants Livewire en fichier unique (auth) |
| Tailwind CSS | 4.x | Framework CSS utilitaire |
| Vite | 6.x | Bundler assets front-end |
| D3.js | 7.x | Bibliothèque de visualisation SVG |
| Laravel Sanctum | 4.x | Authentification API par token |

---

## 5. Modèle de données

### 5.1 Schéma entité-relation (existant)

```
┌──────────────┐       ┌──────────────────┐       ┌──────────────┐
│    users     │       │     regions      │       │    agents    │
├──────────────┤       ├──────────────────┤       ├──────────────┤
│ id (PK)      │       │ id (PK)          │       │ id (PK)      │
│ name         │       │ nom (100)        │       │ region_id FK │
│ email        │       │ zone (100)       │       │ agence (150) │
│ password     │       │ couleur (7)      │◄──────│ nom (150)    │
│ is_admin     │       │ created_at       │       │ departement  │
│ created_at   │       │ updated_at       │       │ tel (20)     │
│ updated_at   │       └──────────────────┘       │ tel_raw (20) │
└──────────────┘                                  │ email (255)  │
                                                  │ color (7)    │
                                                  │ created_at   │
                                                  │ updated_at   │
                                                  └──────────────┘
```

**Relation :** `regions` (1) ←→ (N) `agents` — Un agent appartient à une région ; une région possède plusieurs agents. La suppression d'une région entraîne la suppression en cascade de ses agents (`cascadeOnDelete`).

### 5.2 Schéma à créer — Module Devis

```
┌────────────────────────────┐       ┌──────────────────────────────┐
│          devis             │       │       devis_prises           │
├────────────────────────────┤       ├──────────────────────────────┤
│ id (PK)                    │       │ id (PK)                      │
│ type_configurateur (enum)  │       │ devis_id (FK)                │
│   chantier / etage /       │       │ type_prise (varchar 50)      │
│   industrie / evenementiel │◄──────│ nom_prise (varchar 150)      │
│   / prise-industrielle     │       │ amperage (varchar 20)        │
│ distributeur (varchar 150) │       │ phases (varchar 20)          │
│ contact_dist (varchar 150) │       │ quantite (tinyint)           │
│ installateur (varchar 150) │       └──────────────────────────────┘
│ affaire (varchar 150)      │
│ email (varchar 255)        │       ┌──────────────────────────────┐
│ type_coffret (varchar 50)  │       │     devis_protections        │
│ materiau (varchar 50)      │       ├──────────────────────────────┤
│ ip (varchar 10)            │       │ id (PK)                      │
│ observations (text)        │◄──────│ devis_id (FK)                │
│ statut (enum)              │       │ groupe (enum: tete/prises)   │
│   nouveau / en_cours /     │       │ valeur (varchar 100)         │
│   traite / archive         │       └──────────────────────────────┘
│ created_at                 │
│ updated_at                 │
└────────────────────────────┘
```

---

## 6. Spécifications fonctionnelles — Module Agents

### 6.1 Liste des agents

**Acteur :** Administrateur connecté  
**Accès :** `/admin/dashboard` → composant `livewire:admin.agent-manager`

**Comportement attendu :**
- Afficher la liste complète des agents groupés par région (ordre alphabétique)
- Chaque région affiche son nombre d'agents entre parenthèses
- Chaque ligne d'agent expose : nom, agence, département(s), téléphone (cliquable `tel:`), email (cliquable `mailto:`), couleur, boutons Modifier et Supprimer
- Un bouton « Ajouter un agent » ouvre le formulaire en mode création

### 6.2 Formulaire d'ajout / modification

**Champs du formulaire :**

| Champ | Type | Obligatoire | Validation |
|-------|------|-------------|------------|
| Région | `<select>` | ✅ | Doit exister dans la table `regions` |
| Nom de l'agent | `<input text>` | ✅ | Max 255 caractères |
| Email | `<input email>` | ✅ | Format email valide, max 255 |
| Agence | `<input text>` | ❌ | Max 255 caractères |
| Département(s) | `<input text>` | ❌ | Max 255 caractères (ex : "Dépt. 75, 78, 92") |
| Téléphone affiché | `<input text>` | ❌ | Max 50 caractères (ex : "01 23 45 67 89") |
| Téléphone brut | `<input text>` | ❌ | Max 50 caractères (ex : "+33123456789") |
| Couleur | `<input color>` | ❌ | Hex valide `/^#[0-9A-Fa-f]{6}$/`, défaut `#94A3B8` |

**Règles métier :**
- En mode **création** : tous les champs sont vides, la couleur est initialisée à `#94A3B8`
- En mode **modification** : les champs sont pré-remplis avec les données existantes de l'agent
- La validation est exécutée côté serveur par Livewire avant toute persistance
- Un message flash de confirmation est affiché après succès
- En cas d'erreur de validation, les messages sont affichés sous chaque champ concerné

### 6.3 Suppression d'un agent

- Un clic sur « Supprimer » affiche une boîte de dialogue de confirmation native (`wire:confirm`)
- Après confirmation, l'agent est supprimé de la base de données
- La liste est rafraîchie instantanément via Livewire
- Un message flash « Agent supprimé » est affiché

### 6.4 Impact sur la carte interactive

La suppression ou modification d'un agent est répercutée sur la carte France dès le prochain appel à l'API `/api/agents` (rechargement de la page carte).

---

## 7. Spécifications fonctionnelles — Carte interactive France

### 7.1 Affichage de la carte

**Accès :** `/france-map` (public, sans authentification)

**Comportement attendu :**
- La carte SVG de la France métropolitaine est rendue via D3.js à partir du fichier GeoJSON `/data/france.json` (96 départements)
- Chaque département est coloré selon la couleur de l'agent ou de la région auquel il est rattaché
- Les DOM-TOM sont exclus (filtrage par latitude > 40°)
- La carte est responsive : elle s'adapte au redimensionnement de la fenêtre

### 7.2 Interactions utilisateur

| Interaction | Effet |
|-------------|-------|
| Survol d'un département | Infobulle affichant le nom du département |
| Clic sur un département | Sélection de la région correspondante, affichage des agents |
| Double-clic | Réinitialisation de la carte (toutes les régions à égale opacité) |
| Clic sur une région dans la liste latérale | Même effet que le clic sur la carte |

### 7.3 Panneau d'information

Lors de la sélection d'une région, un panneau latéral affiche :
- Le nom de la zone (ex : "ÎLE-DE-FRANCE")
- Le nombre de départements couverts
- Le nombre d'agents présents
- Pour chaque agent : nom, agence, département(s), téléphone (lien `tel:`), email (lien `mailto:`)

### 7.4 Source de données

La carte consomme l'endpoint public `GET /api/agents` qui retourne toutes les régions avec leurs agents. Le champ `color` de l'agent est utilisé pour colorer les départements de sa région.

---

## 8. Spécifications fonctionnelles — Authentification et sécurité

### 8.1 Système d'authentification

| Fonctionnalité | Statut | Détail |
|----------------|--------|--------|
| Connexion email/mot de passe | ✅ | Route `/login`, composant Volt |
| Déconnexion | ✅ | Route POST `/logout` |
| Inscription | ✅ | Route `/register` (désactivable en production) |
| Mot de passe oublié | ✅ | Route `/forgot-password` |
| Réinitialisation mot de passe | ✅ | Route `/reset-password/{token}` |
| Vérification email | ✅ | Route `/verify-email` |
| Middleware admin | ✅ | `is_admin = true` requis pour l'espace admin |

### 8.2 Gestion des rôles

| Rôle | Accès |
|------|-------|
| **Visiteur** (non connecté) | Page d'accueil, carte France, page de connexion |
| **Utilisateur authentifié** | Idem + tableau de bord (sans droit admin) |
| **Administrateur** (`is_admin = true`) | Accès complet : dashboard, gestion agents, futurs modules |

### 8.3 Protection des routes

- Les routes `/admin/*` sont protégées par le double middleware `auth` + `admin`
- Si l'utilisateur n'est pas connecté → redirection vers `/login`
- Si l'utilisateur est connecté mais non-admin → redirection vers `/login`
- Les routes API de modification (`POST`, `PUT`, `DELETE`) sont protégées par `auth:sanctum`

### 8.4 Sécurité applicative

| Mesure | Implémentation |
|--------|----------------|
| Protection CSRF | Token `{{ csrf_token() }}` sur tous les formulaires |
| Hachage des mots de passe | Cast `hashed` sur le champ `password` (bcrypt) |
| Validation des entrées | Règles de validation Livewire côté serveur |
| Protection XSS | Échappement automatique Blade (`{{ }}`) |
| Protection injection SQL | Requêtes Eloquent paramétrées |
| Gestion des sessions | Driver `database`, durée 120 minutes |

---

## 9. Spécifications fonctionnelles — Module Devis (à développer)

### 9.1 Contexte

Le site de Bals France propose un **configurateur de coffrets électriques** accessible au public. Ce configurateur permet aux installateurs et distributeurs de composer un coffret sur mesure en choisissant :
- Le **type de coffret** : chantier, étage, industriel, événementiel, prise industrielle
- Le **matériau** du coffret
- L'**indice de protection** (IP)
- Les **protections de tête** (disjoncteur, interrupteur différentiel, etc.)
- Les **protections de prises** (par prise, par groupe, disjoncteur, etc.)
- Les **prises** souhaitées (type, ampérage, nombre de phases, quantité)
- Des **informations de contact** (distributeur, installateur, affaire, email)

À l'issue de la configuration, l'utilisateur peut générer un **devis au format PDF ou Excel**.

### 9.2 Objectif du module

L'objectif est de permettre à l'équipe administrative de **recevoir, consulter et traiter** les demandes de devis soumises via le configurateur, directement depuis l'interface d'administration.

### 9.3 Types de configurateurs supportés

| Type | Route publique | Description |
|------|---------------|-------------|
| Chantier | `/configurateur/chantier` | Coffret de distribution temporaire pour chantiers |
| Étage | `/configurateur/etage` | Coffret de distribution fixe pour bâtiments |
| Industriel | `/configurateur/coffret-industrie` | Coffret pour environnements industriels |
| Événementiel | `/configurateur/coffret-evenementiel` | Coffret pour spectacles et événements |
| Prise industrielle | `/configurateur/prise-industrielle` | Prise CEE individuelle |

### 9.4 Flux de traitement d'un devis

```
[Utilisateur public]          [Système]                 [Administrateur]
       │                          │                            │
       │ 1. Configure le coffret  │                            │
       │─────────────────────────►│                            │
       │                          │ 2. Valide les données      │
       │                          │    (type, IP, prises...)   │
       │ 3. Soumet le formulaire  │                            │
       │─────────────────────────►│                            │
       │                          │ 4. Persiste en BDD         │
       │                          │    (table `devis`)         │
       │                          │                            │
       │                          │ 5. Notif. email admin      │
       │                          │───────────────────────────►│
       │                          │                            │
       │ 6. Reçoit confirmation   │                            │ 7. Consulte la liste
       │◄─────────────────────────│                            │    des devis
       │                          │                            │──────────────────────►
       │                          │                            │
       │                          │                            │ 8. Change le statut
       │                          │                            │    (nouveau → traité)
```

### 9.5 Interfaces d'administration — Module Devis

#### 9.5.1 Liste des devis

**Accès :** `/admin/devis`

| Colonne | Description |
|---------|-------------|
| N° devis | ID auto-incrémenté |
| Date | `created_at` formatée |
| Type | Type de configurateur (badge coloré) |
| Contact | Nom du distributeur ou installateur |
| Email | Email de contact |
| Affaire | Référence de l'affaire |
| Statut | Badge (Nouveau / En cours / Traité / Archivé) |
| Actions | Voir détail, Changer statut, Supprimer |

**Filtres disponibles :**
- Par type de coffret
- Par statut
- Par période (date de soumission)
- Recherche texte libre (nom, email, affaire)

#### 9.5.2 Détail d'un devis

**Accès :** `/admin/devis/{id}`

Affiche toutes les informations du devis :
- Informations de contact (distributeur, installateur, email, affaire)
- Caractéristiques techniques (type, matériau, IP)
- Protections sélectionnées (tête et prises)
- Liste des prises avec quantités
- Observations libres
- Bouton de génération PDF
- Bouton de changement de statut

#### 9.5.3 Règles d'exclusivité des protections

Le configurateur applique des règles d'exclusivité qui doivent être validées aussi côté serveur :

**Protection de tête :**
- `Interrupteur` et `Inter différentiel` sont mutuellement exclusifs
- `Disjoncteur` et `Disjoncteur Diff.` sont mutuellement exclusifs

**Protection des prises :**
- `Par prise` et `Par groupe` sont mutuellement exclusifs
- `Disjoncteur` et `Disjoncteur Diff.` sont mutuellement exclusifs

### 9.6 Génération des documents

| Format | Endpoint | Bibliothèque envisagée |
|--------|----------|------------------------|
| PDF | `POST /api/generate-quote/pdf` | Laravel DomPDF (`barryvdh/laravel-dompdf`) |
| Excel | `POST /api/generate-quote/excel` | Laravel Excel (`maatwebsite/excel`) |

Le document généré inclut :
- En-tête avec logo Bals France
- Informations de contact du demandeur
- Tableau récapitulatif des choix techniques
- Tableau des prises avec quantités
- Zone de signature et conditions générales

---

## 10. API REST

### 10.1 Endpoints existants

#### `GET /api/agents`

**Accès :** Public  
**Description :** Retourne toutes les régions avec leurs agents (utilisé par la carte France)

**Réponse exemple :**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Normandie",
      "zone": "NORMANDIE",
      "couleur": "#94A3B8",
      "agents": [
        {
          "id": 1,
          "region_id": 1,
          "agence": "Électro Normandie",
          "nom": "Jean Dupont",
          "departement": "Dépt. 14, 50, 61, 27, 76",
          "tel": "06 12 34 56 78",
          "tel_raw": "+33612345678",
          "email": "j.dupont@electro-normandie.fr",
          "color": "#16A34A"
        }
      ]
    }
  ]
}
```

#### `POST /api/agents` *(auth:sanctum)*

| Paramètre | Type | Obligatoire |
|-----------|------|-------------|
| region_id | integer | ✅ |
| nom | string | ✅ |
| email | string (email) | ✅ |
| agence | string | ❌ |
| departement | string | ❌ |
| tel | string | ❌ |
| tel_raw | string | ❌ |

#### `PUT /api/agents/{id}` *(auth:sanctum)*
Mêmes paramètres que POST, tous optionnels (mise à jour partielle).

#### `DELETE /api/agents/{id}` *(auth:sanctum)*
Supprime l'agent identifié par `{id}`.

### 10.2 Endpoints à créer — Module Devis

| Méthode | Endpoint | Accès | Description |
|---------|----------|-------|-------------|
| `POST` | `/api/generate-quote/pdf` | Public | Génère et télécharge un PDF |
| `POST` | `/api/generate-quote/excel` | Public | Génère et télécharge un Excel |
| `POST` | `/api/devis` | Public | Soumet un devis et le persiste |
| `GET` | `/api/devis` | Admin | Liste tous les devis (paginé) |
| `GET` | `/api/devis/{id}` | Admin | Détail d'un devis |
| `PATCH` | `/api/devis/{id}/statut` | Admin | Met à jour le statut |
| `DELETE` | `/api/devis/{id}` | Admin | Supprime un devis |

---

## 11. Contraintes techniques et non-fonctionnelles

### 11.1 Performance

| Contrainte | Valeur cible |
|-----------|-------------|
| Temps de chargement page d'accueil | < 2 secondes |
| Temps de rendu de la carte | < 1 seconde après réception JSON |
| Réponse API `/api/agents` | < 500 ms |
| Chargement composant Livewire | Sans rechargement de page entière |

**Optimisations mises en place :**
- Eager loading des relations (`Region::with('agents')`) pour éviter les requêtes N+1
- Pagination prévue sur la liste des devis
- Cache HTTP sur l'endpoint public `/api/agents` (à implémenter)

### 11.2 Compatibilité navigateurs

| Navigateur | Version minimale |
|-----------|-----------------|
| Chrome / Chromium | 90+ |
| Firefox | 88+ |
| Safari | 14+ |
| Edge | 90+ |
| Mobile (iOS Safari, Android Chrome) | Versions actuelles |

### 11.3 Accessibilité

- Balises sémantiques HTML5 (`<header>`, `<main>`, `<nav>`, `<section>`)
- Attributs `alt` sur les images
- Contraste des couleurs conforme WCAG 2.1 niveau AA
- Navigation au clavier possible sur les formulaires

### 11.4 Responsivité

| Point de rupture | Comportement |
|-----------------|-------------|
| Mobile (< 768px) | Colonne unique, carte pleine largeur |
| Tablette (768–1024px) | Colonne unique, panneau info sous la carte |
| Desktop (> 1024px) | Grille 2 colonnes (carte + panneau info) |

### 11.5 Environnement de déploiement

| Paramètre | Valeur |
|-----------|--------|
| Serveur web | Apache (via Laragon en développement) |
| PHP | 8.2+ |
| Base de données | MySQL 8.0+ |
| Node.js | 20+ (build Vite) |
| Gestionnaire de paquets PHP | Composer 2.x |
| Gestionnaire de paquets JS | npm |

---

## 12. Charte graphique

### 12.1 Couleurs

| Variable CSS | Valeur | Usage |
|-------------|--------|-------|
| `--bals-blue` | `#0095DA` | Couleur principale, boutons, liens |
| `--bals-red` | `#ED1C24` | Couleur secondaire, accents, alertes |
| `--bals-black` | `#1A1A1A` | Texte principal |
| `--bals-grey` | `#B3B3B3` | Texte secondaire |
| `--bals-grey-light` | `#F4F6F8` | Fonds de sections |
| `--bals-grey-border` | `#E2E6EA` | Bordures |
| Couleur par défaut agents | `#94A3B8` | Slate-400, agents sans couleur définie |

### 12.2 Typographie

| Usage | Police | Graisses |
|-------|--------|---------|
| Titres et corps | Exo 2 (Google Fonts) | 300, 400, 500, 600, 700, 800 |
| Interface admin (Tailwind) | Sans-serif système | Variable |

### 12.3 Composants UI récurrents

- **Cartes (cards)** : `rounded-3xl bg-white shadow-sm ring-1 ring-stone-200`
- **Bouton primaire** : fond `#0095DA`, texte blanc, `rounded-2xl`
- **Bouton secondaire** : bordure `stone-300`, texte `stone-700`
- **Barre de marque** : dégradé `90deg, #0095DA 70%, #ED1C24 100%`, hauteur 4px

---

## 13. Plan de tests

### 13.1 Tests fonctionnels — Module Agents

| Scénario | Résultat attendu |
|----------|-----------------|
| Connexion avec `admin@bals-france.fr` / `admin1234` | Redirection vers `/admin/dashboard` |
| Accès `/admin/dashboard` sans connexion | Redirection vers `/login` |
| Ajout d'un agent avec tous les champs valides | Agent créé, message flash "Nouvel agent ajouté" |
| Ajout d'un agent sans email | Message d'erreur de validation sous le champ email |
| Modification d'un agent existant | Données mises à jour, message flash "Agent modifié avec succès" |
| Suppression d'un agent après confirmation | Agent retiré de la liste, message flash "Agent supprimé" |
| Suppression sans confirmation (annulation) | Aucune action, agent conservé |

### 13.2 Tests fonctionnels — Carte France

| Scénario | Résultat attendu |
|----------|-----------------|
| Chargement de la page `/france-map` | Carte SVG affichée, régions colorées |
| Clic sur un département | Région sélectionnée, fiches agents affichées |
| Double-clic sur la carte | Réinitialisation, toutes les régions visibles |
| Mise à jour d'un agent en admin | Couleur mise à jour à la prochaine consultation de la carte |

### 13.3 Tests de sécurité

| Scénario | Résultat attendu |
|----------|-----------------|
| Appel `POST /api/agents` sans token Sanctum | Réponse 401 Unauthorized |
| Tentative d'injection SQL dans le formulaire | Requête Eloquent paramétrée, aucun impact |
| Tentative XSS dans le champ "nom" | Contenu échappé par Blade, aucun script exécuté |
| Accès `/admin/*` avec compte non-admin | Redirection vers `/login` |

---

## 14. Livrables attendus

### 14.1 Livrables existants

- [x] Application Laravel fonctionnelle (modules agents + carte)
- [x] Base de données MySQL avec migrations et seeders
- [x] API REST (agents, régions)
- [x] Interface d'administration Livewire (CRUD agents)
- [x] Carte interactive France avec D3.js
- [x] Authentification avec gestion des rôles (admin middleware)
- [x] Code source versionné sous Git

### 14.2 Livrables à produire

- [ ] Module de gestion des devis (routes, contrôleurs, modèles, vues Livewire)
- [ ] Formulaires configurateur intégrés côté public (5 types de coffrets)
- [ ] Génération PDF et Excel des devis (`barryvdh/laravel-dompdf`, `maatwebsite/excel`)
- [ ] Notifications email à la réception d'un devis
- [ ] Interface d'administration des devis (liste, détail, gestion des statuts)

---

*Document rédigé dans le cadre du titre professionnel Développeur Web et Web Mobile (RNCP 37674).*  
*Projet : Bals France Admin — Application de gestion du réseau commercial*
