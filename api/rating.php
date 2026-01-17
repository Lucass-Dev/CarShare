<?php
/**
 * Rating API - Handle rating submissions
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/Database.php';

// Check if user is logged in
if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$carpoolingId = $data['carpooling_id'] ?? null;
$rating = $data['rating'] ?? null;
$comment = $data['content'] ?? '';

// Validation
if (!$carpoolingId || !$rating || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

try {
    $db = Database::getDb();
    
    // Check if already rated this carpooling
    $checkStmt = $db->prepare("
        SELECT id FROM ratings 
        WHERE rater_id = ? AND carpooling_id = ?
    ");
    $checkStmt->execute([$_SESSION['user_id'], $carpoolingId]);
    
    if ($checkStmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vous avez déjà noté ce trajet']);
        exit;
    }
    
    // Insert rating
    $stmt = $db->prepare("
        INSERT INTO ratings (rater_id, carpooling_id, rating, content)
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $carpoolingId,
        $rating,
        $comment
    ]);
    
    // Update provider's global rating
    // Get the provider of this carpooling
    $providerStmt = $db->prepare("SELECT provider_id FROM carpoolings WHERE id = ?");
    $providerStmt->execute([$carpoolingId]);
    $provider = $providerStmt->fetch();
    
    if ($provider) {
        $updateStmt = $db->prepare("
            UPDATE users 
            SET global_rating = (
                SELECT AVG(r.rating) 
                FROM ratings r
                JOIN carpoolings c ON r.carpooling_id = c.id
                WHERE c.provider_id = ?
            )
            WHERE id = ?
        ");
        $updateStmt->execute([$provider['provider_id'], $provider['provider_id']]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Note enregistrée avec succès']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
