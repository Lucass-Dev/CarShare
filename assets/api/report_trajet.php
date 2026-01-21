<?php
/**
 * Report Trajet API - Signaler le conducteur d'un trajet
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
$reason = $data['reason'] ?? '';
$description = $data['description'] ?? '';

// Protection basique XSS
$reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

// Validation
if (!$carpoolingId || empty($reason) || empty($description)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
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
    
    // Can't report yourself
    if ($providerId == $_SESSION['user_id']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas vous signaler vous-même']);
        exit;
    }
    
    // Insert report (même requête que pour un utilisateur normal)
    $stmt = $db->prepare("
        INSERT INTO report (reporter_id, content, is_in_progress, is_treated)
        VALUES (?, ?, 1, 0)
    ");
    
    // Même format que report.php
    $content = "USER_ID:$providerId\n\nRaison: " . $reason . "\n\nDescription: " . $description;
    
    $stmt->execute([
        $_SESSION['user_id'],
        $content
    ]);
    
    // Mettre à jour la note globale de l'utilisateur (pénalité -0.5)
    $updateRatingStmt = $db->prepare("
        UPDATE users 
        SET global_rating = GREATEST(1.0, COALESCE(global_rating, 5.0) - 0.5)
        WHERE id = ?
    ");
    $updateRatingStmt->execute([$providerId]);
    
    echo json_encode(['success' => true, 'message' => '✅ Signalement enregistré']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
