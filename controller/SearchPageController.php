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
            if (isset($_GET["q"])) {
                $query = $_GET["q"];
            }

        }
    }   

?>