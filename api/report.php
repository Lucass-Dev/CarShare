<?php
/**
 * Report API - Handle user reports
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
$reason = $data['reason'] ?? '';
$description = $data['description'] ?? '';

// Validation
if (!$userId || empty($reason) || empty($description)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
    exit;
}

// Can't report yourself
if ($userId == $_SESSION['user_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas vous signaler vous-même']);
    exit;
}

try {
    $db = Database::getDb();
    
    // Insert report
    $stmt = $db->prepare("
        INSERT INTO signalements (reporter_id, reported_id, reason, description, status, created_at)
        VALUES (?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $userId,
        $reason,
        $description
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Signalement envoyé à l\'équipe de modération']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
