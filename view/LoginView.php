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
  
  <form method="POST" action="/CarShare/index.php?action=login">
    <?php if (isset($_GET['return_url'])): ?>
      <input type="hidden" name="return_url" value="<?= htmlspecialchars($_GET['return_url']) ?>" />
    <?php endif; ?>
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Mot de passe" required />
    
    <div style="text-align: right; margin: 10px 0;">
      <a href="/CarShare/index.php?action=forgot_password" style="color: #3065ad; font-size: 0.9em; text-decoration: none;">Mot de passe oublié ?</a>
    </div>
    
    <div class="buttons">
      <a class="secondary" href="/CarShare/index.php?action=register">Pas de compte ?</a>
      <button type="submit" class="primary">Se connecter</button>
    </div>
  </form>
</div>