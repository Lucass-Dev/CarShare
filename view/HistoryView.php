<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarShare - Historique</title>
    <link rel="stylesheet" href="/CarShare/historique style.css">
    <link href="/CarShare/assets/styles/header.css" rel="stylesheet">
    <link href="/CarShare/assets/styles/footer.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f9f9f9;
        }

        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .title {
            text-align: center;
            font-size: 32px;
            margin-bottom: 30px;
        }

        .section-prochaines, .section-historique {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
            justify-content: space-around;
        }

        .col {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .col h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #333;
        }

        .trajet-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .trajet-card .left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ddd;
        }

        .nom {
            font-weight: 600;
        }

        .middle {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fleche {
            font-weight: bold;
            font-size: 20px;
        }

        .right {
            text-align: right;
        }

        .small-btn {
            background-color: #b6c8ff;
            border: none;
            padding: 8px 15px;
            border-radius: 15px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
        }

        .bar-horizontal {
            border: 1px solid #ddd;
            margin: 40px 0;
        }

        .historique-center {
            text-align: center;
        }
    </style>
</head>

<body>

<?php require __DIR__ . "/components/header.php"; ?>

<main>
    <h1 class="title">Mes trajets</h1>

    <section class="section-prochaines">
        <div class="col">
            <h2>En tant que conducteur</h2>
            <?php if (empty($carpoolings)): ?>
                <p>Aucun trajet proposé</p>
            <?php else: ?>
                <?php foreach ($carpoolings as $trip): ?>
                    <?php if (strtotime($trip['start_date']) > time()): ?>
                    <div class="trajet-card">
                        <div class="middle">
                            <span><?= htmlspecialchars($trip['start_location']) ?></span>
                            <span class="fleche">→</span>
                            <span><?= htmlspecialchars($trip['end_location']) ?></span>
                        </div>
                        <div class="right">
                            <small>Le <?= date('d/m/Y', strtotime($trip['start_date'])) ?> à <?= date('H:i', strtotime($trip['start_date'])) ?></small>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="/CarShare/index.php?action=create_trip" class="small-btn">Publier un trajet +</a>
        </div>

        <div class="col">
            <h2>En tant que voyageur</h2>
            <?php if (empty($bookings)): ?>
                <p>Aucune réservation</p>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <?php if (strtotime($booking['start_date']) > time()): ?>
                    <div class="trajet-card">
                        <div class="left">
                            <div class="avatar"></div>
                            <span class="nom"><?= htmlspecialchars($booking['provider_first_name']) ?></span>
                        </div>
                        <div class="middle">
                            <span><?= htmlspecialchars($booking['start_location']) ?></span>
                            <span class="fleche">→</span>
                            <span><?= htmlspecialchars($booking['end_location']) ?></span>
                        </div>
                        <div class="right">
                            <small>Le <?= date('d/m/Y', strtotime($booking['start_date'])) ?> à <?= date('H:i', strtotime($booking['start_date'])) ?></small>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="/CarShare/index.php?action=search" class="small-btn">Réserver un trajet</a>
        </div>
    </section>

    <hr class="bar-horizontal">

    <section class="section-historique">
        <div class="col">
            <h2>Historique - Conducteur</h2>
            <?php foreach ($carpoolings as $trip): ?>
                <?php if (strtotime($trip['start_date']) <= time()): ?>
                <div class="trajet-card">
                    <div class="middle">
                        <span><?= htmlspecialchars($trip['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($trip['end_location']) ?></span>
                    </div>
                    <div class="right">
                        <small>Le <?= date('d/m/Y', strtotime($trip['start_date'])) ?></small>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="col">
            <h2>Historique - Voyageur</h2>
            <?php foreach ($bookings as $booking): ?>
                <?php if (strtotime($booking['start_date']) <= time()): ?>
                <div class="trajet-card">
                    <div class="left">
                        <div class="avatar"></div>
                        <span class="nom"><?= htmlspecialchars($booking['provider_first_name']) ?></span>
                    </div>
                    <div class="middle">
                        <span><?= htmlspecialchars($booking['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($booking['end_location']) ?></span>
                    </div>
                    <div class="right">
                        <small>Le <?= date('d/m/Y', strtotime($booking['start_date'])) ?></small>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php require __DIR__ . "/components/footer.php"; ?>

</body>
</html>
