<?php
require_once __DIR__ . "/../model/TokenManager.php";
require_once __DIR__ . "/../model/RegisterModel.php";
require_once __DIR__ . "/../config.php";

class AdminEmailValidationController {
    
    public function validateEmail() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            header('Location: ' . url('index.php?action=admin_email_validation&error=' . urlencode('Token manquant')));
            exit();
        }
        
        try {
            $tokenManager = new TokenManager();
            $tokenData = $tokenManager->validateToken($token, 'admin_email_validation');
            
            if (!$tokenData) {
                header('Location: ' . url('index.php?action=admin_email_validation&error=' . urlencode('Token invalide ou expiré')));
                exit();
            }
            
            // Update user email_verified status
            $model = new RegisterModel();
            $userId = $tokenData['user_id'];
            
            $updated = $model->updateEmailVerified($userId);
            
            if ($updated) {
                // Invalidate the token
                $tokenManager->invalidateToken($token);
                
                // Redirect to success page
                header('Location: ' . url('index.php?action=admin_email_validation&success=1'));
                exit();
            } else {
                header('Location: ' . url('index.php?action=admin_email_validation&error=' . urlencode('Erreur lors de la validation')));
                exit();
            }
            
        } catch (Exception $e) {
            error_log("Admin Email Validation Error: " . $e->getMessage());
            header('Location: ' . url('index.php?action=admin_email_validation&error=' . urlencode('Une erreur est survenue')));
            exit();
        }
    }
}
