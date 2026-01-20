<?php
/**
 * Search API - Real-time search for users and trips
 */

// Désactiver l'affichage des erreurs pour éviter de casser le JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Toujours envoyer du JSON, même en cas d'erreur
header('Content-Type: application/json; charset=utf-8');

// Gestion des erreurs globales
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Erreur serveur lors de la recherche',
        'users' => []
    ]);
    exit;
});

try {
    require_once __DIR__ . '/../../config.php';
    require_once __DIR__ . '/../../model/Database.php';

    $query = $_GET['q'] ?? '';

    if (strlen($query) < 2) {
        echo json_encode(['users' => [], 'error' => false]);
        exit;
    }

    $db = Database::getDb();
    $searchTerm = '%' . $query . '%';

    // Search users
    $stmtUsers = $db->prepare("
        SELECT id, first_name, last_name, global_rating, car_brand, car_model
        FROM users
        WHERE (CONCAT(first_name, ' ', last_name) LIKE ? OR email LIKE ?)
          AND is_admin = 0
        LIMIT 5
    ");
    $stmtUsers->execute([$searchTerm, $searchTerm]);
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    // La recherche du header est uniquement pour les utilisateurs
    // Pour les trajets, utiliser la page de recherche dédiée

    echo json_encode([
        'users' => $users,
        'error' => false
    ]);
    
} catch (Exception $e) {
    error_log("Erreur recherche API: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Erreur lors de la recherche',
        'users' => []
    ]);
}
