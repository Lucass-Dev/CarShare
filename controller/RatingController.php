<?php
require_once __DIR__ . '/../model/RatingModel.php';

class RatingController
{
    private $model;

    public function __construct()
    {
        $this->model = new RatingModel();
    }

    public function render(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
            exit;
        }

        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        $carpoolingId = isset($_GET['carpooling_id']) ? (int)$_GET['carpooling_id'] : null;
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;

        // If no user selected, show user selection
        if (!$userId) {
            $users = $this->model->getAllUsers($_SESSION['user_id']);
            require __DIR__ . '/../view/RatingSelectUserView.php';
            return;
        }

        // Get user data
        $user = $this->model->getUserById($userId);

        if (!$user) {
            header('Location: /CarShare/index.php?action=rating&error=user_not_found');
            exit;
        }

        $tripCount = $this->model->countUserTrips($userId);
        $ratingsCount = $this->model->countUserRatings($userId);

        // Prepare driver data
        $driver = [
            'id' => $user['id'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'avg' => $user['global_rating'] ? round($user['global_rating'], 1) : 'N/A',
            'carpooling_id' => $carpoolingId, // Can be null
            'trips' => $tripCount,
            'reviews' => $ratingsCount
        ];

        require __DIR__ . '/../view/RatingView.php';
    }

    public function submit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /CarShare/index.php?action=login&return_url=' . $returnUrl);
            exit;
        }

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $carpoolingId = isset($_POST['carpooling_id']) && !empty($_POST['carpooling_id']) ? (int)$_POST['carpooling_id'] : null;
        $comment = trim($_POST['comment'] ?? '');
        $stars = (int)($_POST['stars'] ?? 0);

        // Validation
        if (!$userId) {
            header('Location: /CarShare/index.php?action=rating&error=missing_data');
            exit;
        }

        // Check that user is not rating themselves
        if ($userId === $_SESSION['user_id']) {
            $redirectUrl = '/CarShare/index.php?action=rating&user_id=' . $userId . '&error=self_rating';
            if ($carpoolingId) {
                $redirectUrl .= '&carpooling_id=' . $carpoolingId;
            }
            header('Location: ' . $redirectUrl);
            exit;
        }

        if ($stars < 1) $stars = 1;
        if ($stars > 5) $stars = 5;

        // Verify user exists
        $user = $this->model->getUserById($userId);
        if (!$user) {
            header('Location: /CarShare/index.php?action=rating&error=user_not_found');
            exit;
        }

        // If no carpooling_id was provided (e.g. rating from user profile),
        // try to infer the most recent shared trip between the two users.
        if ($carpoolingId === null) {
            $inferredCarpoolingId = $this->model->findLatestCarpoolingBetweenUsers($userId, $_SESSION['user_id']);

            if ($inferredCarpoolingId !== null) {
                $carpoolingId = $inferredCarpoolingId;
            } else {
                // No shared trip found – cannot attach rating to a concrete carpooling
                $redirectUrl = '/CarShare/index.php?action=rating&user_id=' . $userId . '&error=no_trip';
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        // Save rating - carpooling_id is now optional
        $ratingData = [
            'rater_id' => $_SESSION['user_id'],
            'carpooling_id' => $carpoolingId, // Can be null
            'rating' => $stars,
            'content' => !empty($comment) ? $comment : null
        ];

        $result = $this->model->save($ratingData);

        if ($result) {
            $redirectUrl = '/CarShare/index.php?action=rating&user_id=' . $userId . '&success=1';
            if ($carpoolingId) {
                $redirectUrl .= '&carpooling_id=' . $carpoolingId;
            }
            header('Location: ' . $redirectUrl);
        } else {
            $redirectUrl = '/CarShare/index.php?action=rating&user_id=' . $userId . '&error=save_failed';
            if ($carpoolingId) {
                $redirectUrl .= '&carpooling_id=' . $carpoolingId;
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
