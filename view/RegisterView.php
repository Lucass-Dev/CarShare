<div class="login-box">
  <h2>S'inscrire</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="color: red; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="/CarShare/index.php?action=register">
    <input type="text" name="last_name" placeholder="Nom" required />
    <input type="text" name="first_name" placeholder="Prénom" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Mot de passe" required />
    <input type="password" name="confirm_password" placeholder="Confirmation" required />

    <div class="buttons">
      <a class="secondary" href="/CarShare/index.php?action=login">Déjà un compte ?</a>
      <button type="submit" class="primary">S'inscrire</button>
    </div>
  </form>
</div>