<?php
    require "./model/HomeModel.php";

     class HomeController {

        public function index(){
            $index = new HomeModel();
            echo($index->getStrHelloWorld());
            include './view/HomeView.php';
        }

    }

?>