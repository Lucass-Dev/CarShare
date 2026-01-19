<link rel="stylesheet" href="<?= asset('styles/conn.css?v=' . time()) ?>">

<div class="login-box" style="max-width: 600px;">
  <?php
  $token = $_GET['token'] ?? '';
  $success = $_GET['success'] ?? false;
  $error = $_GET['error'] ?? '';
  ?>
  
  <?php if ($success): ?>
    <!-- Success state -->
    <div style="text-align: center; margin-bottom: 30px;">
      <div style="font-size: 64px; margin-bottom: 20px;">✅</div>
      <h2 style="color: #059669; margin-bottom: 15px;">Email validé !</h2>
    </div>
    
    <div style="background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%); border: 2px solid #6ee7b7; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
      <p style="font-size: 16px; line-height: 1.6; color: #065f46; margin: 0; text-align: center;">
        <strong>Félicitations !</strong> Votre adresse email a été validée avec succès.
      </p>
    </div>
    
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
      <h3 style="color: #6b21a8; font-size: 18px; margin-bottom: 16px;">
        📌 Prochaine étape
      </h3>
      <p style="color: #4b5563; line-height: 1.6; margin-bottom: 16px;">
        Votre compte administrateur est maintenant <strong>en attente de validation finale</strong> par un super-administrateur.
      </p>
      <p style="color: #6b7280; font-size: 14px; margin: 0;">
        Vous recevrez un email dès que votre compte sera activé et prêt à l'emploi.
      </p>
    </div>
    
    <div class="buttons" style="display: flex; gap: 12px; justify-content: center;">
      <a href="<?= url('index.php?action=home') ?>" class="secondary">
        Retour à l'accueil
      </a>
      <a href="<?= url('index.php?action=admin_login') ?>" class="primary" style="background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%); color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600;">
        Connexion admin
      </a>
    </div>
    
  <?php elseif ($error): ?>
    <!-- Error state -->
    <div style="text-align: center; margin-bottom: 30px;">
      <div style="font-size: 64px; margin-bottom: 20px;">❌</div>
      <h2 style="color: #dc2626; margin-bottom: 15px;">Erreur de validation</h2>
    </div>
    
    <div style="background: #fee2e2; border: 2px solid #f87171; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
      <p style="font-size: 16px; line-height: 1.6; color: #991b1b; margin: 0; text-align: center;">
        <?= htmlspecialchars($error) ?>
      </p>
    </div>
    
    <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <h3 style="color: #92400e; font-size: 16px; margin-bottom: 12px;">🔧 Solutions possibles :</h3>
      <ul style="color: #78350f; margin: 0; padding-left: 20px; line-height: 1.8;">
        <li>Vérifiez que vous avez cliqué sur le bon lien</li>
        <li>Le lien peut avoir expiré (24h de validité)</li>
        <li>Contactez le support à <a href="mailto:carshare.cov@gmail.com" style="color: #6b21a8;">carshare.cov@gmail.com</a></li>
      </ul>
    </div>
    
    <div class="buttons" style="display: flex; gap: 12px; justify-content: center;">
      <a href="<?= url('index.php?action=home') ?>" class="secondary">
        Retour à l'accueil
      </a>
      <a href="<?= url('index.php?action=admin_register') ?>" class="primary" style="background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%); color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600;">
        Nouvelle inscription
      </a>
    </div>
    
  <?php else: ?>
    <!-- Processing state -->
    <div style="text-align: center; margin-bottom: 30px;">
      <div style="font-size: 64px; margin-bottom: 20px;">
        <span style="display: inline-block; animation: spin 2s linear infinite;">🔄</span>
      </div>
      <h2 style="color: #6b21a8; margin-bottom: 15px;">Validation en cours...</h2>
    </div>
    
    <div style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border: 2px solid #93c5fd; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
      <p style="font-size: 16px; line-height: 1.6; color: #1e40af; margin: 0; text-align: center;">
        Veuillez patienter pendant que nous validons votre email...
      </p>
    </div>
  <?php endif; ?>
</div>

<style>
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.buttons a {
  display: inline-block;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  text-decoration: none;
}

.buttons a.secondary {
  background: white;
  color: #6b7280;
  border: 2px solid #e5e7eb;
}

.buttons a.secondary:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

.buttons a.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(107, 33, 168, 0.3);
}
</style>

<?php if (empty($success) && empty($error) && !empty($token)): ?>
<script>
// Auto-validation du token
document.addEventListener('DOMContentLoaded', function() {
    const token = '<?= htmlspecialchars($token, ENT_QUOTES) ?>';
    if (token) {
        // Rediriger vers le contrôleur de validation
        window.location.href = '<?= url('index.php?action=admin_validate_email&token=') ?>' + token;
    }
});
</script>
<?php endif; ?>
