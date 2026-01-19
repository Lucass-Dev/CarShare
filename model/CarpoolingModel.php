<?php
require_once __DIR__ . '/Database.php';

class CarpoolingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function createCarpooling($providerId, $startId, $endId, $startDate, $price, $availablePlaces) {
        $stmt = $this->db->prepare("
            INSERT INTO carpoolings (provider_id, start_id, end_id, start_date, price, available_places, status) 
            VALUES (?, ?, ?, ?, ?, ?, 1)
        ");
        
        try {
            $stmt->execute([$providerId, $startId, $endId, $startDate, $price, $availablePlaces]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCarpoolingById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   u.first_name, u.last_name, u.global_rating,
                   l1.name as start_location, l1.postal_code as start_postal,
                   l2.name as end_location, l2.postal_code as end_postal
            FROM carpoolings c
            JOIN users u ON c.provider_id = u.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function searchCarpoolings($startId = null, $endId = null, $date = null) {
        $sql = "
            SELECT c.*, 
                   u.first_name, u.last_name, u.global_rating,
                   l1.name as start_location, l1.postal_code as start_postal,
                   l2.name as end_location, l2.postal_code as end_postal
            FROM carpoolings c
            JOIN users u ON c.provider_id = u.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.status = 1 AND c.available_places > 0
        ";
        
        $params = [];
        if ($startId) {
            $sql .= " AND c.start_id = ?";
            $params[] = $startId;
        }
        if ($endId) {
            $sql .= " AND c.end_id = ?";
            $params[] = $endId;
        }
        if ($date) {
            $sql .= " AND DATE(c.start_date) = ?";
            $params[] = $date;
        }
        
        $sql .= " ORDER BY c.start_date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getLocationByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM location WHERE name LIKE ? LIMIT 1");
        $stmt->execute(["%$name%"]);
        return $stmt->fetch();
    }

    public function getAllLocations() {
        $stmt = $this->db->query("SELECT * FROM location ORDER BY name");
        return $stmt->fetchAll();
    }

    /**
     * Check if a user has already booked a specific trip
     * @param int $userId User ID
     * @param int $carpoolingId Carpooling ID
     * @return bool True if user has booked this trip
     */
    public function hasUserBookedTrip($userId, $carpoolingId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM bookings 
            WHERE booker_id = ? AND carpooling_id = ?
        ");
        $stmt->execute([$userId, $carpoolingId]);
        $result = $stmt->fetch();
        return $result && $result['count'] > 0;
    }
}