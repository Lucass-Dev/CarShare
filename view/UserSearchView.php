<link rel="stylesheet" href="<?= asset('styles/offers.css') ?>">
<link rel="stylesheet" href="<?= asset('styles/user-search-enhanced.css') ?>">

<div class="offers-page">
    <!-- Page Header -->
    <div class="offers-header">
        <h1>Recherche d'utilisateurs</h1>
        <?php if (!empty($query)): ?>
            <p class="offers-subtitle"><?= count($users) ?> utilisateur<?= count($users) > 1 ? 's' : '' ?> trouvé<?= count($users) > 1 ? 's' : '' ?></p>
        <?php else: ?>
            <p class="offers-subtitle">Recherchez un utilisateur par nom ou prénom</p>
        <?php endif; ?>
    </div>

    <!-- Filters Section -->
    <div class="offers-filters">
        <form method="GET" action="<?= url('index.php') ?>" class="filters-form">
            <input type="hidden" name="action" value="user_search">
            
            <div class="filter-group">
                <label for="search">Rechercher</label>
                <input type="text" 
                       id="search" 
                       name="q" 
                       placeholder="Nom ou prénom..."
                       autocomplete="off"
                       value="<?= htmlspecialchars($query) ?>"
                       autofocus>
            </div>

            <?php if (!empty($query)): ?>
            <div class="filter-group">
                <label for="sort">Trier par</label>
                <select id="sort" name="sort" onchange="this.form.submit()">
                    <option value="name" <?= ($sortBy ?? 'name') === 'name' ? 'selected' : '' ?>>Nom</option>
                    <option value="rating" <?= $sortBy === 'rating' ? 'selected' : '' ?>>Note</option>
                    <option value="trips" <?= $sortBy === 'trips' ? 'selected' : '' ?>>Trajets</option>
                </select>
            </div>
            <?php endif; ?>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Rechercher</button>
                <?php if (!empty($query)): ?>
                    <a href="?action=user_search" class="btn-reset">Réinitialiser</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Users List -->
    <?php if (!empty($query)): ?>
        <?php if (empty($users)): ?>
            <div class="no-offers">
                <div class="no-results-icon">🔍</div>
                <p>Aucun utilisateur trouvé pour "<?= htmlspecialchars($query) ?>"</p>
                <p style="font-size: 0.9em; color: #666;">Essayez avec d'autres mots-clés</p>
            </div>
        <?php else: ?>
            <div class="offers-list">
                <?php foreach ($users as $user): ?>
                    <a href="<?= url('index.php?action=user_profile&id=' . $user['id']) ?>" class="offer-card">
                        <!-- Driver/Avatar Section -->
                        <div class="offer-driver">
                            <div class="driver-avatar">
                                <div class="avatar-initials">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                </div>
                            </div>
                            <div class="driver-info">
                                <div class="driver-name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                                <?php if ($user['global_rating']): ?>
                                    <div class="driver-rating">
                                        <span class="rating-stars">⭐ <?= number_format($user['global_rating'], 1) ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="driver-rating">Nouveau</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- User Info Section -->
                        <div class="offer-route" style="border-left: none; padding-left: 0;">
                            <div class="route-from">
                                <?php if ($user['car_brand']): ?>
                                    <span class="route-label">
                                        <span style="margin-right: 5px;">🚗</span>
                                        Conducteur
                                    </span>
                                    <span class="route-city" style="font-size: 0.9em;">
                                        <?= htmlspecialchars($user['car_brand']) ?> <?= htmlspecialchars($user['car_model']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="route-label">
                                        <span style="margin-right: 5px;">👤</span>
                                        Passager
                                    </span>
                                    <span class="route-city" style="font-size: 0.9em; color: #666;">
                                        Recherche des trajets
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Stats Section -->
                        <div class="offer-details">
                            <div class="detail-item">
                                <span class="detail-icon">🚗</span>
                                <span class="detail-text">
                                    <?= $user['trip_count'] ?> trajet<?= $user['trip_count'] > 1 ? 's' : '' ?>
                                </span>
                            </div>
                            <?php if ($user['global_rating']): ?>
                                <div class="detail-item">
                                    <span class="detail-icon">⭐</span>
                                    <span class="detail-text">
                                        <?= number_format($user['global_rating'], 1) ?>/5
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- View Profile Button -->
                        <div class="offer-price">
                            <span class="price-amount" style="font-size: 0.95em;">Voir profil</span>
                            <span class="price-label">→</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="no-offers">
            <div class="search-prompt-icon" style="font-size: 4em; margin-bottom: 20px;">👥</div>
            <h2 style="color: #2b4d9a; margin-bottom: 10px;">Rechercher un utilisateur</h2>
            <p style="color: #666;">Entrez un nom ou un prénom pour trouver des utilisateurs</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<script src="<?= asset('js/pagination.js?v=' . time()) ?>"></script>
<script>
// Auto-submit on input with debounce
let searchTimeout;
const searchInput = document.querySelector('input[name="q"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.trim().length >= 2) {
                this.form.submit();
            }
        }, 800);
    });
}

// Initialiser la pagination si plus de 10 utilisateurs
<?php if (!empty($users) && count($users) > 10): ?>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.offers-list');
    const items = Array.from(document.querySelectorAll('.offer-card'));
    
    if (container && items.length > 10) {
        new Pagination({
            container: container,
            items: items,
            itemsPerPage: 12,
            maxButtons: 7,
            onChange: (data) => {
                // Mettre à jour le titre avec le numéro de page
                const subtitle = document.querySelector('.offers-subtitle');
                if (subtitle) {
                    subtitle.textContent = `${data.startIndex}-${data.endIndex} sur ${items.length} utilisateur${items.length > 1 ? 's' : ''}`;
                }
            }
        });
    }
});
<?php endif; ?>
</script>
