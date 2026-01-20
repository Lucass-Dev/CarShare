<link rel="stylesheet" href="<?= asset('styles/inscr.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/register-validation.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/password-toggle-checkbox.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/password-validator.css?v=' . time()) ?>">

<style>
/* Couleurs admin pour les boutons uniquement */
.admin-register-page .buttons button.primary {
    background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%) !important;
}

.admin-register-page .buttons button.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(147, 51, 234, 0.3);
}

.admin-badge {
    display: inline-block;
    background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 15px;
}
</style>

<div class="login-box admin-register-page">
  <div style="text-align: center;">
    <span class="admin-badge">🔐 ESPACE ADMINISTRATEUR</span>
  </div>
  
  <h2>S'inscrire en tant qu'Administrateur</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #dc2626;">
      <strong>⚠️ Erreur :</strong> <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="<?= url('index.php?action=admin_register') ?>" novalidate id="register-form">
    <div class="form-field">
      <input 
        type="text" 
        name="last_name" 
        placeholder="Nom *" 
        value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
        required 
        maxlength="50" 
        pattern="[a-zA-ZÀ-ÿ\s'-]+" 
        title="Le nom est obligatoire (2-50 caractères, lettres uniquement)" 
      />
    </div>
    
    <div class="form-field">
      <input 
        type="text" 
        name="first_name" 
        placeholder="Prénom *" 
        value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
        required 
        maxlength="50" 
        pattern="[a-zA-ZÀ-ÿ\s'-]+" 
        title="Le prénom est obligatoire (2-50 caractères, lettres uniquement)" 
      />
    </div>
    
    <div class="form-field">
      <input 
        type="email" 
        name="email" 
        id="email-input"
        placeholder="Email" 
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        required 
      />
      <span id="email-error" class="validation-error"></span>
    </div>
    
    <div class="form-field">
      <div class="password-input-wrapper">
        <input 
          type="email" 
          name="email_confirm" 
          id="email-confirm-input"
          placeholder="Confirmer l'email" 
          value="<?= htmlspecialchars($_POST['email_confirm'] ?? '') ?>"
          required 
        />
        <span class="email-match-icon" id="email-match-icon"></span>
      </div>
    </div>
    
    <div class="form-field">
      <input 
        type="password" 
        name="password" 
        id="password-input"
        placeholder="Mot de passe (min 12 caractères)" 
        required 
        minlength="12" 
      />
    </div>
    
    <div class="form-field">
      <input 
        type="password" 
        name="confirm_password" 
        id="confirm-password-input"
        placeholder="Confirmer le mot de passe" 
        required 
      />
    </div>
    
    <div class="show-password-container">
      <label class="show-password-label">
        <input type="checkbox" id="show-password-toggle" />
        <span>Afficher les mots de passe</span>
      </label>
    </div>

    <div class="buttons">
      <a class="secondary" href="<?= url('index.php?action=admin_login') ?>">Retour</a>
      <button type="submit" class="primary" id="register-submit-btn">S'inscrire</button>
    </div>
  </form>
</div>

<script>
// ✅ INSCRIPTION ADMIN - Version avec validation en temps réel
(function() {
  'use strict';
  
  console.log('[CarShare Admin] Garantie anti-blocage activée');
  
  // Fonction pour débloquer le formulaire SAUF le bouton submit (géré par register.js)
  function garantirFormulaireUtilisable() {
    try {
      const form = document.getElementById('register-form');
      if (!form) return;
      
      // Activer TOUS les éléments SAUF le bouton submit
      form.querySelectorAll('input, textarea, select, a').forEach(function(element) {
        // Ne pas toucher aux champs disabled intentionnellement
        if (element.hasAttribute('readonly')) return;
        
        // Retirer attributs bloquants
        element.removeAttribute('readonly');
        
        // S'assurer que le style permet l'interaction
        element.style.pointerEvents = '';
        if (element.tagName !== 'BUTTON') {
          element.style.opacity = '';
          element.style.cursor = '';
        }
      });
      
      // Réinitialiser le bouton submit SEULEMENT s'il est en mode "Inscription..."
      const submitBtn = document.getElementById('register-submit-btn');
      if (submitBtn && submitBtn.textContent === 'Inscription...') {
        submitBtn.textContent = 'S\'inscrire';
        // Le disabled sera géré par register.js selon la validation
      }
      
    } catch (error) {
      // Ignorer silencieusement
    }
  }
  
  // Garantie absolue: déblocage toutes les 500ms
  setInterval(garantirFormulaireUtilisable, 500);
  garantirFormulaireUtilisable();
  
  // Débloquer sur tous les événements
  window.addEventListener('load', garantirFormulaireUtilisable);
  window.addEventListener('pageshow', garantirFormulaireUtilisable);
  document.addEventListener('visibilitychange', garantirFormulaireUtilisable);
})();
</script>

<script src="<?= asset('js/password-toggle.js?v=' . time()) ?>"></script>
