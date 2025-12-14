<?php

require_once __DIR__ . '/../models/SignalementModel.php';

class SignalementController
{
    public function index(): void
    {
        // Données mock (plus tard DB)
        $user = [
            'name'  => 'Paul Martin',
            'trip'  => 'Paris → Tours — 24/10/2025',
            'avg'   => '4,2 ★',
            'count' => '18 trajets réalisés'
        ];

        require __DIR__ . '/../views/signalement/signalement.php';
    }

    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $data = [
            'user'        => $_POST['user'] ?? '',
            'reason'      => $_POST['reason'] ?? '',
            'description' => trim($_POST['description'] ?? ''),
            'date'        => date('c')
        ];

        (new SignalementModel())->save($data);

        header('Location: /signalement?success=1');
        exit;
    }
}
