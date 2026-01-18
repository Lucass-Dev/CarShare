<link rel="stylesheet" href="<?= asset('styles/conn.css?v=' . time()) ?>">
<link rel="stylesheet" href="<?= asset('styles/password-toggle-checkbox.css?v=' . time()) ?>">

<div class="login-box">
  <h2>Se connecter</h2>
  
  <?php if (isset($_GET['password_reset'])): ?>
    <div class="success-message" style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
      ✓ Votre mot de passe a été modifié avec succès ! Vous pouvez maintenant vous connecter.
    </div>
  <?php endif; ?>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="<?= url('index.php?action=login') ?>">
    <?php if (isset($_GET['return_url'])): ?>
      <input type="hidden" name="return_url" value="<?= htmlspecialchars($_GET['return_url']) ?>" />
    <?php endif; ?>
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" id="login-password" placeholder="Mot de passe" required />
    
    <div class="show-password-container">
      <label class="show-password-label">
        <input type="checkbox" id="show-password-login" />
        <span>Afficher le mot de passe</span>
      </label>
    </div>
    
    <div style="text-align: right; margin: 8px 0 14px;">
      <a href="<?= url('index.php?action=forgot_password') ?>" style="color: #3065ad; text-decoration: none; font-size: 14px;">Mot de passe oublié ?</a>
    </div>
    
    <div class="buttons">
      <a class="secondary" href="<?= url('index.php?action=register') ?>">Pas de compte ?</a>
      <button type="submit" class="primary" id="login-submit-btn">Se connecter</button>
    </div>
  </form>
</div>

<script src="<?= asset('js/password-toggle.js?v=' . time()) ?>"></script>
<script src="<?= asset('js/login-validation.js?v=' . time()) ?>"></script>