/**
 * ═══════════════════════════════════════════════════════════
 * APPLICATION MAIN ENTRY POINT
 * ═══════════════════════════════════════════════════════════
 * Point d'entrée principal pour tous les modules JavaScript
 */

import DashboardModule from './modules/dashboard.js';
import AuthModule from './modules/auth.js';
import { apiRequest, showNotification, debounce, throttle } from './modules/utils.js';

/**
 * Initialisation de l'application
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('Application initialized');

    // Déterminer quelle page est chargée et initialiser les modules correspondants
    const page = document.documentElement.getAttribute('data-page');

    switch (page) {
        case 'dashboard':
            if (typeof DashboardModule !== 'undefined') {
                DashboardModule.init();
            }
            break;
        case 'login':
            if (typeof AuthModule !== 'undefined') {
                AuthModule.init();
            }
            break;
        default:
            console.log('No specific module for this page');
    }
});

// Exporter les utilitaires globalement si nécessaire
window.App = {
    modules: {
        Dashboard: DashboardModule,
        Auth: AuthModule
    },
    utils: {
        apiRequest,
        showNotification,
        debounce,
        throttle
    }
};
