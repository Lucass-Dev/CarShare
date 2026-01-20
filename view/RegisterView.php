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
// ✅ INSCRIPTION - Version FINALE avec validation en temps réel
(function() {
  'use strict';
  
  console.log('[CarShare] Chargement script inscription');
  
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
      // Ignorer les erreurs silencieusement
    }
  }
  
  // Appeler la fonction de déblocage toutes les 500ms (garantie absolue)
  setInterval(garantirFormulaireUtilisable, 500);
  
  // Appeler immédiatement
  garantirFormulaireUtilisable();
  
  // Fonction d'initialisation principale
  function init() {
    const form = document.getElementById('register-form');
    if (!form) return;
    
    console.log('[CarShare] Formulaire trouvé, initialisation...');
    
    // Débloquer immédiatement
    garantirFormulaireUtilisable();
    
    // === VALIDATION SIMPLE (sans blocage) ===
    form.addEventListener('submit', function(e) {
      console.log('[CarShare] Soumission du formulaire');
      
      // Supprimer anciennes erreurs
      const oldErrors = document.querySelectorAll('.js-error-message');
      oldErrors.forEach(function(err) { err.remove(); });
      
      // Récupérer les champs
      const lastName = form.querySelector('input[name="last_name"]');
      const firstName = form.querySelector('input[name="first_name"]');
      const email = form.querySelector('input[name="email"]');
      const emailConfirm = form.querySelector('input[name="email_confirm"]');
      const password = form.querySelector('input[name="password"]');
      const confirmPassword = form.querySelector('input[name="confirm_password"]');
      const cguCheckbox = form.querySelector('input[name="accept_terms"]');
      
      // Fonction helper pour afficher une erreur
      function afficherErreur(message, champAFocuser) {
        e.preventDefault();
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message js-error-message';
        errorDiv.innerHTML = '<strong>⚠️</strong> ' + message;
        form.insertBefore(errorDiv, form.firstChild);
        
        if (champAFocuser) {
          setTimeout(function() { champAFocuser.focus(); }, 100);
        }
        
        // CRITIQUE: Garantir que le formulaire reste utilisable après l'erreur
        setTimeout(garantirFormulaireUtilisable, 100);
        setTimeout(garantirFormulaireUtilisable, 300);
        setTimeout(garantirFormulaireUtilisable, 500);
        
        return false;
      }
      
      // Validations
      if (!cguCheckbox || !cguCheckbox.checked) {
        return afficherErreur('Vous devez accepter les CGU, CGV et Mentions légales', cguCheckbox);
      }
      
      if (!lastName || !lastName.value.trim()) {
        return afficherErreur('Le nom est obligatoire', lastName);
      }
      
      if (!firstName || !firstName.value.trim()) {
        return afficherErreur('Le prénom est obligatoire', firstName);
      }
      
      if (!email || !email.value.trim()) {
        return afficherErreur('L\'email est obligatoire', email);
      }
      
      if (!emailConfirm || email.value !== emailConfirm.value) {
        return afficherErreur('Les adresses email ne correspondent pas', emailConfirm);
      }
      
      if (!password || password.value.length < 12) {
        return afficherErreur('Le mot de passe doit contenir au moins 12 caractères', password);
      }
      
      if (!confirmPassword || password.value !== confirmPassword.value) {
        return afficherErreur('Les mots de passe ne correspondent pas', confirmPassword);
      }
      
      // Si tout est valide, laisser le formulaire se soumettre normalement
      console.log('[CarShare] Validation OK, soumission...');
      const submitBtn = document.getElementById('register-submit-btn');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Inscription...';
      }
      
      return true;
    });
    
    console.log('[CarShare] ✓ Initialisation terminée');
  }
  
  // Lancer l'initialisation dès que possible
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  // Débloquer sur tous les événements importants
  window.addEventListener('load', garantirFormulaireUtilisable);
  window.addEventListener('pageshow', garantirFormulaireUtilisable);
  document.addEventListener('visibilitychange', garantirFormulaireUtilisable);
  
  console.log('[CarShare] Script inscription chargé ✓');
})();
</script>

<script src="<?= asset('js/password-toggle.js?v=' . time()) ?>"></script>
