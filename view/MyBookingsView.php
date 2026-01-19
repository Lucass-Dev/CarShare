<?php
// View: MyBookingsView - list passenger reservations
?>
<link rel="stylesheet" href="<?= asset('styles/history.css') ?>">
<h1 class="title">Mes réservations</h1>

<?php if (empty($bookings)): ?>
    <p class="historique-center">Aucune réservation trouvée. Vous n'avez pas encore réservé de trajet.</p>
<?php else: ?>
    <section class="section-prochaines">
        <div class="col">
            <h2>Prochains trajets</h2>
            <?php
            $hasUpcoming = false;
            $currentTime = time();
            // Utiliser la date/heure actuelle avec fuseau horaire
            date_default_timezone_set('Europe/Paris');
            
            foreach ($bookings as $booking):
                // Convertir la date du trajet en timestamp (format: Y-m-d H:i:s depuis MySQL)
                $tripTimestamp = strtotime($booking['start_date']);
                
                // Afficher seulement si la date/heure du trajet est FUTURE
                if ($tripTimestamp <= $currentTime) {
                    continue;
                }
                $hasUpcoming = true;
            ?>
                <div class="trajet-card">
                    <div class="status-badge status-upcoming">À venir</div>
                    <div class="middle">
                        <span><?= htmlspecialchars($booking['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($booking['end_location']) ?></span>
                    </div>
                    <div class="right history-link">
                        <small>
                            <strong>Trajet :</strong> <?= date('d/m/Y', strtotime($booking['start_date'])) ?>
                            à <?= date('H:i', strtotime($booking['start_date'])) ?>
                        </small>
                        <a href="<?= url('index.php?action=trip_details&id=' . $booking['carpooling_id']) ?>">Voir détails ></a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$hasUpcoming): ?>
                <p>Aucun trajet à venir pour le moment</p>
            <?php endif; ?>
        </div>
    </section>

    <hr class="bar-horizontal">

    <section class="section-historique">
        <div class="col">
            <h2>Historique des trajets</h2>
            <?php
            $hasPast = false;
            $currentTime = time();
            date_default_timezone_set('Europe/Paris');
            
            foreach ($bookings as $booking):
                $tripTimestamp = strtotime($booking['start_date']);
                
                // Afficher seulement si la date/heure du trajet est PASSÉE
                if ($tripTimestamp > $currentTime) {
                    continue;
                }
                $hasPast = true;
            ?>
                <div class="trajet-card completed">
                    <div class="status-badge status-completed">Effectué</div>
                    <div class="middle">
                        <span><?= htmlspecialchars($booking['start_location']) ?></span>
                        <span class="fleche">→</span>
                        <span><?= htmlspecialchars($booking['end_location']) ?></span>
                    </div>
                    <div class="right history-link">
                        <small>
                            <strong>Trajet :</strong> <?= date('d/m/Y', strtotime($booking['start_date'])) ?>
                            à <?= date('H:i', strtotime($booking['start_date'])) ?>
                        </small>
                        <a href="<?= url('index.php?action=trip_details&id=' . $booking['carpooling_id']) ?>">Voir détails ></a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$hasPast): ?>
                <p>Aucun trajet effectué pour le moment</p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
