<?php
/**
 * API endpoint for city autocomplete
 * Returns cities matching the search query
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Enable error logging
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../model/Database.php';

try {
    $db = Database::getDb();
    
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
        ORDER BY name ASC
        LIMIT 10
    ");
    
    $searchQuery = $query . '%';
    $stmt->execute([
        'query1' => $searchQuery,
        'query2' => $searchQuery
    ]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($cities);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur base de données: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la recherche: ' . $e->getMessage()]);
}
