/**
 * Password Toggle Utility - Modern Implementation
 * Gestion élégante de l'affichage des mots de passe via checkbox
 */

(function() {
  'use strict';
  
  /**
   * Initialise le toggle de mot de passe pour un formulaire
   * @param {Object} options - Configuration
   * @param {string} options.toggleId - ID de la checkbox toggle
   * @param {string|string[]} options.passwordInputIds - ID(s) des champs de mot de passe
   */
  function initPasswordToggle(options) {
    try {
      const { toggleId, passwordInputIds } = options;
      
      const toggle = document.getElementById(toggleId);
      if (!toggle) {
        console.warn('[PasswordToggle] Toggle not found:', toggleId);
        return;
      }
      
      // Récupérer les champs de mot de passe
      const inputs = Array.isArray(passwordInputIds)
        ? passwordInputIds.map(id => document.getElementById(id)).filter(Boolean)
        : [document.getElementById(passwordInputIds)].filter(Boolean);
      
      if (inputs.length === 0) {
        console.warn('[PasswordToggle] No password inputs found');
        return;
      }
      
      // Gérer le changement d'état
      toggle.addEventListener('change', function() {
        try {
          const type = this.checked ? 'text' : 'password';
          
          inputs.forEach((input, index) => {
            // Animation subtile avec délai progressif
            setTimeout(() => {
              try {
                // Préparer l'animation
                input.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                input.style.transform = 'scale(0.99)';
                
                // Changer le type
                input.type = type;
                
                // Restaurer après l'animation
                setTimeout(() => {
                  input.style.transform = 'scale(1)';
                }, 150);
              } catch (e) {
                // Fallback sans animation si erreur
                input.type = type;
              }
            }, index * 50);
          });
          
          // Feedback visuel subtil sur la checkbox elle-même
          const label = toggle.closest('.show-password-label');
          if (label) {
            try {
              label.style.transform = 'scale(0.98)';
              setTimeout(() => {
                label.style.transform = 'scale(1)';
              }, 100);
            } catch (e) {
              // Ignorer l'erreur d'animation
            }
          }
        } catch (error) {
          console.warn('[PasswordToggle] Toggle change error:', error);
        }
      });
      
      // Accessibilité : gérer le clavier
      const label = toggle.closest('.show-password-label');
      if (label) {
        label.addEventListener('keydown', function(e) {
          try {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              toggle.checked = !toggle.checked;
              toggle.dispatchEvent(new Event('change'));
            }
          } catch (error) {
            console.warn('[PasswordToggle] Keyboard event error:', error);
          }
        });
      }
      
      console.log('[PasswordToggle] Initialized for', inputs.length, 'input(s)');
    } catch (error) {
      console.error('[PasswordToggle] Initialization failed:', error);
    }
  }
  
  // Auto-initialisation pour les formulaires standards
  // Utiliser un try-catch pour éviter que les erreurs JS bloquent l'initialisation
  function safeInit() {
    try {
      // Formulaire de connexion
      const loginToggle = document.getElementById('show-password-login');
      const loginPassword = document.getElementById('login-password');
      
      if (loginToggle && loginPassword) {
        initPasswordToggle({
          toggleId: 'show-password-login',
          passwordInputIds: 'login-password'
        });
      }
      
      // Formulaire d'inscription
      const registerToggle = document.getElementById('show-password-toggle');
      const passwordInput = document.getElementById('password-input');
      const confirmPasswordInput = document.getElementById('confirm-password-input');
      
      if (registerToggle && (passwordInput || confirmPasswordInput)) {
        const inputs = [];
        if (passwordInput) inputs.push('password-input');
        if (confirmPasswordInput) inputs.push('confirm-password-input');
        
        initPasswordToggle({
          toggleId: 'show-password-toggle',
          passwordInputIds: inputs
        });
      }
      
      // Formulaire admin (connexion ou inscription)
      const adminToggle = document.getElementById('show-password-admin');
      
      if (adminToggle) {
        const adminPassword = document.getElementById('admin-password');
        const adminConfirmPassword = document.getElementById('admin-confirm-password');
        
        const adminInputs = [];
        if (adminPassword) adminInputs.push('admin-password');
        if (adminConfirmPassword) adminInputs.push('admin-confirm-password');
        
        if (adminInputs.length > 0) {
          initPasswordToggle({
            toggleId: 'show-password-admin',
            passwordInputIds: adminInputs.length === 1 ? adminInputs[0] : adminInputs
          });
        }
      }
    } catch (error) {
      console.warn('[PasswordToggle] Initialization error (non-critical):', error);
    }
  }
  
  // Initialiser au DOMContentLoaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', safeInit);
  } else {
    // Si le DOM est déjà chargé, initialiser immédiatement
    safeInit();
  }
  
  // Exposer la fonction pour une utilisation personnalisée
  window.PasswordToggle = {
    init: initPasswordToggle
  };
})();
