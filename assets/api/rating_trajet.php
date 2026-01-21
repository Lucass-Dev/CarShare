<?php
/**
 * Rating Trajet API - Noter le conducteur d'un trajet
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../model/Database.php';

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

// Protection basique XSS
$comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

// Validation
if (!$carpoolingId || !$rating || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

try {
    $db = Database::getDb();
    
    // Récupérer le provider_id du trajet
    $stmt = $db->prepare("SELECT provider_id FROM carpoolings WHERE id = ?");
    $stmt->execute([$carpoolingId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Trajet introuvable']);
        exit;
    }
    
    $providerId = $result['provider_id'];
    
    // Can't rate yourself
    if ($providerId == $_SESSION['user_id']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas vous noter vous-même']);
        exit;
    }
    
    // Insérer la notation (utiliser le vrai carpooling_id pour respecter la foreign key)
    $stmt = $db->prepare("
        INSERT INTO ratings (rater_id, carpooling_id, rating, content)
        VALUES (?, ?, ?, ?)
    ");
    
    $contentWithUserId = "USER_ID:$providerId\n" . $comment;
    
    $stmt->execute([
        $_SESSION['user_id'],
        $carpoolingId,
        $rating,
        $contentWithUserId
    ]);
    
    // Mettre à jour la note globale du conducteur
    $updateStmt = $db->prepare("
        UPDATE users 
        SET global_rating = (
            SELECT AVG(rating) 
            FROM ratings 
            WHERE content LIKE ?
        )
        WHERE id = ?
    ");
    $userPattern = "USER_ID:$providerId%";
    $updateStmt->execute([$userPattern, $providerId]);
    
    echo json_encode(['success' => true, 'message' => '✅ Note enregistrée avec succès']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
