<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres de covoiturage - CarShare</title>
    <link rel="stylesheet" href="<?= asset('styles/offers.css') ?>">
</head>
<body>
    <div class="offers-page">
        <!-- Page Header -->
        <div class="offers-header">
            <h1>Toutes les offres de covoiturage</h1>
            <p class="offers-subtitle"><?= $totalOffers ?> trajet<?= $totalOffers > 1 ? 's' : '' ?> disponible<?= $totalOffers > 1 ? 's' : '' ?></p>
        </div>



        <!-- Filters Section -->
        <div class="offers-filters">
            <form method="GET" action="" class="filters-form">
                <input type="hidden" name="action" value="offers">
                
                <div class="filter-group">
                    <label for="search">Rechercher une ville</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           placeholder="Départ ou arrivée"
                           autocomplete="off"
                           value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="filter-group">
                    <label for="date_depart">Date</label>
                    <input type="date" 
                           id="date_depart" 
                           name="date_depart"
                           value="<?= htmlspecialchars($dateFilter) ?>"
                           min="<?= date('Y-m-d') ?>">
                </div>

                <div class="filter-group">
                    <label for="prix_max">Prix maximum</label>
                    <input type="number" 
                           id="prix_max" 
                           name="prix_max" 
                           placeholder="Ex: 50"
                           min="0"
                           value="<?= htmlspecialchars($priceMax) ?>">
                </div>

                <div class="filter-group">
                    <label for="places_min">Places minimum</label>
                    <input type="number" 
                           id="places_min" 
                           name="places_min" 
                           min="1"
                           max="8"
                           value="<?= htmlspecialchars($placesMin) ?>">
                </div>

                <div class="filter-group">
                    <label for="sort">Trier par</label>
                    <select id="sort" name="sort" onchange="this.form.submit()">
                        <option value="date" <?= $sortBy === 'date' ? 'selected' : '' ?>>Date</option>
                        <option value="price" <?= $sortBy === 'price' ? 'selected' : '' ?>>Prix</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="order">Ordre</label>
                    <select id="order" name="order" onchange="this.form.submit()">
                        <option value="asc" <?= $sortOrder === 'asc' ? 'selected' : '' ?>>
                            <?= $sortBy === 'price' ? 'Moins cher' : 'Plus proche' ?>
                        </option>
                        <option value="desc" <?= $sortOrder === 'desc' ? 'selected' : '' ?>>
                            <?= $sortBy === 'price' ? 'Plus cher' : 'Plus loin' ?>
                        </option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Filtrer</button>
                    <a href="<?= url('index.php?action=offers') ?>" class="btn-reset">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Offers List -->
        <div class="offers-list">
            <?php if (empty($offers)): ?>
                <div class="no-offers">
                    <p>Aucune offre disponible pour le moment</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= url('index.php?action=create_trip') ?>" class="btn-create">Proposer un trajet</a>
                    <?php else: ?>
                        <a href="<?= url('index.php?action=login&return_url=' . urlencode('index.php?action=offers')) ?>" class="btn-create">Se connecter</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php 
                $currentUrl = urlencode($_SERVER['REQUEST_URI']);
                foreach ($offers as $offer): 
                    $isOwnOffer = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $offer['provider_id'];
                ?>
                    <a href="<?= url('index.php?action=trip_details&id=' . $offer['id']) ?>" class="offer-card" style="<?= $isOwnOffer ? 'pointer-events: none; opacity: 0.7;' : '' ?>">
                        <?php 
                        // Badge uniquement pour status inactif ou complet (pas besoin de "passé" car filtré)
                        $isInactive = $offer['status'] == 0;
                        $isComplete = $offer['available_places'] == 0;
                        ?>
                        
                        <!-- Badges d'état -->
                        <?php if ($isInactive || $isComplete): ?>
                        <div class="trip-status-badges">
                            <?php if ($isInactive): ?>
                                <span class="status-badge inactive">Inactif</span>
                            <?php endif; ?>
                            <?php if ($isComplete): ?>
                                <span class="status-badge complete">Complet</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
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
                                <span class="detail-text">
                                    <?= $offer['available_places'] ?> place<?= $offer['available_places'] > 1 ? 's' : '' ?>
                                    <?php if ($offer['available_places'] <= 2): ?>
                                        <span class="availability-badge low" title="Dépêchez-vous !">Peu de places</span>
                                    <?php elseif ($offer['available_places'] >= 5): ?>
                                        <span class="availability-badge high" title="Large disponibilité">Nombreuses places</span>
                                    <?php endif; ?>
                                </span>
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
                    <a href="?action=offers&page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sortBy) ? '&sort=' . $sortBy : '' ?><?= !empty($sortOrder) ? '&order=' . $sortOrder : '' ?><?= !empty($dateFilter) ? '&date_depart=' . $dateFilter : '' ?><?= !empty($priceMax) ? '&prix_max=' . $priceMax : '' ?><?= !empty($placesMin) ? '&places_min=' . $placesMin : '' ?>" 
                       class="pagination-btn">Précédent</a>
                <?php endif; ?>

                <div class="pagination-pages">
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <a href="?action=offers&page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sortBy) ? '&sort=' . $sortBy : '' ?><?= !empty($sortOrder) ? '&order=' . $sortOrder : '' ?><?= !empty($dateFilter) ? '&date_depart=' . $dateFilter : '' ?><?= !empty($priceMax) ? '&prix_max=' . $priceMax : '' ?><?= !empty($placesMin) ? '&places_min=' . $placesMin : '' ?>" 
                           class="pagination-page <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $totalPages): ?>
                    <a href="?action=offers&page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sortBy) ? '&sort=' . $sortBy : '' ?><?= !empty($sortOrder) ? '&order=' . $sortOrder : '' ?><?= !empty($dateFilter) ? '&date_depart=' . $dateFilter : '' ?><?= !empty($priceMax) ? '&prix_max=' . $priceMax : '' ?><?= !empty($placesMin) ? '&places_min=' . $placesMin : '' ?>" 
                       class="pagination-btn">Suivant</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // Auto-submit form when search input changes (with debounce)
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 800);
        });
    }

    // Auto-submit on other filter changes
    const priceInput = document.querySelector('input[name="prix_max"]');
    const placesInput = document.querySelector('input[name="places_min"]');
    
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 800);
        });
    }
    
    if (placesInput) {
        placesInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 800);
        });
    }

    // Update order select label based on sort type
    const sortSelect = document.querySelector('select[name="sort"]');
    const orderSelect = document.querySelector('select[name="order"]');

    if (sortSelect && orderSelect) {
        sortSelect.addEventListener('change', function() {
            updateOrderLabels(this.value, orderSelect);
        });
        
        // Initialize on load
        updateOrderLabels(sortSelect.value, orderSelect);
    }

    function updateOrderLabels(sortType, orderSelect) {
        const options = orderSelect.options;
        if (sortType === 'price') {
            options[0].text = 'Moins cher';
            options[1].text = 'Plus cher';
        } else {
            options[0].text = 'Plus proche';
            options[1].text = 'Plus loin';
        }
    }
    </script>
</body>
</html>
