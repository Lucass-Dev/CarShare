<?php
require_once __DIR__ . "/../model/RegisterModel.php";

class RegisterController {
    
    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = null;
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            if (!$firstName || !$lastName || !$email || !$password || !$confirmPassword) {
                $error = "Veuillez remplir tous les champs";
            } elseif ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas";
            } elseif (strlen($password) < 12) {
                $error = "Le mot de passe doit contenir au moins 12 caractères";
            } else {
                $model = new RegisterModel();
                
                if ($model->emailExists($email)) {
                    $error = "Cet email est déjà utilisé";
                } else {
                    $userId = $model->createUser($firstName, $lastName, $email, $password);
                    
                    if ($userId) {
                        $success = true;
                        // Auto login after registration
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
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