<?php
require_once __DIR__ . '/../model/SignalementModel.php';

class SignalementController
{
    private $model;

    public function __construct()
    {
        $this->model = new SignalementModel();
    }

    public function render(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit;
        }

        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        $carpoolingId = isset($_GET['carpooling_id']) ? (int)$_GET['carpooling_id'] : null;
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;

        // If no user selected, show user selection
        if (!$userId) {
            $users = $this->model->getAllUsers($_SESSION['user_id']);
            require __DIR__ . '/../view/SignalementSelectUserView.php';
            return;
        }

        // Get user data
        $user = $this->model->getUserById($userId);
        
        if (!$user) {
            header('Location: /CarShare/index.php?action=signalement&error=user_not_found');
            exit;
        }

        // Count trips for this user
        $tripCount = $this->model->countUserTrips($userId);
        $ratingCount = $this->model->countUserRatings($userId);

        // Prepare user data
        $userData = [
            'id' => $user['id'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'trip' => null, // No specific trip required
            'avg' => $user['global_rating'] ? round($user['global_rating'], 1) . ' ★' : 'N/A',
            'count' => $tripCount . ' trajet' . ($tripCount > 1 ? 's' : '') . ' réalisé' . ($tripCount > 1 ? 's' : ''),
            'reviews' => $ratingCount . ' avis reçu' . ($ratingCount > 1 ? 's' : ''),
            'carpooling_id' => $carpoolingId // Can be null
        ];

        require __DIR__ . '/../view/SignalementView.php';
    }

    public function submit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /CarShare/index.php?action=login');
            exit;
        }

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $carpoolingId = isset($_POST['carpooling_id']) && !empty($_POST['carpooling_id']) ? (int)$_POST['carpooling_id'] : null;
        $reason = $_POST['reason'] ?? '';
        $description = trim($_POST['description'] ?? '');

        // Validation
        if (!$userId) {
            header('Location: /CarShare/index.php?action=signalement&error=missing_data');
            exit;
        }

        // Check that user is not reporting themselves
        if ($userId === $_SESSION['user_id']) {
            if ($carpoolingId) {
                $redirectUrl = '/CarShare/index.php?controller=trip&action=signalement&trip_id=' . $carpoolingId . '&error=self_reporting';
            } else {
                $redirectUrl = '/CarShare/index.php?action=signalement&user_id=' . $userId . '&error=self_reporting';
            }
            header('Location: ' . $redirectUrl);
            exit;
        }

        if (empty($description)) {
            if ($carpoolingId) {
                $redirectUrl = '/CarShare/index.php?controller=trip&action=signalement&trip_id=' . $carpoolingId . '&error=empty_description';
            } else {
                $redirectUrl = '/CarShare/index.php?action=signalement&user_id=' . $userId . '&error=empty_description';
            }
            header('Location: ' . $redirectUrl);
            exit;
        }

        if (empty($reason)) {
            if ($carpoolingId) {
                $redirectUrl = '/CarShare/index.php?controller=trip&action=signalement&trip_id=' . $carpoolingId . '&error=empty_reason';
            } else {
                $redirectUrl = '/CarShare/index.php?action=signalement&user_id=' . $userId . '&error=empty_reason';
            }
            header('Location: ' . $redirectUrl);
            exit;
        }

        // Verify user exists
        $user = $this->model->getUserById($userId);
        if (!$user) {
            header('Location: /CarShare/index.php?action=signalement&error=user_not_found');
            exit;
        }

        // Build report content
        $contentParts = ["Motif: " . $reason];
        
        // Add trip info if carpooling_id is provided
        if ($carpoolingId) {
            $carpooling = $this->model->getCarpoolingById($carpoolingId);
            if ($carpooling && (int)$carpooling['provider_id'] === $userId) {
                $contentParts[] = "Trajet: " . $carpooling['start_name'] . ' → ' . $carpooling['end_name'];
            }
        }
        
        $contentParts[] = "Utilisateur: " . $user['first_name'] . ' ' . $user['last_name'] . ' (ID: ' . $userId . ')';
        $contentParts[] = "Description: " . $description;
        
        $content = implode(" | ", $contentParts);

        $data = [
            'reporter_id' => $_SESSION['user_id'],
            'content' => $content
        ];

        $result = $this->model->save($data);

        if ($result) {
            if ($carpoolingId) {
                $redirectUrl = '/CarShare/index.php?controller=trip&action=signalement&trip_id=' . $carpoolingId . '&success=1';
            } else {
                $redirectUrl = '/CarShare/index.php?action=signalement&user_id=' . $userId . '&success=1';
            }
            header('Location: ' . $redirectUrl);
        } else {
            if ($carpoolingId) {
                $redirectUrl = '/CarShare/index.php?controller=trip&action=signalement&trip_id=' . $carpoolingId . '&error=save_failed';
            } else {
                $redirectUrl = '/CarShare/index.php?action=signalement&user_id=' . $userId . '&error=save_failed';
            }
            header('Location: ' . $redirectUrl);
        }
        exit;
    }

    public function getCarpoolings(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Not authenticated']);
            exit;
        }

        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

        if (!$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'User ID required']);
            exit;
        }

        $carpoolings = $this->model->getCarpoolingsByUser($userId);

        header('Content-Type: application/json');
        echo json_encode($carpoolings);
        exit;
    }
}
