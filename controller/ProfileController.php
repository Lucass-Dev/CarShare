<?php

class ProfileController {
    
    public function render() {
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?action=login');
            exit();
        }

        $action = "show";
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
        }
        
        

        $user = UserModel::getUserById($_SESSION['user_id']);

        switch ($action) {
            case 'history':
                $history = UserModel::getUserHistory($user->id);
                ProfileView::displayHistory($history);
                break;
            case "show":
                ProfileView::displayProfile($user);
                break;
            case "update_user":
                if (!UserModel::updateUser($_POST)) {
                   ?>
                   <span>Une erreur est survenue, veuillez réessayer plus tard. Si le problème persiste, contactez le support.</span>
                   <?php
                }else{
                    ?>
                   <span>Mise à jour du profil avec succès, redirection dans 5 secondes...</span>
                   <?php
                }
                sleep(2);
                header('Location: index.php?controller=profile&action=show&profile_update=1');
            case "update_car":
                if (!ProfileModel::updateVehicle($_SESSION['user_id'], $_POST)) {
                   ?>
                   <span>Une erreur est survenue, veuillez réessayer plus tard. Si le problème persiste, contactez le support.</span>
                   <?php
                }else{
                    ?>
                   <span>Mise à jour du profil avec succès, redirection dans 5 secondes...</span>
                   <?php
                }
                sleep(2);
                header('Location: index.php?controller=profile&action=show&car_update=1');
            default:
                # code...
                break;
        }

<<<<<<< Updated upstream
=======
        require __DIR__ . "/../view/ProfileView.php";
    }

    public function logout() {
        session_destroy();
        header('Location: /index.php?action=home');
        exit();
>>>>>>> Stashed changes
    }
}