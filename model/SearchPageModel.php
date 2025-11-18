<?php
    require_once("./model/Database.php");


    class SearchPageModel
    {
        public function getToleranceDate($start_date, $start_hour, $tolerance_hour, $before) : string{
            
            if($before){
                $new_hour = intval( explode(":", $start_hour)[0]) - intval($tolerance_hour);
                if ($new_hour < 0) {
                    $new_hour += 24;
                    $date_array = explode("-", explode(" ", $start_date)[0]);
                    $start_date = $date_array[0] . "-" . $date_array[1] . "-" . sprintf("%02d", intval($date_array[2]) - 1);
                }
            }else{
                $new_hour = intval( explode(":", $start_hour)[0]) + intval($tolerance_hour);
                if ($new_hour > 23) {
                    $new_hour -= 24;
                    $date_array = explode("-", explode(" ", $start_date)[0]);
                    $start_date = $date_array[0] . "-" . $date_array[1] . "-" . sprintf("%02d", intval($date_array[2]) + 1);
                }
            }

            $tolerance_date = $start_date." ". strval($new_hour);
            $tolerance_date .= ":".explode(":", $start_hour)[1];
            return $tolerance_date.":00";

        }
        public function getCarpooling($start_id, $end_id, $date, $hour, $seats, $filters) : array{
            $results = "";
            $db = Database::$db;

            $sql =
                "SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places , u.first_name as provider_name
                        FROM `carpoolings` c
                        INNER JOIN `location` l2 on (c.start_id = l2.id)
                        INNER JOIN `location` l1 on (c.end_id = l1.id)
                        INNER JOIN `users` u on (c.provider_id = u.id)
                        WHERE :start_id = c.start_id";
            if (!empty($end_id)) {
                $sql .= " AND c.end_id = :end_id";
            }

            $start_date = $this->getToleranceDate($date, $hour, $filters['start_time_range_before'], true);
            $tolerance_date = $this->getToleranceDate($date, $hour, $filters['start_time_range_after'], false);


            $sql .= " AND c.start_date >= :start_date
                    AND c.start_date <= :tolerance
                    AND c.available_places >= :seats
                    AND c.pets_allowed = :pets_allowed
                    AND c.smoker_allowed = :smoker_allowed
                    AND c.luggage_allowed = :luggage_allowed
                    ";

            
            $sql .= "ORDER BY c.start_date ASC";


            $stmt = $db->prepare($sql);
            $params = [
                ':start_id' => $start_id,
                ':start_date' => $start_date,
                ':seats' => $seats,
                ':pets_allowed' => $filters['pets_allowed'],
                ':smoker_allowed' => $filters['smoker_allowed'],
                ':luggage_allowed' => $filters['luggage_allowed'],
                ':tolerance' => $tolerance_date
            ];

            if (!empty($end_id)) {
                $params[':end_id'] = $end_id;
            }

            $stmt->execute($params);
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