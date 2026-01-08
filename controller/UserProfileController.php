<?php

require_once 'model/Database.php';
require_once 'model/Utils.php';

class UserProfileController {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    public function viewProfile() {
        $userId = $_GET['id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?action=home');
            exit;
        }

        // Get user info
        $stmt = $this->db->prepare("
            SELECT id, first_name, last_name, email, global_rating, 
                   car_brand, car_model, car_plate, car_is_verified, 
                   is_verified_user, created_at
            FROM users
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header('Location: index.php?action=home');
            exit;
        }

        // Get ratings count and average (using actual table structure)
        $ratingStmt = $this->db->prepare("
            SELECT COUNT(*) as count, AVG(rating) as avg_rating
            FROM ratings r
            JOIN carpoolings c ON r.carpooling_id = c.id
            WHERE c.provider_id = ?
        ");
        $ratingStmt->execute([$userId]);
        $ratings_stats = $ratingStmt->fetch(PDO::FETCH_ASSOC);

        // Get recent trips as driver
        $tripsStmt = $this->db->prepare("
            SELECT l1.name as departure, 
                   l2.name as destination,
                   c.start_date as date,
                   c.available_places,
                   c.price
            FROM carpoolings c
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.provider_id = ? AND c.start_date < NOW()
            ORDER BY c.start_date DESC
            LIMIT 5
        ");
        $tripsStmt->execute([$userId]);
        $trips = $tripsStmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'view/UserProfileView.php';
    }
}
