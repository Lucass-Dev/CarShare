<?php
require_once __DIR__ . "/../model/AdminModel.php";

class AdminController {
    
    public function render() {
        
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /index.php?action=login');
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