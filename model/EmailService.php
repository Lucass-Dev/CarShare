<?php

require_once __DIR__ . '/../src/Exception.php';
require_once __DIR__ . '/../src/PHPMailer.php';
require_once __DIR__ . '/../src/SMTP.php';
require_once __DIR__ . '/../config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    
    private $from = 'carshare.cov@gmail.com';
    private $fromName = 'CarShare';
    
    /**
     * Configure PHPMailer with Gmail SMTP settings
     */
    private function getMailer() {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'carshare.cov@gmail.com';
        $mail->Password = 'mhyyxhsdvhxgxvmn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Options SSL pour environnement local XAMPP
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->setFrom($this->from, $this->fromName);
        
        return $mail;
    }
    
    /**
     * Send registration confirmation email with validation link
     * 
     * @param string $email User email
     * @param string $name User full name
     * @param string $token Validation token
     * @return bool Success status
     */
    public function sendRegistrationConfirmation($email, $name, $token) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmez votre inscription à CarShare';
            
            $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $baseUrl = Config::getProductionUrl();
            $validationLink = $baseUrl . "index.php?action=validate_email&token=" . urlencode($token);
            
            $mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f8ff; padding: 30px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue sur CarShare !</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>' . $safeName . '</strong>,</p>
            <p>Merci de vous être inscrit(e) sur CarShare ! Pour finaliser votre inscription et activer votre compte, veuillez cliquer sur le bouton ci-dessous :</p>
            <p style="text-align: center;">
                <a href="' . $validationLink . '" class="button">Confirmer mon inscription</a>
            </p>
            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p style="font-size: 12px; word-break: break-all; color: #8f9bff;">' . $validationLink . '</p>
            <p><strong>Ce lien est valable pendant 24 heures.</strong></p>
            <p>Si vous n\'avez pas créé de compte sur CarShare, veuillez ignorer cet email.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 CarShare - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>';
            
            $mail->AltBody = "Bonjour $name,\n\nMerci de vous être inscrit(e) sur CarShare !\n\nPour confirmer votre inscription, cliquez sur ce lien :\n$validationLink\n\nCe lien est valable pendant 24 heures.\n\nSi vous n'avez pas créé de compte, ignorez cet email.\n\nCordialement,\nL'équipe CarShare";
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email inscription : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send admin account confirmation email to main admin (carshare.cov@gmail.com)
     * 
     * @param string $adminEmail Main admin email (carshare.cov@gmail.com)
     * @param string $userName New admin user full name
     * @param string $userEmail New admin user email
     * @param string $token Validation token
     * @return bool Success status
     */
    public function sendAdminAccountConfirmation($adminEmail, $userName, $userEmail, $token) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($adminEmail, 'Admin CarShare');
            $mail->isHTML(true);
            $mail->Subject = 'Nouvelle demande de compte administrateur - CarShare';
            
            $safeUserName = htmlspecialchars($userName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safeUserEmail = htmlspecialchars($userEmail, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $baseUrl = Config::getProductionUrl();
            $validationLink = $baseUrl . "index.php?action=validate_admin_email&token=" . urlencode($token);
            
            $mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff5f5; padding: 30px; border-radius: 0 0 8px 8px; }
        .info-box { background: white; padding: 20px; border-left: 4px solid #ff5252; margin: 20px 0; border-radius: 4px; }
        .button { display: inline-block; background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Nouvelle demande Admin</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Une nouvelle demande de compte <strong>administrateur</strong> a été soumise sur CarShare.</p>
            <div class="info-box">
                <p><strong>Nom :</strong> ' . $safeUserName . '</p>
                <p><strong>Email :</strong> ' . $safeUserEmail . '</p>
            </div>
            <p>Pour <strong>valider et activer</strong> ce compte administrateur, cliquez sur le bouton ci-dessous :</p>
            <p style="text-align: center;">
                <a href="' . $validationLink . '" class="button">Valider le compte admin</a>
            </p>
            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p style="font-size: 12px; word-break: break-all; color: #ff5252;">' . $validationLink . '</p>
            <p><strong>Ce lien est valable pendant 24 heures.</strong></p>
            <p>Si vous n\'avez pas demandé cette création de compte admin, veuillez ignorer cet email.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 CarShare - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>';
            
            $mail->AltBody = "Bonjour,\n\nNouvelle demande de compte administrateur :\n\nNom : $userName\nEmail : $userEmail\n\nPour valider ce compte admin, cliquez sur ce lien :\n$validationLink\n\nCe lien est valable pendant 24 heures.\n\nCordialement,\nL'équipe CarShare";
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email validation admin : ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send admin account activation confirmation to the user
     * 
     * @param string $userEmail User email address
     * @param string $userName User full name
     * @return bool Success status
     */
    public function sendAdminAccountActivatedEmail($userEmail, $userName) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($userEmail, $userName);
            $mail->isHTML(true);
            $mail->Subject = '✅ Votre compte administrateur CarShare est activé !';
            
            $safeUserName = htmlspecialchars($userName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $baseUrl = Config::getProductionUrl();
            $loginLink = $baseUrl . "index.php?action=admin_login";
            
            $mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #faf5ff; padding: 30px; border-radius: 0 0 8px 8px; }
        .success-box { background: white; padding: 20px; border-left: 4px solid #10b981; margin: 20px 0; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .button { display: inline-block; background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .info-box { background: #fffbeb; border: 1px solid #fcd34d; padding: 16px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Félicitations !</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>' . $safeUserName . '</strong>,</p>
            
            <div class="success-box">
                <h2 style="color: #10b981; margin-top: 0;">✅ Votre compte administrateur est activé !</h2>
                <p style="margin-bottom: 0;">Votre demande de compte administrateur a été approuvée. Vous pouvez maintenant accéder à l\'espace administrateur de CarShare.</p>
            </div>
            
            <p><strong>Vous pouvez désormais :</strong></p>
            <ul style="color: #4b5563; line-height: 1.8;">
                <li>Gérer les utilisateurs de la plateforme</li>
                <li>Modérer les trajets et les réservations</li>
                <li>Accéder aux statistiques et rapports</li>
                <li>Administrer tous les paramètres du site</li>
            </ul>
            
            <p style="text-align: center;">
                <a href="' . $loginLink . '" class="button">Se connecter maintenant</a>
            </p>
            
            <div class="info-box">
                <strong>📌 Vos identifiants :</strong><br>
                <strong>Email :</strong> ' . htmlspecialchars($userEmail, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '<br>
                <strong>Mot de passe :</strong> Celui que vous avez défini lors de l\'inscription
            </div>
            
            <p><strong>Besoin d\'aide ?</strong></p>
            <p>Si vous avez des questions ou besoin d\'assistance, n\'hésitez pas à contacter l\'équipe technique à <a href="mailto:carshare.cov@gmail.com" style="color: #6b21a8;">carshare.cov@gmail.com</a></p>
            
            <p>Bienvenue dans l\'équipe administrative de CarShare ! 🚀</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 CarShare - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>';
            
            $mail->AltBody = "Bonjour $userName,\n\nFélicitations ! Votre compte administrateur CarShare a été activé.\n\nVous pouvez maintenant vous connecter à l'espace administrateur :\n$loginLink\n\nEmail : $userEmail\nMot de passe : Celui que vous avez défini lors de l'inscription\n\nBienvenue dans l'équipe administrative !\n\nCordialement,\nL'équipe CarShare";
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email activation admin : ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send password reset email with secure link
     * 
     * @param string $email User email
     * @param string $name User full name
     * @param string $token Reset token
     * @return bool Success status
     */
    public function sendPasswordResetEmail($email, $name, $token) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe CarShare';
            
            $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $baseUrl = Config::getProductionUrl();
            $resetLink = $baseUrl . "index.php?action=reset_password&token=" . urlencode($token);
            
            $mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f8ff; padding: 30px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .alert { background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 20px 0; border-radius: 4px; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Réinitialisation de mot de passe</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>' . $safeName . '</strong>,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe CarShare. Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe :</p>
            <p style="text-align: center;">
                <a href="' . $resetLink . '" class="button">Réinitialiser mon mot de passe</a>
            </p>
            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p style="font-size: 12px; word-break: break-all; color: #8f9bff;">' . $resetLink . '</p>
            <div class="alert">
                <strong>⚠️ Important :</strong> Ce lien est valable pendant 1 heure seulement.
            </div>
            <p>Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email. Votre mot de passe restera inchangé.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 CarShare - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>';
            
            $mail->AltBody = "Bonjour $name,\n\nVous avez demandé la réinitialisation de votre mot de passe.\n\nCliquez sur ce lien pour définir un nouveau mot de passe :\n$resetLink\n\nCe lien est valable pendant 1 heure.\n\nSi vous n'avez pas fait cette demande, ignorez cet email.\n\nCordialement,\nL'équipe CarShare";
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email reset password : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send account deletion confirmation email
     * 
     * @param string $email User email
     * @param string $name User full name
     * @return bool Success status
     */
    public function sendAccountDeletionConfirmation($email, $name) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de suppression de votre compte CarShare';
            
            $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f8ff; padding: 30px; border-radius: 0 0 10px 10px; }
        .icon { font-size: 48px; margin-bottom: 10px; }
        h1 { margin: 0; font-size: 24px; }
        .message { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #e74c3c; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">✓</div>
            <h1>Compte supprimé</h1>
        </div>
        <div class="content">
            <p>Bonjour ' . htmlspecialchars($name) . ',</p>
            
            <div class="message">
                <p><strong>Votre compte CarShare a été définitivement supprimé.</strong></p>
                <p>Toutes vos données personnelles ont été effacées de nos serveurs.</p>
            </div>
            
            <p>Nous sommes désolés de vous voir partir. Si vous changez d\'avis, vous pourrez toujours créer un nouveau compte.</p>
            
            <p>Merci d\'avoir utilisé CarShare.</p>
            
            <div class="footer">
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                <p>&copy; ' . date('Y') . ' CarShare - Service de covoiturage</p>
            </div>
        </div>
    </div>
</body>
</html>';
            
            $mail->AltBody = "Bonjour $name,\n\nVotre compte CarShare a été définitivement supprimé.\n\nToutes vos données personnelles ont été effacées de nos serveurs.\n\nNous sommes désolés de vous voir partir. Si vous changez d'avis, vous pourrez toujours créer un nouveau compte.\n\nMerci d'avoir utilisé CarShare.\n\nCordialement,\nL'équipe CarShare";
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email suppression compte : ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envoyer un email de confirmation au passager après réservation
     * 
     * @param string $email Email du passager
     * @param string $name Nom du passager
     * @param string $subject Sujet de l'email
     * @param string $body Corps HTML de l'email
     * @param string $altBody Corps texte de l'email
     * @return bool Succès de l'envoi
     */
    public function sendBookingConfirmationToBooker($email, $name, $subject, $body, $altBody) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $altBody;
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email confirmation passager : ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envoyer un email de notification au conducteur après réservation
     * 
     * @param string $email Email du conducteur
     * @param string $name Nom du conducteur
     * @param string $subject Sujet de l'email
     * @param string $body Corps HTML de l'email
     * @param string $altBody Corps texte de l'email
     * @return bool Succès de l'envoi
     */
    public function sendBookingNotificationToProvider($email, $name, $subject, $body, $altBody) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $altBody;
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email notification conducteur : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email de confirmation de suppression de compte admin
     */
    public function sendAdminAccountDeletionConfirmation($email, $name) {
        try {
            $mail = $this->getMailer();
            
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de suppression de votre compte administrateur CarShare';
            
            $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #fff5f5; padding: 30px; border-radius: 0 0 10px 10px; }
        .icon { font-size: 48px; margin-bottom: 10px; }
        h1 { margin: 0; font-size: 24px; }
        .message { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545; }
        .warning-box { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🛡️</div>
            <h1>Compte Administrateur Supprimé</h1>
        </div>
        <div class="content">
            <p>Bonjour ' . htmlspecialchars($name) . ',</p>
            
            <div class="message">
                <p><strong>Votre compte administrateur CarShare a été définitivement supprimé.</strong></p>
                <p>Tous vos privilèges d\'administration ont été révoqués et vos données ont été effacées de nos serveurs.</p>
            </div>
            
            <div class="warning-box">
                <p><strong>⚠️ Important :</strong></p>
                <ul>
                    <li>Vos accès au tableau de bord administrateur sont révoqués</li>
                    <li>Vous ne pouvez plus gérer les utilisateurs et trajets</li>
                    <li>Cette action est irréversible</li>
                </ul>
            </div>
            
            <p>Si cette suppression n\'était pas volontaire ou si vous pensez qu\'il s\'agit d\'une erreur, veuillez contacter immédiatement notre équipe technique.</p>
            
            <p>Merci pour votre contribution à CarShare.</p>
            
            <div class="footer">
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                <p>&copy; ' . date('Y') . ' CarShare - Service de covoiturage</p>
            </div>
        </div>
    </div>
</body>
</html>';
            
            $mail->AltBody = "Bonjour $name,\n\nVotre compte administrateur CarShare a été définitivement supprimé.\n\nTous vos privilèges d'administration ont été révoqués et vos données ont été effacées de nos serveurs.\n\nSi cette suppression n'était pas volontaire, contactez immédiatement notre équipe technique.\n\nMerci pour votre contribution à CarShare.\n\nCordialement,\nL'équipe CarShare";
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email suppression compte admin : ' . $e->getMessage());
            return false;
        }
    }
}
