<?php
require_once __DIR__ . "/../model/LoginModel.php";

class ForgotPasswordController {

    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($email && $oldPassword && $newPassword && $confirmPassword) {
                if ($newPassword !== $confirmPassword) {
                    $error = "Les nouveaux mots de passe ne correspondent pas";
                } elseif (strlen($newPassword) < 6) {
                    $error = "Le nouveau mot de passe doit contenir au moins 6 caractères";
                } else {
                    $model = new LoginModel();
                    
                    // Vérifier que l'ancien mot de passe est correct
                    $user = $model->authenticate($email, $oldPassword);
                    
                    if ($user) {
                        // Mettre à jour le mot de passe
                        $result = $model->updatePassword($user['id'], $newPassword);
                        
                        if ($result) {
                            $success = "Mot de passe réinitialisé avec succès ! Vous pouvez maintenant vous connecter.";
                        } else {
                            $error = "Erreur lors de la mise à jour du mot de passe";
                        }
                    } else {
                        $error = "Email ou ancien mot de passe incorrect";
                    }
                }
            } else {
                $error = "Veuillez remplir tous les champs";
            }
        }

        require __DIR__ . "/../view/ForgotPasswordView.php";
    }
}
