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

    /**
     * Create multiple bookings (one per seat requested)
     */
    public function createMultipleBookings($bookerId, $carpoolingId, $seatsCount) {
        // Check if places are available
        $stmt = $this->db->prepare("SELECT available_places FROM carpoolings WHERE id = ?");
        $stmt->execute([$carpoolingId]);
        $carpooling = $stmt->fetch();
        
        if (!$carpooling || $carpooling['available_places'] < $seatsCount) {
            return false;
        }

        // Create multiple bookings (one per seat)
        $stmt = $this->db->prepare("INSERT INTO bookings (booker_id, carpooling_id) VALUES (?, ?)");
        
        try {
            $this->db->beginTransaction();
            
            $firstBookingId = null;
            for ($i = 0; $i < $seatsCount; $i++) {
                $stmt->execute([$bookerId, $carpoolingId]);
                if ($i === 0) {
                    $firstBookingId = $this->db->lastInsertId();
                }
            }
            
            // Update available places
            $stmt = $this->db->prepare("UPDATE carpoolings SET available_places = available_places - ? WHERE id = ?");
            $stmt->execute([$seatsCount, $carpoolingId]);
            
            $this->db->commit();
            return $firstBookingId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get the number of booked places for a carpooling
     */
    public function getBookedPlacesCount($carpoolingId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM bookings WHERE carpooling_id = ?");
        $stmt->execute([$carpoolingId]);
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }

    /**
     * Cancel a booking and restore available places
     */
    public function cancelBooking($bookingId, $userId) {
        try {
            $this->db->beginTransaction();
            
            // Get booking info
            $stmt = $this->db->prepare("
                SELECT b.carpooling_id, c.provider_id 
                FROM bookings b
                JOIN carpoolings c ON b.carpooling_id = c.id
                WHERE b.id = ?
            ");
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();
            
            if (!$booking) {
                $this->db->rollBack();
                return false;
            }
            
            // Check if user is the provider (owner of the trip)
            if ($booking['provider_id'] != $userId) {
                $this->db->rollBack();
                return false;
            }
            
            // Delete booking
            $stmt = $this->db->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$bookingId]);
            
            // Restore available place
            $stmt = $this->db->prepare("UPDATE carpoolings SET available_places = available_places + 1 WHERE id = ?");
            $stmt->execute([$booking['carpooling_id']]);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error canceling booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancel all bookings for a user on a specific carpooling
     */
    public function cancelUserBookingsForTrip($bookerId, $carpoolingId, $providerId) {
        try {
            $this->db->beginTransaction();
            
            // Check if user is the provider
            $stmt = $this->db->prepare("SELECT provider_id FROM carpoolings WHERE id = ?");
            $stmt->execute([$carpoolingId]);
            $carpooling = $stmt->fetch();
            
            if (!$carpooling || $carpooling['provider_id'] != $providerId) {
                $this->db->rollBack();
                return false;
            }
            
            // Count bookings to cancel
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM bookings WHERE booker_id = ? AND carpooling_id = ?");
            $stmt->execute([$bookerId, $carpoolingId]);
            $result = $stmt->fetch();
            $canceledCount = $result ? (int)$result['count'] : 0;
            
            if ($canceledCount == 0) {
                $this->db->rollBack();
                return false;
            }
            
            // Delete all bookings for this user on this trip
            $stmt = $this->db->prepare("DELETE FROM bookings WHERE booker_id = ? AND carpooling_id = ?");
            $stmt->execute([$bookerId, $carpoolingId]);
            
            // Restore available places
            $stmt = $this->db->prepare("UPDATE carpoolings SET available_places = available_places + ? WHERE id = ?");
            $stmt->execute([$canceledCount, $carpoolingId]);
            
            $this->db->commit();
            return $canceledCount;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error canceling user bookings: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get bookings for a specific carpooling with passenger details
     */
    public function getBookingsByCarpooling($carpoolingId) {
        $stmt = $this->db->prepare("
            SELECT b.id, b.booker_id, b.carpooling_id,
                   u.first_name, u.last_name, u.email, u.global_rating,
                   COUNT(*) as seats_count
            FROM bookings b
            JOIN users u ON b.booker_id = u.id
            WHERE b.carpooling_id = ?
            GROUP BY b.booker_id
            ORDER BY MIN(b.id)
        ");
        $stmt->execute([$carpoolingId]);
        return $stmt->fetchAll();
    }

    public function getBookingsByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                b.id as booking_id,
                b.booker_id,
                b.carpooling_id,
                c.id,
                c.provider_id,
                c.start_date,
                c.price,
                c.available_places,
                c.pets_allowed,
                c.smoker_allowed,
                c.luggage_allowed,
                u.first_name as provider_first_name,
                u.last_name as provider_last_name,
                u.first_name,
                u.last_name,
                u.global_rating,
                l1.name as start_location,
                l2.name as end_location
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
