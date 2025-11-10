<?php
    require_once("./model/Database.php");


    class SearchPageModel
    {
        public function getCarpooling($start_id, $end_id, $date, $seats) : array{
            $results = "";
            $db = Database::$db;
            $stmt = $db->query(
                "SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places , u.first_name as provider_name
                        FROM `carpoolings` c
                        INNER JOIN `location` l2 on (c.start_id = l2.id)
                        INNER JOIN `location` l1 on (c.end_id = l1.id)
                        INNER JOIN `users` u on (c.provider_id = u.id)
                        WHERE $start_id = c.start_id AND $end_id = c.end_id AND c.start_date >= '$date' AND available_places >= $seats
                        ORDER BY c.start_date ASC;
            ");

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;

        }

        public function getAllCarpoolings() : array{
            $arResults = array();
            $db = Database::$db;
            $stmt = $db->query("SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places FROM `carpoolings` c INNER JOIN `location` l2 on (c.start_id = l2.id) INNER JOIN `location` l1 on (c.end_id = l1.id) ORDER BY l2.id DESC;");
            $arResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $arResults;
        }

        public function getCityNameWithPostalCode($cityId) : string{
            $stResult = "";
            $db = Database::$db;
            $stmt = $db->prepare("SELECT name, postal_code FROM `location` WHERE id = :city_id;");
            $stmt->bindParam(":city_id", $cityId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result && isset($result['name'])){
                $stResult = $result['name']." (".$result['postal_code'].")";
            }
            return $stResult;
        }
    }
    

?>