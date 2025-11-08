<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("./Database.php");

    if (Database::$db == null) {
        Database::instanciateDb("carshare", "localhost", "root", "root");
    }
    

    if(isset($_GET["need"])){
        $action = $_GET["need"];
        switch($action){
            case "fetchCities":
                if(isset($_GET["query"])){
                    $strQuery = $_GET["query"];
                    fetchCities($strQuery);
                }
                break;
            default:
                
                break;
        }
    }

    function fetchCities($strQuery){

        

        $query = Database::$db->prepare("SELECT * from location WHERE name LIKE '%{$strQuery}%' LIMIT 10");
        $query->execute(array($strQuery));
        header('Content-Type: application/json; charset=utf-8');
        echo(json_encode($query->fetchAll(PDO::FETCH_ASSOC)));

    }
?>