<?php
    class HomeView{
        public static function render($topUsers = []){
            ?>
            <link rel="stylesheet" href="./assets/styles/home.css">
            
            <?php if (isset($_GET['account_deleted'])): ?>
            <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
                <div style="background: linear-gradient(135deg, #fee 0%, #fdd 100%); color: #721c24; padding: 1.5rem; border-radius: 12px; border-left: 5px solid #e74c3c; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);">
                    <strong style="font-size: 1.1rem;">✓ Compte supprimé</strong>
                    <p style="margin: 0.5rem 0 0 0;">Votre compte et toutes vos données ont été définitivement supprimés. Un email de confirmation vous a été envoyé.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['admin_account_deleted'])): ?>
            <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
                <div style="background: linear-gradient(135deg, #fee 0%, #fdd 100%); color: #721c24; padding: 1.5rem; border-radius: 12px; border-left: 5px solid #e74c3c; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);">
                    <strong style="font-size: 1.1rem;">🛡️ Compte administrateur supprimé</strong>
                    <p style="margin: 0.5rem 0 0 0;">Votre compte administrateur et tous vos privilèges ont été définitivement supprimés. Un email de confirmation vous a été envoyé.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['registration_complete'])): ?>
            <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
                <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; padding: 1.5rem; border-radius: 12px; border-left: 5px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                    <strong style="font-size: 1.1rem;">🎉 Bienvenue sur CarShare !</strong>
                    <p style="margin: 0.5rem 0 0 0;">Votre compte est maintenant actif. Vous pouvez publier des trajets ou rechercher un covoiturage.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <section class="hero">
    <div class="search-container">
        <h2>Rechercher un trajet</h2>
        <form class="search-form" method="GET" action="index.php">
            <input type="hidden" name="action" value="search">
            <input type="hidden" id="form_start_input" name="form_start_input">

            <input type="hidden" id="form_end_input" name="form_end_input">
            <input type="text" name="start_place" placeholder="Ville de départ" data-city-autocomplete required>
            <input type="text" name="end_place" placeholder="Ville d'arrivée" data-city-autocomplete>
            <input type="date" name="date" required>
            <input type="number" name="seats" placeholder="Passagers" min="1" max="10" value="1">
            <button type="submit" class="search-button">Rechercher</button>
        </form>
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
<!-- Autocomplétion des villes -->
<?php
        } // Fin de la méthode render()
    } // Fin de la classe HomeView
?>