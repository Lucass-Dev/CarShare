<link rel="stylesheet" href="<?= asset('styles/conn.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/password-toggle-checkbox.css?v=' . time()) ?>">

<div class="login-box">
  <h2>Connexion Administrateur</h2>
  <p style="text-align: center; color: #6b21a8; font-size: 14px; margin-bottom: 20px;">
    🔐 Accès réservé aux administrateurs
  </p>
  
  <?php if (isset($_SESSION['admin_error'])): ?>
    <div class="error-message" style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
      <?= htmlspecialchars($_SESSION['admin_error']) ?>
    </div>
    <?php unset($_SESSION['admin_error']); ?>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['admin_success'])): ?>
    <div class="success-message" style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
      ✓ <?= htmlspecialchars($_SESSION['admin_success']) ?>
    </div>
    <?php unset($_SESSION['admin_success']); ?>
  <?php endif; ?>
  
  <form method="POST" action="<?= url('index.php?action=admin_process_login') ?>" id="admin-login-form">
    <input type="email" name="email" id="admin-email" placeholder="Email administrateur" required autofocus />
    <span class="validation-error" id="email-error"></span>
    
    <input type="password" name="password" id="admin-password" placeholder="Mot de passe" required />
    <span class="validation-error" id="password-error"></span>
    
    <div class="show-password-container">
      <label class="show-password-label">
        <input type="checkbox" id="show-password-admin" />
        <span>Afficher le mot de passe</span>
      </label>
    </div>
    
    <div style="text-align: right; margin: 8px 0 14px;">
      <a href="<?= url('index.php?action=forgot_password') ?>" style="color: #6b21a8; text-decoration: none; font-size: 14px;">Mot de passe oublié ?</a>
    </div>
    
    <div class="buttons">
      <a class="secondary" href="<?= url('index.php?action=admin_register') ?>">Créer un compte admin</a>
      <button type="submit" class="primary" id="admin-login-submit-btn" style="background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%);">
        Se connecter
      </button>
    </div>
  </form>
  
  <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
    <a href="<?= url('index.php?action=login') ?>" style="color: #6b21a8; text-decoration: none; font-size: 14px;">
      ← Retour connexion normale
    </a>
  </div>
</div>

<style>
.validation-error {
  display: block;
  color: #dc2626;
  font-size: 13px;
  margin-top: 4px;
  margin-bottom: 8px;
  min-height: 18px;
}
</style>

<script src="<?= asset('js/password-toggle.js?v=' . time()) ?>"></script>
<script>
// Validation côté client pour login admin
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-login-form');
    const emailInput = document.getElementById('admin-email');
    const passwordInput = document.getElementById('admin-password');
    const submitBtn = document.getElementById('admin-login-submit-btn');
    
    function showError(fieldId, message) {
        const errorEl = document.getElementById(fieldId + '-error');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
        }
    }
    
    function clearError(fieldId) {
        const errorEl = document.getElementById(fieldId + '-error');
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.display = 'none';
        }
    }
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        clearError('admin-email');
        clearError('admin-password');
        
        // Validation email
        if (!emailInput.value.trim()) {
            showError('admin-email', 'Email requis');
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
            showError('admin-email', 'Email invalide');
            isValid = false;
        } else {
            clearError('admin-email');
        }
        
        // Validation mot de passe
        if (!passwordInput.value) {
            showError('admin-password', 'Mot de passe requis');
            isValid = false;
        } else if (passwordInput.value.length < 6) {
            showError('admin-password', 'Minimum 6 caractères');
            isValid = false;
        } else {
            clearError('admin-password');
        }
        
        if (!isValid) {
            e.preventDefault();
            submitBtn.textContent = 'Veuillez corriger les erreurs';
            setTimeout(() => {
                submitBtn.textContent = 'Se connecter';
            }, 2000);
        }
    });
});
</script>
