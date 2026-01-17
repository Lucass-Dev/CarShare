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

    public function updateVehicle($userId, $data) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET car_brand = ?, car_model = ?, car_plate = ?
            WHERE id = ?
        ");
        
        try {
            $stmt->execute([
                $data['car_brand'],
                $data['car_model'],
                $data['car_plate'],
                $userId
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteAccount($userId) {
        // Delete user from database
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        
        try {
            $stmt->execute([$userId]);
            return true;
        } catch (PDOException $e) {
            error_log('Erreur suppression compte : ' . $e->getMessage());
            return false;
        }
    }
}