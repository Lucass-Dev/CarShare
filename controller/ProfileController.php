<?php
require_once __DIR__ . "/../model/ProfileModel.php";

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
            header('Location: /CarShare/index.php?action=login');
            exit();
        }

        $model = new ProfileModel();
        $user = $model->getUserProfile($_SESSION['user_id']);
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
                $newPassword = $_POST['new_password'] ?? '';
                $confirmNewPassword = $_POST['confirm_new_password'] ?? '';
                
                // Validate current password
                if (empty($currentPassword)) {
                    $error = "Le mot de passe actuel est requis";
                } else {
                    // Verify current password
                    if (!password_verify($currentPassword, $user['password_hash'])) {
                        $error = "Le mot de passe actuel est incorrect";
                    } elseif (empty($newPassword)) {
                        $error = "Le nouveau mot de passe est requis";
                    } elseif (strlen($newPassword) < 12) {
                        $error = "Le nouveau mot de passe doit contenir au moins 12 caractères";
                    } elseif (strlen($newPassword) > 128) {
                        $error = "Le nouveau mot de passe est trop long";
                    } else {
                        // Check password complexity
                        $hasUppercase = preg_match('/[A-Z]/', $newPassword);
                        $hasLowercase = preg_match('/[a-z]/', $newPassword);
                        $hasNumber = preg_match('/[0-9]/', $newPassword);
                        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $newPassword);
                        
                        if (!$hasUppercase || !$hasLowercase || !$hasNumber || !$hasSpecial) {
                            $error = "Le nouveau mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial";
                        } elseif ($newPassword !== $confirmNewPassword) {
                            $error = "Les nouveaux mots de passe ne correspondent pas";
                        } elseif ($currentPassword === $newPassword) {
                            $error = "Le nouveau mot de passe doit être différent de l'ancien";
                        } else {
                            // Update password
                            if ($model->updatePassword($_SESSION['user_id'], $newPassword)) {
                                $success = "Mot de passe modifié avec succès";
                                $user = $model->getUserProfile($_SESSION['user_id']);
                            } else {
                                $error = "Erreur lors de la modification du mot de passe";
                            }
                        }
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
        session_destroy();
        header('Location: /CarShare/index.php?action=home');
        exit();
    }
}