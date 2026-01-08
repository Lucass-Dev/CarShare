<?php
require_once __DIR__ . "/../model/BookingModel.php";
require_once __DIR__ . "/../model/CarpoolingModel.php";

class PaymentController {
    
    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit();
        }

        $carpoolingId = $_GET['carpooling_id'] ?? null;
        if (!$carpoolingId) {
            header('Location: /CarShare/index.php?action=home');
            exit();
        }

        $carpoolingModel = new CarpoolingModel();
        $carpooling = $carpoolingModel->getCarpoolingById($carpoolingId);

        if (!$carpooling) {
            header('Location: /CarShare/index.php?action=home');
            exit();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // In a real app, process payment here
            // Before creating the booking, ensure legal terms are accepted

            $acceptedTerms = isset($_POST['accept_terms']);

            if (!$acceptedTerms) {
                $error = "Vous devez accepter les CGV, les CGU et les Mentions légales pour confirmer votre réservation";
            } else {
                $bookingModel = new BookingModel();
                $bookingId = $bookingModel->createBooking($_SESSION['user_id'], $carpoolingId);

                if ($bookingId) {
                    header('Location: /CarShare/index.php?action=booking_confirmation&booking_id=' . $bookingId);
                    exit();
                } else {
                    $error = "Impossible de créer la réservation. Le trajet est peut-être complet.";
                }
            }
        }

        require __DIR__ . "/../view/PaymentView.php";
    }
}
