<?php
/**
 * Search API - Real-time search for users and trips
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../model/Database.php';

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode(['users' => [], 'trips' => []]);
    exit;
}

$db = Database::getDb();
$searchTerm = '%' . $query . '%';

// Search users
$stmtUsers = $db->prepare("
    SELECT id, first_name, last_name, global_rating, car_brand, car_model
    FROM users
    WHERE CONCAT(first_name, ' ', last_name) LIKE ?
       OR email LIKE ?
    LIMIT 5
");
$stmtUsers->execute([$searchTerm, $searchTerm]);
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// La recherche du header est uniquement pour les utilisateurs
// Pour les trajets, utiliser la page de recherche dédiée

echo json_encode([
    'users' => $users
]);
