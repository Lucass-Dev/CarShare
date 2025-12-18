<?php
require_once __DIR__ . '/Database.php';

class RegisterModel {
    
    public function check_form_values($values) {
        if (!isset($values["first_name"]) || $values["first_name"] == "") {
            return false;
        }
        if (!isset($values["last_name"]) || $values["last_name"] == "") {
            return false;
        }
        if (!isset($values["mail"]) || $values["mail"] == "") {
            return false;
        }
        if (!isset($values["birthdate"]) || $values["birthdate"] == "") {
            return false;
        }
        if (!isset($values["pass"]) || $values["pass"] == "") {
            return false;
        }
        if (!isset($values["confirm_pass"]) || $values["confirm_pass"] == "") {
            return false;
        }
        if (!isset($values["sexe"]) || $values["sexe"] == "") {
            return false;
        }

        return true;
    }

    private function user_exists($mail): bool{
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

    public function send_form($values): array {
        $str = "INSERT INTO users(first_name, last_name, email, password_hash, is_admin, is_verified_user, car_brand, car_model, car_plate, car_is_verified, created_at, global_rating, profile_picture_path)
                VALUES (:first_name, :last_name, :mail, :password_hash, 0, 0, NULL, NULL, NULL, 0, NOW(), 5, NULL)";
        try {
            $db = Database::getDb();
            $stmt = $db->prepare($str);
            if ($this->user_exists($values["mail"])) {
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