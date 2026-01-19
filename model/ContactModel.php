<?php

require_once __DIR__ . '/Database.php';

class ContactModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function saveMessage($name, $email, $subject, $message) {
        try {
            $query = "INSERT INTO contact_messages (name, email, subject, message, created_at) 
                      VALUES (:name, :email, :subject, :message, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message', $message);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving contact message: " . $e->getMessage());
            return false;
        }
    }

    public function getAllMessages() {
        try {
            $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching contact messages: " . $e->getMessage());
            return [];
        }
    }
}
