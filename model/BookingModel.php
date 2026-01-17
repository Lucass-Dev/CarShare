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
                   u.id as provider_id,
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

    public function getCarpoolingsByProvider($providerId, $search = '', $sortBy = 'date', $sortOrder = 'desc') {
        $sql = "
            SELECT c.*, 
                   l1.name as start_location, l2.name as end_location
            FROM carpoolings c
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.provider_id = ?";
        
        $params = [$providerId];
        
        // Add search filter if provided
        if (!empty($search)) {
            $sql .= " AND (l1.name LIKE ? OR l2.name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Add sorting
        switch ($sortBy) {
            case 'price':
                $sql .= " ORDER BY c.price " . ($sortOrder === 'asc' ? 'ASC' : 'DESC');
                break;
            case 'date':
            default:
                $sql .= " ORDER BY c.start_date " . ($sortOrder === 'asc' ? 'ASC' : 'DESC');
                break;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
