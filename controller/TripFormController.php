<?php
require_once __DIR__ . '/../model/TripFormModel.php';

class TripFormController
{
    private $model;

    public function __construct()
    {
        $this->model = new TripFormModel();
    }
    
    /**
     * Sanitize user input to prevent XSS and other attacks
     */
    private function sanitizeInput($input) {
        if (!is_string($input)) {
            return '';
        }
        
        // Remove control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }
    
    /**
     * Validate input for security threats (SQL injection, XSS, etc.)
     */
    private function validateSecurity($value, &$errors, $fieldName = 'champ') {
        if (empty($value)) {
            return true; // Empty values are handled by other validators
        }
        
        // Patterns de détection d'attaques
        $patterns = [
            'sql' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/i',
            'xss' => '/<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover/i',
            'hex' => '/(\\\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/',
            'binary' => '/[01]{32,}/',
            'unicode' => '/[\\\\u][0-9a-fA-F]{4}/i',
            'html' => '/<[^>]+>/'
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $value)) {
                $errors[] = "Le champ '{$fieldName}' contient des caractères interdits ou dangereux";
                return false;
            }
        }
        
        return true;
    }

    public function render(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit;
        }

        $locations = $this->model->getAllLocations();
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;

        // Use TripView to display the form
        require_once __DIR__ . '/../view/TripView.php';
        TripView::display_publish_form();
    }

    public function submit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit;
        }

        $errors = [];

        // Get and validate form data with enhanced security
        $depCity = $this->sanitizeInput($_POST['dep-city'] ?? '');
        $depStreet = $this->sanitizeInput($_POST['dep-street'] ?? '');
        $depNum = $this->sanitizeInput($_POST['dep-num'] ?? '');
        $arrCity = $this->sanitizeInput($_POST['arr-city'] ?? '');
        $arrStreet = $this->sanitizeInput($_POST['arr-street'] ?? '');
        $arrNum = $this->sanitizeInput($_POST['arr-num'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $places = (int)($_POST['places'] ?? 0);
        $price = isset($_POST['price']) && $_POST['price'] !== '' ? floatval($_POST['price']) : null;
        
        // Security validation - detect malicious patterns
        $this->validateSecurity($depCity, $errors, 'ville de départ');
        $this->validateSecurity($arrCity, $errors, 'ville d\'arrivée');
        $this->validateSecurity($depStreet, $errors, 'rue de départ');
        $this->validateSecurity($arrStreet, $errors, 'rue d\'arrivée');

        // Validate and sanitize streets using Model methods
        $depStreet = TripFormModel::validateStreetName($depStreet, $errors, 'rue de départ');
        $arrStreet = TripFormModel::validateStreetName($arrStreet, $errors, 'rue d\'arrivée');
        $depNum = TripFormModel::validateStreetNumber($depNum, $errors, 'numéro de voie de départ');
        $arrNum = TripFormModel::validateStreetNumber($arrNum, $errors, 'numéro de voie d\'arrivée');

        // Validation
        if (empty($depCity)) {
            $errors[] = "La ville de départ est obligatoire";
        }
        if (empty($arrCity)) {
            $errors[] = "La ville d'arrivée est obligatoire";
        }
        if (empty($date)) {
            $errors[] = "La date est obligatoire";
        } else {
            // Validate date is in future
            $tripDate = strtotime($date . ' ' . ($time ?: '00:00:00'));
            if ($tripDate < time()) {
                $errors[] = "La date du trajet doit être dans le futur";
            }
            
            // Check date is not too far in future (1 year max)
            $maxDate = strtotime('+1 year');
            if ($tripDate > $maxDate) {
                $errors[] = "La date ne peut pas dépasser un an dans le futur";
            }
        }
        if ($places < 1 || $places > 10) {
            $errors[] = "Le nombre de places doit être entre 1 et 10";
        }
        
        // Validate price if provided
        if ($price !== null) {
            if ($price < 0) {
                $errors[] = "Le prix doit être un nombre positif";
            } elseif ($price > 9999.99) {
                $errors[] = "Le prix ne peut pas dépasser 9999.99 €";
            }
        }

        // Get locations from database
        $startLocation = $this->model->getLocationByName($depCity);
        $endLocation = $this->model->getLocationByName($arrCity);

        if (!$startLocation) {
            $errors[] = "Ville de départ non trouvée dans la base de données";
        }
        if (!$endLocation) {
            $errors[] = "Ville d'arrivée non trouvée dans la base de données";
        }
        if ($startLocation && $endLocation && $startLocation['id'] === $endLocation['id']) {
            $errors[] = "La ville de départ et d'arrivée doivent être différentes";
        }

        if (!empty($errors)) {
            $_SESSION['trip_form_errors'] = $errors;
            $_SESSION['trip_form_data'] = $_POST;
            header('Location: /CarShare/index.php?action=create_trip&error=1');
            exit;
        }

        // Create datetime string
        $startDate = $date . ' ' . ($time ?: '00:00:00');

        // Prepare data for insertion
        $tripData = [
            'provider_id' => $_SESSION['user_id'],
            'start_date' => $startDate,
            'price' => $price,
            'available_places' => $places,
            'status' => 1,
            'start_id' => $startLocation['id'],
            'end_id' => $endLocation['id']
        ];

        // Save to database
        $result = $this->model->createTrip($tripData);

        if ($result) {
            unset($_SESSION['trip_form_errors']);
            unset($_SESSION['trip_form_data']);
            header('Location: /CarShare/index.php?action=create_trip&success=1');
        } else {
            $_SESSION['trip_form_errors'] = ["Erreur lors de la création du trajet"];
            $_SESSION['trip_form_data'] = $_POST;
            header('Location: /CarShare/index.php?action=create_trip&error=1');
        }
        exit;
    }

    /**
     * Render the edit trip form
     */
    public function renderEdit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit;
        }

        // Get trip ID from URL
        $tripId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($tripId <= 0) {
            header('Location: /CarShare/index.php?action=my_trips&error=invalid_trip');
            exit;
        }

        // Get trip details
        $trip = $this->model->getTripById($tripId);
        
        // Check if trip exists and belongs to current user
        if (!$trip || $trip['provider_id'] != $_SESSION['user_id']) {
            header('Location: /CarShare/index.php?action=my_trips&error=unauthorized');
            exit;
        }

        // Check if trip is in the future
        if (strtotime($trip['start_date']) <= time()) {
            header('Location: /CarShare/index.php?action=my_trips&error=past_trip');
            exit;
        }

        $locations = $this->model->getAllLocations();
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;

        // Use TripView to display the edit form
        require_once __DIR__ . '/../view/TripView.php';
        TripView::display_edit_form($trip);
    }

    /**
     * Submit the edited trip
     */
    public function submitEdit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit;
        }

        // Get trip ID
        $tripId = isset($_POST['trip_id']) ? (int)$_POST['trip_id'] : 0;
        
        if ($tripId <= 0) {
            header('Location: /CarShare/index.php?action=my_trips&error=invalid_trip');
            exit;
        }

        // Verify trip belongs to user
        $existingTrip = $this->model->getTripById($tripId);
        if (!$existingTrip || $existingTrip['provider_id'] != $_SESSION['user_id']) {
            header('Location: /CarShare/index.php?action=my_trips&error=unauthorized');
            exit;
        }

        // Check if trip is in the future
        if (strtotime($existingTrip['start_date']) <= time()) {
            header('Location: /CarShare/index.php?action=my_trips&error=past_trip');
            exit;
        }

        $errors = [];

        // Get and validate form data (same validation as create)
        $depCity = $this->sanitizeInput($_POST['dep-city'] ?? '');
        $depStreet = $this->sanitizeInput($_POST['dep-street'] ?? '');
        $depNum = $this->sanitizeInput($_POST['dep-num'] ?? '');
        $arrCity = $this->sanitizeInput($_POST['arr-city'] ?? '');
        $arrStreet = $this->sanitizeInput($_POST['arr-street'] ?? '');
        $arrNum = $this->sanitizeInput($_POST['arr-num'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $places = (int)($_POST['places'] ?? 0);
        $price = isset($_POST['price']) && $_POST['price'] !== '' ? floatval($_POST['price']) : null;
        
        // Security validation
        $this->validateSecurity($depCity, $errors, 'ville de départ');
        $this->validateSecurity($arrCity, $errors, 'ville d\'arrivée');
        $this->validateSecurity($depStreet, $errors, 'rue de départ');
        $this->validateSecurity($arrStreet, $errors, 'rue d\'arrivée');

        // Validate and sanitize streets
        $depStreet = TripFormModel::validateStreetName($depStreet, $errors, 'rue de départ');
        $arrStreet = TripFormModel::validateStreetName($arrStreet, $errors, 'rue d\'arrivée');
        $depNum = TripFormModel::validateStreetNumber($depNum, $errors, 'numéro de voie de départ');
        $arrNum = TripFormModel::validateStreetNumber($arrNum, $errors, 'numéro de voie d\'arrivée');

        // Validation
        if (empty($depCity)) {
            $errors[] = "La ville de départ est obligatoire";
        }
        if (empty($arrCity)) {
            $errors[] = "La ville d'arrivée est obligatoire";
        }
        if (empty($date)) {
            $errors[] = "La date est obligatoire";
        } else {
            // Validate date is in future
            $tripDate = strtotime($date . ' ' . ($time ?: '00:00:00'));
            if ($tripDate < time()) {
                $errors[] = "La date du trajet doit être dans le futur";
            }
            
            // Check date is not too far in future (1 year max)
            $maxDate = strtotime('+1 year');
            if ($tripDate > $maxDate) {
                $errors[] = "La date ne peut pas dépasser un an dans le futur";
            }
        }
        if ($places < 1 || $places > 10) {
            $errors[] = "Le nombre de places doit être entre 1 et 10";
        }
        
        // Validate price if provided
        if ($price !== null) {
            if ($price < 0) {
                $errors[] = "Le prix doit être un nombre positif";
            } elseif ($price > 9999.99) {
                $errors[] = "Le prix ne peut pas dépasser 9999.99 €";
            }
        }

        // Get locations from database
        $startLocation = $this->model->getLocationByName($depCity);
        $endLocation = $this->model->getLocationByName($arrCity);

        if (!$startLocation) {
            $errors[] = "Ville de départ non trouvée dans la base de données";
        }
        if (!$endLocation) {
            $errors[] = "Ville d'arrivée non trouvée dans la base de données";
        }
        if ($startLocation && $endLocation && $startLocation['id'] === $endLocation['id']) {
            $errors[] = "La ville de départ et d'arrivée doivent être différentes";
        }

        if (!empty($errors)) {
            $_SESSION['trip_form_errors'] = $errors;
            $_SESSION['trip_form_data'] = $_POST;
            header('Location: /CarShare/index.php?action=edit_trip&id=' . $tripId . '&error=1');
            exit;
        }

        // Create datetime string
        $startDate = $date . ' ' . ($time ?: '00:00:00');

        // Check if any changes were made
        $hasChanges = false;
        if ($existingTrip['start_date'] !== $startDate ||
            $existingTrip['price'] != $price ||
            $existingTrip['available_places'] != $places ||
            $existingTrip['start_id'] != $startLocation['id'] ||
            $existingTrip['end_id'] != $endLocation['id']) {
            $hasChanges = true;
        }

        // If no changes, redirect with info message
        if (!$hasChanges) {
            header('Location: /CarShare/index.php?action=my_trips&info=no_changes');
            exit;
        }

        // Prepare data for update
        $tripData = [
            'provider_id' => $_SESSION['user_id'],
            'start_date' => $startDate,
            'price' => $price,
            'available_places' => $places,
            'start_id' => $startLocation['id'],
            'end_id' => $endLocation['id']
        ];

        // Update in database
        $result = $this->model->updateTrip($tripId, $tripData);

        if ($result) {
            unset($_SESSION['trip_form_errors']);
            unset($_SESSION['trip_form_data']);
            header('Location: /CarShare/index.php?action=my_trips&success=trip_updated');
        } else {
            $_SESSION['trip_form_errors'] = ["Erreur lors de la modification du trajet"];
            $_SESSION['trip_form_data'] = $_POST;
            header('Location: /CarShare/index.php?action=edit_trip&id=' . $tripId . '&error=1');
        }
        exit;
    }
}
