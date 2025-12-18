<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("./Database.php");

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
    // Use prepared statement to prevent SQL injection
    $query = Database::getDb()->prepare("SELECT * FROM location WHERE name LIKE ? LIMIT 10");
    $searchTerm = '%' . $strQuery . '%';
    $query->execute(array($searchTerm));
    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($query->fetchAll(PDO::FETCH_ASSOC)));
}
?>
