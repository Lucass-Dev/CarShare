<div class="login-box">
  <h2>Réinitialiser le mot de passe</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="color: red; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <?php if (isset($success)): ?>
    <div class="success-message" style="color: green; margin-bottom: 15px;">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="/CarShare/index.php?action=forgot_password">
    <p style="font-size: 0.9em; color: #666; margin-bottom: 20px;">
      Entrez votre email et vos anciens et nouveaux mots de passe pour réinitialiser votre accès.
    </p>
    
    <input type="email" name="email" placeholder="Votre email" required />
    <input type="password" name="old_password" placeholder="Ancien mot de passe" required />
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required />
    <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" required />
    
    <div class="buttons">
      <a class="secondary" href="/CarShare/index.php?action=login">Retour à la connexion</a>
      <button type="submit" class="primary">Réinitialiser</button>
    </div>
  </form>
</div>
