<?php
    require_once("./model/Database.php");


    class SearchPageModel
    {
        public function getToleranceDate($start_date, $start_hour, $tolerance_hour, $before) : string{
            $dateTime = new DateTime($start_date . " " . $start_hour);
            $interval = new DateInterval('PT' . abs($tolerance_hour) . 'H');
            
            if($before){
                $dateTime->sub($interval);
            }else{
                $dateTime->add($interval);
            }
            
            return $dateTime->format('Y-m-d H:i:s');
        }
        /**
         * Replace SQL parameter tokens with values from array
         * Useful for debugging SQL queries
         * 
         * @param string $sql SQL query with :param tokens
         * @param array $params Array of parameters [':param' => value]
         * @return string SQL query with tokens replaced
         */
        public function replaceSqlTokens(string $sql, array $params): string {
            $result = $sql;
            
            foreach ($params as $token => $value) {
                // Ensure token starts with colon
                $tokenKey = strpos($token, ':') === 0 ? $token : ':' . $token;
                
                // Format value based on type
                if ($value === null) {
                    $replacement = 'NULL';
                } elseif (is_bool($value)) {
                    $replacement = $value ? '1' : '0';
                } elseif (is_numeric($value)) {
                    $replacement = (string)$value;
                } else {
                    $replacement = "'" . addslashes($value) . "'";
                }
                
                $result = str_replace($tokenKey, $replacement, $result);
            }
            
            return $result;
        }

        public function getCarpooling($start_id, $end_id, $date, $hour, $seats, $filters) : array{
            if(empty($hour)) $hour = "00:00";
            $results = "";
            $db = Database::getDb();

            $sql =
                "SELECT c.id, l2.name as start_name, l1.name as end_name, c.start_date, c.price, available_places, 
                        u.id as provider_id, u.first_name as provider_name
                        FROM `carpoolings` c
                        INNER JOIN `location` l2 on (c.start_id = l2.id)
                        INNER JOIN `location` l1 on (c.end_id = l1.id)
                        INNER JOIN `users` u on (c.provider_id = u.id)
                        WHERE :start_id = c.start_id";
            if (!empty($end_id)) {
                $sql .= " AND c.end_id = :end_id";
            }

            if ($hour === "00:00") {
                $start_date = $date . " 00:00:00";
                $tolerance_date = $date . " 23:59:59";
            } else {
                $start_date = $this->getToleranceDate($date, $hour, $filters['start_time_range_before'], true);
                $tolerance_date = $this->getToleranceDate($date, $hour, $filters['start_time_range_after'], false);
            }

            $sql .= " AND c.start_date >= :start_date
                    AND c.start_date <= :tolerance
                    AND c.available_places >= :seats
                    AND c.available_places > 0";

            $params = [
                ':start_id' => $start_id,
                ':start_date' => $start_date,
                ':seats' => $seats,
                ':tolerance' => $tolerance_date
            ];

            if (isset($filters['pets_allowed']) && !empty($filters['pets_allowed'])) {
                $sql .= " AND c.pets_allowed = :pets_allowed";
                $params[':pets_allowed'] = 1;
            }

            if (isset($filters['smoker_allowed']) && !empty($filters['smoker_allowed'])) {
                $sql .= " AND c.smoker_allowed = :smoker_allowed";
                $params[':smoker_allowed'] = 1;
            }

            if (isset($filters['luggage_allowed']) && !empty($filters['luggage_allowed'])) {
                $sql .= " AND c.luggage_allowed = :luggage_allowed";
                $params[':luggage_allowed'] = 1;
            }

            if (!empty($end_id)) {
                $params[':end_id'] = $end_id;
            }
            $stmt = $db->prepare($sql);
            
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($results); $i++){
                $results[$i]['remaining_places'] = self::getRemainingPlaces($results[$i]['id']);
            }
            print_r($results);
            return $results;
        }

        public function getAllCarpoolings() : array{
            $arResults = array();
            $db = Database::getDb();
            $stmt = $db->query("SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places FROM `carpoolings` c INNER JOIN `location` l2 on (c.start_id = l2.id) INNER JOIN `location` l1 on (c.end_id = l1.id) ORDER BY l2.id DESC;");
            $arResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $arResults;
        }

        public function getCityNameWithPostalCode($cityId) : string{
            $stResult = "";
            $db = Database::getDb();
            $stmt = $db->prepare("SELECT name, postal_code FROM `location` WHERE id = :city_id;");
            $stmt->bindParam(":city_id", $cityId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result && isset($result['name'])){
                $stResult = $result['name']." (".$result['postal_code'].")";
            }
            return $stResult;
        }

        public function searchCities($query) : array {
            $db = Database::getDb();
            $stmt = $db->prepare("SELECT id, CONCAT(name, ' (', postal_code, ')') as name FROM `location` WHERE name LIKE :query LIMIT 10");
            $searchTerm = $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public static function getRemainingPlaces($carpooling_id){
            $db = Database::getDb();
            
            // Get available places
            $stmt = $db->prepare("SELECT available_places FROM carpoolings WHERE id = :id");
            $stmt->execute([':id' => $carpooling_id]);
            $carpooling = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$carpooling) {
                return 0; // or throw error
            }
            
            $available = $carpooling['available_places'];
            
            // Get number of bookings
            $stmt = $db->prepare("SELECT COUNT(*) as booking_count FROM bookings WHERE carpooling_id = :id");
            $stmt->execute([':id' => $carpooling_id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $booked = $booking['booking_count'];
            
            return $available - $booked;
        }
    }
    

?>