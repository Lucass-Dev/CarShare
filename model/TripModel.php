<?php
class TripModel {
    public static function getToleranceDate($start_date, $start_hour, $tolerance_hour, $before) : string{
            
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
    public static function getCarpooling($start_id, $end_id, $date, $hour, $seats, $filters, $user_id) : array{
        $results = "";
        $db = Database::getDb();

        $sql =
            "SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places , u.first_name as provider_name, c.id, c.price, c.provider_id, u.global_rating, u.profile_picture_path
                    FROM `carpoolings` c
                    INNER JOIN `location` l2 on (c.start_id = l2.id)
                    INNER JOIN `location` l1 on (c.end_id = l1.id)
                    INNER JOIN `users` u on (c.provider_id = u.id)
                    WHERE :start_id = c.start_id";
        if ($user_id != null) {
            $sql.= " AND provider_id != :user_id";
        }
        if (!empty($end_id)) {
            $sql .= " AND c.end_id = :end_id";
        }

        if(empty($hour)){
            $hour = date("00:00");
        }

        $start_date = TripModel::getToleranceDate($date, $hour, $filters['start_time_range_before'], true);
        if($filters['start_time_range_before'] != 0){
            $tolerance_date = TripModel::getToleranceDate($date, $hour, $filters['start_time_range_after'], false);
            $sql .= " AND c.start_date <= :tolerance ";
        }


        $sql .= " AND c.start_date >= :start_date
                AND c.available_places >= :seats
                ";
        if (isset($filters["is_verified_user"]) && $filters["is_verified_user"] == "on") {
            $sql .= " AND u.is_verified_user = 1 ";
        }
        if (isset($filters["luggage_allowed"]) && $filters["luggage_allowed"] == "on") {
            $sql .= " AND c.luggage_allowed = 1 ";
        }
        if (isset($filters["smoker_allowed"]) && $filters["smoker_allowed"] == "on") {
            $sql .= " AND c.smoker_allowed = 1 ";
        }
        if (isset($filters["pets_allowed"]) && $filters["pets_allowed"] == "on") {
            $sql .= " AND c.pets_allowed = 1 ";
        }

        if (isset($filters["sort_by"]) && $filters["sort_by"] != "") {
            switch ($filters["sort_by"]) {
                case 'price':
                    $sql .= 'ORDER BY c.price ';
                    break;
                case 'date':
                    $sql .= 'ORDER BY c.start_date ';
                    break;
                case 'seats':
                    $sql .= 'ORDER BY c.available_places ';
                    break;
                case 'rating':
                    $sql .= 'ORDER BY u.global_rating ';
                    break;
                default:
                    break;
            }
            if (isset($filters["order_type"]) && $filters["order_type"] != "") {
                if ($filters["order_type"] == "asc") {
                    $sql .= "ASC";
                }else if ($filters["order_type"] == "desc"){
                    $sql .= "DESC";
                }
            }
        }else{
            $sql .= "ORDER BY c.start_date ASC";
        }

        $stmt = $db->prepare($sql);
        $params = [
            ':start_id' => $start_id,
            ':start_date' => $start_date,
            ':seats' => $seats,
        ];

        if ($user_id != null) {
            $params[":user_id"] = $user_id;
        }

        if($filters['start_time_range_before'] != 0){
            $params[':tolerance'] = $tolerance_date;
        }

        if (!empty($end_id)) {
            $params[':end_id'] = $end_id;
        }

        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $key => $value) {
            $results[$key]["remaining_places"] = TripModel::getRemainingPlaces($value["id"]);
        }
        return $results;

    }

