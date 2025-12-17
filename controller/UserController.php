<?php
require_once __DIR__ . '/../model/ProfileModel.php';

class UserController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
        if ($uid <= 0) {
            http_response_code(400);
            echo "Utilisateur manquant";
            return;
        }

        $model = new ProfileModel();
        $user = $model->getUserProfile($uid);
        if (!$user) {
            http_response_code(404);
            echo "Utilisateur introuvable";
            return;
        }

        // Exposer $user à la vue
        include __DIR__ . '/../view/UserView.php';
    }
}
