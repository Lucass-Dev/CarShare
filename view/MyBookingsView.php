<?php
// View: MyBookingsView - list passenger reservations
?>
<h1 class="title">Mes réservations</h1>

<?php if (empty($bookings)): ?>
    <p class="historique-center">Aucun trajet pour le moment</p>
<?php else: ?>
    <section class="section-prochaines">
        <div class="col">
            <h2>Prochains trajets</h2>
            <?php
            $hasUpcoming = false;
            foreach ($bookings as $booking):
                if (strtotime($booking['start_date']) <= time()) {
                    continue;
                }
                $hasUpcoming = true;
            ?>
                <div class="trajet-card">
                    <div class="middle">
                        <span><?= htmlspecialchars($booking['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($booking['end_location']) ?></span>
                    </div>
                    <div class="right">
                        <small>
                            Le <?= date('d/m/Y', strtotime($booking['start_date'])) ?>
                            à <?= date('H:i', strtotime($booking['start_date'])) ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$hasUpcoming): ?>
                <p>Aucun trajet pour le moment</p>
            <?php endif; ?>
        </div>
    </section>

    <hr class="bar-horizontal">

    <section class="section-historique">
        <div class="col">
            <h2>Historique des trajets</h2>
            <?php
            $hasPast = false;
            foreach ($bookings as $booking):
                if (strtotime($booking['start_date']) > time()) {
                    continue;
                }
                $hasPast = true;
            ?>
                <div class="trajet-card completed">
                    <div class="middle">
                        <span><?= htmlspecialchars($booking['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($booking['end_location']) ?></span>
                    </div>
                    <div class="right">
                        <small>Le <?= date('d/m/Y', strtotime($booking['start_date'])) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$hasPast): ?>
                <p>Aucun trajet pour le moment</p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
