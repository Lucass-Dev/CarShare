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
}