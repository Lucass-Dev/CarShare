<?php
require_once __DIR__ . '/Database.php';

class BookingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function createBooking($bookerId, $carpoolingId) {
        // Check if booking already exists
        $stmt = $this->db->prepare("SELECT id FROM bookings WHERE booker_id = ? AND carpooling_id = ?");
        $stmt->execute([$bookerId, $carpoolingId]);
        if ($stmt->fetch()) {
            return false; // Booking already exists
        }

        // Check if places are available
        $stmt = $this->db->prepare("SELECT available_places FROM carpoolings WHERE id = ?");
        $stmt->execute([$carpoolingId]);
        $carpooling = $stmt->fetch();
        
        if (!$carpooling || $carpooling['available_places'] < 1) {
            return false;
        }

        // Create booking
        $stmt = $this->db->prepare("INSERT INTO bookings (booker_id, carpooling_id) VALUES (?, ?)");
        
        try {
            $this->db->beginTransaction();
            
            // Insert booking
            $stmt->execute([$bookerId, $carpoolingId]);
            $bookingId = $this->db->lastInsertId();
            
            // Update available places
            $stmt = $this->db->prepare("UPDATE carpoolings SET available_places = available_places - 1 WHERE id = ?");
            $stmt->execute([$carpoolingId]);
            
            $this->db->commit();
            return $bookingId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getBookingsByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT b.*, c.*, 
                   u.first_name as provider_first_name, u.last_name as provider_last_name,
                   l1.name as start_location, l2.name as end_location
            FROM bookings b
            JOIN carpoolings c ON b.carpooling_id = c.id
            JOIN users u ON c.provider_id = u.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE b.booker_id = ?
            ORDER BY c.start_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getCarpoolingsByProvider($providerId) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   l1.name as start_location, l2.name as end_location
            FROM carpoolings c
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.provider_id = ?
            ORDER BY c.start_date DESC
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }
}
