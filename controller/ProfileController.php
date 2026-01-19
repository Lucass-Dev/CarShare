<?php
require_once __DIR__ . "/../model/ProfileModel.php";
require_once __DIR__ . "/../model/EmailService.php";
require_once __DIR__ . "/../model/TokenManager.php";
require_once __DIR__ . "/../config.php";

class ProfileController {
    
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
    private function validateSecurity($value) {
        if (empty($value)) {
            return true;
        }
        
        // Patterns de détection d'attaques
        $patterns = [
            'sql' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/i',
            'xss' => '/<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover/i',
            'hex' => '/(\\\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/',
            'html' => '/<[^>]+>/'
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate name format
     */
    private function validateName($name) {
        if (empty($name) || strlen($name) < 2 || strlen($name) > 50) {
            return false;
        }
        
        // Only letters, spaces, hyphens, and apostrophes
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/u", $name)) {
            return false;
        }
        
        return $this->validateSecurity($name);
    }
    
    /**
     * Validate car plate format
     */
    private function validateCarPlate($plate) {
        if (empty($plate)) {
            return true; // Optional field
        }
        
        // French plate format: AA-123-AA or similar
        if (strlen($plate) > 15) {
            return false;
        }
        
        // Alphanumeric and hyphens only
        if (!preg_match("/^[A-Z0-9-]+$/", $plate)) {
            return false;
        }
        
        return $this->validateSecurity($plate);
    }
    
    /**
     * Validate vehicle info
     */
    private function validateVehicleInfo($value) {
        if (empty($value)) {
            return true; // Optional field
        }
        
        if (strlen($value) < 2 || strlen($value) > 50) {
            return false;
        }
        
        // Alphanumeric, spaces, hyphens only
        if (!preg_match("/^[a-zA-Z0-9\s-]+$/", $value)) {
            return false;
        }
        
        return $this->validateSecurity($value);
    }
    
    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: ' . url('index.php?action=login&return_url=' . $returnUrl));
            exit();
        }

        $model = new ProfileModel();
        $userData = $model->getUserProfile($_SESSION['user_id']);
        $stats = $model->getUserStats($_SESSION['user_id']);
        $user = $userData; // Keep for compatibility with update logic
        $error = null;
        $success = null;

        // Handle profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $firstName = $this->sanitizeInput($_POST['first_name'] ?? '');
                $lastName = $this->sanitizeInput($_POST['last_name'] ?? '');
                $email = $this->sanitizeInput($_POST['email'] ?? '');
                
                // Validate inputs
                if (!$this->validateName($firstName)) {
                    $error = "Le prénom contient des caractères invalides";
                } elseif (!$this->validateName($lastName)) {
                    $error = "Le nom contient des caractères invalides";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "L'email est invalide";
                } elseif (!$this->validateSecurity($email)) {
                    $error = "L'email contient des caractères dangereux";
                } else {
                    $data = [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => strtolower($email)
                    ];
                    
                    if ($model->updateUserProfile($_SESSION['user_id'], $data)) {
                        $success = "Profil mis à jour avec succès";
                        $user = $model->getUserProfile($_SESSION['user_id']);
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    } else {
                        $error = "Erreur lors de la mise à jour du profil";
                    }
                }
            } elseif (isset($_POST['update_vehicle'])) {
                $carBrand = $this->sanitizeInput($_POST['car_brand'] ?? '');
                $carModel = $this->sanitizeInput($_POST['car_model'] ?? '');
                $carPlate = strtoupper($this->sanitizeInput($_POST['car_plate'] ?? ''));
                
                // Validate inputs
                if (!$this->validateVehicleInfo($carBrand)) {
                    $error = "La marque du véhicule contient des caractères invalides";
                } elseif (!$this->validateVehicleInfo($carModel)) {
                    $error = "Le modèle du véhicule contient des caractères invalides";
                } elseif (!$this->validateCarPlate($carPlate)) {
                    $error = "La plaque d'immatriculation est invalide";
                } else {
                    $data = [
                        'car_brand' => $carBrand,
                        'car_model' => $carModel,
                        'car_plate' => $carPlate
                    ];
                    
                    if ($model->updateVehicle($_SESSION['user_id'], $data)) {
                        $success = "Véhicule mis à jour avec succès";
                        $user = $model->getUserProfile($_SESSION['user_id']);
                    } else {
                        $error = "Erreur lors de la mise à jour du véhicule";
                    }
                }
            } elseif (isset($_POST['update_password'])) {
                $currentPassword = $_POST['current_password'] ?? '';
                
                // Validate current password
                if (empty($currentPassword)) {
                    $error = "Le mot de passe actuel est requis";
                } else {
                    // Verify current password
                    if (!password_verify($currentPassword, $user['password_hash'])) {
                        $error = "Le mot de passe actuel est incorrect";
                    } else {
                        // Generate reset token and send email
                        $tokenManager = new TokenManager();
                        $token = $tokenManager->generateToken('password_reset', $_SESSION['user_id'], $user['email'], 3600); // 1h
                        
                        $emailService = new EmailService();
                        $fullName = $user['first_name'] . ' ' . $user['last_name'];
                        $emailSent = $emailService->sendPasswordResetEmail($user['email'], $fullName, $token);
                        
                        if ($emailSent) {
                            $success = "Un email de confirmation a été envoyé à votre adresse. Cliquez sur le lien pour définir votre nouveau mot de passe.";
                        } else {
                            $error = "Erreur lors de l'envoi de l'email de confirmation";
                        }
                    }
                }
            } elseif (isset($_POST['delete_account'])) {
                $confirmPassword = $_POST['confirm_password'] ?? '';
                $confirmText = $_POST['confirm_text'] ?? '';
                
                // Validate confirmations
                if (empty($confirmPassword)) {
                    $error = "Le mot de passe est requis pour supprimer le compte";
                } elseif (!password_verify($confirmPassword, $user['password_hash'])) {
                    $error = "Le mot de passe est incorrect";
                } elseif (strtoupper(trim($confirmText)) !== 'SUPPRIMER') {
                    $error = "Veuillez taper 'SUPPRIMER' pour confirmer";
                } else {
                    // Delete account
                    if ($model->deleteAccount($_SESSION['user_id'])) {
                        // Send confirmation email
                        $emailService = new EmailService();
                        $fullName = $user['first_name'] . ' ' . $user['last_name'];
                        $emailService->sendAccountDeletionConfirmation($user['email'], $fullName);
                        
                        // Destroy session and redirect
                        session_unset();
                        session_destroy();
                        header('Location: ' . url('index.php?action=home&account_deleted=1'));
                        exit();
                    } else {
                        $error = "Erreur lors de la suppression du compte. Veuillez contacter le support.";
                    }
                }
            }
        }

        require __DIR__ . "/../view/ProfileView.php";
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ' . url('index.php?action=home'));
        exit();
    }
}