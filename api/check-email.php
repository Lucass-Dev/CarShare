<?php
// Check email availability API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../model/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';

// Basic validation
if (empty($email)) {
    echo json_encode(['available' => true, 'message' => '']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'message' => 'Format d\'email invalide']);
    exit;
}

// Check in database
try {
    $db = Database::getDb();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $exists = $stmt->fetch() !== false;
    
    if ($exists) {
        echo json_encode([
            'available' => false, 
            'message' => 'Cet email est déjà utilisé'
        ]);
    } else {
        echo json_encode([
            'available' => true, 
            'message' => 'Email disponible'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
