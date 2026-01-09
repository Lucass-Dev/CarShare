<?php
require_once __DIR__ . "/../model/BookingModel.php";

class BookingController {
    
    public function confirmation() {
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit();
        }

        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) {
            header('Location: /CarShare/index.php?action=home');
            exit();
        }

        require __DIR__ . "/../view/BookingConfirmationView.php";
    }

    public function history() {
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit();
        }

        $model = new BookingModel();
        $bookings = $model->getBookingsByUser($_SESSION['user_id']);
        $carpoolings = $model->getCarpoolingsByProvider($_SESSION['user_id']);

        require __DIR__ . "/../view/HistoryView.php";
    }
}
