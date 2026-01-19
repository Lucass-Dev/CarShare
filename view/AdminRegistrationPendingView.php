<link rel="stylesheet" href="<?= asset('styles/conn.css?v=' . time()) ?>">

<div class="login-box" style="max-width: 600px;">
  <div style="text-align: center; margin-bottom: 30px;">
    <div style="font-size: 64px; margin-bottom: 20px;">⏳</div>
    <h2 style="color: #6b21a8; margin-bottom: 15px;">Demande en attente</h2>
  </div>
  
  <div style="background: linear-gradient(135deg, #f3e8ff 0%, #faf5ff 100%); border: 2px solid #e9d5ff; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
    <p style="font-size: 16px; line-height: 1.6; color: #374151; margin: 0;">
      Votre demande de compte administrateur a été envoyée avec succès !
    </p>
  </div>
  
  <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
    <h3 style="color: #6b21a8; font-size: 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
      <span>📋</span> Prochaines étapes
    </h3>
    
    <ol style="padding-left: 20px; margin: 0; color: #4b5563; line-height: 1.8;">
      <li style="margin-bottom: 12px;">
        <strong>Vérification en cours</strong> : Un administrateur va examiner votre demande.
      </li>
      <li style="margin-bottom: 12px;">
        <strong>Email de confirmation</strong> : Vous recevrez un email à <strong style="color: #6b21a8;"><?= htmlspecialchars($_SESSION['pending_admin_email'] ?? 'votre adresse') ?></strong> dès que votre compte sera validé.
      </li>
      <li style="margin-bottom: 12px;">
        <strong>Délai de traitement</strong> : Généralement sous 24-48 heures.
      </li>
    </ol>
  </div>
  
  <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
    <div style="display: flex; align-items: flex-start; gap: 12px;">
      <span style="font-size: 24px;">💡</span>
      <div style="flex: 1;">
        <strong style="color: #92400e; display: block; margin-bottom: 8px;">Conseil</strong>
        <p style="color: #78350f; margin: 0; font-size: 14px; line-height: 1.6;">
          Vérifiez vos spams/courriers indésirables si vous ne recevez pas d'email dans les 48h. 
          Pour toute question, contactez-nous à <a href="mailto:carshare.cov@gmail.com" style="color: #6b21a8; text-decoration: none; font-weight: 600;">carshare.cov@gmail.com</a>
        </p>
      </div>
    </div>
  </div>
  
  <div class="buttons" style="display: flex; gap: 12px; justify-content: center;">
    <a href="<?= url('index.php?action=home') ?>" class="secondary">
      Retour à l'accueil
    </a>
    <a href="<?= url('index.php?action=admin_login') ?>" class="primary" style="background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%); color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s;">
      Connexion admin
    </a>
  </div>
</div>

<style>
.login-box ol li strong {
  color: #1f2937;
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
