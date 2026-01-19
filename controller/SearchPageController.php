<?php
    require_once("./model/SearchPageModel.php");
    include_once("./view/SearchPageView.php");



    final class SearchPageController
    {
        private $searchPageModel;
        private $searchPageView;

        private $filters;

        public function __construct() {
            $this->searchPageModel = new SearchPageModel();
            $this->searchPageView = new SearchPageView();
            $this->filters = array();
            $this->filters["pets_allowed"] = "";
            $this->filters["smoker_allowed"] = "";
            $this->filters["luggage_allowed"] = "";
            $this->filters["user_verified"] = "";
            $this->filters["start_time_range"] = "";
        }

        public function render() {
            $start_name = null;
            $start_id = null;
            $end_name = null;
            $end_id = null;
            $requested_date = null;
            $requested_hour = null;
            $requested_seats = null;

            if (isset($_GET["form_start_input"]) && $_GET["form_start_input"] != "") {
                $start_id = $_GET["form_start_input"];
                
                $start_name = $this->searchPageModel->getCityNameWithPostalCode($start_id);
            }
            if (isset($_GET["form_end_input"]) && $_GET["form_end_input"] != ""){
                $end_id = $_GET["form_end_input"];
                $end_name = $this->searchPageModel->getCityNameWithPostalCode( $end_id);
            }
            if (isset($_GET["date"]) && $_GET["date"] != ""){
                $requested_date = $_GET["date"];
            }
            if (isset($_GET["hour"]) && $_GET["hour"] != ""){
                $requested_hour = $_GET["hour"];
            }
            if (isset($_GET["seats"]) && $_GET["seats"] != ""){
                $requested_seats = $_GET["seats"];
            }

            ?> 
                <div class="search-page-layout">
                    <aside class="search-sidebar">
                        <?php $this->searchPageView->display_search_bar($start_name, $start_id,$end_name, $end_id,$requested_date, $requested_hour, $requested_seats); ?>
                        <?php $this->searchPageView->display_search_filters(); ?>
                    </aside>
                    
                    <main class="search-results">
                        <?php
                        if (isset($_GET['action']) && $_GET['action'] === 'display_search') {
                            $filters = array();
                            if (isset($_GET['pets_allowed'])) {
                                $filters['pets_allowed'] = true;
                            } else {
                                $filters['pets_allowed'] = false;
                            }
                            if (isset($_GET['smoker_allowed'])) {
                                $filters['smoker_allowed'] = true;
                            } else {
                                $filters['smoker_allowed'] = false;
                            }
                            if (isset($_GET['luggage_allowed'])) {
                                $filters['luggage_allowed'] = true;
                            } else {
                                $filters['luggage_allowed'] = false;
                            }
                            if (isset($_GET['user_is_verified'])) {
                                $filters['user_verified'] = true;
                            } else {
                                $filters['user_verified'] = false;
                            }
                            if (isset($_GET['start_time_range_before'])) {
                                $filters['start_time_range_before'] = $_GET['start_time_range_before'];
                            } else {
                                $filters['start_time_range_before'] = 1;
                            }
                            if (isset($_GET['start_time_range_after'])) {
                                $filters['start_time_range_after'] = $_GET['start_time_range_after'];
                            } else {
                                $filters['start_time_range_after'] = 1;
                            }
                            $carpoolings = $this->searchPageModel->getCarpooling($start_id, $end_id, $requested_date, $requested_hour, $requested_seats, $filters);
                            $this->searchPageView->display_search_results($carpoolings);
                        }
                        ?>
                    </main>
                </div>
            <?php
        }

        public function get_cities() {
            header('Content-Type: application/json');
            
            if (!isset($_GET['query']) || strlen($_GET['query']) < 2) {
                echo json_encode(['cities' => []]);
                return;
            }

            $query = $_GET['query'];
            $cities = $this->searchPageModel->searchCities($query);
            echo json_encode(['cities' => $cities]);
        }
    }   
?>
<script src="/CarShare/script/searchPage.js"></script>
<script src="/CarShare/assets/js/city-autocomplete-enhanced.js"></script>