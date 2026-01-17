<link rel="stylesheet" href="/CarShare/assets/styles/register-validation.css">
<div class="login-box">
  <h2>S'inscrire</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="color: red; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="/CarShare/index.php?action=register" novalidate>
    <div class="form-field">
      <input type="text" name="last_name" placeholder="Nom *" required maxlength="50" pattern="[a-zA-ZÀ-ÿ\s'-]+" title="Le nom est obligatoire (2-50 caractères, lettres uniquement)" />
    </div>
    
    <div class="form-field">
      <input type="text" name="first_name" placeholder="Prénom *" required maxlength="50" pattern="[a-zA-ZÀ-ÿ\s'-]+" title="Le prénom est obligatoire (2-50 caractères, lettres uniquement)" />
    </div>
    
    <div class="form-field">
      <input type="email" name="email" placeholder="Email" required />
    </div>
    
    <div class="form-field">
      <input type="email" name="email_confirm" placeholder="Confirmer l'email" required />
    </div>
    
    <div class="form-field">
      <input type="password" name="password" placeholder="Mot de passe (min 12 caractères)" required minlength="12" />
    </div>
    
    <div class="form-field">
      <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required />
    </div>

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

<script src="/CarShare/assets/js/register-validation.js"></script>
