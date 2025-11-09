<?php
    require_once("./model/Database.php");


    class SearchPageModel
    {
        public function getAllCarpoolings() : array{
            $arResults = array();
            $db = Database::$db;
            $stmt = $db->query("SELECT * FROM `carpoolings` ORDER BY `id` DESC");
            $arResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $arResults;
        }
    }
    

?>