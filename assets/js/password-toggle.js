/**
 * Password Toggle - Version ULTRA-SIMPLE
 * Un seul objectif: changer le type des inputs password
 * AUCUNE complexité qui pourrait causer des problèmes
 */

(function() {
  'use strict';
  
  console.log('[PasswordToggle] Chargement...');
  
  function init() {
    try {
      // Formulaire d'inscription
      const registerToggle = document.getElementById('show-password-toggle');
      if (registerToggle) {
        const passwordInput = document.getElementById('password-input');
        const confirmPasswordInput = document.getElementById('confirm-password-input');
        
        registerToggle.addEventListener('change', function() {
          const type = this.checked ? 'text' : 'password';
          if (passwordInput) passwordInput.type = type;
          if (confirmPasswordInput) confirmPasswordInput.type = type;
          console.log('[PasswordToggle] Type changé:', type);
        });
        
        console.log('[PasswordToggle] ✓ Inscription initialisé');
      }
      
      // Formulaire de connexion
      const loginToggle = document.getElementById('show-password-login');
      if (loginToggle) {
        const loginPassword = document.getElementById('login-password');
        
        loginToggle.addEventListener('change', function() {
          const type = this.checked ? 'text' : 'password';
          if (loginPassword) loginPassword.type = type;
          console.log('[PasswordToggle] Type changé:', type);
        });
        
        console.log('[PasswordToggle] ✓ Login initialisé');
      }
      
      // Formulaire admin
      const adminToggle = document.getElementById('show-password-admin');
      if (adminToggle) {
        const adminPassword = document.getElementById('admin-password');
        const adminConfirmPassword = document.getElementById('admin-confirm-password');
        
        adminToggle.addEventListener('change', function() {
          const type = this.checked ? 'text' : 'password';
          if (adminPassword) adminPassword.type = type;
          if (adminConfirmPassword) adminConfirmPassword.type = type;
          console.log('[PasswordToggle] Type changé:', type);
        });
        
        console.log('[PasswordToggle] ✓ Admin initialisé');
      }
      
    } catch (error) {
      console.error('[PasswordToggle] Erreur:', error);
    }
  }
  
  // Initialiser dès que possible
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    setTimeout(init, 100);
  }
  
  console.log('[PasswordToggle] Script chargé');
})();
