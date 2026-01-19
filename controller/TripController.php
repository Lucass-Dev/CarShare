<?php
/**
 * TripController - Trip management (search, create, booking)
 * Core business logic for carpooling
 */

require_once __DIR__ . "/../model/TripModel.php";
require_once __DIR__ . "/../model/SecurityValidator.php";

class TripController {
    
    /**
     * Display trip search page
     */
    public function search(): void {
        $filters = [
            'start_place' => $_GET['start_place'] ?? '',
            'end_place' => $_GET['end_place'] ?? '',
            'date' => $_GET['date'] ?? '',
            'seats' => $_GET['seats'] ?? 1
        ];
        
        // DEEP MERGE: Filtres avancés Lucas (sort_by, order_type)
        $sortBy = $_GET['sort_by'] ?? null;
        $orderType = $_GET['order_type'] ?? 'asc';
        
        $trips = [];
        if (!empty($filters['start_place']) || !empty($filters['end_place'])) {
            $model = new TripModel();
            // Pass sorting parameters to model
            $trips = $model->searchTrips($filters, $sortBy, $orderType);
        }
        
        require __DIR__ . '/../view/SearchPageView.php';
    }
    
    /**
     * Display create trip form
     */
    public function create(): void {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vous devez être connecté pour publier un trajet';
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        require __DIR__ . '/../view/TripView.php';
    }
    
