<?php

require_once __DIR__ . '/../model/ContactModel.php';
require_once __DIR__ . '/../src/Exception.php';
require_once __DIR__ . '/../src/PHPMailer.php';
require_once __DIR__ . '/../src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController {
    private $contactModel;

    public function __construct() {
        $this->contactModel = new ContactModel();
    }
    
    /**
     * Sanitize user input to prevent XSS and other attacks
     */
    private function sanitizeInput($input) {
        if (!is_string($input)) {
            return '';
        }
        
        // Remove control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }
    
    /**
     * Validate input for security threats
     */
    private function validateSecurity($value, $fieldName = 'champ') {
        if (empty($value)) {
            return true;
        }
        
        // Patterns de détection d'attaques
        $patterns = [
            'sql' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/i',
            'xss' => '/<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover/i',
            'hex' => '/(\\\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/',
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }
        
        return true;
    }

    public function render() {
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        
        require_once __DIR__ . '/../view/ContactView.php';
        $view = new ContactView();
        $view->display($success, $error);
    }

    /**
     * Send contact form content by email to the support inbox.
     *
     * @return bool true on success, false on failure
     */
    private function sendContactEmail($name, $email, $subject, $message) {
        try {
            $mail = new PHPMailer(true);

            // Server settings (Gmail SMTP example)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'carshare.cov@gmail.com';
            // Mot de passe applicatif Gmail pour carshare.cov@gmail.com
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
            
            // Débogage (à désactiver en production)
            // $mail->SMTPDebug = 2; // Décommenter pour voir les détails SMTP
            // $mail->Debugoutput = 'error_log';

            // Recipients
            $mail->setFrom('carshare.cov@gmail.com', 'Formulaire de contact CarShare');
            $mail->addAddress('carshare.cov@gmail.com');
            // Permet de répondre directement à l'utilisateur
            $mail->addReplyTo($email, $name);

            // Content
            $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safeEmail = htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safeSubject = htmlspecialchars($subject, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message de contact : ' . $safeSubject;
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
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #8f9bff; }
        .info-row { margin: 10px 0; }
        .label { font-weight: bold; color: #1e293b; }
        .value { color: #64748b; }
        .message-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nouveau message de contact</h1>
        </div>
        <div class="content">
            <div class="info-box">
                <div class="info-row">
                    <span class="label">De :</span>
                    <span class="value">' . $safeName . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Email :</span>
                    <span class="value"><a href="mailto:' . $safeEmail . '" style="color: #8f9bff;">' . $safeEmail . '</a></span>
                </div>
                <div class="info-row">
                    <span class="label">Sujet :</span>
                    <span class="value">' . $safeSubject . '</span>
                </div>
            </div>
            
            <div class="message-box">
                <p class="label">Message :</p>
                <p style="color: #333; line-height: 1.8;">' . $safeMessage . '</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' CarShare - Formulaire de contact</p>
        </div>
    </div>
</body>
</html>';

            $mail->AltBody =
                "Nouveau message depuis le formulaire de contact\n" .
                "Nom : " . $name . "\n" .
                "Email : " . $email . "\n" .
                "Sujet : " . $subject . "\n\n" .
                "Message :\n" . $message;

            return $mail->send();
        } catch (Exception $e) {
            // Log détaillé pour diagnostic
            error_log('=== ERREUR EMAIL CONTACT ===');
            error_log('Message: ' . $e->getMessage());
            error_log('Code: ' . $e->getCode());
            error_log('Trace: ' . $e->getTraceAsString());
            error_log('===========================');
            return false;
        }
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /CarShare/index.php?action=contact&error=invalid_method');
            exit();
        }

        $name = $this->sanitizeInput($_POST['name'] ?? '');
        $email = $this->sanitizeInput($_POST['email'] ?? '');
        $subject = $this->sanitizeInput($_POST['subject'] ?? '');
        $message = $this->sanitizeInput($_POST['message'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            header('Location: /CarShare/index.php?action=contact&error=missing_fields');
            exit();
        }
        
        // Security validation
        if (!$this->validateSecurity($name, 'nom') || 
            !$this->validateSecurity($subject, 'sujet') || 
            !$this->validateSecurity($message, 'message')) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_input');
            exit();
        }
        
        // Validate name length and format
        if (strlen($name) < 2 || strlen($name) > 100) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_name');
            exit();
        }
        
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/u", $name)) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_name');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_email');
            exit();
        }
        
        // Validate subject length
        if (strlen($subject) < 3 || strlen($subject) > 200) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_subject');
            exit();
        }

        if (strlen($message) < 10 || strlen($message) > 5000) {
            header('Location: /CarShare/index.php?action=contact&error=message_length');
            exit();
        }

        // Envoi direct de l'email sans enregistrement en base
        $emailSent = $this->sendContactEmail($name, $email, $subject, $message);
        
        if ($emailSent) {
            header('Location: /CarShare/index.php?action=contact&success=1');
        } else {
            error_log('Échec de l\'envoi de l\'email de contact.');
            header('Location: /CarShare/index.php?action=contact&error=email_error');
        }
        exit();
    }
}
