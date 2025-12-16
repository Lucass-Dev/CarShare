<?php
require_once __DIR__ . '/Database.php';

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function getStats() {
        $stats = [];
        
        // Total users
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        $stats['total_users'] = $stmt->fetch()['total'];
        
        // Total carpoolings
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM carpoolings");
        $stats['total_carpoolings'] = $stmt->fetch()['total'];
        
        // Total bookings
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM bookings");
        $stats['total_bookings'] = $stmt->fetch()['total'];
        
        // Active carpoolings
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM carpoolings WHERE status = 1 AND start_date > NOW()");
        $stats['active_carpoolings'] = $stmt->fetch()['total'];
        
        return $stats;
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getAllCarpoolings() {
        $stmt = $this->db->query("
            SELECT c.*, u.first_name, u.last_name, u.email,
                   l1.name as start_location, l2.name as end_location
            FROM carpoolings c
            JOIN users u ON c.provider_id = u.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            ORDER BY c.start_date DESC
        ");
        return $stmt->fetchAll();
    }

    public function getAllBookings() {
        $stmt = $this->db->query("
            SELECT b.*, 
                   u.first_name as booker_first_name, u.last_name as booker_last_name,
                   c.start_date, l1.name as start_location, l2.name as end_location
            FROM bookings b
            JOIN users u ON b.booker_id = u.id
            JOIN carpoolings c ON b.carpooling_id = c.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            ORDER BY b.id DESC
        ");
        return $stmt->fetchAll();
    }
}