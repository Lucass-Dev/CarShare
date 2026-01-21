/**
 * Fonctions utilitaires pour générer des URLs dynamiques en JavaScript
 * Compatible avec n'importe quel environnement (localhost, sous-dossier, production)
 */

// S'assurer que APP_CONFIG est défini
if (typeof window.APP_CONFIG === 'undefined') {
    console.warn('APP_CONFIG n\'est pas défini. Utilisation de valeurs par défaut.');
    window.APP_CONFIG = {
        basePath: '',
        baseUrl: window.location.origin
    };
}

/**
 * Génère une URL relative à la racine de l'application
 * @param {string} path - Le chemin relatif (ex: 'index.php?action=home')
 * @returns {string} L'URL complète
 */
function url(path = '') {
    path = path.replace(/^\/+/, ''); // Enlever les slashes au début
    return window.APP_CONFIG.basePath + '/' + path;
}

/**
 * Génère une URL absolue
 * @param {string} path - Le chemin relatif
 * @returns {string} L'URL absolue
 */
function fullUrl(path = '') {
    path = path.replace(/^\/+/, '');
    return window.APP_CONFIG.baseUrl + '/' + path;
}

/**
 * Génère un chemin vers un asset (CSS, JS, images)
 * @param {string} assetPath - Le chemin de l'asset (ex: 'styles/home.css')
 * @returns {string} L'URL complète de l'asset
 */
function asset(assetPath) {
    assetPath = assetPath.replace(/^\/+/, '');
    return window.APP_CONFIG.basePath + '/assets/' + assetPath;
}

/**
 * Génère un chemin vers l'API
 * @param {string} apiPath - Le chemin de l'API (ex: 'check-email.php')
 * @returns {string} L'URL complète de l'API
 */
function apiUrl(apiPath) {
    
    apiPath = apiPath.replace(/^\/+/, '');
    return window.APP_CONFIG.baseUrl + '/assets/api/' + apiPath;
}

// Log pour debug
console.log('[URL Helper] Loaded with basePath:', window.APP_CONFIG.basePath);
