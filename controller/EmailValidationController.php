<?php

require_once __DIR__ . '/../model/TokenManager.php';
require_once __DIR__ . '/../model/RegisterModel.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Database.php';

class EmailValidationController {
    
    public function validate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = $_GET['token'] ?? '';
        $error = null;
        $success = false;
        
        if (empty($token)) {
            $error = "Token de validation manquant";
        } else {
            $tokenManager = new TokenManager();
            $tokenData = $tokenManager->validateToken($token, 'email_validation');
            
            if (!$tokenData) {
                $error = "Token invalide ou expiré. Veuillez vous réinscrire.";
            } else {
                $model = new RegisterModel();
                $user = $model->getUserById($tokenData['user_id']);
                
                if (!$user) {
                    $error = "Utilisateur introuvable";
                } else {
                    // Activate user (assuming there's an 'active' field, or just log them in)
                    // Since we can't modify DB, we'll just log them in
                    $success = true;
                    
                    // Auto login
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
                    $_SESSION['logged'] = true;
                    
                    // Delete token
                    $tokenManager->deleteToken($token);
                    
                    // Redirect to home
                    redirect(url('index.php?action=home&registration_complete=1'));
                }
            }
        }
        
        require __DIR__ . '/../view/EmailValidationView.php';
    }
    
    public function pending() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $email = $_SESSION['pending_email'] ?? null;
        
        require __DIR__ . '/../view/RegistrationPendingView.php';
    }

    /**
     * Admin registration pending page
     */
    public function adminPending() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $email = $_SESSION['pending_admin_email'] ?? null;
        $message = $_SESSION['registration_success_message'] ?? "Votre demande de compte administrateur a été envoyée.";
        unset($_SESSION['registration_success_message']); // Clear message after showing
        
        require __DIR__ . '/../view/AdminRegistrationPendingView.php';
    }

    /**
     * Validate admin email and activate admin account
     */
    public function validateAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = $_GET['token'] ?? '';
        $error = null;
        $success = false;
        
        if (empty($token)) {
            $error = "Token de validation manquant";
        } else {
            $tokenManager = new TokenManager();
            $tokenData = $tokenManager->validateToken($token, 'admin_email_validation');
            
            if (!$tokenData) {
                $error = "Token invalide ou expiré.";
            } else {
                $model = new RegisterModel();
                $user = $model->getUserById($tokenData['user_id']);
                
                if (!$user) {
                    $error = "Utilisateur introuvable";
                } elseif (!$user['is_admin']) {
                    $error = "Ce compte n'est pas un compte administrateur";
                } else {
                    // Admin account validated - activate user
                    $db = Database::getDb();
                    $stmt = $db->prepare("UPDATE users SET is_verified_user = 1 WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    $success = true;
                    
                    // Delete token
                    $tokenManager->deleteToken($token);
                    
                    // ✅ Send confirmation email to the new admin
                    require_once __DIR__ . '/../model/EmailService.php';
                    $emailService = new EmailService();
                    $fullName = $user['first_name'] . ' ' . $user['last_name'];
                    $emailSent = $emailService->sendAdminAccountActivatedEmail($user['email'], $fullName);
                    
                    if ($emailSent) {
                        error_log("Email de confirmation envoyé à {$user['email']} pour activation compte admin");
                        $_SESSION['admin_activation_success'] = "Le compte administrateur de " . htmlspecialchars($fullName) . " a été activé avec succès. Un email de confirmation lui a été envoyé.";
                    } else {
                        error_log("Échec envoi email de confirmation à {$user['email']} pour activation compte admin");
                        $_SESSION['admin_activation_success'] = "Le compte administrateur de " . htmlspecialchars($fullName) . " a été activé avec succès.";
                    }
                }
            }
        }
        
        require __DIR__ . '/../view/AdminEmailValidationView.php';
    }
}
