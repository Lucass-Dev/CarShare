<?php
/**
 * API endpoint for city autocomplete
 * Returns cities matching the search query
 */

// Désactiver complètement l'affichage des erreurs
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);

// Démarrer le buffer pour capturer toute sortie non désirée
ob_start();

// Headers JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

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
        WHERE name LIKE ? OR postal_code LIKE ?
        ORDER BY 
            CASE 
                WHEN name LIKE ? THEN 1
                WHEN name LIKE ? THEN 2
                ELSE 3
            END,
            name ASC
        LIMIT 10
    ");
    
    $searchQuery = $query . '%';
    $exactMatch = $query;
    
    $stmt->execute([
        $searchQuery,      // name LIKE
        $searchQuery,      // postal_code LIKE
        $exactMatch,       // exact match
        $searchQuery       // fuzzy match
    ]);
    
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Nettoyer le buffer et envoyer uniquement le JSON
    ob_clean();
    echo json_encode($cities);
    
} catch (PDOException $e) {
    error_log('[CarShare API cities.php] Erreur PDO: ' . $e->getMessage());
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur de connexion à la base de données',
        'details' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log('[CarShare API cities.php] Erreur: ' . $e->getMessage());
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur lors de la recherche',
        'details' => $e->getMessage()
    ]);
}

// Fin du buffer et envoi propre
ob_end_flush();
