<?php
require_once __DIR__ . '/Database.php';

class SignalementModel
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
     * Count trips for a user
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
     * Save report to database
     */
    public function save(array $signalement): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO report (reporter_id, content, is_in_progress, is_treated)
                VALUES (:reporter_id, :content, 1, 0)
            ");
            
            return $stmt->execute([
                ':reporter_id' => $signalement['reporter_id'],
                ':content' => $signalement['content']
            ]);
        } catch (PDOException $e) {
            error_log("Error saving report: " . $e->getMessage());
            return false;
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
