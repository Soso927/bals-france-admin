/**
 * ═══════════════════════════════════════════════════════════
 * UTILS MODULE — Utilitaires réutilisables
 * ═══════════════════════════════════════════════════════════
 *
 * Ce fichier est un "module" JavaScript (grâce au mot-clé `export`).
 * Un module permet d'isoler du code dans un fichier séparé et de
 * n'exposer que ce dont les autres fichiers ont besoin.
 * Les fonctions marquées `export` peuvent être importées ailleurs :
 *   import { apiRequest, debounce } from './utils.js'
 */


// ─────────────────────────────────────────────────────────────
// 1. showNotification
// ─────────────────────────────────────────────────────────────

/**
 * Affiche une notification à l'utilisateur.
 *
 * @param {string} message - Le texte à afficher.
 * @param {string} [type='info'] - Le type de notification ('info', 'error',
 *   'success'...). La valeur entre crochets signifie que c'est OPTIONNEL :
 *   si on ne le passe pas, JavaScript utilise 'info' par défaut.
 *
 * Exemple d'appel :
 *   showNotification("Sauvegarde réussie", "success")
 *   → affiche : [SUCCESS] Sauvegarde réussie
 */
export function showNotification(message, type = 'info') {
    // `toUpperCase()` transforme le type en majuscules pour le rendre
    // bien visible dans la console. Ex: 'error' → 'ERROR'.
    // Les backticks `` permettent d'utiliser des template literals :
    // on insère des variables directement dans la chaîne avec ${...}.
    console.log(`[${type.toUpperCase()}] ${message}`);

    // Ce commentaire indique que la fonction est intentionnellement
    // incomplète : dans un vrai projet, on remplacerait ce console.log
    // par une vraie UI de notification (toast, bannière, modale, etc.)
    // À implémenter selon vos besoins
}


// ─────────────────────────────────────────────────────────────
// 2. apiRequest
// ─────────────────────────────────────────────────────────────

/**
 * Effectue une requête HTTP vers une API et retourne les données JSON.
 *
 * Le mot-clé `async` indique que cette fonction est ASYNCHRONE :
 * elle ne bloque pas l'exécution du reste du programme pendant qu'elle
 * attend la réponse du serveur. Elle retourne toujours une Promise.
 *
 * @param {string} url     - L'URL de l'API à appeler.
 * @param {object} options - Options supplémentaires pour `fetch`
 *   (method, body, headers...). Optionnel, vaut {} par défaut.
 */
export async function apiRequest(url, options = {}) {

    // `try/catch` : on "essaie" le code du bloc try. Si une erreur
    // survient n'importe où à l'intérieur, on "attrape" l'erreur
    // dans le bloc catch au lieu de faire planter toute l'application.
    try {
        // `fetch` est l'API native du navigateur pour faire des requêtes HTTP.
        // `await` signifie "attends que la Promise se résolve avant de continuer".
        // Sans `await`, on récupèrerait une Promise non résolue, pas la vraie réponse.
        const response = await fetch(url, {

            // On fusionne les headers par défaut avec ceux éventuellement
            // passés dans `options.headers`. Le spread operator `...` "déplie"
            // un objet pour en intégrer les propriétés dans un autre.
            headers: {
                // On indique au serveur qu'on envoie du JSON.
                'Content-Type': 'application/json',

                // Header classique pour signaler que c'est une requête AJAX
                // (utile pour certains backends qui distinguent AJAX vs navigation normale).
                'X-Requested-With': 'XMLHttpRequest',

                // Si `options.headers` contient des headers supplémentaires,
                // ils sont ajoutés ici — et peuvent même écraser les défauts ci-dessus.
                ...options.headers
            },

            // On déplie également le reste des options (method, body, credentials...)
            // APRÈS le bloc headers, pour que tout soit transmis à fetch.
            // Note : si `options` contient aussi une clé `headers`, elle sera ignorée
            // car elle a déjà été traitée manuellement ci-dessus.
            ...options
        });

        // `response.ok` est un booléen fourni par fetch : il vaut `true`
        // si le code HTTP est entre 200 et 299 (succès), `false` sinon.
        // fetch ne lance PAS d'erreur automatiquement pour un 404 ou 500,
        // c'est pourquoi on doit vérifier manuellement ici.
        if (!response.ok) {
            // On crée et on lance une erreur explicite avec le code HTTP,
            // ce qui interrompra l'exécution et ira directement dans le catch.
            throw new Error(`API Error: ${response.status}`);
        }

        // `response.json()` est aussi asynchrone : il lit le corps de la réponse
        // et le parse en objet JavaScript. On `await` encore une fois.
        // C'est la valeur retournée par la fonction si tout s'est bien passé.
        return await response.json();

    } catch (error) {
        // On logue l'erreur pour aider au débogage dans la console développeur.
        console.error('API Error:', error);

        // IMPORTANT : on "re-lance" l'erreur avec `throw`.
        // Sans ça, la fonction retournerait `undefined` silencieusement,
        // et le code appelant ne saurait pas qu'il y a eu un problème.
        // En re-lançant, on laisse l'appelant gérer l'erreur comme il le souhaite.
        throw error;
    }
}


// ─────────────────────────────────────────────────────────────
// 3. debounce
// ─────────────────────────────────────────────────────────────

