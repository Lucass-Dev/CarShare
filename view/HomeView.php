<<<<<<< Updated upstream
<section class="hero">
=======
<?php
    class HomeView{
        public static function render($topUsers = []){
            ?>
            <link rel="stylesheet" href="./assets/styles/home.css">
            <section class="hero">
>>>>>>> Stashed changes
    <div class="search-container">
        <form class="search-form">
            <input type="text" placeholder="Départ">
            <input type="text" placeholder="Arrivée">
            <input type="text" placeholder="Date">
            <input type="text" placeholder="Nombre de passagers">

            <button class="search-button">Rechercher</button>
        </form>

        <p style="margin-top:10px; font-size:14px; color:#555;">
            <?= htmlspecialchars($hello) ?>
        </p>
    </div>
</section>

<section class="reviews-section">
<?php if (!empty($topUsers)): ?>
    <?php foreach ($topUsers as $user): ?>
        <div class="review-card">
            <div class="review-header">
                <div class="review-user-info">
                    <h4><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
                    <p class="review-source">
                        <?= htmlspecialchars($user['trip_count']) ?> trajet<?= $user['trip_count'] > 1 ? 's' : '' ?> • 
                        <?= htmlspecialchars($user['review_count']) ?> avis
                    </p>
                </div>
                
                <div class="dropdown-menu">
                    <button class="menu-toggle">⋮</button>
                    <ul class="menu-items">
                        <?php if (!empty($user['last_trip_id'])): ?>
                            <li><a href="index.php?controller=trip&action=rating&trip_id=<?= $user['last_trip_id'] ?>">Noter</a></li>
                            <li><a href="index.php?controller=trip&action=signalement&trip_id=<?= $user['last_trip_id'] ?>">Signaler</a></li>
                        <?php else: ?>
                            <li><span style="color: #999; cursor: not-allowed;">Aucun trajet</span></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <h3 class="rating-value"><?= number_format($user['global_rating'], 1) ?> / 5</h3>

            <div class="stars">
                <?php
                $rating = round($user['global_rating']);
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo '<span class="star filled">★</span>';
                    } else {
                        echo '<span class="star">☆</span>';
                    }
                }
                ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align: center; color: #777;">Aucun avis disponible pour le moment.</p>
<?php endif; ?>
</section>

<section class="features-section">
    <div class="feature-text">
        <h2>Partagez vos trajets simplement.</h2>
        <p>
            Économique, écologique et convivial, CarShare favorise le partage
            et réduit l’impact environnemental.
        </p>
    </div>

    <div class="feature-image">
        <img src="./assets/img/Ville_eco.jpg" alt="Ville écologique">
    </div>
</section>