    /**
     * Process trip creation form
     */
    public function processCreate(): void {
        if (!isLoggedIn()) {
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        $validator = new SecurityValidator();
        $errors = [];
        
        // Get and validate form data
        $data = [
            'start_place' => $_POST['start_place'] ?? '',
            'end_place' => $_POST['end_place'] ?? '',
            'departure_time' => $_POST['departure_date'] . ' ' . $_POST['departure_time'],
            'available_seats' => $_POST['available_seats'] ?? 0,
            'price' => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'preferences' => $_POST['preferences'] ?? ''
        ];
        
        // Validate city names
        if (!$validator->validateCityName($data['start_place'], $errors)) {
            $_SESSION['error'] = $errors['start_place'] ?? 'Ville de départ invalide';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        if (!$validator->validateCityName($data['end_place'], $errors)) {
            $_SESSION['error'] = $errors['end_place'] ?? 'Ville d\'arrivée invalide';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        // Validate date (must be in future)
        $departureTimestamp = strtotime($data['departure_time']);
        if ($departureTimestamp < time()) {
            $_SESSION['error'] = 'La date de départ doit être dans le futur';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        // Validate seats
        if (!$validator->validateInteger($data['available_seats'], 'places disponibles', $errors, 1, 8)) {
            $_SESSION['error'] = $errors['available_seats'] ?? 'Nombre de places invalide (1-8)';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        // Validate price
        if (!$validator->validatePrice($data['price'], $errors)) {
            $_SESSION['error'] = $errors['price'] ?? 'Prix invalide';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        // Validate description
        if (!empty($data['description']) && !$validator->validateTextarea($data['description'], 'description', $errors, 500)) {
            $_SESSION['error'] = $errors['description'] ?? 'Description invalide';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        // Validate preferences
        if (!empty($data['preferences']) && !$validator->validateTextarea($data['preferences'], 'préférences', $errors, 200)) {
            $_SESSION['error'] = $errors['preferences'] ?? 'Préférences invalides';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        // Create trip
        $data['provider_id'] = $_SESSION['user_id'];
        $model = new TripModel();
        $tripId = $model->createTrip($data);
        
        if (!$tripId) {
            $_SESSION['error'] = 'Erreur lors de la création du trajet';
            $_SESSION['form_data'] = $_POST;
            redirect(url('index.php?controller=trip&action=create'));
            return;
        }
        
        $_SESSION['success'] = 'Trajet publié avec succès !';
        redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
    }
    
    /**
     * Display trip details
     */
    public function details(): void {
        $tripId = $_GET['id'] ?? 0;
        
        if (empty($tripId)) {
            $_SESSION['error'] = 'Trajet introuvable';
            redirect(url('index.php?controller=home'));
            return;
        }
        
        $model = new TripModel();
        $trip = $model->getTripById($tripId);
        
        if (!$trip) {
            $_SESSION['error'] = 'Trajet introuvable';
            redirect(url('index.php?controller=home'));
            return;
        }
        
        // Get provider info
        $provider = $model->getProviderInfo($trip['provider_id']);
        
        // Get existing bookings count
        $bookingsCount = $model->getBookingsCount($tripId);
        $trip['remaining_seats'] = $trip['available_seats'] - $bookingsCount;
        
        // Check if current user already booked
        $userBooked = false;
        if (isLoggedIn()) {
            $userBooked = $model->hasUserBooked($tripId, $_SESSION['user_id']);
        }
        
        require __DIR__ . '/../view/TripDetailsViewEnhanced.php';
    }
    
    /**
     * Process trip booking
     */
    public function book(): void {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vous devez être connecté pour réserver';
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('index.php?controller=home'));
            return;
        }
        
        $tripId = $_POST['trip_id'] ?? 0;
        $seatsRequested = $_POST['seats'] ?? 1;
        
        $validator = new SecurityValidator();
        $errors = [];
        
        if (!$validator->validateInteger($seatsRequested, 'nombre de places', $errors, 1, 8)) {
            $_SESSION['error'] = 'Nombre de places invalide';
            redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
            return;
        }
        
        $model = new TripModel();
        $trip = $model->getTripById($tripId);
        
        if (!$trip) {
            $_SESSION['error'] = 'Trajet introuvable';
            redirect(url('index.php?controller=home'));
            return;
        }
        
        // Check if user is the provider
        if ($trip['provider_id'] == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Vous ne pouvez pas réserver votre propre trajet';
            redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
            return;
        }
        
        // Check if user already booked
        if ($model->hasUserBooked($tripId, $_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous avez déjà réservé ce trajet';
            redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
            return;
        }
        
        // Check if enough seats available
        $bookingsCount = $model->getBookingsCount($tripId);
        $remainingSeats = $trip['available_seats'] - $bookingsCount;
        
        if ($seatsRequested > $remainingSeats) {
            $_SESSION['error'] = 'Pas assez de places disponibles';
            redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
            return;
        }
        
        // Check if trip is in the future
        if (strtotime($trip['departure_time']) < time()) {
            $_SESSION['error'] = 'Ce trajet est déjà passé';
            redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
            return;
        }
        
        // Create booking
        $bookingData = [
            'carpooling_id' => $tripId,
            'user_id' => $_SESSION['user_id'],
            'seats_booked' => $seatsRequested,
            'total_price' => $trip['price'] * $seatsRequested,
            'status' => 'confirmed'
        ];
        
        $bookingId = $model->createBooking($bookingData);
        
        if (!$bookingId) {
            $_SESSION['error'] = 'Erreur lors de la réservation';
            redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
            return;
        }
        
        // Send confirmation email
        try {
            $emailService = new EmailService();
            require_once __DIR__ . "/../model/ProfileModel.php";
            $profileModel = new ProfileModel();
            $user = $profileModel->getUserById($_SESSION['user_id']);
            $provider = $profileModel->getUserById($trip['provider_id']);
            
            // Email to booker
            $emailService->sendBookingConfirmationToBooker(
                $user['email'],
                $user['first_name'],
                $trip,
                $provider,
                $bookingData
            );
            
            // Email to provider
            $emailService->sendBookingNotificationToProvider(
                $provider['email'],
                $provider['first_name'],
                $trip,
                $user,
                $bookingData
            );
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
        }
        
        $_SESSION['success'] = 'Réservation confirmée !';
        redirect(url('index.php?controller=booking&action=confirmation&id=' . $bookingId));
    }
    
    /**
     * Display user's trips (as provider)
     */
    public function myTrips(): void {
        if (!isLoggedIn()) {
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        $model = new TripModel();
        $trips = $model->getUserTrips($_SESSION['user_id']);
        
        require __DIR__ . '/../view/MyTripsView.php';
    }
    
    /**
     * Edit trip
     */
    public function edit(): void {
        if (!isLoggedIn()) {
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        $tripId = $_GET['id'] ?? 0;
        
        $model = new TripModel();
        $trip = $model->getTripById($tripId);
        
        if (!$trip || $trip['provider_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Trajet introuvable ou accès non autorisé';
            redirect(url('index.php?controller=trip&action=myTrips'));
            return;
        }
        
        require __DIR__ . '/../view/TripView.php';
    }
    
    /**
     * Process trip update
     */
    public function processEdit(): void {
        if (!isLoggedIn()) {
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('index.php?controller=trip&action=myTrips'));
            return;
        }
        
        $tripId = $_POST['trip_id'] ?? 0;
        
        $model = new TripModel();
        $trip = $model->getTripById($tripId);
        
        if (!$trip || $trip['provider_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Accès non autorisé';
            redirect(url('index.php?controller=trip&action=myTrips'));
            return;
        }
        
        $validator = new SecurityValidator();
        $errors = [];
        
        $data = [
            'available_seats' => $_POST['available_seats'] ?? 0,
            'price' => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'preferences' => $_POST['preferences'] ?? ''
        ];
        
        // Validate
        if (!$validator->validateInteger($data['available_seats'], 'places', $errors, 1, 8)) {
            $_SESSION['error'] = 'Nombre de places invalide';
            redirect(url('index.php?controller=trip&action=edit&id=' . $tripId));
            return;
        }
        
        if (!$validator->validatePrice($data['price'], $errors)) {
            $_SESSION['error'] = 'Prix invalide';
            redirect(url('index.php?controller=trip&action=edit&id=' . $tripId));
            return;
        }
        
        // Update trip
        if ($model->updateTrip($tripId, $data)) {
            $_SESSION['success'] = 'Trajet mis à jour avec succès';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour';
        }
        
        redirect(url('index.php?controller=trip&action=details&id=' . $tripId));
    }
    
    /**
     * Cancel trip
     */
    public function cancel(): void {
        if (!isLoggedIn()) {
            redirect(url('index.php?controller=profile&action=login'));
            return;
        }
        
        $tripId = $_POST['trip_id'] ?? 0;
        
        $model = new TripModel();
        $trip = $model->getTripById($tripId);
        
        if (!$trip || $trip['provider_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Accès non autorisé';
            redirect(url('index.php?controller=trip&action=myTrips'));
            return;
        }
        
        // Cancel trip and notify all passengers
        if ($model->cancelTrip($tripId)) {
            $_SESSION['success'] = 'Trajet annulé. Les passagers ont été remboursés.';
        } else {
            $_SESSION['error'] = 'Erreur lors de l\'annulation';
        }
        
        redirect(url('index.php?controller=trip&action=myTrips'));
    }
}
