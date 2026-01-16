<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les offres - CarShare</title>
    <link rel="stylesheet" href="./assets/styles/footer.css">
</head>
<body>
    <div class="offers-page">
        <div class="offers-header">
            <h1>Toutes les offres de covoiturage</h1>
            <p class="offers-subtitle"><?= $totalOffers ?> trajet<?= $totalOffers > 1 ? 's' : '' ?> disponible<?= $totalOffers > 1 ? 's' : '' ?></p>
        </div>

        <!-- Filters Section -->
        <div class="offers-filters">
            <form method="GET" action="index.php" class="filters-form">
                <input type="hidden" name="action" value="offers">
                
                <div class="filter-group">
                    <label for="ville_depart">Ville de départ</label>
                    <input type="text" 
                           id="ville_depart" 
                           name="ville_depart" 
                           class="city-autocomplete"
                           placeholder="Ex: Paris"
                           autocomplete="off"
                           value="<?= htmlspecialchars($filters['ville_depart']) ?>">
                    <div class="autocomplete-dropdown" id="ville_depart-dropdown"></div>
                </div>

                <div class="filter-group">
                    <label for="ville_arrivee">Ville d'arrivée</label>
                    <input type="text" 
                           id="ville_arrivee" 
                           name="ville_arrivee" 
                           class="city-autocomplete"
                           placeholder="Ex: Lyon"
                           autocomplete="off"
                           value="<?= htmlspecialchars($filters['ville_arrivee']) ?>">
                    <div class="autocomplete-dropdown" id="ville_arrivee-dropdown"></div>
                </div>

                <div class="filter-group">
                    <label for="date_depart">Date</label>
                    <input type="date" 
                           id="date_depart" 
                           name="date_depart"
                           value="<?= htmlspecialchars($filters['date_depart']) ?>"
                           min="<?= date('Y-m-d') ?>">
                </div>

                <div class="filter-group">
                    <label for="prix_max">Prix maximum</label>
                    <input type="number" 
                           id="prix_max" 
                           name="prix_max" 
                           placeholder="Ex: 50"
                           min="0"
                           value="<?= htmlspecialchars($filters['prix_max']) ?>">
                </div>

                <div class="filter-group">
                    <label for="places_min">Places minimum</label>
                    <input type="number" 
                           id="places_min" 
                           name="places_min" 
                           min="1"
                           max="8"
                           value="<?= htmlspecialchars($filters['places_min']) ?>">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Filtrer</button>
                    <a href="index.php?action=offers" class="btn-reset">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Offers List -->
        <div class="offers-list">
            <?php if (empty($offers)): ?>
                <div class="no-offers">
                    <p>Aucune offre disponible pour le moment</p>
                    <a href="index.php?action=create_trip" class="btn-create">Proposer un trajet</a>
                </div>
            <?php else: ?>
                <?php foreach ($offers as $offer): ?>
                    <a href="index.php?action=trip_details&id=<?= $offer['id'] ?>" class="offer-card">
                        <div class="offer-driver">
                            <div class="driver-avatar">
                                <div class="avatar-initials">
                                    <?= strtoupper(substr($offer['first_name'], 0, 1) . substr($offer['last_name'], 0, 1)) ?>
                                </div>
                            </div>
                            <div class="driver-info">
                                <div class="driver-name"><?= htmlspecialchars($offer['first_name'] . ' ' . substr($offer['last_name'], 0, 1)) ?>.</div>
                                <?php if ($offer['review_count'] > 0): ?>
                                    <div class="driver-rating">
                                        <span class="rating-stars"><?= number_format($offer['avg_rating'], 1) ?></span>
                                        <span class="rating-count">(<?= $offer['review_count'] ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <div class="driver-rating">Nouveau</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="offer-route">
                            <div class="route-from">
                                <span class="route-label">Départ</span>
                                <span class="route-city"><?= htmlspecialchars($offer['ville_depart']) ?></span>
                            </div>
                            <div class="route-arrow">→</div>
                            <div class="route-to">
                                <span class="route-label">Arrivée</span>
                                <span class="route-city"><?= htmlspecialchars($offer['ville_arrivee']) ?></span>
                            </div>
                        </div>

                        <div class="offer-details">
                            <div class="detail-item">
                                <span class="detail-icon">📅</span>
                                <span class="detail-text">
                                    <?php
                                    $date = new DateTime($offer['start_date']);
                                    echo $date->format('d/m/Y');
                                    ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">🕐</span>
                                <span class="detail-text">
                                    <?php
                                    $time = new DateTime($offer['start_date']);
                                    echo $time->format('H:i');
                                    ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">👤</span>
                                <span class="detail-text"><?= $offer['available_places'] ?> place<?= $offer['available_places'] > 1 ? 's' : '' ?></span>
                            </div>
                        </div>

                        <div class="offer-price">
                            <span class="price-amount"><?= number_format($offer['price'], 2) ?> €</span>
                            <span class="price-label">par personne</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?action=offers&page=<?= $page - 1 ?><?= http_build_query($filters) ? '&' . http_build_query($filters) : '' ?>" 
                       class="pagination-btn">Précédent</a>
                <?php endif; ?>

                <div class="pagination-pages">
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <a href="?action=offers&page=<?= $i ?><?= http_build_query($filters) ? '&' . http_build_query($filters) : '' ?>" 
                           class="pagination-page <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $totalPages): ?>
                    <a href="?action=offers&page=<?= $page + 1 ?><?= http_build_query($filters) ? '&' . http_build_query($filters) : '' ?>" 
                       class="pagination-btn">Suivant</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="/CarShare/assets/js/city-autocomplete-enhanced.js"></script>
    <script>
        // Initialize city autocomplete for filters
        document.addEventListener('DOMContentLoaded', function() {
            const villeDepart = document.getElementById('ville_depart');
            const villeArrivee = document.getElementById('ville_arrivee');
            
            if (villeDepart) {
                initCityAutocomplete(villeDepart, 'ville_depart-dropdown');
            }
            
            if (villeArrivee) {
                initCityAutocomplete(villeArrivee, 'ville_arrivee-dropdown');
            }
        });
    </script>
</body>
</html>
