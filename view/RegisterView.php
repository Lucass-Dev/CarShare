<link rel="stylesheet" href="<?= asset('styles/inscr.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/register-validation.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/password-toggle-checkbox.css?v=' . time()) ?>">

<div class="login-box">
  <h2>S'inscrire</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #dc2626;">
      <strong>⚠️ Erreur :</strong> <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="<?= url('index.php?action=register') ?>" novalidate id="register-form">
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

    <p class="legal-consent">
      <label>
        <input 
          type="checkbox" 
          name="accept_terms" 
          value="1" 
          <?= isset($_POST['accept_terms']) ? 'checked' : '' ?>
          required
        >
        <span>
          J'accepte les conditions CarShare (
          <a href="<?= url('index.php?action=cgu') ?>" target="_blank" onclick="event.stopPropagation();">CGU</a>,
          <a href="<?= url('index.php?action=cgv') ?>" target="_blank" onclick="event.stopPropagation();">CGV</a>,
          <a href="<?= url('index.php?action=legal') ?>" target="_blank" onclick="event.stopPropagation();">Mentions légales</a>
          )
        </span>
      </label>
    </p>

    <div class="buttons">
      <a class="secondary" href="<?= url('index.php?action=login') ?>">Déjà un compte ?</a>
      <button type="submit" class="primary" id="register-submit-btn">S'inscrire</button>
    </div>
  </form>
</div>

<script>
// 🔒 INSCRIPTION ANTI-FREEZE + VALIDATION - Version Corrigée
(function() {
  'use strict';
  
  const form = document.getElementById('register-form');
  const submitBtn = document.getElementById('register-submit-btn');
  const cguCheckbox = document.querySelector('input[name="accept_terms"]');
  
  // FONCTION D'URGENCE : Réactivation totale
  function unlockForm() {
    if (!form) return;
    
    form.querySelectorAll('input, button, select, textarea').forEach(el => {
      el.disabled = false;
      el.removeAttribute('disabled');
      el.style.opacity = '1';
      el.style.pointerEvents = 'auto';
    });
    
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = 'S\'inscrire';
      submitBtn.style.cursor = 'pointer';
    }
    
    console.log('[CarShare] Formulaire débloqué');
  }
  
  // Fonction d'affichage d'erreur
  function showError(message) {
    // Supprimer les anciennes erreurs JS
    const oldError = form.querySelector('.js-error-message');
    if (oldError) oldError.remove();
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message js-error-message';
    errorDiv.style.cssText = 'background: #fee2e2; color: #dc2626; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 600; animation: shake 0.5s;';
    errorDiv.innerHTML = '<strong>⚠️ Erreur :</strong> ' + message;
    
    form.insertBefore(errorDiv, form.firstChild);
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  
  // Déblocage immédiat au chargement
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', unlockForm);
  } else {
    unlockForm();
  }
  
  // Déblocage sur événements critiques
  window.addEventListener('pageshow', unlockForm);
  document.addEventListener('visibilitychange', function() {
    if (!document.hidden) unlockForm();
  });
  
  // VALIDATION AVANT SOUMISSION
  if (form) {
    form.addEventListener('submit', function(e) {
      // Supprimer les anciennes erreurs JS
      const oldError = form.querySelector('.js-error-message');
      if (oldError) oldError.remove();
      
      // VALIDATION CGU OBLIGATOIRE
      if (cguCheckbox && !cguCheckbox.checked) {
        e.preventDefault();
        showError('Vous devez accepter les CGU, CGV et Mentions légales pour continuer');
        cguCheckbox.focus();
        return false;
      }
      
      // VALIDATION BASIQUE DES CHAMPS
      const lastName = form.querySelector('input[name="last_name"]');
      const firstName = form.querySelector('input[name="first_name"]');
      const email = form.querySelector('input[name="email"]');
      const emailConfirm = form.querySelector('input[name="email_confirm"]');
      const password = form.querySelector('input[name="password"]');
      const confirmPassword = form.querySelector('input[name="confirm_password"]');
      
      if (!lastName || !lastName.value.trim()) {
        e.preventDefault();
        showError('Le nom est obligatoire');
        lastName.focus();
        return false;
      }
      
      if (!firstName || !firstName.value.trim()) {
        e.preventDefault();
        showError('Le prénom est obligatoire');
        firstName.focus();
        return false;
      }
      
      if (!email || !email.value.trim()) {
        e.preventDefault();
        showError('L\'email est obligatoire');
        email.focus();
        return false;
      }
      
      if (email.value !== emailConfirm.value) {
        e.preventDefault();
        showError('Les adresses email ne correspondent pas');
        emailConfirm.focus();
        return false;
      }
      
      if (password.value.length < 12) {
        e.preventDefault();
        showError('Le mot de passe doit contenir au moins 12 caractères');
        password.focus();
        return false;
      }
      
      if (password.value !== confirmPassword.value) {
        e.preventDefault();
        showError('Les mots de passe ne correspondent pas');
        confirmPassword.focus();
        return false;
      }
      
      // ✅ VALIDATION PASSÉE - AUTORISER LA SOUMISSION
      console.log('[CarShare] Formulaire valide, soumission...');
      
      // Désactiver temporairement (évite double-soumission)
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Inscription...';
      }
      
      // Sécurité : Réactiver après 5 secondes en cas de problème
      setTimeout(unlockForm, 5000);
      
      // LAISSER LE FORMULAIRE SE SOUMETTRE NORMALEMENT
      return true;
    });
  }
  
  // Si erreur PHP présente, débloquer immédiatement
  if (document.querySelector('.error-message:not(.js-error-message)')) {
    unlockForm();
  }
})();
</script>

<script src="<?= asset('js/password-toggle.js?v=' . time()) ?>"></script>
