<?php
require_once __DIR__ . '/../models/RatingModel.php';

class RatingController
{
    public function index(): void
    {
        $driver = [
            'name' => 'Paul',
            'avg'  => 4.0,
        ];

        // ✅ nouvelle vue
        require __DIR__ . '/../views/rating/rating.php';
    }

    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        $comment = trim($_POST['comment'] ?? '');
        $stars   = (int)($_POST['stars'] ?? 0);

        if ($stars < 1) $stars = 1;
        if ($stars > 5) $stars = 5;

        (new RatingModel())->save([
            'driver'  => $_POST['driver'] ?? 'Paul',
            'comment' => $comment,
            'stars'   => $stars,
            'date'    => date('c'),
        ]);

        header('Location: /rating?success=1');
        exit;
    }
}
