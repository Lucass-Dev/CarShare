<?php
require_once __DIR__ . '/Database.php';

class ProfileModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function getUserProfile($userId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function updateUserProfile($userId, $data) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, email = ?
            WHERE id = ?
        ");
        
        try {
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $userId
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePassword($userId, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        
        try {
            $stmt->execute([$passwordHash, $userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    static public function updateVehicle($userId, $data) {
        $stmt = Database::getDb()->prepare("
            UPDATE users 
            SET car_brand = ?, car_model = ?, car_plate = ?, car_year = ?, car_crit_air = ?
            WHERE id = ?
        ");
        
        try {
            $stmt->execute([
                $data['car_brand'],
                $data['car_model'],
                $data['car_plate'],
                $data['car_year'],
                $data['car_crit_air'],
                $userId
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}