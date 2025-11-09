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
            $this->searchPageView->display_search_bar();

            ?> 
                                <h2>Search Results</h2>
                <div class="search-page-container">

                    <?php
                        $this->searchPageView->display_search_filters();
                        if (isset($_GET['display_search']) && $_GET['display_search'] === 'true') {
                            $carpoolings = $this->searchPageModel->getAllCarpoolings();
                            $this->searchPageView->display_search_results($carpoolings);
                        }
                    ?>
                </div>
            <?php

        }
    }   

?>