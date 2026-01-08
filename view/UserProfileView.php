<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f6f8fa;
            color: #24292f;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #0969da;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 24px;
            padding: 8px 0;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .profile-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .profile-card {
            background: white;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 16px;
        }

        .profile-header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 1px solid #d0d7de;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 600;
            color: white;
            margin: 0 auto 12px;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 6px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: center;
        }

        .profile-email {
            font-size: 13px;
            color: #57606a;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: center;
        }

        .profile-stats {
            display: flex;
            gap: 8px;
            justify-content: center;
            padding: 12px 0;
            flex-wrap: wrap;
            border-bottom: 1px solid #d0d7de;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-verified {
            background: #ddf4ff;
            color: #0969da;
        }

        .badge-driver {
            background: #dcffe4;
            color: #1a7f37;
        }

        .rating-display {
            text-align: center;
            padding-top: 16px;
        }

        .rating-number {
            font-size: 32px;
            font-weight: 700;
            color: #24292f;
            line-height: 1;
        }

        .rating-label {
            font-size: 13px;
            color: #57606a;
            margin-top: 4px;
        }

        .stars {
            color: #f59e0b;
            margin-top: 8px;
            font-size: 16px;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #57606a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 14px;
            color: #24292f;
            padding: 8px 12px;
            background: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
        }

        .empty-state {
            text-align: center;
            padding: 32px;
            color: #57606a;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-direction: column;
        }

        .btn {
            padding: 10px 16px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            background: white;
            color: #24292f;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn:hover {
            background: #f6f8fa;
            border-color: #57606a;
        }

        .btn-primary {
            background: #0969da;
            border-color: #0969da;
            color: white;
        }

        .btn-primary:hover {
            background: #0550ae;
            border-color: #0550ae;
        }

        .btn-danger {
            background: white;
            border-color: #d0d7de;
            color: #cf222e;
        }

        .btn-danger:hover {
            background: #ffebe9;
            border-color: #cf222e;
        }

        .member-since {
            font-size: 13px;
            color: #57606a;
            text-align: center;
            margin-top: 12px;
        }

        .trip-item {
            padding: 12px;
            background: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            margin-bottom: 12px;
        }

        .trip-route {
            font-size: 14px;
            color: #24292f;
            margin-bottom: 6px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .trip-details {
            font-size: 13px;
            color: #57606a;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .profile-layout {
                grid-template-columns: 1fr;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php?action=search" class="back-link">
            <span>←</span> Retour à la recherche
        </a>

        <div class="profile-layout">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar">
                            <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                        </div>
                        <div class="profile-name">
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                        </div>
                    </div>

                    <div class="profile-stats">
                        <?php if ($user['is_verified_user']): ?>
                            <span class="badge badge-verified">Vérifié</span>
                        <?php endif; ?>
                        
                        <?php if ($user['car_brand']): ?>
                            <span class="badge badge-driver">
                                <?= $user['car_is_verified'] ? 'Conducteur vérifié' : 'Conducteur' ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($user['global_rating'] !== null): ?>
                        <div class="rating-display">
                            <div class="rating-number">
                                <?= number_format($user['global_rating'], 1) ?>
                            </div>
                            <div class="rating-label">Note globale</div>
                            <div class="stars">
                                <?php
                                $rating = round($user['global_rating']);
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $rating ? '★' : '☆';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="member-since">
                        Membre depuis <?= date('M Y', strtotime($user['created_at'])) ?>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['id']): ?>
                    <div class="actions">
                        <a href="index.php?action=messaging_conversation&user_id=<?= $user['id'] ?>" class="btn btn-primary">
                            Envoyer un message
                        </a>
                        <a href="index.php?action=rating&user_id=<?= $user['id'] ?>" class="btn">
                            Noter
                        </a>
                        <a href="index.php?action=signalement&user_id=<?= $user['id'] ?>" class="btn btn-danger">
                            Signaler
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Vehicle Info -->
                <?php if ($user['car_brand'] && $user['car_model']): ?>
                    <div class="profile-card">
                        <div class="section-title">Véhicule</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Marque</div>
                                <div class="info-value"><?= htmlspecialchars($user['car_brand']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Modèle</div>
                                <div class="info-value"><?= htmlspecialchars($user['car_model']) ?></div>
                            </div>
                            <?php if ($user['car_plate']): ?>
                                <div class="info-item">
                                    <div class="info-label">Immatriculation</div>
                                    <div class="info-value"><?= htmlspecialchars($user['car_plate']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Ratings -->
                <?php if (isset($ratings_stats) && $ratings_stats['count'] > 0): ?>
                    <div class="profile-card">
                        <div class="section-title">
                            Évaluations (<?= $ratings_stats['count'] ?>)
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Note moyenne</div>
                                <div class="info-value">
                                    <?= number_format($ratings_stats['avg_rating'], 1) ?>/5 ★
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Recent Trips -->
                <?php if (isset($trips) && count($trips) > 0): ?>
                    <div class="profile-card">
                        <div class="section-title">Trajets récents</div>
                        <?php foreach ($trips as $trip): ?>
                            <div class="trip-item">
                                <div class="trip-route">
                                    <strong><?= htmlspecialchars($trip['departure'] ?? 'Non spécifié') ?></strong> → 
                                    <strong><?= htmlspecialchars($trip['destination'] ?? 'Non spécifié') ?></strong>
                                </div>
                                <div class="trip-details">
                                    <?= date('d/m/Y', strtotime($trip['date'])) ?> 
                                    • <?= $trip['available_places'] ?? 0 ?> places
                                    • <?= number_format($trip['price'] ?? 0, 2) ?>€
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
