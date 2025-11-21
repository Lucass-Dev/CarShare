<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("./Database.php");

    Database::instanciateDb();
    

function fetchCities($strQuery){
        try {
            $query = Database::$db->prepare("SELECT * from location WHERE name LIKE ? or postal_code LIKE ? LIMIT 10");
            $query->execute(array("%{$strQuery}%", "%{$strQuery}%"));
            header('Content-Type: application/json; charset=utf-8');
            echo(json_encode($query->fetchAll(PDO::FETCH_ASSOC)));
        } catch (Throwable $th) {
            print_r($th);
            http_response_code(500);
        }
}
?>
