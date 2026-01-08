<?php
/**
 * Model for managing carpooling offers
 */
class OffersModel {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getDb();
    }

    /**
     * Get all offers with filters and pagination
     */
    public function getAllOffers($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT 
                    c.id,
                    c.start_date,
                    c.price,
                    c.available_places,
                    u.id as driver_id,
                    u.first_name,
                    u.last_name,
                    l1.name as ville_depart,
                    l2.name as ville_arrivee,
                    COALESCE(u.global_rating, 0) as avg_rating,
                    (SELECT COUNT(*) FROM ratings r2 
                     JOIN carpoolings c2 ON r2.carpooling_id = c2.id 
                     WHERE c2.provider_id = u.id) as review_count
                FROM carpoolings c
                INNER JOIN users u ON c.provider_id = u.id
                LEFT JOIN location l1 ON c.start_id = l1.id
                LEFT JOIN location l2 ON c.end_id = l2.id
                WHERE DATE(c.start_date) >= CURDATE()
                AND c.available_places > 0
                AND c.status = 1";

        $params = [];

        // Apply filters
        if (!empty($filters['ville_depart'])) {
            $sql .= " AND l1.name LIKE ?";
            $params[] = '%' . $filters['ville_depart'] . '%';
        }

        if (!empty($filters['ville_arrivee'])) {
            $sql .= " AND l2.name LIKE ?";
            $params[] = '%' . $filters['ville_arrivee'] . '%';
        }

        if (!empty($filters['date_depart'])) {
            $sql .= " AND DATE(c.start_date) = ?";
            $params[] = $filters['date_depart'];
        }

        if (!empty($filters['prix_max'])) {
            $sql .= " AND c.price <= ?";
            $params[] = $filters['prix_max'];
        }

        if (!empty($filters['places_min'])) {
            $sql .= " AND c.available_places >= ?";
            $params[] = $filters['places_min'];
        }

        $sql .= " GROUP BY c.id, u.id, u.first_name, u.last_name, u.global_rating, l1.name, l2.name
                  ORDER BY c.start_date ASC
                  LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total offers with filters
     */
    public function countOffers($filters = []) {
        $sql = "SELECT COUNT(DISTINCT c.id) as total
                FROM carpoolings c
                INNER JOIN users u ON c.provider_id = u.id
                LEFT JOIN location l1 ON c.start_id = l1.id
                LEFT JOIN location l2 ON c.end_id = l2.id
                WHERE DATE(c.start_date) >= CURDATE()
                AND c.available_places > 0
                AND c.status = 1";

        $params = [];

        // Apply same filters
        if (!empty($filters['ville_depart'])) {
            $sql .= " AND l1.name LIKE ?";
            $params[] = '%' . $filters['ville_depart'] . '%';
        }

        if (!empty($filters['ville_arrivee'])) {
            $sql .= " AND l2.name LIKE ?";
            $params[] = '%' . $filters['ville_arrivee'] . '%';
        }

        if (!empty($filters['date_depart'])) {
            $sql .= " AND DATE(c.start_date) = ?";
            $params[] = $filters['date_depart'];
        }

        if (!empty($filters['prix_max'])) {
            $sql .= " AND c.price <= ?";
            $params[] = $filters['prix_max'];
        }

        if (!empty($filters['places_min'])) {
            $sql .= " AND c.available_places >= ?";
            $params[] = $filters['places_min'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get unique cities for filter dropdowns
     */
    public function getUniqueCities() {
        $sql = "SELECT DISTINCT l.name as city 
                FROM location l
                INNER JOIN carpoolings c ON (l.id = c.start_id OR l.id = c.end_id)
                WHERE DATE(c.start_date) >= CURDATE()
                ORDER BY l.name";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
