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
}
