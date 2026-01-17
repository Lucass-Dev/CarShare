<?php
require_once __DIR__ . '/Database.php';

class LoginModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([strtolower(trim($email))]);
        return $stmt->fetch();
    }

    public function updatePassword($userId, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }
}