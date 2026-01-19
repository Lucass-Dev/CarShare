<?php
/**
 * TripModel - Trip data management
 */

class TripModel {
    
    /**
     * Search trips with filters
     */
    public function searchTrips(array $filters, ?string $sortBy = null, string $orderType = 'asc'): array {
        try {
            $db = Database::getDb();
            
            $query = "
                SELECT 
                    c.*,
                    u.first_name,
                    u.last_name,
                    u.profile_picture,
                    COALESCE(AVG(r.rating), 0) as avg_rating,
                    COUNT(DISTINCT r.id) as rating_count,
                    (c.available_seats - COALESCE(b.booked_seats, 0)) as remaining_seats
                FROM carpooling c
                INNER JOIN users u ON c.provider_id = u.id
                LEFT JOIN ratings r ON u.id = r.rated_user_id
                LEFT JOIN (
                    SELECT carpooling_id, COUNT(*) as booked_seats
                    FROM bookings
                    WHERE status = 'confirmed'
                    GROUP BY carpooling_id
                ) b ON c.id = b.carpooling_id
                WHERE c.departure_time > NOW()
            ";
            
            $params = [];
            
            if (!empty($filters['start_place'])) {
                $query .= " AND c.start_place LIKE :start_place";
                $params[':start_place'] = '%' . $filters['start_place'] . '%';
            }
            
            if (!empty($filters['end_place'])) {
                $query .= " AND c.end_place LIKE :end_place";
                $params[':end_place'] = '%' . $filters['end_place'] . '%';
            }
            
            if (!empty($filters['date'])) {
                $query .= " AND DATE(c.departure_time) = :date";
                $params[':date'] = $filters['date'];
            }
            
            $query .= " GROUP BY c.id";
            
            if (!empty($filters['seats'])) {
                $query .= " HAVING remaining_seats >= :seats";
                $params[':seats'] = $filters['seats'];
            }
            
            // DEEP MERGE: Filtres avancés Lucas (ORDER BY dynamique)
            if ($sortBy) {
                $direction = strtoupper($orderType) === 'DESC' ? 'DESC' : 'ASC';
                switch ($sortBy) {
                    case 'price':
                        $query .= " ORDER BY c.price $direction";
                        break;
                    case 'date':
                        $query .= " ORDER BY c.departure_time $direction";
                        break;
                    case 'seats':
                        $query .= " ORDER BY remaining_seats $direction";
                        break;
                    case 'rating':
                        $query .= " ORDER BY avg_rating $direction";
                        break;
                    default:
                        $query .= " ORDER BY c.departure_time ASC";
                }
            } else {
                $query .= " ORDER BY c.departure_time ASC";
            }
            
            $query .= " LIMIT 50";
            
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Search trips error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create new trip
     */
    public function createTrip(array $data): ?int {
        try {
            $db = Database::getDb();
            
            $query = "
                INSERT INTO carpooling (
                    provider_id, start_place, end_place, departure_time,
                    available_seats, price, description, preferences, created_at
                ) VALUES (
                    :provider_id, :start_place, :end_place, :departure_time,
                    :available_seats, :price, :description, :preferences, NOW()
                )
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':provider_id' => $data['provider_id'],
                ':start_place' => $data['start_place'],
                ':end_place' => $data['end_place'],
                ':departure_time' => $data['departure_time'],
                ':available_seats' => $data['available_seats'],
                ':price' => $data['price'],
                ':description' => $data['description'] ?? null,
                ':preferences' => $data['preferences'] ?? null
            ]);
            
            return (int)$db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create trip error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get trip by ID
     */
    public function getTripById(int $tripId): ?array {
        try {
            $db = Database::getDb();
            
            $query = "SELECT c.*, l1.name as start_location, l2.name as end_location 
                      FROM carpoolings c
                      LEFT JOIN location l1 ON c.start_id = l1.id
                      LEFT JOIN location l2 ON c.end_id = l2.id
                      WHERE c.id = :id LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute([':id' => $tripId]);
            
            $trip = $stmt->fetch(PDO::FETCH_ASSOC);
            return $trip ?: null;
        } catch (PDOException $e) {
            error_log("Get trip error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get provider information
     */
    public function getProviderInfo(int $userId): array {
        try {
            $db = Database::getDb();
            
            $query = "
                SELECT 
                    u.*,
                    COUNT(DISTINCT c.id) as trip_count,
                    COALESCE(AVG(r.rating), 0) as avg_rating,
                    COUNT(DISTINCT r.id) as rating_count
                FROM users u
                LEFT JOIN carpooling c ON u.id = c.provider_id
                LEFT JOIN ratings r ON u.id = r.rated_user_id
                WHERE u.id = :user_id
                GROUP BY u.id
                LIMIT 1
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Get provider info error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get bookings count for a trip
     */
    public function getBookingsCount(int $tripId): int {
        try {
            $db = Database::getDb();
            
            $query = "SELECT COUNT(*) FROM bookings WHERE carpooling_id = :trip_id AND status = 'confirmed'";
            $stmt = $db->prepare($query);
            $stmt->execute([':trip_id' => $tripId]);
            
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Get bookings count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Check if user has booked this trip
     */
    public function hasUserBooked(int $tripId, int $userId): bool {
        try {
            $db = Database::getDb();
            
            $query = "SELECT COUNT(*) FROM bookings WHERE carpooling_id = :trip_id AND user_id = :user_id AND status = 'confirmed'";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':trip_id' => $tripId,
                ':user_id' => $userId
            ]);
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check user booking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create booking
     */
    public function createBooking(array $data): ?int {
        try {
            $db = Database::getDb();
            
            $query = "
                INSERT INTO bookings (
                    carpooling_id, user_id, seats_booked, total_price, status, created_at
                ) VALUES (
                    :carpooling_id, :user_id, :seats_booked, :total_price, :status, NOW()
                )
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':carpooling_id' => $data['carpooling_id'],
                ':user_id' => $data['user_id'],
                ':seats_booked' => $data['seats_booked'],
                ':total_price' => $data['total_price'],
                ':status' => $data['status']
            ]);
            
            return (int)$db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create booking error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get user's trips (as provider)
     */
    public function getUserTrips(int $userId): array {
        try {
            $db = Database::getDb();
            
            $query = "
                SELECT 
                    c.*,
                    COUNT(DISTINCT b.id) as bookings_count,
                    SUM(b.seats_booked) as total_seats_booked
                FROM carpooling c
                LEFT JOIN bookings b ON c.id = b.carpooling_id AND b.status = 'confirmed'
                WHERE c.provider_id = :user_id
                GROUP BY c.id
                ORDER BY c.departure_time DESC
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get user trips error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update trip
     */
    public function updateTrip(int $tripId, array $data): bool {
        try {
            $db = Database::getDb();
            
            $query = "
                UPDATE carpooling 
                SET available_seats = :available_seats,
                    price = :price,
                    description = :description,
                    preferences = :preferences
                WHERE id = :trip_id
            ";
            
            $stmt = $db->prepare($query);
            return $stmt->execute([
                ':available_seats' => $data['available_seats'],
                ':price' => $data['price'],
                ':description' => $data['description'],
                ':preferences' => $data['preferences'],
                ':trip_id' => $tripId
            ]);
        } catch (PDOException $e) {
            error_log("Update trip error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cancel trip
     */
    public function cancelTrip(int $tripId): bool {
        try {
            $db = Database::getDb();
            
            // Start transaction
            $db->beginTransaction();
            
            // Update trip status
            $query = "UPDATE carpooling SET status = 'cancelled' WHERE id = :trip_id";
            $stmt = $db->prepare($query);
            $stmt->execute([':trip_id' => $tripId]);
            
            // Cancel all bookings
            $query = "UPDATE bookings SET status = 'cancelled' WHERE carpooling_id = :trip_id";
            $stmt = $db->prepare($query);
            $stmt->execute([':trip_id' => $tripId]);
            
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            error_log("Cancel trip error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get trip bookings (for provider)
     */
    public function getTripBookings(int $tripId): array {
        try {
            $db = Database::getDb();
            
            $query = "
                SELECT 
                    b.*,
                    u.first_name,
                    u.last_name,
                    u.profile_picture,
                    u.phone
                FROM bookings b
                INNER JOIN users u ON b.user_id = u.id
                WHERE b.carpooling_id = :trip_id
                ORDER BY b.created_at ASC
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([':trip_id' => $tripId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get trip bookings error: " . $e->getMessage());
            return [];
        }
    }
}
