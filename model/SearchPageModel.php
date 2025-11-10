<?php
    require_once("./model/Database.php");


    class SearchPageModel
    {
        public function getAllCarpoolings() : array{
            $arResults = array();
            $db = Database::$db;
            $stmt = $db->query("SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places FROM `carpoolings` c INNER JOIN `location` l2 on (c.start_id = l2.id) INNER JOIN `location` l1 on (c.end_id = l1.id) ORDER BY l2.id DESC;");
            $arResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $arResults;
        }
    }
    

?>