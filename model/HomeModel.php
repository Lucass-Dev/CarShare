<?php
require_once __DIR__ . '/Database.php';

class HomeModel {

    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    /**
     * Get top 4 users with most reviews or trips
     */
    public function getTopRatedUsers(): array {
        try {
            $stmt = $this->db->query("
                SELECT 
                    u.id,
                    u.first_name,
                    u.last_name,
                    u.global_rating,
                    COUNT(DISTINCT c.id) as trip_count,
                    COUNT(r.id) as review_count,
                    (SELECT MAX(c2.id) FROM carpoolings c2 WHERE c2.provider_id = u.id) as last_trip_id
                FROM users u
                LEFT JOIN carpoolings c ON u.id = c.provider_id
                LEFT JOIN ratings r ON c.id = r.carpooling_id
                WHERE u.global_rating IS NOT NULL
                GROUP BY u.id, u.first_name, u.last_name, u.global_rating
                ORDER BY review_count DESC, trip_count DESC, u.global_rating DESC
                LIMIT 4
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching top rated users: " . $e->getMessage());
            return [];
        }
    }
}

?>