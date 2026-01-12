<?php

class ProfileController {
    
    public function render() {
       $action = $_GET['action'] ?? 'login';

    if (isset($_SESSION['user_id'])) {

        if ($action === 'login' || $action === 'register') {
            header('Location: index.php?controller=profile&action=show');
            exit;
        }
        $user = UserModel::getUserById($_SESSION['user_id']);
    }

        
        
        
        

        

        switch ($action) {
            case "login": 
                ProfileView::displayLogin();
                if (sizeof($_POST) > 0) {
                    UserModel::send_login_form($_POST);
                }
                break;
            case "register":
                
                if (sizeof($_POST) > 0) {
                    $checkForm = UserModel::check_form_values($_POST);
                    if ($checkForm["success"]) {
                        $result = UserModel::send_register_form($_POST);
                        ProfileView::displayRegister($result["message"], $result["success"]);
                    }else{
                        ProfileView::displayRegister($checkForm["message"], $checkForm["success"]);
                    }
                }else{
                    ProfileView::displayRegister("", true);
                }
                break;
            case "disconnect":
                $this->logout();
                break;
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
            case "mp":
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /index.php?controller=profile&action=login');
                    exit();
                }
                $resumes = [];
                global $user_id;
                
                ?>
                <div class="mp-page-container">
                    <?php
                    if (isset($_POST['sended_message_content']) && $_POST['sended_message_content'] !== "" && isset($_GET['chat_with'])) {
                        MPModel::sendMessage($user_id, $_POST['sended_message_content'], $_GET['chat_with']);
                        $_POST= array();
                    }
                    if ($user_id && $user_id !== '') {
                        $resumes = MPModel::getResumes($user_id);
                    }
                    MPView::display_MP($resumes);
                    if ($user_id && $user_id !== '' && isset($_GET['chat_with']) && $_GET['chat_with'] !== '') {
                        $discussion = MPModel::getDiscussion($user_id, $_GET['chat_with']);
                        MPView::display_discussion($discussion);
                    }
                    ?>
                </div>
                <?php
            default:
                # code...
                break;
        }
    }

    public function logout() {
        session_destroy();
        $_SESSION = [];
        header('Location: /index.php?controller=home');
        exit();
    }
}