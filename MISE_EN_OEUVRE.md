#!/user/bin/env bash

# 📋 CHECKLIST DE MISE EN ŒUVRE
# Suivez ces étapes pour intégrer complètement la refactorisation

## ✅ ÉTAPE 1 : Vérifier la Structure des Dossiers

- [ ] Dossier `resources/css/components/` existe
- [ ] Dossier `resources/css/admin/` existe
- [ ] Dossier `resources/js/modules/` existe

## ✅ ÉTAPE 2 : Vérifier les Fichiers CSS

- [ ] `resources/css/app.css` - Point d'entrée avec tous les imports
- [ ] `resources/css/components/theme.css` - Variables Tailwind
- [ ] `resources/css/components/base.css` - Styles de base
- [ ] `resources/css/admin/variables.css` - Variables Bals
- [ ] `resources/css/admin/brand.css` - Barre de marque
- [ ] `resources/css/admin/login.css` - Styles login
- [ ] `resources/css/admin/utils.css` - Classes utilitaires

## ✅ ÉTAPE 3 : Vérifier les Fichiers JavaScript

- [ ] `resources/js/app.js` - Point d'entrée avec initialisation
- [ ] `resources/js/modules/dashboard.js` - Module dashboard
- [ ] `resources/js/modules/auth.js` - Module authentification
- [ ] `resources/js/modules/utils.js` - Utilitaires

## ✅ ÉTAPE 4 : Mettre à Jour les Vues Blade

### 4.1 Page Login
- [ ] Ouvrir `resources/views/admin/login.blade.php`
- [ ] Remplacer par le contenu de `login-refactored.blade.php`
- [ ] OU Appliquer les changements manuels :

**Changements à appliquer :**
```blade
<!-- AVANT -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
</head>

<!-- APRÈS -->
<!DOCTYPE html>
<html lang="fr" data-page="login">
<head>
    @vite('resources/css/app.css')
</head>
```

**Remplacer les styles inline :**
```blade
<!-- AVANT -->
<div style="background: rgba(0, 149, 218, 0.1); border: 1px solid rgba(0, 149, 218, 0.2);">

<!-- APRÈS -->
<div class="lock-icon-circle">
```

**À la fin du body, ajouter :**
```blade
@vite('resources/js/app.js')
```

### 4.2 Page Dashboard (optionnel)
- [ ] Ajouter `data-page="dashboard"` au tag HTML
- [ ] Ajouter `@vite('resources/css/app.css')` en head
- [ ] Ajouter `@vite('resources/js/app.js')` avant `</body>`

## ✅ ÉTAPE 5 : Mettre à Jour Vite Config

- [ ] Vérifier que `vite.config.js` contient :
```javascript
input: [
    'resources/css/app.css',
    'resources/js/app.js'
],
```

## ✅ ÉTAPE 6 : Tester en Développement

- [ ] Arrêter `npm run dev` s'il est en cours
- [ ] Exécuter `npm run dev`
- [ ] Vérifier que les ressources CSS et JS se chargent
- [ ] Vérifier dans la console qu'il n'y a pas d'erreurs
- [ ] Tester sur http://localhost:5173

## ✅ ÉTAPE 7 : Explorer les Fichiers CSS et JS

**Fichiers CSS à explorer :**
```bash
# Vérifier l'import des fichiers CSS
cat resources/css/app.css
cat resources/css/components/base.css
cat resources/css/admin/login.css
```

**Fichiers JS à explorer :**
```bash
# Vérifier l'import des modules
cat resources/js/app.js
cat resources/js/modules/auth.js
cat resources/js/modules/utils.js
```

## ✅ ÉTAPE 8 : Nettoyer les Anciens Fichiers

- [ ] Supprimer le fichier ancien `resources/views/admin/login-refactored.blade.php` (copie)
- [ ] Vérifier que `public/css/admin.css` ne s'utilise plus
- [ ] Vérifier que `public/js/admin/dashboard.js` ne s'utilise plus

## ✅ ÉTAPE 9 : Build Production

- [ ] Exécuter `npm run build`
- [ ] Vérifier que le build réussit
- [ ] Vérifier les fichiers générés dans `public/build/`

## ✅ ÉTAPE 10 : Documentation et Maintenance

- [ ] Lire `REFACTORING_CSS_JS.md` pour comprendre la structure
- [ ] Lire `STRUCTURE_FINALE.md` pour voir l'arborescence complète
- [ ] Consulter les commentaires dans `resources/css/app.css`
- [ ] Consulter les commentaires dans `resources/js/app.js`

---

## 🚨 Résolution des Problèmes

### Problème : Les CSS ne se chargent pas
**Solution :**
- Vérifier que `npm run dev` est en cours
- Vérifier que `@vite('resources/css/app.css')` est en head
- Vérifier la console pour les erreurs
- Vider le cache du navigateur (Ctrl+Shift+Del)

### Problème : Le JavaScript ne s'exécute pas
**Solution :**
- Vérifier que `@vite('resources/js/app.js')` est avant `</body>`
- Vérifier que `data-page` est défini sur le tag HTML
- Vérifier la console pour les erreurs
- Vérifier dans DevTools que le module JS s'initialise

### Problème : Les styles ne s'appliquent pas aux champs
**Solution :**
- Vérifier que les classes CSS sont appliquées (ex: `class="input-field"`)
- Vérifier que le fichier CSS correspondant est importé dans `app.css`
- Vérifier que la classe existe dans le fichier CSS

### Problème : Les modules JS ne s'initialisent pas
**Solution :**
- Vérifier que `data-page="login"` (ou autre) est sur l'HTML
- Vérifier que le module est impor
- Vérifier la console pour voir quel page est détectée

---

## 📚 Fichiers de Documentation Créés

| Fichier | Description |
|---------|-------------|
| `REFACTORING_CSS_JS.md` | Guide complet de la refactorisation |
| `STRUCTURE_FINALE.md` | Arborescence et organisation complète |
| `MISE_EN_OEUVRE.md` | Ce fichier - checklist d'intégration |

---

## 🎯 Résumé des Changements

### CSS
- ✅ Créé une structure modulaire avec `app.css` comme point d'entrée
- ✅ Séparé les styles en composants, variables, brand, login, etc.
- ✅ Éliminé les styles inline en utilisant des classes CSS

### JavaScript
- ✅ Créé une structure modulaire avec `app.js` comme point d'entrée
- ✅ Séparé la logique en modules (dashboard, auth, utils)
- ✅ Mise en place d'initialisation automatique selon la page

### Vite Configuration
- ✅ Mise à jour du `vite.config.js` pour traiter les fichiers modulaires
- ✅ Configuration de la minification et du code splitting

### Templates Blade
- ✅ Mis à jour pour utiliser `@vite()` au lieu des imports CDN/assets
- ✅ Ajout de `data-page` pour l'initialisation des modules
- ✅ Suppression des styles inline en faveur de classes CSS

---

## 🚀 Prochaines Améliorations (Optionnel)

1. **Ajouter TypeScript** - Convertir les modules JS en TypeScript
2. **Ajouter des Tests** - Tester les modules JavaScript
3. **Ajouter des Linters** - ESLint pour JS, Stylelint pour CSS
4. **Organiser les Composants** - Créer plus de fichiers CSS modulaires
5. **Ajouter des Icônes** - Utiliser icon libraries intégrées à Vite

---

## ✨ Félicitations!

Votre projet est maintenant refactorisé avec une excellente structure CSS et JavaScript! 🎉

Pour toute question ou problème, consultez les fichiers de documentation ou vérifiez la console du navigateur.

**Happy coding!** 💻
