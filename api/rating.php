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

$userId = $data['user_id'] ?? null;
$rating = $data['rating'] ?? null;
$comment = $data['comment'] ?? '';
$punctuality = $data['punctuality'] ?? null;
$friendliness = $data['friendliness'] ?? null;
$safety = $data['safety'] ?? null;

// Validation
if (!$userId || !$rating || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

// Can't rate yourself
if ($userId == $_SESSION['user_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas vous noter vous-même']);
    exit;
}

try {
    $db = Database::getDb();
    
    // Check if already rated this user
    $checkStmt = $db->prepare("
        SELECT id FROM ratings 
        WHERE evaluator_id = ? AND evaluated_id = ?
    ");
    $checkStmt->execute([$_SESSION['user_id'], $userId]);
    
    if ($checkStmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vous avez déjà noté cet utilisateur']);
        exit;
    }
    
    // Insert rating
    $stmt = $db->prepare("
        INSERT INTO ratings (evaluator_id, evaluated_id, rating, comment, punctuality, friendliness, safety, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $userId,
        $rating,
        $comment,
        $punctuality,
        $friendliness,
        $safety
    ]);
    
    // Update user's global rating
    $updateStmt = $db->prepare("
        UPDATE users 
        SET global_rating = (
            SELECT AVG(rating) FROM ratings WHERE evaluated_id = ?
        )
        WHERE id = ?
    ");
    $updateStmt->execute([$userId, $userId]);
    
    echo json_encode(['success' => true, 'message' => 'Note enregistrée avec succès']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
