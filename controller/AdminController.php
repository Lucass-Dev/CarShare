<?php
require_once __DIR__ . "/../model/AdminModel.php";

class AdminController {
    
    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
            exit();
        }

        $model = new AdminModel();
        $stats = $model->getStats();
        $users = $model->getAllUsers();
        $carpoolings = $model->getAllCarpoolings();
        $bookings = $model->getAllBookings();

        require __DIR__ . "/../view/AdminView.php";
    }
}