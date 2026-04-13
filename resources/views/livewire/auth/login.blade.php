<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

/*
 * Composant Livewire Volt autonome pour la page de connexion.
 *
 * Ce composant gère lui-même la totalité du processus de connexion,
 * sans dépendre d'une classe LoginForm externe. C'est plus simple
 * et plus lisible pour comprendre le flux d'authentification.
 *
 * L'attribut #[Layout] indique à Livewire quel gabarit HTML utiliser
 * pour envelopper ce composant. Il cherche le fichier :
 * resources/views/layouts/guest.blade.php
 */
new #[Layout('layouts.guest')] class extends Component
{
    /*
     * Les propriétés publiques de Livewire sont "réactives" :
     * quand leur valeur change (à chaque frappe dans un champ),
     * Livewire synchronise automatiquement avec le template Blade.
     *
     * L'attribut #[Validate] définit les règles de validation
     * directement sur chaque propriété. C'est la syntaxe moderne
     * de Livewire 3 qui remplace l'ancien tableau $rules.
     */

    /** Email saisi par l'utilisateur */
    #[Validate('required|string|email')]
    public string $email = '';

    /** Mot de passe saisi */
    #[Validate('required|string')]
    public string $password = '';

    /** Case "Se souvenir de moi" */
    public bool $remember = false;


    /**
     * Méthode principale de connexion.
     * Déclenchée par wire:submit="login" sur le formulaire.
     *
     * Le flux est le suivant :
     * 1. On vérifie le rate limiting (protection anti-brute force)
     * 2. On valide le format des données (email valide, champs non vides)
     * 3. On tente l'authentification avec Auth::attempt()
     * 4. Si succès : on régénère la session et on redirige
     * 5. Si échec : on incrémente le compteur de tentatives et on affiche une erreur
     */
    public function login(): void
    {
        // Étape 1 : validation des données du formulaire.
        // Si le format est incorrect (ex: email invalide), Livewire
        // arrête l'exécution et renvoie les erreurs vers le Blade
        // via les directives @error(). Pas de rechargement de page.
        $this->validate();

        // Étape 2 : protection anti-brute force avec le RateLimiter.
        // On bloque les tentatives répétées pour éviter qu'un attaquant
        // puisse essayer des milliers de mots de passe automatiquement.
        $this->ensureIsNotRateLimited();

        // Étape 3 : tentative d'authentification.
        // Auth::attempt() cherche un utilisateur avec cet email,
        // compare le mot de passe avec le hash en base de données,
        // et crée une session si les identifiants sont corrects.
        if (! Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember  // true = cookie de longue durée
        )) {
            // Authentification échouée : on incrémente le compteur
            // de tentatives pour le rate limiter.
            RateLimiter::hit($this->throttleKey());

            // On lève une erreur de validation sur le champ email
            // pour que le message s'affiche dans le formulaire.
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        // Étape 4 : connexion réussie.
        // On efface le compteur de tentatives puisque la connexion a réussi.
        RateLimiter::clear($this->throttleKey());

        // Régénération de la session : obligatoire après connexion pour
        // prévenir les attaques de "session fixation" (un attaquant
        // qui aurait obtenu l'ID de session avant connexion ne peut
        // plus l'utiliser après cette régénération).
        Session::regenerate();

        // Étape 5 : redirection selon le rôle de l'utilisateur.
        // Un admin va vers le tableau de bord d'administration.
        // Un utilisateur ordinaire va vers la page d'accueil.
        // redirectIntended() redirige vers la page que l'utilisateur
        // voulait atteindre avant d'être renvoyé sur /login.
        if (Auth::user()->is_admin) {
            $this->redirectIntended(
                default: route('admin.dashboard', absolute: false),
                navigate: true
            );
        } else {
            $this->redirectIntended(
                default: route('home', absolute: false),
                navigate: true
            );
        }
    }


    /**
     * Vérifie que l'utilisateur n'a pas dépassé le nombre maximum
     * de tentatives de connexion autorisées (5 tentatives par minute).
     *
     * Si la limite est dépassée, on déclenche l'événement Lockout
     * et on lève une exception avec le temps d'attente restant.
     */
    protected function ensureIsNotRateLimited(): void
    {
        // RateLimiter::tooManyAttempts() vérifie si la clé de throttle
        // dépasse 5 tentatives. La clé est unique par email + IP
        // pour éviter qu'un attaquant change d'email pour contourner.
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // On déclenche l'événement Lockout pour que l'application
        // puisse réagir (logs, alertes, etc.)
        event(new Lockout(request()));

        // On calcule le temps d'attente restant en secondes
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes.",
        ]);
    }


    /**
     * Génère une clé unique pour le rate limiter.
     *
     * La clé combine l'email (en minuscules) et l'adresse IP
     * de l'utilisateur. Str::transliterate() normalise les
     * caractères accentués pour éviter les problèmes d'encodage.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->email) . '|' . request()->ip()
        );
    }

}; ?>


{{--
    ════════════════════════════════════════════════════════
    TEMPLATE BLADE — Interface du formulaire de connexion
    ════════════════════════════════════════════════════════
--}}
<div>

    {{-- En-tête avec titre et description --}}
    <x-auth-header
        title="Connexion à votre compte"
        description="Entrez votre adresse email et votre mot de passe "
    />

    {{-- Message de statut (ex: après réinitialisation de mot de passe) --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{--
        wire:submit="login" intercepte la soumission du formulaire
        et appelle la méthode login() via AJAX, sans rechargement.
    --}}
    <form wire:submit="login" class="flex flex-col gap-6">

        {{-- Champ email — wire:model synchronise en temps réel avec $email --}}
        <flux:input
            wire:model="email"
            label="Adresse email"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="votre@email.fr"
        />

        {{-- Champ mot de passe avec lien "Mot de passe oublié ?" --}}
        <div class="relative">
            <flux:input
                wire:model="password"
                label="Mot de passe"
                type="password"
                required
                autocomplete="current-password"
                placeholder="Votre mot de passe"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link
                    class="absolute end-0 top-0 text-sm"
                    :href="route('password.request')"
                    wire:navigate
                >
                    Mot de passe oublié ?
                </flux:link>
            @endif
        </div>

        {{-- Case "Se souvenir de moi" --}}
        <flux:checkbox
            wire:model="remember"
            label="Se souvenir de moi"
        />

        {{-- Bouton de soumission --}}
        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                Se connecter
            </flux:button>
        </div>

    </form>
</div>