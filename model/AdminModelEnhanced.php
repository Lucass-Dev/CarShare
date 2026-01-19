<?php
require_once __DIR__ . '/Database.php';

class AdminModelEnhanced {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    /**
     * Authentifie un administrateur
     */
    public function authenticateAdmin($email, $password) {
        $stmt = $this->db->prepare("
            SELECT id, email, password_hash, first_name, last_name, is_admin
            FROM users 
            WHERE email = :email AND is_admin = 1
        ");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            return $admin;
        }
        
        return null;
    }

    /**
     * Récupère les statistiques globales pour le dashboard
     */
    public function getDashboardStats() {
        $stats = [];
        
        // Total utilisateurs
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE is_admin = 0");
        $stats['total_users'] = $stmt->fetch()['total'];
        
        // Utilisateurs vérifiés
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE is_admin = 0 AND is_verified_user = 1");
        $stats['verified_users'] = $stmt->fetch()['total'];
        
        // Total trajets
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM carpoolings");
        $stats['total_trips'] = $stmt->fetch()['total'];
        
        // Total réservations
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM bookings");
        $stats['total_bookings'] = $stmt->fetch()['total'];
        
        // Chiffre d'affaires total (somme des prix des trajets réservés)
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(c.price), 0) as total 
            FROM bookings b 
            JOIN carpoolings c ON b.carpooling_id = c.id
        ");
        $stats['total_revenue'] = $stmt->fetch()['total'];
        
        // Total véhicules
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE is_admin = 0 AND car_brand IS NOT NULL");
        $stats['total_vehicles'] = $stmt->fetch()['total'];
        
        // Nouveaux utilisateurs ce mois
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM users 
            WHERE is_admin = 0 AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
        ");
        $stats['new_users_month'] = $stmt->fetch()['total'];
        
        // Trajets ce mois
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM carpoolings 
            WHERE MONTH(start_date) = MONTH(CURRENT_DATE()) 
            AND YEAR(start_date) = YEAR(CURRENT_DATE())
        ");
        $stats['trips_this_month'] = $stmt->fetch()['total'];
        
