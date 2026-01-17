<?php

require_once __DIR__ . '/../model/TokenManager.php';
require_once __DIR__ . '/../model/RegisterModel.php';
require_once __DIR__ . '/../model/Config.php';

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
                    Config::redirect('home', ['registration_complete' => '1']);
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
}
