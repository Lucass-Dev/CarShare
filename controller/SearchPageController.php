<?php
    require_once("./model/SearchPageModel.php");
    include_once("./view/SearchPageView.php");



    final class SearchPageController
    {
        private $searchPageModel;
        private $searchPageView;

        public function __construct() {
            $this->searchPageModel = new SearchPageModel();
            $this->searchPageView = new SearchPageView();
        }

        public function render() {
            $start_name = null;
            $start_id = null;
            $end_name = null;
            $end_id = null;
            $requested_date = null;
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
            if (isset($_GET["seats"]) && $_GET["seats"] != ""){
                $requested_seats = $_GET["seats"];
            }

            $this->searchPageView->display_search_bar($start_name, $start_id,$end_name, $end_id,$requested_date, $requested_seats);

            ?> 
                <h2>Search Results</h2>
                <div class="search-page-container">

                    <?php
                        $this->searchPageView->display_search_filters();
                        if (isset($_GET['action']) && $_GET['action'] === 'display_search') {
                            $carpoolings = $this->searchPageModel->getCarpooling($start_id, $end_id, $requested_date, $requested_seats, array());
                            $this->searchPageView->display_search_results($carpoolings);
                        }
                    ?>
                </div>
            <?php

        }
    }   

?>