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
    public static function getCarpooling($start_id, $end_id, $date, $hour, $seats, $filters) : array{
        $results = "";
        $db = Database::getDb();

        $sql =
            "SELECT l2.name as start_name, l1.name as end_name, c.start_date, available_places , u.first_name as provider_name, c.id, c.price, c.provider_id, u.global_rating
                    FROM `carpoolings` c
                    INNER JOIN `location` l2 on (c.start_id = l2.id)
                    INNER JOIN `location` l1 on (c.end_id = l1.id)
                    INNER JOIN `users` u on (c.provider_id = u.id)
                    WHERE :start_id = c.start_id";
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
        // Note: luggage_allowed, smoker_allowed, pets_allowed n'existent pas dans la table carpoolings
        // Ces filtres sont ignorés

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

        if($filters['start_time_range_before'] != 0){
            $params[':tolerance'] = $tolerance_date;
        }

        if (!empty($end_id)) {
            $params[':end_id'] = $end_id;
        }

        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;

    }

    public static function getCarpoolingById($trip_id) : array{
        $result = array();
        $db = Database::getDb();
        $stmt = $db->prepare("SELECT c.id as trip_id, l1.name as start_name, l2.name as end_name, c.start_date, c.available_places , c.luggage_allowed, c.pets_allowed, c.smoker_allowed, u.first_name as provider_name, u.id as provider_id, c.price, c.provider_id, u.global_rating, u.profile_picture_path
                    FROM `carpoolings` c
                    INNER JOIN `location` l2 on (c.start_id = l2.id)
                    INNER JOIN `location` l1 on (c.end_id = l1.id)
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
}
?>