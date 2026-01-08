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
                            <a href="index.php?action=user_profile&id=<?= $booking['provider_id'] ?>" class="nom user-link">
                                <?= htmlspecialchars($booking['provider_first_name']) ?>
                            </a>
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
                <div class="trajet-card completed">
                    <div class="left">
                        <div class="avatar"></div>
                        <a href="index.php?action=user_profile&id=<?= $booking['provider_id'] ?>" class="nom user-link">
                            <?= htmlspecialchars($booking['provider_first_name']) ?>
                        </a>
                    </div>
                    <div class="middle">
                        <span><?= htmlspecialchars($booking['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($booking['end_location']) ?></span>
                    </div>
                    <div class="right">
                        <small>Le <?= date('d/m/Y', strtotime($booking['start_date'])) ?></small>
                        <div class="trip-actions">
                            <button class="action-btn rate-btn" 
                                    data-action="rate-user" 
                                    data-user-id="<?= $booking['provider_id'] ?>" 
                                    data-user-name="<?= htmlspecialchars($booking['provider_first_name']) ?>" 
                                    title="Noter ce conducteur">
                                ⭐ Noter
                            </button>
                            <button class="action-btn report-btn" 
                                    data-action="report-user" 
                                    data-user-id="<?= $booking['provider_id'] ?>" 
                                    data-user-name="<?= htmlspecialchars($booking['provider_first_name']) ?>" 
                                    title="Signaler un problème">
                                ⚠️ Signaler
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>