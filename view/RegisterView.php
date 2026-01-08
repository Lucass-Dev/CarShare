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
    <input type="password" name="password" placeholder="Mot de passe (min 12 caractères)" required minlength="12" />
    <input type="password" name="confirm_password" placeholder="Confirmation" required />

    <p class="legal-consent">
      <label>
        <input type="checkbox" name="accept_terms" value="1" required>
        <span>
          J'accepte les conditions CarShare (
          <a href="/CarShare/index.php?action=cgu" target="_blank">CGU</a>,
          <a href="/CarShare/index.php?action=cgv" target="_blank">CGV</a>,
          <a href="/CarShare/index.php?action=legal" target="_blank">Mentions légales</a>
          )
        </span>
      </label>
    </p>

    <div class="buttons">
      <a class="secondary" href="?action=login">Déjà un compte ?</a>
      <button type="submit" class="primary">S'inscrire</button>
    </div>
  </form>
</div>
