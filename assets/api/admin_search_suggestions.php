<?php
/**
 * API pour l'autosuggestion dans les recherches admin
 */
session_start();
require_once '../../config.php';
require_once '../../model/Database.php';

header('Content-Type: application/json');

// Vérifier que l'utilisateur est admin
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

// Récupérer les paramètres
$type = isset($_GET['type']) ? $_GET['type'] : '';
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query) || strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$db = Database::getInstance()->getConnection();
$results = [];

try {
    switch ($type) {
        case 'users':
            // Recherche utilisateurs
            $stmt = $db->prepare("
                SELECT 
                    id,
                    CONCAT(first_name, ' ', last_name) as name,
                    email,
                    is_verified_user
                FROM users 
                WHERE is_admin = 0 
                  AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)
                ORDER BY 
                    CASE 
                        WHEN first_name LIKE ? THEN 1
                        WHEN last_name LIKE ? THEN 2
                        ELSE 3
                    END,
                    first_name ASC
                LIMIT 10
            ");
            $searchTerm = '%' . $query . '%';
            $startsWith = $query . '%';
            $stmt->execute([
                $searchTerm, $searchTerm, $searchTerm,
                $startsWith, $startsWith
            ]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = [
                    'id' => $row['id'],
                    'label' => $row['name'],
                    'subtitle' => $row['email'],
                    'verified' => (bool)$row['is_verified_user']
                ];
            }
            break;
            
        case 'cities':
            // Recherche villes
            $stmt = $db->prepare("
                SELECT DISTINCT name
                FROM location 
                WHERE name LIKE ?
                ORDER BY 
                    CASE WHEN name LIKE ? THEN 1 ELSE 2 END,
                    name ASC
                LIMIT 10
            ");
            $searchTerm = '%' . $query . '%';
            $startsWith = $query . '%';
            $stmt->execute([$searchTerm, $startsWith]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = [
                    'label' => $row['name'],
                    'value' => $row['name']
                ];
            }
            break;
            
        case 'vehicles':
            // Recherche véhicules par marque ou modèle
            $stmt = $db->prepare("
                SELECT DISTINCT 
                    car_brand,
                    car_model,
                    user_id,
                    CONCAT(first_name, ' ', last_name) as owner_name
                FROM users 
                WHERE is_admin = 0 
                  AND car_brand IS NOT NULL
                  AND (car_brand LIKE ? OR car_model LIKE ? OR car_plate LIKE ?)
                ORDER BY car_brand ASC
                LIMIT 10
            ");
            $searchTerm = '%' . $query . '%';
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $vehicleLabel = $row['car_brand'];
                if (!empty($row['car_model'])) {
                    $vehicleLabel .= ' ' . $row['car_model'];
                }
                
                $results[] = [
                    'label' => $vehicleLabel,
                    'subtitle' => 'Propriétaire: ' . $row['owner_name'],
                    'value' => $row['car_brand']
                ];
            }
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Type de recherche invalide']);
            exit;
    }
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données']);
}
