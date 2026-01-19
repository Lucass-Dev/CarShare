<?php
require_once __DIR__ . "/../model/LoginModel.php";

class LoginController {

    /**
     * Sanitize and validate input against attacks
     */
    private function sanitizeInput($input) {
        if (!is_string($input)) return '';
        // Remove control characters and null bytes
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        return trim($input);
    }
    
    private function validateSecurity($value) {
        if (empty($value)) return true;
        
        // Détection null bytes et encodages suspects
        if (preg_match('/\\x00|\\0+|%00|\\x{0000}/i', $value)) return false;
        
        // Détection patterns SQL/XSS
        $patterns = [
            '/(SELECT|INSERT|UPDATE|DELETE|DROP|UNION|EXEC|SCRIPT|--|;\/\*)/i',
            '/<script|<iframe|javascript:|onerror|onload/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) return false;
        }
        
        return true;
    }

    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // Ne pas sanitize le password

            // Validation sécurité
            if (!$this->validateSecurity($email)) {
                $error = "Caractères interdits détectés";
            } elseif ($email && $password) {
                $model = new LoginModel();
                $user = $model->authenticate($email, $password);

                if ($user) {
                    // BLOQUER les administrateurs qui essaient de se connecter via login normal
                    if (isset($user['is_admin']) && $user['is_admin']) {
                        $error = "Les administrateurs doivent utiliser la connexion dédiée. Cliquez sur le menu utilisateur puis sur 'Admin'.";
                    } else {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                        $_SESSION['is_admin'] = $user['is_admin'];
                        $_SESSION['logged'] = true;
                    
                        // Check for return URL
                        $returnUrl = $_POST['return_url'] ?? $_GET['return_url'] ?? null;
                        
                        // Ne JAMAIS rediriger vers register après connexion
                        if ($returnUrl && !empty($returnUrl)) {
                            // Bloquer les redirections vers register/login
                            if (strpos($returnUrl, 'action=register') !== false || 
                                strpos($returnUrl, 'action=login') !== false) {
                                $returnUrl = null; // Ignorer cette redirection
                            }
                        }
                        
                        // Redirect based on priority: return_url > profile
                        // Les utilisateurs normaux ne doivent JAMAIS aller vers admin
                        if ($returnUrl && !empty($returnUrl)) {
                            // Bloquer toute redirection vers admin depuis login normal
                            if (strpos($returnUrl, 'admin') !== false) {
                                header('Location: ' . url('index.php?action=profile'));
                            } elseif (strpos($returnUrl, '?') === 0) {
                                header('Location: ' . url('index.php' . $returnUrl));
                            } else {
                                header('Location: ' . $returnUrl);
                            }
                        } else {
                            // Les utilisateurs normaux vont toujours au profil
                            header('Location: ' . url('index.php?action=profile'));
                        }
                        exit();
                    }
                } else {
                    $error = "Email ou mot de passe incorrect";
                }
            } else {
                $error = "Veuillez remplir tous les champs";
            }
        }

        require __DIR__ . "/../view/LoginView.php";
    }
}
