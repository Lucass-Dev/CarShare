/**
 * Login Form Validation & Loading State Management
 * Version ultra-robuste avec multiples sécurités anti-blocage
 */

(function() {
    'use strict';
    
    // Variables globales pour le debugging
    window.loginFormDebug = {
        initialized: false,
        buttonRestored: false,
        lastError: null
    };
    
    function initLoginForm() {
        const form = document.querySelector('form[action*="login"]');
        if (!form) {
            console.warn('[Login] Formulaire non trouvé');
            return;
        }

        const emailInput = form.querySelector('input[type="email"]');
        const passwordInput = form.querySelector('input[type="password"]');
        const submitBtn = form.querySelector('button[type="submit"]') || 
                         form.querySelector('#login-submit-btn') ||
                         form.querySelector('.primary');
        
        if (!submitBtn) {
            console.error('[Login] Bouton submit non trouvé !');
            return;
        }
        
        // Sauvegarder l'état original IMMÉDIATEMENT
        const originalButtonText = submitBtn.textContent.trim();
        const originalButtonHTML = submitBtn.innerHTML;
        
        console.log('[Login] Form initialized', { originalButtonText });
        window.loginFormDebug.initialized = true;
        
        // Fonction ULTRA-ROBUSTE de restauration
        function forceRestoreButton() {
            if (!submitBtn) return;
            
            try {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.textContent = originalButtonText || 'Se connecter';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.pointerEvents = 'auto';
                
                window.loginFormDebug.buttonRestored = true;
                console.log('[Login] Bouton restauré avec succès');
            } catch (e) {
                console.error('[Login] Erreur restauration:', e);
                window.loginFormDebug.lastError = e.message;
            }
        }
        
        // RESTAURATION IMMÉDIATE au chargement
        forceRestoreButton();
        
        // RESTAURATION sur tous les événements possibles
        window.addEventListener('pageshow', forceRestoreButton);
        window.addEventListener('load', forceRestoreButton);
        document.addEventListener('DOMContentLoaded', forceRestoreButton);
        
        // RESTAURATION toutes les 100ms pendant les 3 premières secondes
        let restorationAttempts = 0;
        const restorationInterval = setInterval(() => {
            forceRestoreButton();
            restorationAttempts++;
            if (restorationAttempts >= 30) { // 30 * 100ms = 3 secondes
                clearInterval(restorationInterval);
            }
        }, 100);
        
        // Gestion de la soumission
        form.addEventListener('submit', (e) => {
            console.log('[Login] Submit triggered');
            
            // Validation basique
            const errors = [];
            
            if (!emailInput || !emailInput.value.trim()) {
                errors.push('L\'email est obligatoire');
            } else if (!isValidEmail(emailInput.value)) {
                errors.push('Format d\'email invalide');
            }
            
            if (!passwordInput || !passwordInput.value) {
                errors.push('Le mot de passe est obligatoire');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                showError(errors.join('<br>'));
                forceRestoreButton();
                return false;
            }
            
            // Activer l'état de chargement
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Connexion en cours...';
                submitBtn.classList.add('loading');
                console.log('[Login] État chargement activé');
            }
            
            // TRIPLE SÉCURITÉ de restauration
            // 1. Après 5 secondes (rapide)
            setTimeout(forceRestoreButton, 5000);
            
            // 2. Après 10 secondes (standard)
            setTimeout(forceRestoreButton, 10000);
            
            // 3. Après 15 secondes (ultime)
            setTimeout(forceRestoreButton, 15000);
            
            // Laisser le formulaire se soumettre normalement
        }, false);
        
        // Remember email feature
        const savedEmail = localStorage.getItem('carshare_email');
        if (savedEmail && emailInput) {
            emailInput.value = savedEmail;
        }

        // Save email on change
        if (emailInput) {
            emailInput.addEventListener('change', () => {
                if (emailInput.value) {
                    localStorage.setItem('carshare_email', emailInput.value);
                }
            });
        }
        
        // Helper: validation email
        function isValidEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }
        
        // Helper: afficher une erreur
        function showError(message) {
            // Supprimer les anciennes erreurs dynamiques
            const existingError = form.querySelector('.error-message-dynamic');
            if (existingError) {
                existingError.remove();
            }
            
            // Créer le message d'erreur
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message error-message-dynamic';
            errorDiv.style.cssText = 'background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 15px; animation: shake 0.3s;';
            errorDiv.innerHTML = message;
            
            // Insérer au début du formulaire
            form.insertBefore(errorDiv, form.firstChild);
            
            // Scroll vers l'erreur
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    // INITIALISATION MULTIPLE pour garantir l'exécution
    // 1. Si le DOM est déjà chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLoginForm);
    } else {
        initLoginForm();
    }
    
    // 2. Sur window.load
    window.addEventListener('load', initLoginForm);
    
    // 3. Exécution immédiate
    setTimeout(initLoginForm, 0);
    
    // 4. Exécution retardée de secours
    setTimeout(initLoginForm, 500);
    setTimeout(initLoginForm, 1000);
})();
