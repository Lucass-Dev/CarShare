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

        static public function send_login_form($values): string {
        $str = "SELECT id FROM users WHERE email=:email AND password_hash=:pass_hash";

        Database::instanciateDb();
        $stmt = Database::getDb()->prepare($str);
        $stmt->execute([
            ":email"=> $values["email"],
            ":pass_hash"=> hash("sha256", $values["password"])
        ]);
        $result = $stmt->fetchObject();
        if ($result) {
            session_destroy();
            session_start();
            $_SESSION["user_id"] = $result->id;
            $_SESSION["logged"] = true;
            session_regenerate_id(true);
            ini_set('session.gc_maxlifetime', 86400);
            header('Location: index.php?controller=home');
            exit;
        }else{
            return "L'email ou le mot de passe n'est pas correct";
        }


        }

        static public function check_form_values($values) {
            $return = array();
            $return["success"] = false;
            $return["message"] = "";

            if (!isset($values["first_name"]) || trim($values["first_name"]) === "") {
                $return["message"] = "Veuillez renseigner votre prénom";
                return $return;
            }

            if (!isset($values["last_name"]) || trim($values["last_name"]) === "") {
                $return["message"] = "Veuillez renseigner votre nom";
                return $return;
            }

            if (!isset($values["mail"]) || trim($values["mail"]) === "") {
                $return["message"] = "Veuillez renseigner votre e‑mail";
                return $return;
            }

            if (!filter_var($values["mail"], FILTER_VALIDATE_EMAIL)) {
                $return["message"] = "Veuillez renseigner un e‑mail valide";
                return $return;
            }

            if (!isset($values["birthdate"]) || trim($values["birthdate"]) === "") {
                $return["message"] = "Veuillez renseigner votre date de naissance";
                return $return;
            }

            if (!isset($values["pass"]) || $values["pass"] === "") {
                $return["message"] = "Veuillez renseigner un mot de passe";
                return $return;
            }

            if (!isset($values["confirm_pass"]) || $values["confirm_pass"] === "") {
                $return["message"] = "Veuillez confirmer votre mot de passe";
                return $return;
            }

            if ($values["pass"] !== $values["confirm_pass"]) {
                $return["message"] = "Les mots de passe ne correspondent pas";
                return $return;
            }

            $return["success"] = true;
            return $return;
        }

        static private function user_exists($mail): bool{
            $str = "SELECT * FROM users WHERE email=:email";
            try {
                $db = Database::getDb();
                $stmt = $db->prepare($str);
                $stmt->execute([
                    ":email"=> $mail
                ]);
                $result = $stmt->fetchObject();
                return $result !== false;
            } catch (\Throwable $th) {
                echo $th->getMessage();
                return false;
            }
        }

        static public function send_register_form($values): array {
            $str = "INSERT INTO users(first_name, last_name, email, password_hash, is_admin, is_verified_user, car_brand, car_model, car_plate, car_is_verified, created_at, global_rating, profile_picture_path)
                    VALUES (:first_name, :last_name, :mail, :password_hash, 0, 0, NULL, NULL, NULL, 0, NOW(), 5, NULL)";
            try {
                $db = Database::getDb();
                $stmt = $db->prepare($str);
                if (UserModel::user_exists($values["mail"])) {
                    return ["message" => "Un utilisateur avec cet email existe déjà. S'il s'agit de vous, veuillez vous connecter ou en choisir un autre.", "success" => "false"];
                }
                if ($values["pass"] !== $values["confirm_pass"]) {
                    return ["message" =>"Attention, les mots de passes ne corespondent pas.", "success" => "false"];
                }
                if (strlen($values["pass"]) <12) {
                    return ["message" =>"Le mot de passe doit contenir au minimum 12 carcatères.", "success" => "false"];
                }
                $stmt->execute([
                    ":first_name"=> $values["first_name"],
                    ":last_name"=> $values["last_name"],
                    ":mail"=> $values["mail"],
                    ":password_hash"=> hash("sha256", $values["pass"]),

                ]);
                return ["message"=> "Compte créé avec succès","success"=> "true"];
            } catch (\Throwable $th) {
                return ["message" => $th->getMessage(), "success" => false];
            }
        }
    }
?>