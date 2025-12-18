<link rel="stylesheet" href="./assets/styles/history.css">
<script src="./assets/js/history.js" defer></script>

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
            <a href="index.php?controller=trip&action=publish" class="small-btn">Publier un trajet +</a>
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
            <a href="index.php?controller=trip&action=search" class="small-btn">Réserver un trajet</a>
        </div>
    </section>

    <hr class="bar-horizontal">

    <section class="section-historique">
        <div class="col">
            <h2>Historique - Conducteur</h2>
            <?php if (empty($carpoolings)): ?>
                <p>Aucun trajet passé</p>
            <?php else: foreach ($carpoolings as $trip): ?>
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
            <?php endforeach; endif; ?>
        </div>

        <div class="col">
            <h2>Historique - Voyageur</h2>
            <?php if (empty($bookings)): ?>
                <p>Aucun trajet passé</p>
            <?php else: foreach ($bookings as $booking): ?>
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
                        <div>
                            <a class="small-btn" href="?controller=trip&action=rating&trip_id=<?= urlencode($booking['carpooling_id']) ?>">Noter le conducteur</a>
                            <a class="small-btn" href="?controller=trip&action=signalement&trip_id=<?= urlencode($booking['carpooling_id']) ?>">Signaler</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; endif; ?>
        </div>
    </section>

</main>
