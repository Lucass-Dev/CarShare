<?php
require_once __DIR__ . "/../model/ProfileModel.php";

class ProfileController {
    
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
                $data = [
                    'first_name' => trim($_POST['first_name'] ?? ''),
                    'last_name' => trim($_POST['last_name'] ?? ''),
                    'email' => trim($_POST['email'] ?? '')
                ];
                
                if ($model->updateUserProfile($_SESSION['user_id'], $data)) {
                    $success = "Profil mis à jour avec succès";
                    $user = $model->getUserProfile($_SESSION['user_id']);
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                } else {
                    $error = "Erreur lors de la mise à jour du profil";
                }
            } elseif (isset($_POST['update_vehicle'])) {
                $data = [
                    'car_brand' => trim($_POST['car_brand'] ?? ''),
                    'car_model' => trim($_POST['car_model'] ?? ''),
                    'car_plate' => strtoupper(trim($_POST['car_plate'] ?? ''))
                ];
                
                if ($model->updateVehicle($_SESSION['user_id'], $data)) {
                    $success = "Véhicule mis à jour avec succès";
                    $user = $model->getUserProfile($_SESSION['user_id']);
                } else {
                    $error = "Erreur lors de la mise à jour du véhicule";
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