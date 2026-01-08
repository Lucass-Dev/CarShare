<?php
require_once __DIR__ . "/../model/LoginModel.php";

class LoginController {

    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($email && $password) {
                $model = new LoginModel();
                $user = $model->authenticate($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    $_SESSION['logged'] = true;
                    
                    // Redirect based on role
                    if ($user['is_admin']) {
                        header('Location: /CarShare/index.php?action=admin');
                    } else {
                        header('Location: /CarShare/index.php?action=profile');
                    }
                    exit();
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
