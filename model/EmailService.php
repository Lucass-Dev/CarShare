<?php

require_once __DIR__ . '/../src/Exception.php';
require_once __DIR__ . '/../src/PHPMailer.php';
require_once __DIR__ . '/../src/SMTP.php';
require_once __DIR__ . '/Config.php';

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
            $baseUrl = Config::getBaseUrl();
            $validationLink = $baseUrl . "/index.php?action=validate_email&token=" . urlencode($token);
            
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
            $baseUrl = Config::getBaseUrl();
            $resetLink = $baseUrl . "/index.php?action=reset_password&token=" . urlencode($token);
            
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
}