    public static function getCarpoolingById($trip_id) : array{
        $result = array();
        $db = Database::getDb();
        $stmt = $db->prepare("SELECT c.id as trip_id, c.status, l1.name as start_name, l1.id as start_id, l2.name as end_name, l2.id as end_id, c.start_date, c.available_places , c.luggage_allowed, c.pets_allowed, c.smoker_allowed, u.first_name as provider_name, u.id as provider_id, c.price, c.provider_id, u.global_rating, u.profile_picture_path
                    FROM `carpoolings` c
                    INNER JOIN `location` l1 on (c.start_id = l1.id)
                    INNER JOIN `location` l2 on (c.end_id = l2.id)
                    INNER JOIN `users` u on (c.provider_id = u.id)
                    WHERE c.id = :trip_id;");
        $stmt->bindParam(":trip_id", $trip_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public static function getAllCarpoolings() : array{
        $arResults = array();
        $db = Database::getDb();
        $stmt = $db->query("SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places FROM `carpoolings` c INNER JOIN `location` l2 on (c.start_id = l2.id) INNER JOIN `location` l1 on (c.end_id = l1.id) ORDER BY l2.id DESC;");
        $arResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $arResults;
    }

    public static function getCityNameWithPostalCode($cityId) : string{
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

    public static function createTrip($values){

        $str = "INSERT INTO carpoolings(provider_id, start_date, price, available_places, status, start_id, end_id, pets_allowed, smoker_allowed, luggage_allowed)
                VALUES (:provider_id, :start_date, :price, :available_places, :status, :start_id, :end_id, :pets_allowed, :smoker_allowed, :luggage_allowed)";
        $start_date = $_POST["date"] . " " . $_POST["time"];
        $stmt = Database::getDb()->prepare($str);

        $result = $stmt->execute([
            ":provider_id" => $_SESSION["user_id"],
            ":start_date" => $start_date,
            ":price" => $values["price"],
            ":available_places" => $values["seats"],
            ":status" => 0,
            ":start_id" => $values["form_start_input"],
            ":end_id" => $values["form_end_input"],
            ":pets_allowed" => $values["pets_allowed"],
            ":smoker_allowed" => $values["smoker_allowed"],
            ":luggage_allowed" => $values["luggage_allowed"]
        ]);

        if ($result) {
            $_POST = array();
            return true;
        }

        return true;
    }

    static public function hasAlreadyBooked(int $userId, int $carpoolingId): bool
    {
        $sql = "
            SELECT 1
            FROM bookings
            WHERE booker_id = :user_id
            AND carpooling_id = :carpooling_id
            LIMIT 1
        ";

        $stmt = Database::getDb()->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':carpooling_id' => $carpoolingId
        ]);

        return (bool) $stmt->fetchColumn();

        
    }
    static function hasAvailablePlaces(int $carpoolingId): bool
    {
        $sql = "
            SELECT 
                c.available_places,
                COUNT(b.id) AS booked_places
            FROM carpoolings c
            LEFT JOIN bookings b 
                ON b.carpooling_id = c.id
            WHERE c.id = :carpooling_id
            GROUP BY c.available_places
        ";

        $stmt = Database::getDb()->prepare($sql);
        $stmt->execute([
            ':carpooling_id' => $carpoolingId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return (int)$result['booked_places'] < (int)$result['available_places'];
    }
    
    static function submit_trip_report($array){
        // expected keys: user_id, carpooling_id, reason, description
        $db = Database::getDb();

        $reporterId = isset($array['user_id']) ? intval($array['user_id']) : null;
        $carpoolingId = isset($array['carpooling_id']) ? intval($array['carpooling_id']) : null;
        $reason = isset($array['reason']) ? trim($array['reason']) : null;
        $description = isset($array['description']) ? trim($array['description']) : null;

        if ($reporterId === null || $carpoolingId === null) {
            return false;
        }
        if ($reason === null || $reason === '') {
            return false;
        }
        if ($description === null || $description === '') {
            return false;
        }

        $sql = "INSERT INTO report (reporter_id, carpooling_id, reason, content, is_in_progress, is_treated, created_at, closed_at)
                VALUES (:reporter_id, :carpooling_id, :reason, :content, :is_in_progress, :is_treated, NOW(), '0001-01-01 00:00:00')";

        try {
            $stmt = $db->prepare($sql);
            $ok = $stmt->execute([
                ':reporter_id' => $reporterId,
                ':carpooling_id' => $carpoolingId,
                ':reason' => $reason,
                ':content' => $description,
                ':is_in_progress' => 0,
                ':is_treated' => 0
            ]);

            if (!$ok) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    static function getRemainingPlaces($carpooling_id){
        $query = "SELECT carpoolings.available_places-count(carpooling_id) as 'remaining_places' FROM `bookings` INNER JOIN carpoolings ON carpooling_id=carpoolings.id GROUP by carpooling_id HAVING carpooling_id=:id";
        $stmt = Database::getDb()->prepare($query);
        $result = $stmt->execute([":id" => $carpooling_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result["remaining_places"];
        }
    }
}
?>