        return $stats;
    }

    /**
     * Récupère les transactions récentes
     */
    public function getRecentTransactions($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT 
                b.id,
                b.booker_id,
                b.carpooling_id,
                u1.first_name as booker_first_name,
                u1.last_name as booker_last_name,
                u2.first_name as provider_first_name,
                u2.last_name as provider_last_name,
                c.price,
                c.start_date as departure_date,
                l1.name as start_location,
                l2.name as end_location
            FROM bookings b
            JOIN users u1 ON b.booker_id = u1.id
            JOIN carpoolings c ON b.carpooling_id = c.id
            JOIN users u2 ON c.provider_id = u2.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            ORDER BY b.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère la liste des utilisateurs avec pagination et recherche
     */
    public function getUsers($page = 1, $limit = 20, $search = '', $filter = '') {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT id, first_name, last_name, email, created_at, is_verified_user 
                FROM users 
                WHERE is_admin = 0";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        if ($filter === 'verified') {
            $sql .= " AND is_verified_user = 1";
        } elseif ($filter === 'unverified') {
            $sql .= " AND is_verified_user = 0";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total d'utilisateurs (pour pagination)
     */
    public function getUsersCount($search = '', $filter = '') {
        $sql = "SELECT COUNT(*) as total FROM users WHERE is_admin = 0";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        if ($filter === 'verified') {
            $sql .= " AND is_verified_user = 1";
        } elseif ($filter === 'unverified') {
            $sql .= " AND is_verified_user = 0";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Récupère les détails d'un utilisateur
     */
    public function getUserDetails($userId) {
        $stmt = $this->db->prepare("
            SELECT id, first_name, last_name, email, created_at, is_verified_user 
            FROM users 
            WHERE id = :id AND is_admin = 0
        ");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques d'un utilisateur
     */
    public function getUserStats($userId) {
        $stats = [];
        
        // Trajets proposés
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM carpoolings WHERE provider_id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $stats['trips_provided'] = $stmt->fetch()['total'];
        
        // Trajets réservés
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM bookings WHERE booker_id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $stats['trips_booked'] = $stmt->fetch()['total'];
        
        // Chiffre d'affaires généré
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(c.price), 0) as total 
            FROM bookings b 
            JOIN carpoolings c ON b.carpooling_id = c.id 
            WHERE c.provider_id = :id
        ");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $stats['total_revenue'] = $stmt->fetch()['total'];
        
        return $stats;
    }

    /**
     * Récupère l'historique d'un utilisateur
     */
    public function getUserHistory($userId) {
        $history = [
            'provided' => [],
            'booked' => []
        ];
        
        // Trajets proposés
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                c.start_date as departure_date,
                c.price,
                c.available_places as available_seats,
                l1.name as start_location,
                l2.name as end_location,
                (SELECT COUNT(*) FROM bookings WHERE carpooling_id = c.id) as booking_count
            FROM carpoolings c
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE c.provider_id = :id
            ORDER BY c.start_date DESC
        ");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $history['provided'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Trajets réservés
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                c.start_date as departure_date,
                c.price,
                l1.name as start_location,
                l2.name as end_location,
                CONCAT(u.first_name, ' ', u.last_name) as provider_name
            FROM bookings b
            JOIN carpoolings c ON b.carpooling_id = c.id
            JOIN users u ON c.provider_id = u.id
            LEFT JOIN location l1 ON c.start_id = l1.id
            LEFT JOIN location l2 ON c.end_id = l2.id
            WHERE b.booker_id = :id
            ORDER BY c.start_date DESC
        ");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $history['booked'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $history;
    }

    /**
     * Supprime un utilisateur (avec cascade sur ses trajets et réservations)
     */
    public function deleteUser($userId) {
        try {
            $this->db->beginTransaction();
            
            // Supprimer les réservations de cet utilisateur
            $stmt = $this->db->prepare("DELETE FROM bookings WHERE booker_id = :id");
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Supprimer les réservations des trajets de cet utilisateur
            $stmt = $this->db->prepare("
                DELETE FROM bookings 
                WHERE carpooling_id IN (SELECT id FROM carpoolings WHERE provider_id = :id)
            ");
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Supprimer les trajets de cet utilisateur
            $stmt = $this->db->prepare("DELETE FROM carpoolings WHERE provider_id = :id");
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Supprimer l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Bascule la vérification d'un utilisateur
     */
    public function toggleUserVerification($userId) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET is_verified_user = NOT is_verified_user 
            WHERE id = :id AND is_admin = 0
        ");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Récupère la liste des trajets avec pagination et recherche
     */
    public function getTrips($page = 1, $limit = 20, $search = '', $filter = '', $dateFrom = '', $dateTo = '') {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT 
                    c.id,
                    c.start_date as departure_date,
                    c.price,
                    c.available_places as available_seats,
                    c.provider_id,
                    CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                    l1.name as start_location,
                    l2.name as end_location,
                    u.car_brand as vehicle_brand,
                    u.car_model as vehicle_model,
                    (SELECT COUNT(*) FROM bookings WHERE carpooling_id = c.id) as booking_count
                FROM carpoolings c
                JOIN users u ON c.provider_id = u.id
                LEFT JOIN location l1 ON c.start_id = l1.id
                LEFT JOIN location l2 ON c.end_id = l2.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (l1.name LIKE ? OR l2.name LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        if ($filter === 'upcoming') {
            $sql .= " AND c.start_date >= NOW()";
        } elseif ($filter === 'past') {
            $sql .= " AND c.start_date < NOW()";
        }
        
        // Filtrage par date début
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(c.start_date) >= ?";
            $params[] = $dateFrom;
        }
        
        // Filtrage par date fin
        if (!empty($dateTo)) {
            $sql .= " AND DATE(c.start_date) <= ?";
            $params[] = $dateTo;
        }
        
        $sql .= " ORDER BY c.start_date DESC LIMIT ? OFFSET ?";
        
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total de trajets (pour pagination)
     */
    public function getTripsCount($search = '', $filter = '', $dateFrom = '', $dateTo = '') {
        $sql = "SELECT COUNT(*) as total FROM carpoolings c
                LEFT JOIN location l1 ON c.start_id = l1.id
                LEFT JOIN location l2 ON c.end_id = l2.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (l1.name LIKE ? OR l2.name LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        if ($filter === 'upcoming') {
            $sql .= " AND c.start_date >= NOW()";
        } elseif ($filter === 'past') {
            $sql .= " AND c.start_date < NOW()";
        }
        
        // Filtrage par date début
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(c.start_date) >= ?";
            $params[] = $dateFrom;
        }
        
        // Filtrage par date fin
        if (!empty($dateTo)) {
            $sql .= " AND DATE(c.start_date) <= ?";
            $params[] = $dateTo;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Supprime un trajet (avec cascade sur les réservations)
     */
    public function deleteTrip($tripId) {
        try {
            $this->db->beginTransaction();
            
            // Supprimer les réservations de ce trajet
            $stmt = $this->db->prepare("DELETE FROM bookings WHERE carpooling_id = :id");
            $stmt->bindValue(':id', $tripId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Supprimer le trajet
            $stmt = $this->db->prepare("DELETE FROM carpoolings WHERE id = :id");
            $stmt->bindValue(':id', $tripId, PDO::PARAM_INT);
            $stmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Récupère la liste des véhicules avec pagination et recherche
     */
    public function getVehicles($page = 1, $limit = 20, $search = '') {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT 
                    u.id as user_id,
                    u.car_brand,
                    u.car_model,
                    u.car_plate,
                    CONCAT(u.first_name, ' ', u.last_name) as owner_name,
                    u.email,
                    (SELECT COUNT(*) FROM carpoolings WHERE provider_id = u.id) as trip_count
                FROM users u
                WHERE u.is_admin = 0 AND u.car_brand IS NOT NULL";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (u.car_brand LIKE ? OR u.car_model LIKE ? OR u.car_plate LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY u.first_name, u.last_name LIMIT ? OFFSET ?";
        
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total de véhicules (pour pagination)
     */
    public function getVehiclesCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM users u WHERE u.is_admin = 0 AND u.car_brand IS NOT NULL";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (car_brand LIKE ? OR car_model LIKE ? OR car_plate LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Récupère le profil de l'admin
     */
    public function getAdminProfile($adminId) {
        $stmt = $this->db->prepare("
            SELECT id, first_name, last_name, email, created_at 
            FROM users 
            WHERE id = :id AND is_admin = 1
        ");
        $stmt->bindValue(':id', $adminId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le profil de l'admin
     */
    public function updateAdminProfile($adminId, $data) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET first_name = :first_name, last_name = :last_name, email = :email 
            WHERE id = :id AND is_admin = 1
        ");
        $stmt->bindValue(':first_name', $data['first_name']);
        $stmt->bindValue(':last_name', $data['last_name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':id', $adminId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Met à jour le mot de passe de l'admin
     */
    public function updateAdminPassword($adminId, $newPasswordHash) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password_hash = :password 
            WHERE id = :id AND is_admin = 1
        ");
        $stmt->bindValue(':password', $newPasswordHash);
        $stmt->bindValue(':id', $adminId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Vérifie le mot de passe actuel de l'admin
     */
    public function verifyAdminPassword($adminId, $password) {
        $stmt = $this->db->prepare("
            SELECT password_hash 
            FROM users 
            WHERE id = :id AND is_admin = 1
        ");
        $stmt->bindValue(':id', $adminId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return password_verify($password, $result['password_hash']);
        }
        return false;
    }

    /**
     * Réinitialise le mot de passe d'un utilisateur
     */
    public function resetUserPassword($userId, $newPasswordHash) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password_hash = :password 
            WHERE id = :id AND is_admin = 0
        ");
        $stmt->bindValue(':password', $newPasswordHash);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Statistiques par ville (top 10)
     */
    public function getCityStats() {
        $stmt = $this->db->query("
            SELECT 
                l.name as city,
                COUNT(DISTINCT c1.id) as trips_from,
                COUNT(DISTINCT c2.id) as trips_to,
                (COUNT(DISTINCT c1.id) + COUNT(DISTINCT c2.id)) as total_trips
            FROM location l
            LEFT JOIN carpoolings c1 ON l.id = c1.start_id
            LEFT JOIN carpoolings c2 ON l.id = c2.end_id
            GROUP BY l.id, l.name
            HAVING total_trips > 0
            ORDER BY total_trips DESC
            LIMIT 10
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statistiques mensuelles des inscriptions
     */
    public function getMonthlyRegistrations($months = 6) {
        $stmt = $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
            FROM users
            WHERE is_admin = 0 
            AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL $months MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statistiques mensuelles des trajets
     */
    public function getMonthlyTrips($months = 6) {
        $stmt = $this->db->query("
            SELECT 
                DATE_FORMAT(start_date, '%Y-%m') as month,
                COUNT(*) as count
            FROM carpoolings
            WHERE start_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $months MONTH)
            GROUP BY DATE_FORMAT(start_date, '%Y-%m')
            ORDER BY month ASC
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Revenus mensuels
     */
    public function getMonthlyRevenue($months = 6) {
        $stmt = $this->db->query("
            SELECT 
                DATE_FORMAT(c.start_date, '%Y-%m') as month,
                SUM(c.price) as revenue
            FROM bookings b
            JOIN carpoolings c ON b.carpooling_id = c.id
            WHERE c.start_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $months MONTH)
            GROUP BY DATE_FORMAT(c.start_date, '%Y-%m')
            ORDER BY month ASC
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
