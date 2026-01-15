<?php
require_once __DIR__ . '/Database.php';

class TripFormModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getDb();
    }

    /**
     * Get location by name (city name)
     */
    public function getLocationByName(string $name): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM location WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Create a new carpooling trip
     */
    public function createTrip(array $data): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO carpoolings (
                    provider_id, start_date, price, available_places, 
                    status, start_id, end_id
                )
                VALUES (
                    :provider_id, :start_date, :price, :available_places,
                    :status, :start_id, :end_id
                )
            ");
            
            return $stmt->execute([
                ':provider_id' => $data['provider_id'],
                ':start_date' => $data['start_date'],
                ':price' => $data['price'] ?? null,
                ':available_places' => $data['available_places'],
                ':status' => $data['status'] ?? 1,
                ':start_id' => $data['start_id'],
                ':end_id' => $data['end_id']
            ]);
        } catch (PDOException $e) {
            error_log("Error creating trip: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all locations for autocomplete
     */
    public function getAllLocations(): array
    {
        $stmt = $this->db->query("SELECT id, name, postal_code FROM location ORDER BY name");
        return $stmt->fetchAll();
    }

    /**
     * Validate street name
     */
    public static function validateStreetName(string $street, &$errors, string $field = 'rue'): string
    {
        // Remove null bytes and control characters
        $street = preg_replace('/[\x00-\x1F\x7F]/', '', $street);
        
        if (!empty($street)) {
            if (strlen($street) > 100) {
                $errors[] = "Le nom de {$field} est trop long (max 100 caractères)";
            }
            if (preg_match('/[<>{}\[\]\\\\]/', $street)) {
                $errors[] = "Le nom de {$field} contient des caractères invalides";
            }
        }
        
        return $street;
    }

    /**
     * Validate street number
     */
    public static function validateStreetNumber(string $number, &$errors, string $field = 'numéro de voie'): string
    {
        // Keep only digits
        $number = preg_replace('/[^\d]/', '', $number);
        
        if (!empty($number)) {
            $numValue = intval($number);
            if ($numValue < 0 || $numValue > 99999) {
                $errors[] = "Le {$field} doit être entre 0 et 99999";
            }
        }
        
        return $number;
    }

    /**
     * Get a trip by ID with location details
     */
    public function getTripById(int $tripId): ?array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    c.id,
                    c.provider_id,
                    c.start_date,
                    c.price,
                    c.available_places,
                    c.status,
                    l1.id as start_location_id,
                    l1.name as start_location_name,
                    l2.id as end_location_id,
                    l2.name as end_location_name
                FROM carpoolings c
                JOIN location l1 ON c.start_id = l1.id
                JOIN location l2 ON c.end_id = l2.id
                WHERE c.id = :id
            ");
            $stmt->execute([':id' => $tripId]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching trip: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an existing carpooling trip
     */
    public function updateTrip(int $tripId, array $data): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE carpoolings 
                SET start_date = :start_date,
                    price = :price,
                    available_places = :available_places,
                    start_id = :start_id,
                    end_id = :end_id
                WHERE id = :id AND provider_id = :provider_id
            ");
            
            return $stmt->execute([
                ':id' => $tripId,
                ':provider_id' => $data['provider_id'],
                ':start_date' => $data['start_date'],
                ':price' => $data['price'] ?? null,
                ':available_places' => $data['available_places'],
                ':start_id' => $data['start_id'],
                ':end_id' => $data['end_id']
            ]);
        } catch (PDOException $e) {
            error_log("Error updating trip: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a trip by ID
     * Note: Database should have CASCADE DELETE for related bookings and messages
     */
    public function deleteTrip(int $tripId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM carpoolings WHERE id = :id");
            return $stmt->execute([':id' => $tripId]);
        } catch (PDOException $e) {
            error_log("Error deleting trip: " . $e->getMessage());
            return false;
        }
    }
}