/**
 * Crée une version "anti-rebond" d'une fonction.
 *
 * CONCEPT CLÉ — Le debounce :
 * Imagine que tu tapes dans une barre de recherche. Sans debounce, chaque
 * frappe de touche déclenche une requête API → 20 frappes = 20 requêtes.
 * Avec debounce(func, 300), on attend que l'utilisateur ARRÊTE de taper
 * pendant 300ms avant de déclencher la requête. Résultat : 1 seule requête.
 *
 * @param {Function} func - La fonction à "débouncer".
 * @param {number}   wait - Le délai d'attente en millisecondes.
 * @returns {Function}    - Une nouvelle fonction avec le comportement debounce.
 */
export function debounce(func, wait) {

    // `timeout` est une variable qui "vit" dans la closure (voir ci-dessous).
    // Elle stocke l'identifiant du timer en cours, ou `undefined` s'il n'y en a pas.
    let timeout;

    // On retourne une NOUVELLE fonction — c'est le pattern "fonction qui retourne une fonction".
    // C'est une CLOSURE : `executedFunction` a accès à `timeout`, `func` et `wait`
    // même après que `debounce` ait fini de s'exécuter. Ces variables "survivent"
    // dans la mémoire tant que la fonction retournée existe.
    return function executedFunction(...args) {
        // `...args` capture tous les arguments passés à la fonction retournée,
        // peu importe leur nombre. Ex: si on appelle search("chat", true), args = ["chat", true]

        // On définit `later` : la fonction qui sera vraiment exécutée après le délai.
        const later = () => {
            // On annule le timer (sécurité supplémentaire, même si en pratique
            // il est déjà expiré à ce stade).
            clearTimeout(timeout);
            // On appelle enfin la vraie fonction avec les arguments d'origine.
            func(...args);
        };

        // CŒUR DU DEBOUNCE :
        // Chaque fois qu'on appelle la fonction retournée, on annule d'abord
        // le timer précédent (s'il existe). Cela "repousse" le délai.
        clearTimeout(timeout);

        // On repart un nouveau timer. Si personne ne rappelle la fonction
        // pendant `wait` millisecondes, `later` sera exécuté.
        timeout = setTimeout(later, wait);

        // Résumé du flux :
        // Appel 1 → timer T1 démarre
        // Appel 2 (avant T1) → T1 annulé, timer T2 démarre
        // Appel 3 (avant T2) → T2 annulé, timer T3 démarre
        // [silence pendant `wait`ms] → T3 expire → `later` s'exécute → func() appelée
    };
}


// ─────────────────────────────────────────────────────────────
// 4. throttle
// ─────────────────────────────────────────────────────────────

/**
 * Crée une version "étranglée" d'une fonction.
 *
 * CONCEPT CLÉ — Le throttle, et sa différence avec le debounce :
 * - debounce : "attends que ça s'arrête, puis exécute UNE fois."
 * - throttle : "exécute au maximum UNE fois par intervalle, quoi qu'il arrive."
 *
 * Exemple concret : un handler sur l'événement `scroll` de la page.
 * Sans throttle, il peut se déclencher 60 fois par seconde.
 * Avec throttle(handler, 200), il se déclenche au maximum 1 fois toutes les 200ms,
 * ce qui représente une économie massive de calcul.
 *
 * @param {Function} func  - La fonction à "throttler".
 * @param {number}   limit - L'intervalle minimum entre deux exécutions (en ms).
 * @returns {Function}     - Une nouvelle fonction avec le comportement throttle.
 */
export function throttle(func, limit) {

    // `inThrottle` est un "verrou" booléen.
    // false = la fonction peut s'exécuter.
    // true  = la fonction est bloquée, on est dans la période de silence.
    let inThrottle;

    // Encore une closure. Notez `function(...args)` sans nom explicite,
    // contrairement au debounce — c'est juste une différence de style.
    return function (...args) {

        // On ne fait quelque chose que si le verrou est ouvert (inThrottle est falsy).
        if (!inThrottle) {

            // `func.apply(this, args)` appelle `func` en lui passant :
            // - `this` : le contexte d'appel courant. C'est important si `func`
            //   utilise `this` en interne (ex: méthode d'un objet). Avec `.apply`,
            //   on s'assure que `this` est correctement transmis.
            //   Note : le debounce utilise `func(...args)` qui ne préserve pas `this` —
            //   c'est une légère différence de robustesse entre les deux implémentations.
            // - `args` : le tableau d'arguments.
            func.apply(this, args);

            // On pose le verrou : plus d'exécution possible pendant `limit` ms.
            inThrottle = true;

            // Après `limit` millisecondes, on retire le verrou.
            // Le prochain appel pourra alors s'exécuter.
            setTimeout(() => inThrottle = false, limit);

            // Résumé du flux :
            // Appel 1 → inThrottle=false → func() exécuté → inThrottle=true → timer démarre
            // Appel 2 (pendant le timer) → inThrottle=true → IGNORÉ
            // Appel 3 (pendant le timer) → inThrottle=true → IGNORÉ
            // [timer expire] → inThrottle=false
            // Appel 4 → inThrottle=false → func() exécuté → etc.
        }
    };
}