<?php
require_once __DIR__ . "/../model/BookingModel.php";
require_once __DIR__ . "/../model/CarpoolingModel.php";

class PaymentController {
    
    public function render() {
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?action=login');
            exit();
        }

        $carpoolingId = $_GET['carpooling_id'] ?? null;
        if (!$carpoolingId) {
            header('Location: /index.php?action=home');
            exit();
        }

        $carpoolingModel = new CarpoolingModel();
        $carpooling = $carpoolingModel->getCarpoolingById($carpoolingId);

        if (!$carpooling) {
            header('Location: /index.php?action=home');
            exit();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // In real app, process payment here
            // For now, we'll just create the booking
            
            $bookingModel = new BookingModel();
            $bookingId = $bookingModel->createBooking($_SESSION['user_id'], $carpoolingId);

            if ($bookingId) {
                header('Location: /index.php?action=booking_confirmation&booking_id=' . $bookingId);
                exit();
            } else {
                $error = "Impossible de créer la réservation. Le trajet est peut-être complet.";
            }
        }

        require __DIR__ . "/../view/PaymentView.php";
    }
}
