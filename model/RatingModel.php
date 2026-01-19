<?php
require_once __DIR__ . '/Database.php';

class RatingModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getDb();
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, first_name, last_name, email, global_rating 
            FROM users 
            WHERE id = :id
        ");
        $stmt->execute([':id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get carpooling by ID
     */
    public function getCarpoolingById(int $carpoolingId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.first_name, u.last_name,
                   COALESCE(l1.name, 'Départ à définir') as start_name, 
                   COALESCE(l2.name, 'Arrivée à définir') as end_name
            FROM carpoolings c
            JOIN users u ON c.provider_id = u.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.id = :id
        ");
        $stmt->execute([':id' => $carpoolingId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Save rating to database
     */
    public function save(array $rating): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO ratings (rater_id, carpooling_id, rating, content)
                VALUES (:rater_id, :carpooling_id, :rating, :content)
            ");
            
            $result = $stmt->execute([
                ':rater_id' => $rating['rater_id'],
                ':carpooling_id' => $rating['carpooling_id'],
                ':rating' => $rating['rating'],
                ':content' => $rating['content'] ?? null
            ]);

            // Update user's global rating
            if ($result && !empty($rating['carpooling_id'])) {
                $this->updateUserGlobalRating($rating['carpooling_id']);
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error saving rating: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find the most recent carpooling between a provider (rated user)
     * and a booker (current logged-in user)
     */
    public function findLatestCarpoolingBetweenUsers(int $providerId, int $bookerId): ?int
    {
        $stmt = $this->db->prepare("
            SELECT c.id
            FROM bookings b
            JOIN carpoolings c ON b.carpooling_id = c.id
            WHERE b.booker_id = :booker_id
              AND c.provider_id = :provider_id
            ORDER BY c.start_date DESC
            LIMIT 1
        ");

        $stmt->execute([
            ':booker_id' => $bookerId,
            ':provider_id' => $providerId
        ]);

        $result = $stmt->fetchColumn();
        return $result !== false ? (int)$result : null;
    }

    /**
     * Update user's global rating average
     */
    private function updateUserGlobalRating(int $carpoolingId): void
    {
        try {
            // Get provider_id from carpooling
            $stmt = $this->db->prepare("SELECT provider_id FROM carpoolings WHERE id = :id");
            $stmt->execute([':id' => $carpoolingId]);
            $carpooling = $stmt->fetch();

            if ($carpooling) {
                // Calculate average rating
                $stmt = $this->db->prepare("
                    SELECT AVG(r.rating) as avg_rating
                    FROM ratings r
                    JOIN carpoolings c ON r.carpooling_id = c.id
                    WHERE c.provider_id = :provider_id
                ");
                $stmt->execute([':provider_id' => $carpooling['provider_id']]);
                $result = $stmt->fetch();

                if ($result && $result['avg_rating'] !== null) {
                    $stmt = $this->db->prepare("
                        UPDATE users SET global_rating = :rating WHERE id = :id
                    ");
                    $stmt->execute([
                        ':rating' => round($result['avg_rating'], 2),
                        ':id' => $carpooling['provider_id']
                    ]);
                }
            }
        } catch (PDOException $e) {
            error_log("Error updating global rating: " . $e->getMessage());
        }
    }

    /**
     * Get all users except the current user (for selection)
     */
    public function getAllUsers(int $excludeUserId = null): array
    {
        if ($excludeUserId) {
            $stmt = $this->db->prepare("
                SELECT id, first_name, last_name, email, global_rating 
                FROM users 
                WHERE id != :exclude_id
                ORDER BY first_name, last_name
            ");
            $stmt->execute([':exclude_id' => $excludeUserId]);
        } else {
            $stmt = $this->db->query("
                SELECT id, first_name, last_name, email, global_rating 
                FROM users 
                ORDER BY first_name, last_name
            ");
        }
        return $stmt->fetchAll();
    }

    /**
     * Get carpoolings for a specific user
     */
    public function getCarpoolingsByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   COALESCE(l1.name, 'Départ à définir') as start_name, 
                   COALESCE(l2.name, 'Arrivée à définir') as end_name
            FROM carpoolings c
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.provider_id = :user_id
            ORDER BY c.start_date DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Count trips created by a user
     */
    public function countUserTrips(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM carpoolings 
            WHERE provider_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }

    /**
     * Count ratings received by a user's trips
     */
    public function countUserRatings(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM ratings r
            JOIN carpoolings c ON r.carpooling_id = c.id
            WHERE c.provider_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }
}
