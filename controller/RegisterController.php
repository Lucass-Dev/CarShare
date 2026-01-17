<?php
require_once __DIR__ . "/../model/RegisterModel.php";

class RegisterController {
    
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
     */
    private function validateSecurity($value, $fieldName = 'champ') {
        if (empty($value)) {
            return ['valid' => true];
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
            $acceptedTerms = isset($_POST['accept_terms']);

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
                $emailConfirmValidation = $this->validateEmail($emailConfirm);
                if (!$emailConfirmValidation['valid']) {
                    $error = "La confirmation de l'email est invalide";
                } elseif ($email !== $emailConfirmValidation['value']) {
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
            
            // Check terms acceptance
            if (!$error && !$acceptedTerms) {
                $error = "Vous devez accepter les CGU, les CGV et les Mentions légales pour créer un compte";
            }
            
            // Check if email already exists
            if (!$error) {
                $model = new RegisterModel();
                
                if ($model->emailExists($email)) {
                    $error = "Cet email est déjà utilisé";
                } else {
                    // Create user with sanitized data
                    $userId = $model->createUser(
                        $firstNameValidation['value'],
                        $lastNameValidation['value'],
                        $email,
                        $password
                    );
                    
                    if ($userId) {
                        $success = true;
                        // Auto login after registration
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_name'] = $firstNameValidation['value'] . ' ' . $lastNameValidation['value'];
                        $_SESSION['is_admin'] = 0;
                        $_SESSION['logged'] = true;
                        
                        // Redirect to home page
                        header('Location: /CarShare/index.php?action=home');
                        exit();
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription";
                    }
                }
            }
        }

        require __DIR__ . "/../view/RegisterView.php";
    }
}