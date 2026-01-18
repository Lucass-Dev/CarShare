<link rel="stylesheet" href="<?= asset('styles/conn.css?v=' . time()) ?>">

<div class="login-box">
  <h2>Mot de passe oublié</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <?php if (isset($success)): ?>
    <div class="success-message" style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="<?= url('index.php?action=forgot_password') ?>">
    <p style="font-size: 0.9em; color: #666; margin-bottom: 20px;">
      Entrez votre adresse email et nous vous enverrons un lien sécurisé pour réinitialiser votre mot de passe.
    </p>
    
    <input type="email" name="email" placeholder="Votre adresse email" required />
    
    <div class="buttons">
      <a class="secondary" href="<?= url('index.php?action=login') ?>">Retour à la connexion</a>
      <button type="submit" class="primary">Envoyer le lien</button>
    </div>
  </form>
  
  <p style="font-size: 0.85em; color: #64748b; margin-top: 20px; text-align: center;">
    📧 Vous recevrez un email dans quelques instants avec les instructions pour réinitialiser votre mot de passe.
  </p>
</div>

<script src="<?= asset('js/universal-form-protection.js') ?>"></script>
