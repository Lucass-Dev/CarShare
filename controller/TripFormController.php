<?php
require_once __DIR__ . '/../model/TripFormModel.php';

class TripFormController
{
    private $model;

    public function __construct()
    {
        $this->model = new TripFormModel();
    }

    public function render(): void
    {
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?action=login');
            exit;
        }

        $locations = $this->model->getAllLocations();
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;

        require __DIR__ . '/../view/TripFormView.php';
    }

    public function submit(): void
    {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?action=login');
            exit;
        }

        $errors = [];

        // Get and validate form data
        $depCity = trim($_POST['dep-city'] ?? '');
        $depStreet = trim($_POST['dep-street'] ?? '');
        $depNum = trim($_POST['dep-num'] ?? '');
        $arrCity = trim($_POST['arr-city'] ?? '');
        $arrStreet = trim($_POST['arr-street'] ?? '');
        $arrNum = trim($_POST['arr-num'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $places = (int)($_POST['places'] ?? 0);
        $price = isset($_POST['price']) && $_POST['price'] !== '' ? floatval($_POST['price']) : null;

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
        }
        if ($places < 1 || $places > 10) {
            $errors[] = "Le nombre de places doit être entre 1 et 10";
        }
        
        // Validate street numbers if provided
        if (!empty($depNum) && (!is_numeric($depNum) || intval($depNum) < 0)) {
            $errors[] = "Le numéro de voie de départ doit être un nombre positif";
        }
        if (!empty($arrNum) && (!is_numeric($arrNum) || intval($arrNum) < 0)) {
            $errors[] = "Le numéro de voie d'arrivée doit être un nombre positif";
        }
        
        // Validate price if provided
        if ($price !== null && $price < 0) {
            $errors[] = "Le prix doit être un nombre positif";
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
            header('Location: /index.php?action=create_trip&error=1');
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
            header('Location: /index.php?action=create_trip&success=1');
        } else {
            $_SESSION['trip_form_errors'] = ["Erreur lors de la création du trajet"];
            $_SESSION['trip_form_data'] = $_POST;
            header('Location: /index.php?action=create_trip&error=1');
        }
        exit;
    }
}
