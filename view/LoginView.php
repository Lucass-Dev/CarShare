<div class="login-box">
  <h2>Se connecter</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="color: red; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="/CarShare/index.php?action=login">
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