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
    public function getAllOffers($search = '', $sortBy = 'date', $sortOrder = 'asc', $dateFilter = '', $priceMax = '', $placesMin = '', $limit = 10, $offset = 0, $currentUserId = null) {
        $sql = "SELECT 
                    c.id,
                    c.start_date,
                    c.price,
                    c.available_places,
                    c.provider_id,
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
                WHERE c.start_date >= NOW()
                AND c.available_places > 0
                AND c.status = 1";

        $params = [];

        // Apply search filter
        if (!empty($search)) {
            $sql .= " AND (l1.name LIKE ? OR l2.name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Apply date filter
        if (!empty($dateFilter)) {
            $sql .= " AND DATE(c.start_date) = ?";
            $params[] = $dateFilter;
        }

        // Apply price filter
        if (!empty($priceMax)) {
            $sql .= " AND c.price <= ?";
            $params[] = $priceMax;
        }

        // Apply places filter
        if (!empty($placesMin)) {
            $sql .= " AND c.available_places >= ?";
            $params[] = $placesMin;
        }

        $sql .= " GROUP BY c.id, u.id, u.first_name, u.last_name, u.global_rating, l1.name, l2.name";
        
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

        $sql .= " LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total offers with filters
     */
    public function countOffers($search = '', $dateFilter = '', $priceMax = '', $placesMin = '') {
        $sql = "SELECT COUNT(DISTINCT c.id) as total
                FROM carpoolings c
                INNER JOIN users u ON c.provider_id = u.id
                LEFT JOIN location l1 ON c.start_id = l1.id
                LEFT JOIN location l2 ON c.end_id = l2.id
                WHERE c.start_date >= NOW()
                AND c.available_places > 0
                AND c.status = 1";

        $params = [];

        // Apply same filters
        if (!empty($search)) {
            $sql .= " AND (l1.name LIKE ? OR l2.name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($dateFilter)) {
            $sql .= " AND DATE(c.start_date) = ?";
            $params[] = $dateFilter;
        }

        if (!empty($priceMax)) {
            $sql .= " AND c.price <= ?";
            $params[] = $priceMax;
        }

        if (!empty($placesMin)) {
            $sql .= " AND c.available_places >= ?";
            $params[] = $placesMin;
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
                WHERE c.start_date >= NOW()
                ORDER BY l.name";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
