<?php
    class UserModel{
        static public function getUserProfilePicturePath($id){
            $str = "SELECT profile_picture_path FROM users WHERE id=:id";
            $stmt = Database::getDb()->prepare($str);
            $stmt->execute([
                ":id"=> $id
            ]);
            $result = $stmt->fetchObject();
            return $result->profile_picture_path;
        }

        static public function getUserById($id){
            $str = "SELECT * FROM users WHERE id=:id";
            $stmt = Database::getDb()->prepare($str);
            $stmt->execute([
                ":id"=> $id
            ]);
            $result = $stmt->fetchObject();
            return $result;
        }

        static public function getUserHistory($user_id){

            //History as provider
            $providerQuery = "SELECT l1.name AS start_location, l2.name AS end_location, carpoolings.start_date FROM carpoolings INNER JOIN location l1 ON l1.id=carpoolings.start_id INNER JOIN location l2 ON l2.id=end_id WHERE provider_id = :id AND status=:status";
            $stmt = Database::getDb()->prepare($providerQuery);
            $stmt->execute([
                ":id"=> $user_id,
                ":status"=>1
            ]);
            $result["provider_history"] = $stmt->fetchAll();
            
            //History as passenger
            $passengerQuery = "SELECT l1.name AS start_location, l2.name AS end_location, carpoolings.start_date, users.* FROM carpoolings INNER JOIN bookings ON bookings.carpooling_id=carpoolings.id INNER JOIN location l1 ON l1.id=carpoolings.start_id INNER JOIN location l2 ON l2.id=end_id INNER JOIN users ON users.id=carpoolings.provider_id WHERE bookings.booker_id=:id AND carpoolings.status=:status";
            $stmt = Database::getDb()->prepare($passengerQuery);
            $stmt->execute([
                ":id"=> $user_id,
                ":status"=>1
            ]);
            $result["booker_history"] = $stmt->fetchAll();

            //Incomming providers trip
            $stmt = Database::getDb()->prepare($providerQuery);
            $stmt->execute([
                ":id"=> $user_id,
                ":status"=>0
            ]);
            $result["incoming_provider_history"] = $stmt->fetchAll();
            //Incoming booker trip
            $stmt = Database::getDb()->prepare($passengerQuery);
            $stmt->execute([
                ":id"=> $user_id,
                ":status"=>0
            ]);
            $result["incoming_booker_history"] = $stmt->fetchAll();
            return $result;

        }

        static public function updateUser($form){
            $query = "UPDATE users SET first_name=:first_name, last_name=:last_name, birth_date=:birth_date, email=:email ";
            $params = [
                ":first_name" => $form["first_name"],
                ":last_name" => $form["last_name"],
                ":birth_date" => $form["birth_date"],
                ":email" => $form["email"],
                ":user_id" => $form["user_id"],
            ];
            if (isset($form["pass"]) && !empty($form["pass"])) {
                $query.= ", password_hash=:pass_hash";
                $params[":pass_hash"] =  hash("sha256", $form["pass"]);
            }
            $query.= " WHERE users.id=:user_id";

            $stmt = Database::getDb()->prepare($query);
            $stmt->execute($params);
            return true;
        }
    }
?>