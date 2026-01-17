<?php
require_once __DIR__ . "/../model/LoginModel.php";
require_once __DIR__ . "/../model/EmailService.php";
require_once __DIR__ . "/../model/TokenManager.php";
require_once __DIR__ . "/../model/Config.php";

class ForgotPasswordController {

    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            if (empty($email)) {
                $error = "Veuillez entrer votre adresse email";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Adresse email invalide";
            } else {
                $model = new LoginModel();
                $user = $model->getUserByEmail($email);
                
                if ($user) {
                    // Generate reset token (1 hour expiry)
                    $tokenManager = new TokenManager();
                    $token = $tokenManager->generateToken('password_reset', $user['id'], $email, 3600);
                    
                    // Send reset email
                    $emailService = new EmailService();
                    $fullName = $user['first_name'] . ' ' . $user['last_name'];
                    $emailSent = $emailService->sendPasswordResetEmail($email, $fullName, $token);
                    
                    if ($emailSent) {
                        $success = "Un email de réinitialisation a été envoyé à votre adresse. Vérifiez votre boîte de réception.";
                    } else {
                        $error = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
                    }
                } else {
                    // For security, don't reveal if email exists
                    $success = "Si cet email existe dans notre système, vous recevrez un lien de réinitialisation.";
                }
            }
        }

        require __DIR__ . "/../view/ForgotPasswordView.php";
    }
    
    public function resetPassword() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = $_GET['token'] ?? '';
        $error = null;
        $success = null;
        $tokenData = null;
        
        if (empty($token)) {
            $error = "Token manquant";
        } else {
            $tokenManager = new TokenManager();
            $tokenData = $tokenManager->validateToken($token, 'password_reset');
            
            if (!$tokenData) {
                $error = "Token invalide ou expiré. Veuillez refaire une demande de réinitialisation.";
            }
        }
        
        // Handle password reset form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenData) {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($newPassword) || empty($confirmPassword)) {
                $error = "Veuillez remplir tous les champs";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas";
            } elseif (strlen($newPassword) < 12) {
                $error = "Le mot de passe doit contenir au moins 12 caractères";
            } else {
                // Check password complexity
                $hasUppercase = preg_match('/[A-Z]/', $newPassword);
                $hasLowercase = preg_match('/[a-z]/', $newPassword);
                $hasNumber = preg_match('/[0-9]/', $newPassword);
                $hasSpecial = preg_match('/[^A-Za-z0-9]/', $newPassword);
                
                if (!$hasUppercase || !$hasLowercase || !$hasNumber || !$hasSpecial) {
                    $error = "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial";
                } else {
                    $model = new LoginModel();
                    $result = $model->updatePassword($tokenData['user_id'], $newPassword);
                    
                    if ($result) {
                        $success = true;
                        // Delete used token
                        $tokenManager->deleteToken($token);
                        
                        // Redirect to login
                        Config::redirect('login', ['password_reset' => '1']);
                    } else {
                        $error = "Erreur lors de la mise à jour du mot de passe";
                    }
                }
            }
        }
        
        require __DIR__ . "/../view/ResetPasswordView.php";
    }
}
