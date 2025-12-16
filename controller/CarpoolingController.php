<?php
require_once __DIR__ . "/../model/CarpoolingModel.php";

class CarpoolingController {
    
    public function create() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit();
        }

        $model = new CarpoolingModel();
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startCity = $_POST['start_city'] ?? '';
            $endCity = $_POST['end_city'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '00:00';
            $places = $_POST['places'] ?? 0;
            $price = $_POST['price'] ?? 0;

            if (!$startCity || !$endCity || !$date || !$places) {
                $error = "Veuillez remplir tous les champs obligatoires";
            } else {
                // Get or create locations
                $startLocation = $model->getLocationByName($startCity);
                $endLocation = $model->getLocationByName($endCity);

                if (!$startLocation || !$endLocation) {
                    $error = "Localisation invalide";
                } else {
                    $startDate = $date . ' ' . $time . ':00';
                    $carpoolingId = $model->createCarpooling(
                        $_SESSION['user_id'],
                        $startLocation['id'],
                        $endLocation['id'],
                        $startDate,
                        $price,
                        $places
                    );

                    if ($carpoolingId) {
                        header('Location: /CarShare/index.php?action=trip_details&id=' . $carpoolingId);
                        exit();
                    } else {
                        $error = "Erreur lors de la création du trajet";
                    }
                }
            }
        }

        require __DIR__ . "/../view/CarpoolingView.php";
    }

    public function details() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /CarShare/index.php?action=home');
            exit();
        }

        $model = new CarpoolingModel();
        $carpooling = $model->getCarpoolingById($id);

        if (!$carpooling) {
            header('Location: /CarShare/index.php?action=home');
            exit();
        }

        require __DIR__ . "/../view/TripDetailsView.php";
    }
}