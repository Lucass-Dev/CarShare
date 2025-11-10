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
            $end_name = null;
            $requested_date = null;
            $requested_seats = null;

            if (isset($_GET["form_start_input"]) && $_GET["form_start_input"] != "") {
                $start_name = $_GET["form_start_input"];
            }
            if (isset($_GET["form_end_input"]) && $_GET["form_end_input"] != ""){
                $end_name = $_GET["form_end_input"];
            }
            if (isset($_GET["date"]) && $_GET["date"] != ""){
                $requested_date = $_GET["date"];
            }
            if (isset($_GET["seats"]) && $_GET["seats"] != ""){
                $requested_seats = $_GET["seats"];
            }

            $this->searchPageView->display_search_bar($start_name, $start_name,$end_name, $end_name,$requested_date, $requested_seats);

            ?> 
                                <h2>Search Results</h2>
                <div class="search-page-container">

                    <?php
                        $this->searchPageView->display_search_filters();
                        if (isset($_GET['action']) && $_GET['action'] === 'display_search') {
                            $carpoolings = $this->searchPageModel->getAllCarpoolings();
                            $this->searchPageView->display_search_results($carpoolings);
                        }
                    ?>
                </div>
            <?php

        }
    }   

?>