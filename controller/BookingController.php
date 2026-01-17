<?php
require_once __DIR__ . "/../model/BookingModel.php";

class BookingController {
    
    public function confirmation() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
            exit();
        }

        $model = new BookingModel();
        $bookings = $model->getBookingsByUser($_SESSION['user_id']);
        $carpoolings = $model->getCarpoolingsByProvider($_SESSION['user_id']);

        require __DIR__ . "/../view/HistoryView.php";
    }

    public function myBookings() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
            exit();
        }

        $model = new BookingModel();
        $bookings = $model->getBookingsByUser($_SESSION['user_id']);

        require __DIR__ . "/../view/MyBookingsView.php";
    }
    
    /**
     * Display user's created trips (as driver)
     */
    public function myTrips() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
            exit();
        }

        // Get filter and sort parameters
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'desc';
        
        // Validate sort parameters
        $allowedSorts = ['date', 'price'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'date';
        }
        if (!in_array($sortOrder, $allowedOrders)) {
            $sortOrder = 'desc';
        }

        $model = new BookingModel();
        $carpoolings = $model->getCarpoolingsByProvider($_SESSION['user_id'], $search, $sortBy, $sortOrder);

        require __DIR__ . "/../view/MyTripsView.php";
    }
}
