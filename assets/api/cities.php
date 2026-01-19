<?php
/**
 * API endpoint for city autocomplete
 * Returns cities matching the search query
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Enable error logging but disable display
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../model/Database.php';

try {
    $db = Database::getDb();
    
    if (!$db) {
        throw new Exception('Impossible de se connecter à la base de données');
    }
    
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    
    if (empty($query) || strlen($query) < 2) {
        echo json_encode([]);
        exit;
    }
    
    // Search cities by name or postal code
    $stmt = $db->prepare("
        SELECT id, name, postal_code 
        FROM location 
        WHERE name LIKE :query1 OR postal_code LIKE :query2
        ORDER BY 
            CASE 
                WHEN name LIKE :exactMatch THEN 1
                WHEN name LIKE :query1 THEN 2
                ELSE 3
            END,
            name ASC
        LIMIT 10
    ");
    
    $searchQuery = $query . '%';
    $exactMatch = $query;
    
    $stmt->execute([
        'query1' => $searchQuery,
        'query2' => $searchQuery,
        'exactMatch' => $exactMatch
    ]);
    
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($cities);
    
} catch (PDOException $e) {
    error_log('[CarShare API cities.php] Erreur PDO: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur de connexion à la base de données',
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log('[CarShare API cities.php] Erreur: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur lors de la recherche',
        'message' => $e->getMessage()
    ]);
}
