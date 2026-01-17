<?php
require_once __DIR__ . '/Database.php';

class RegisterModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function createUser($firstName, $lastName, $email, $password) {
        // Check if user already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false; // User already exists
        }

        // Create new user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, email, password_hash, is_admin, is_verified_user) 
            VALUES (?, ?, ?, ?, 0, 0)
        ");
        
        try {
            $stmt->execute([$firstName, $lastName, $email, $passwordHash]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public function user_exists($email) {
        return $this->emailExists($email);
    }

    public function send_form($values): array {
        $str = "INSERT INTO users(first_name, last_name, email, password_hash, is_admin, is_verified_user, car_brand, car_model, car_plate, car_is_verified, created_at, global_rating)
                VALUES (:first_name, :last_name, :mail, :password_hash, 0, 0, NULL, NULL, NULL, 0, NOW(), 5)";
        try {
            $db = Database::getDb();
            $stmt = $db->prepare($str);
            if ($this->user_exists($values["mail"])) {
                return ["message" => "Un utilisateur avec cet email existe déjà. S'il s'agit de vous, veuillez vous connecter ou en choisir un autre.", "success" => "false"];
            }
            if ($values["pass"] !== $values["confirm_pass"]) {
                return ["message" =>"Attention, les mots de passes ne corespondent pas.", "success" => "false"];
            }
            if (strlen($values["pass"]) < 12) {
                return ["message" =>"Le mot de passe doit contenir au minimum 12 caractères.", "success" => "false"];
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
