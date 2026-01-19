<link rel="stylesheet" href="<?= asset('styles/conn.css?v=' . time()) ?>">

<style>
.admin-pending-container {
  max-width: 650px;
  margin: 0 auto;
}

.admin-pending-icon {
  font-size: 80px;
  margin-bottom: 24px;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.05); opacity: 0.8; }
}

.admin-pending-title {
  background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 16px;
}

.success-banner {
  background: linear-gradient(135deg, #f3e8ff 0%, #faf5ff 100%);
  border: 2px solid #e9d5ff;
  border-radius: 16px;
  padding: 28px;
  margin-bottom: 28px;
  box-shadow: 0 4px 12px rgba(107, 33, 168, 0.08);
  position: relative;
  overflow: hidden;
}

.success-banner::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, #6b21a8, #9333ea, #6b21a8);
  background-size: 200% 100%;
  animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.success-banner p {
  font-size: 17px;
  line-height: 1.7;
  color: #374151;
  margin: 0;
  font-weight: 500;
}

.steps-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  padding: 28px;
  margin-bottom: 28px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.steps-title {
  color: #6b21a8;
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.steps-list {
  padding-left: 24px;
  margin: 0;
  color: #4b5563;
  line-height: 1.8;
}

.steps-list li {
  margin-bottom: 16px;
  padding-left: 8px;
  position: relative;
}

.steps-list li::marker {
  font-weight: 700;
  color: #6b21a8;
}

.steps-list strong {
  color: #1f2937;
  font-weight: 600;
}

.user-email-highlight {
  color: #6b21a8;
  font-weight: 600;
  background: #f3e8ff;
  padding: 2px 8px;
  border-radius: 6px;
}

.info-card {
  background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
  border: 2px solid #fcd34d;
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 28px;
  box-shadow: 0 2px 8px rgba(252, 211, 77, 0.15);
}

.info-card-content {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}

.info-card-icon {
  font-size: 28px;
  flex-shrink: 0;
}

.info-card-title {
  color: #92400e;
  font-weight: 700;
  font-size: 16px;
  margin-bottom: 10px;
}

.info-card-text {
  color: #78350f;
  margin: 0;
  font-size: 15px;
  line-height: 1.7;
}

.info-card-text a {
  color: #6b21a8;
  text-decoration: none;
  font-weight: 700;
  border-bottom: 2px solid transparent;
  transition: border-color 0.3s;
}

.info-card-text a:hover {
  border-bottom-color: #6b21a8;
}

.action-buttons {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  display: inline-block;
  padding: 14px 28px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 15px;
  transition: all 0.3s ease;
  text-decoration: none;
  cursor: pointer;
  border: none;
}

.btn-secondary {
  background: white;
  color: #6b7280;
  border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
  background: #f9fafb;
  border-color: #d1d5db;
  transform: translateY(-1px);
}

.btn-primary {
  background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(107, 33, 168, 0.2);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(107, 33, 168, 0.35);
}
</style>

<div class="login-box admin-pending-container">
  <div style="text-align: center; margin-bottom: 32px;">
    <div class="admin-pending-icon">⏳</div>
    <h2 class="admin-pending-title">Demande en attente de validation</h2>
  </div>
  
  <div class="success-banner">
    <p>
      ✨ Votre demande de compte administrateur a été envoyée avec succès ! Nos administrateurs vont examiner votre demande dans les plus brefs délais.
    </p>
  </div>
  
  <div class="steps-card">
    <h3 class="steps-title">
      <span>📋</span> Prochaines étapes
    </h3>
    
    <ol class="steps-list">
      <li>
        <strong>Vérification en cours</strong> : Un administrateur va examiner votre demande et valider votre identité.
      </li>
      <li>
        <strong>Email de confirmation</strong> : Vous recevrez un email automatique à <span class="user-email-highlight"><?= htmlspecialchars($_SESSION['pending_admin_email'] ?? 'votre adresse') ?></span> dès que votre compte sera activé.
      </li>
      <li>
        <strong>Accès immédiat</strong> : Une fois validé, vous pourrez vous connecter instantanément à l'espace administrateur.
      </li>
      <li>
        <strong>Délai de traitement</strong> : Généralement entre 24 et 48 heures maximum.
      </li>
    </ol>
  </div>
  
  <div class="info-card">
    <div class="info-card-content">
      <span class="info-card-icon">💡</span>
      <div style="flex: 1;">
        <div class="info-card-title">Conseil important</div>
        <p class="info-card-text">
          Pensez à vérifier votre dossier <strong>spams/courriers indésirables</strong> si vous ne recevez pas d'email dans les 48h. 
          Pour toute question urgente, contactez-nous à <a href="mailto:carshare.cov@gmail.com">carshare.cov@gmail.com</a>
        </p>
      </div>
    </div>
  </div>
  
  <div class="action-buttons">
    <a href="<?= url('index.php?action=home') ?>" class="btn btn-secondary">
      ← Retour à l'accueil
    </a>
    <a href="<?= url('index.php?action=admin_login') ?>" class="btn btn-primary">
      Connexion admin →
    </a>
  </div>
</div>
