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
        // Get filters from GET parameters
        $filters = [
            'ville_depart' => $_GET['ville_depart'] ?? '',
            'ville_arrivee' => $_GET['ville_arrivee'] ?? '',
            'date_depart' => $_GET['date_depart'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? '',
            'places_min' => $_GET['places_min'] ?? ''
        ];

        // Get pagination
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Get offers from model
        $offers = $this->model->getAllOffers($filters, $limit, $offset);
        $totalOffers = $this->model->countOffers($filters);
        $totalPages = ceil($totalOffers / $limit);

        // Get unique cities for filters
        $cities = $this->model->getUniqueCities();

        // Load view
        require_once __DIR__ . '/../view/OffersView.php';
    }
}
