<?php
    require "./model/Home.php";

     class HomeController {

        public function index(){
            $index = new Home();
            echo($index->getStrHelloWorld());
            include './view/home.php';
        }

    }

?>