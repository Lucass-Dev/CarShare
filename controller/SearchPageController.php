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
            $query = null;


            //switch
            if (isset($_GET["q"])) {
                $query = $_GET["q"];
                echo $query;
            }

            $this->searchPageView::render_data($this->searchPageModel::getAllCarpoolings());
        }
    }   

?>