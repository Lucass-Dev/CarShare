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
    const { toggleId, passwordInputIds } = options;
    
    const toggle = document.getElementById(toggleId);
    if (!toggle) return;
    
    // Récupérer les champs de mot de passe
    const inputs = Array.isArray(passwordInputIds)
      ? passwordInputIds.map(id => document.getElementById(id)).filter(Boolean)
      : [document.getElementById(passwordInputIds)].filter(Boolean);
    
    if (inputs.length === 0) return;
    
    // Gérer le changement d'état
    toggle.addEventListener('change', function() {
      const type = this.checked ? 'text' : 'password';
      
      inputs.forEach((input, index) => {
        // Animation subtile avec délai progressif
        setTimeout(() => {
          // Préparer l'animation
          input.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
          input.style.transform = 'scale(0.99)';
          
          // Changer le type
          input.type = type;
          
          // Restaurer après l'animation
          setTimeout(() => {
            input.style.transform = 'scale(1)';
          }, 150);
        }, index * 50); // Décalage de 50ms entre chaque input
      });
      
      // Feedback visuel subtil sur la checkbox elle-même
      const label = toggle.closest('.show-password-label');
      if (label) {
        label.style.transform = 'scale(0.98)';
        setTimeout(() => {
          label.style.transform = 'scale(1)';
        }, 100);
      }
    });
    
    // Accessibilité : gérer le clavier
    const label = toggle.closest('.show-password-label');
    if (label) {
      label.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          toggle.checked = !toggle.checked;
          toggle.dispatchEvent(new Event('change'));
        }
      });
    }
    
    console.log('[PasswordToggle] Initialized for', inputs.length, 'input(s)');
  }
  
  // Auto-initialisation pour les formulaires standards
  document.addEventListener('DOMContentLoaded', function() {
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
  });
  
  // Exposer la fonction pour une utilisation personnalisée
  window.PasswordToggle = {
    init: initPasswordToggle
  };
})();
