<?php
    require_once("./model/Database.php");


    class SearchPageModel
    {
        static function getAllCarpoolings() : array{
            $arResults = array();
            $db = Database::$db;

            $stmt = $db->query("SELECT * FROM `carpoolings` ORDER BY `id` DESC");
            print_r($stmt->fetchAll());

            return $arResults;
        }
    }
    

?>