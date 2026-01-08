<?php
/**
 * UserSearchController - Handle user search results
 */

require_once __DIR__ . '/../model/Database.php';

class UserSearchController
{
    public function render()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $query = $_GET['q'] ?? '';
        $sortBy = $_GET['sort'] ?? 'name'; // name, rating, trips
        
        $users = [];
        
        if (!empty($query)) {
            $users = $this->searchUsers($query, $sortBy);
        }
        
        require_once __DIR__ . '/../view/UserSearchView.php';
    }
    
    private function searchUsers($query, $sortBy)
    {
        $db = Database::getDb();
        $searchTerm = '%' . $query . '%';
        
        // Build ORDER BY clause
        switch($sortBy) {
            case 'rating':
                $orderClause = 'u.global_rating DESC, CONCAT(u.first_name, u.last_name)';
                break;
            case 'trips':
                $orderClause = 'trip_count DESC, CONCAT(u.first_name, u.last_name)';
                break;
            default:
                $orderClause = 'u.first_name, u.last_name';
        }
        
        $stmt = $db->prepare("
            SELECT 
                u.id,
                u.first_name,
                u.last_name,
                u.email,
                u.global_rating,
                u.car_brand,
                u.car_model,
                COUNT(DISTINCT c.id) as trip_count
            FROM users u
            LEFT JOIN carpooling c ON c.creator_user_id = u.id
            WHERE CONCAT(u.first_name, ' ', u.last_name) LIKE ?
               OR u.first_name LIKE ?
               OR u.last_name LIKE ?
            GROUP BY u.id
            ORDER BY $orderClause
        ");
        
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
