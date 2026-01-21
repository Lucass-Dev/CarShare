<?php
require_once __DIR__ . "/../model/RegisterModel.php";
require_once __DIR__ . "/../model/EmailService.php";
require_once __DIR__ . "/../model/TokenManager.php";
require_once __DIR__ . "/../config.php";

class AdminRegisterController {
    
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
     * Validate input for security threats (SQL injection, XSS, etc.)
     * Protection renforcée contre : null bytes, hex encoding, binaire, décimal, backslash
     */
    private function validateSecurity($value, $fieldName = 'champ') {
        if (empty($value)) {
            return ['valid' => true];
        }
        
        // Détection null bytes (toutes formes)
        if (preg_match('/\x00|%00/i', $value) || strpos($value, "\0") !== false) {
            return [
                'valid' => false,
                'error' => "Caractères nuls détectés dans '{$fieldName}'"
            ];
        }
        
        // Détection encoding hexadécimal suspect
        if (preg_match('/(\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){4,}/', $value)) {
            return [
                'valid' => false,
                'error' => "Encodage suspect détecté dans '{$fieldName}'"
            ];
        }
        
        // Détection tentatives d'échappement backslash
        if (preg_match('/\\\\[\'"]/', $value) || strpos($value, "\\x") !== false) {
            return [
                'valid' => false,
                'error' => "Tentative d'échappement détectée dans '{$fieldName}'"
            ];
        }
        
        // Patterns de détection d'attaques
        $patterns = [
            'sql' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT|DECLARE)\b|--|;|\/\*|\*\/|xp_|sp_)/i',
            'xss' => '/<script|<iframe|<object|<embed|<applet|javascript:|data:text\/html|vbscript:|onload|onerror|onclick|onmouseover/i',
            'html' => '/<[a-z][\s\S]*>/i'
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $value)) {
                return [
                    'valid' => false,
                    'error' => "Le champ '{$fieldName}' contient des caractères interdits ou dangereux"
                ];
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * Validate name (first name or last name)
     */
    private function validateName($name, $fieldName) {
        $name = $this->sanitizeInput($name);
        
        if (empty($name)) {
            return ['valid' => false, 'error' => "Le {$fieldName} est requis"];
        }
        
        if (strlen($name) < 2 || strlen($name) > 50) {
            return ['valid' => false, 'error' => "Le {$fieldName} doit contenir entre 2 et 50 caractères"];
        }
        
        // Only letters, spaces, hyphens, and apostrophes
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/u", $name)) {
            return ['valid' => false, 'error' => "Le {$fieldName} contient des caractères invalides"];
        }
        
        // Security check
        $securityCheck = $this->validateSecurity($name, $fieldName);
        if (!$securityCheck['valid']) {
            return $securityCheck;
        }
        
        return ['valid' => true, 'value' => $name];
    }
    
    /**
     * Validate email format and availability
     */
    private function validateEmail($email) {
        $email = $this->sanitizeInput($email);
        
        if (empty($email)) {
            return ['valid' => false, 'error' => "L'email est requis"];
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => "Le format de l'email est invalide"];
        }
        
        // Additional email validation
        if (strlen($email) > 255) {
            return ['valid' => false, 'error' => "L'email est trop long"];
        }
        
        // Security check
        $securityCheck = $this->validateSecurity($email, 'email');
        if (!$securityCheck['valid']) {
            return $securityCheck;
        }
        
        return ['valid' => true, 'value' => strtolower($email)];
    }
    
    /**
     * Validate password strength
     */
    private function validatePassword($password) {
        if (empty($password)) {
            return ['valid' => false, 'error' => "Le mot de passe est requis"];
        }
        
        if (strlen($password) < 12) {
            return ['valid' => false, 'error' => "Le mot de passe doit contenir au moins 12 caractères"];
        }
        
        if (strlen($password) > 128) {
            return ['valid' => false, 'error' => "Le mot de passe est trop long"];
        }
        
        // Check password complexity
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $password);
        
        if (!$hasUppercase || !$hasLowercase || !$hasNumber || !$hasSpecial) {
            return [
                'valid' => false, 
                'error' => "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial"
            ];
        }
        
        return ['valid' => true, 'value' => $password];
    }
    
    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = null;
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $emailConfirm = $_POST['email_confirm'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate first name
            $firstNameValidation = $this->validateName($firstName, 'prénom');
            if (!$firstNameValidation['valid']) {
                $error = $firstNameValidation['error'];
            }
            
            // Validate last name
            if (!$error) {
                $lastNameValidation = $this->validateName($lastName, 'nom');
                if (!$lastNameValidation['valid']) {
                    $error = $lastNameValidation['error'];
                }
            }
            
            // Validate email
            if (!$error) {
                $emailValidation = $this->validateEmail($email);
                if (!$emailValidation['valid']) {
                    $error = $emailValidation['error'];
                } else {
                    $email = $emailValidation['value'];
                }
            }
            
            // Validate email confirmation
            if (!$error) {
                // Log pour debug
                error_log("DEBUG Admin Register - emailConfirm value: " . var_export($emailConfirm, true));
                
                $emailConfirmValidation = $this->validateEmail($emailConfirm);
                
                error_log("DEBUG Admin Register - emailConfirmValidation: " . var_export($emailConfirmValidation, true));
                
                if (!$emailConfirmValidation['valid']) {
                    // Plus informatif pour debug
                    $error = $emailConfirmValidation['error'] ?? "La confirmation de l'email est invalide";
                } elseif (strtolower(trim($email)) !== strtolower(trim($emailConfirmValidation['value']))) {
                    $error = "Les adresses email ne correspondent pas";
                }
            }
            
            // Validate password
            if (!$error) {
                $passwordValidation = $this->validatePassword($password);
                if (!$passwordValidation['valid']) {
                    $error = $passwordValidation['error'];
                }
            }
            
            // Validate password confirmation
            if (!$error && $password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas";
            }
            
            // Check if email already exists
            if (!$error) {
                try {
                    $model = new RegisterModel();
                    
                    if ($model->emailExists($email)) {
                        $error = "Cet email est déjà utilisé";
                    } else {
                    // Create ADMIN user with sanitized data
                    $userId = $model->createAdminUser(
                        $firstNameValidation['value'],
                        $lastNameValidation['value'],
                        $email,
                        $password
                    );
                    
                    if ($userId) {
                        // Generate email validation token
                        $tokenManager = new TokenManager();
                        $token = $tokenManager->generateToken('admin_email_validation', $userId, $email, 86400); // 24h
                        
                        // Send confirmation email TO BOTH ADMIN EMAILS
                        $emailService = new EmailService();
                        $fullName = $firstNameValidation['value'] . ' ' . $lastNameValidation['value'];
                        
                        // Envoyer à lucas.liger@eleve.isep.fr
                        $emailSent1 = $emailService->sendAdminAccountConfirmation('lucas.liger@eleve.isep.fr', $fullName, $email, $token);
                        
                        // Envoyer à carshare.cov@gmail.com
                        $emailSent2 = $emailService->sendAdminAccountConfirmation('carshare.cov@gmail.com', $fullName, $email, $token);
                        
                        // Si au moins un email est envoyé avec succès
                        if ($emailSent1 || $emailSent2) {
                            $success = true;
                            $_SESSION['pending_admin_email'] = $email;
                            
                            // Message informatif selon le résultat d'envoi
                            if ($emailSent1 && $emailSent2) {
                                $_SESSION['registration_success_message'] = "Votre demande de compte administrateur a été envoyée aux administrateurs. L'un d'eux validera votre compte sous peu.";
                            } else if ($emailSent1) {
                                $_SESSION['registration_success_message'] = "Votre demande a été envoyée à lucas.liger@eleve.isep.fr. Validation en attente.";
                            } else {
                                $_SESSION['registration_success_message'] = "Votre demande a été envoyée à carshare.cov@gmail.com. Validation en attente.";
                            }
                            
                            // Redirect to confirmation page
                            header('Location: ' . url('index.php?action=admin_registration_pending'));
                            exit();
                        } else {
                            error_log("Échec envoi email de confirmation admin pour user ID: $userId (aucun email envoyé)");
                            $error = "Inscription créée mais les emails de confirmation n'ont pas pu être envoyés. Contactez le support.";
                        }
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription";
                    }
                }
                } catch (Exception $e) {
                    error_log("Erreur lors de l'inscription admin: " . $e->getMessage());
                    $error = "Erreur de connexion à la base de données. Veuillez vérifier que MySQL est démarré dans XAMPP.";
                }
            }
        }

        require __DIR__ . "/../view/AdminRegisterView.php";
    }
}
