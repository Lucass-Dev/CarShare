<?php
/**
 * Controller for displaying all available carpooling offers
 */
class OffersController {
    private $model;

    public function __construct() {
        require_once __DIR__ . '/../model/OffersModel.php';
        $this->model = new OffersModel();
    }

    public function render() {
        // Session may or may not be started - check if user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $currentUserId = $_SESSION['user_id'] ?? null;
        
        // Get filters from GET parameters
        $search = $_GET['search'] ?? '';
        $sortBy = $_GET['sort'] ?? 'date';
        $sortOrder = $_GET['order'] ?? 'asc';
        $dateFilter = $_GET['date_depart'] ?? '';
        $priceMax = $_GET['prix_max'] ?? '';
        $placesMin = $_GET['places_min'] ?? '';

        // Validate sort parameters
        $allowedSorts = ['date', 'price'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'date';
        }
        if (!in_array($sortOrder, $allowedOrders)) {
            $sortOrder = 'asc';
        }

        // Get pagination
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Get offers from model
        $offers = $this->model->getAllOffers($search, $sortBy, $sortOrder, $dateFilter, $priceMax, $placesMin, $limit, $offset, $currentUserId);
        $totalOffers = $this->model->countOffers($search, $dateFilter, $priceMax, $placesMin);
        $totalPages = ceil($totalOffers / $limit);

        // Load view
        require_once __DIR__ . '/../view/OffersView.php';
    }
}
