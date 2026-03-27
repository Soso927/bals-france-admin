# 🚀 GUIDE RAPIDE DE DÉMARRAGE

## 1️⃣ Installation / Vérification

```bash
# Vérifier que npm et Node sont installés
node --version
npm --version

# Vérifier les dépendances
npm ls vite laravel-vite-plugin tailwindcss
```

---

## 2️⃣ Fichiers à Mettre à Jour

### 📄 resources/views/admin/login.blade.php

**AVANT** ❌
```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
</head>
<body>
    <div style="background: rgba(0, 149, 218, 0.1);">
        <!-- Contenu -->
    </div>
</body>
</html>
```

**APRÈS** ✅
```blade
<!DOCTYPE html>
<html lang="fr" data-page="login">
<head>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="lock-icon-circle">
        <!-- Contenu -->
    </div>
    @vite('resources/js/app.js')
</body>
</html>
```

---

## 3️⃣ Commandes à Exécuter

```bash
# 1. Arrêter npm run dev si en cours
# Ctrl+C

# 2. Relancer le serveur
npm run dev

# 3. Accéder à votre application
# http://localhost:5173
```

---

## 4️⃣ Vérifications

### ✅ Dans le Navigateur

- [ ] Les styles CSS se chargent (pas d'erreur 404)
- [ ] Les couleurs et animations fonctionnent
- [ ] Pas d'erreur dans la console (F12)
- [ ] Le formulaire login est stylisé correctement

### ✅ Dans la Console (F12)

```javascript
// Vérifier que les modules sont chargés
console.log(window.App);                    // Doit afficher { modules: {...}, utils: {...} }
console.log(window.App.modules.Auth);       // Doit afficher l'objet Auth
console.log(window.App.utils.apiRequest);   // Doit afficher la fonction
```

---

## 5️⃣ Fichiers CSS Disponibles

### Importer dans vos vues

```blade
<!-- Point d'entrée principal (tout est importé) -->
@vite('resources/css/app.css')

<!-- N'importez PAS ces fichiers individuellement! -->
<!-- Ils sont importés par app.css -->
```

---

## 6️⃣ Classes CSS à Utiliser

### Champs Formulaire

```html
<!-- Champ texte, email, password -->
<input class="input-field" type="email" placeholder="Email">
```

### Cartes et Animations

```html
<!-- Carte avec animation slideUp -->
<div class="login-card">Contenu</div>
```

### Éléments Visuels

```html
<!-- Cercle bleu pour icônes -->
<div class="lock-icon-circle">🔒</div>

<!-- Texte grisé -->
<p class="subtitle-text">Texte secondaire</p>

<!-- Boîte d'erreur -->
<div class="error-box">Message d'erreur</div>

<!-- Barre de marque -->
<div class="brand-bar"></div>
```

---

## 7️⃣ Modules JavaScript à Utiliser

### Initialisation Automatique

```blade
<!-- L'attribut data-page indique quel module initialiser -->
<html data-page="login">      <!-- Initialise AuthModule -->
<html data-page="dashboard">  <!-- Initialise DashboardModule -->
```

### API Globale

```javascript
// API Request
window.App.utils.apiRequest('/api/data', { method: 'GET' });

// Notifications
window.App.utils.showNotification('Message', 'success');

// Debounce/Throttle
const debouncedFn = window.App.utils.debounce(myFunction, 300);
```

---

## 8️⃣ Résolution des Problèmes

| Problème | Solution |
|----------|----------|
| **Styles ne se chargent pas** | Vérifier `@vite('resources/css/app.css')` en head |
| **JS ne fonctionne pas** | Vérifier `@vite('resources/js/app.js')` avant `</body>` |
| **Console erreur 404** | Vérifier chemins d'import dans` app.css` |
| **Modules ne s'initialisent pas** | Vérifier `data-page` sur tag HTML |
| **Cache stale** | Ctrl+Shift+Del ou `npm run dev` |

---

## 9️⃣ Structure Des Fichiers

```
resources/
├── css/
│   ├── app.css                    ⭐ IMPORTER CELA
│   ├── components/
│   └── admin/
├── js/
│   ├── app.js                     ⭐ IMPORTER CELA
│   └── modules/
└── views/
    └── admin/
        ├── login.blade.php        ⚒️ METTRE À JOUR
        └── login-refactored.blade.php  (référence)
```

---

## 🔟 Commandes Utiles

```bash
# Développement en temps réel
npm run dev

# Build production
npm run build

# Voir le build production localement
npm run preview

# Nettoyer les caches
rm -rf node_modules/.vite
npm run dev
```

---

## 📞 Documentation Détaillée

Pour plus de détails, consultez:
- 📖 `REFACTORING_CSS_JS.md` - Guide complet
- 🗂️ `STRUCTURE_FINALE.md` - Arborescence
- ✅ `MISE_EN_OEUVRE.md` - Checklist
- 📊 `RESUME_EXECUTIF.md` - Vue d'ensemble

---

## ✨ C'est tout!

Vous êtes maintenant prêt à utiliser la nouvelle structure CSS et JavaScript modulaire de votre projet! 🚀

**Questions?** Consultez les fichiers de documentation détaillés.
