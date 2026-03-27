/**
 * ═══════════════════════════════════════════════════════════
 * AUTH MODULE — Gestion de l'authentification
 * ═══════════════════════════════════════════════════════════
 */

export const AuthModule = {
    /**
     * Initialise les formulaires d'authentification
     */
    init() {
        console.log('Auth module initialized');
        this.setupFormValidation();
        this.setupPasswordToggle();
    },

    /**
     * Configure la validation des formulaires
     */
    setupFormValidation() {
        const forms = document.querySelectorAll('[data-auth-form]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.validateForm(e));
        });
    },

    /**
     * Valide le formulaire avant l'envoi
     */
    validateForm(event) {
        const form = event.target;
        const email = form.querySelector('[type="email"]');
        const password = form.querySelector('[type="password"]');

        if (!email?.value || !password?.value) {
            event.preventDefault();
            console.warn('Veuillez remplir tous les champs');
        }
    },

    /**
     * Configure l'affichage/masquage du mot de passe
     */
    setupPasswordToggle() {
        const toggles = document.querySelectorAll('[data-toggle-password]');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => this.togglePassword(e));
        });
    },

    /**
     * Bascule la visibilité du mot de passe
     */
    togglePassword(event) {
        const input = event.target.closest('[data-password-field]')?.querySelector('input');
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    }
};

export default AuthModule;
