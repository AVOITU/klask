import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import { initSessionTimeout } from './js/session-timeout.js';

// Récupère les données injectées via data-attributes dans le HTML
document.addEventListener('DOMContentLoaded', () => {
    const sessionConfig = document.getElementById('session-config');

    if (sessionConfig) {
        const logoutUrl = sessionConfig.dataset.logoutUrl;
        // On récupère la valeur brute du div
        const timeoutMs = parseInt(sessionConfig.dataset.timeout);

        console.log('Initialisation timeout avec:', timeoutMs, 'ms'); // Pour debug
        initSessionTimeout(logoutUrl, timeoutMs);
    }
});
