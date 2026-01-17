<!-- Vue pour les trajets créés par l'utilisateur (conducteur) -->
<link rel="stylesheet" href="/CarShare/assets/styles/my-trips.css">

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Mes trajets proposés</h1>
        <p class="page-subtitle">Gérez les trajets que vous proposez en tant que conducteur</p>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'trip_updated'): ?>
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
            <svg style="width: 24px; height: 24px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 11 12 14 22 4"/>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            <div>
                <strong>Trajet modifié avec succès !</strong>
                <p style="margin: 4px 0 0 0; opacity: 0.9; font-size: 14px;">Vos modifications ont été enregistrées.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'trip_deleted'): ?>
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
            <svg style="width: 24px; height: 24px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 11 12 14 22 4"/>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            <div>
                <strong>Trajet supprimé avec succès !</strong>
                <p style="margin: 4px 0 0 0; opacity: 0.9; font-size: 14px;">Le trajet et toutes les réservations associées ont été supprimés.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
            <svg style="width: 24px; height: 24px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <div>
                <strong>Erreur</strong>
                <p style="margin: 4px 0 0 0; opacity: 0.9; font-size: 14px;">
                    <?php
                    $errorMessages = [
                        'invalid_trip' => 'Trajet invalide.',
                        'unauthorized' => 'Vous n\'êtes pas autorisé à effectuer cette action.',
                        'delete_failed' => 'Impossible de supprimer le trajet. Veuillez réessayer.',
                        'trip_not_found' => 'Trajet introuvable.',
                        'invalid_request' => 'Requête invalide.'
                    ];
                    echo $errorMessages[$_GET['error']] ?? 'Une erreur est survenue.';
                    ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['info']) && $_GET['info'] === 'no_changes'): ?>
        <div style="background: linear-gradient(135deg, #7fa7f4 0%, #6f9fe6 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(127, 167, 244, 0.3);">
            <svg style="width: 24px; height: 24px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            <div>
                <strong>Aucune modification détectée</strong>
                <p style="margin: 4px 0 0 0; opacity: 0.9; font-size: 14px;">Vous n'avez apporté aucune modification au trajet.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Barre de filtres et tri -->
    <div class="filters-bar">
        <form method="GET" action="" class="filters-form">
            <input type="hidden" name="action" value="my_trips">
            
            <div class="filter-group">
                <div class="search-input-wrapper">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input 
                        type="text" 
                        name="search" 
                        class="search-input" 
                        placeholder="Rechercher une ville..." 
                        value="<?= htmlspecialchars($search ?? '') ?>"
                    >
                    <?php if (!empty($search)): ?>
                        <a href="?action=my_trips" class="clear-search" title="Effacer la recherche">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Trier par</label>
                <select name="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="date" <?= ($sortBy ?? 'date') === 'date' ? 'selected' : '' ?>>Date</option>
                    <option value="price" <?= ($sortBy ?? 'date') === 'price' ? 'selected' : '' ?>>Prix</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Ordre</label>
                <select name="order" class="filter-select" onchange="this.form.submit()">
                    <option value="desc" <?= ($sortOrder ?? 'desc') === 'desc' ? 'selected' : '' ?>>
                        <?= ($sortBy ?? 'date') === 'price' ? 'Plus cher' : 'Plus récent' ?>
                    </option>
                    <option value="asc" <?= ($sortOrder ?? 'desc') === 'asc' ? 'selected' : '' ?>>
                        <?= ($sortBy ?? 'date') === 'price' ? 'Moins cher' : 'Plus ancien' ?>
                    </option>
                </select>
            </div>
            
            <button type="submit" class="btn btn--secondary btn--small" style="display: none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                Filtrer
            </button>
        </form>
    </div>

    <!-- Trajets à venir -->
    <section class="trips-section">
        <div class="section-header">
            <h2 class="section-title">
                <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                Trajets à venir
                <?php 
                $upcomingCount = 0;
                foreach ($carpoolings as $trip) {
                    if (strtotime($trip['start_date']) > time()) {
                        $upcomingCount++;
                    }
                }
                if ($upcomingCount > 0 || !empty($search)): 
                ?>
                    <span class="count-badge"><?= $upcomingCount ?></span>
                <?php endif; ?>
                <?php if (!empty($search)): ?>
                    <span class="search-indicator">pour "<?= htmlspecialchars($search) ?>"</span>
                <?php endif; ?>
            </h2>
            <a href="?action=create_trip" class="btn btn--primary btn--small">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Proposer un trajet
            </a>
        </div>

        <?php 
        $hasUpcoming = false;
        foreach ($carpoolings as $trip): 
            if (strtotime($trip['start_date']) > time()): 
                $hasUpcoming = true;
                $tripId = $trip['trip_id'] ?? $trip['id'];
        ?>
            <div class="trip-card trip-card--upcoming">
                <div class="trip-card__badge trip-card__badge--available">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Disponible
                </div>
                
                <div class="trip-card__main">
                    <div class="trip-card__route">
                        <div class="location location--from">
                            <div class="location__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="10" r="3"/>
                                    <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"/>
                                </svg>
                            </div>
                            <div class="location__text">
                                <span class="location__label">Départ</span>
                                <span class="location__name"><?= htmlspecialchars($trip['start_location']) ?></span>
                            </div>
                        </div>
                        
                        <div class="trip-card__arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                        
                        <div class="location location--to">
                            <div class="location__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="location__text">
                                <span class="location__label">Arrivée</span>
                                <span class="location__name"><?= htmlspecialchars($trip['end_location']) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="trip-card__details">
                        <div class="detail-item">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span><?= date('d/m/Y', strtotime($trip['start_date'])) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span><?= date('H:i', strtotime($trip['start_date'])) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <span><?= $trip['available_places'] ?> place(s)</span>
                        </div>
                        
                        <?php if (isset($trip['price']) && $trip['price'] > 0): ?>
                        <div class="detail-item detail-item--price">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 6h-3a7 7 0 1 0 0 12h3"/>
                                <line x1="8" y1="10" x2="16" y2="10"/>
                                <line x1="8" y1="14" x2="16" y2="14"/>
                            </svg>
                            <span class="price-value"><?= number_format($trip['price'], 2) ?> €</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="trip-card__actions">
                    <a href="index.php?action=trip_details&id=<?= $tripId ?>" class="btn btn--secondary btn--small">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        Détails
                    </a>
                    <button class="btn btn--outline btn--small" onclick="editTrip(<?= $tripId ?>)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Modifier
                    </button>
                    <button class="btn btn--danger btn--small" onclick="confirmDeleteTrip(<?= $tripId ?>)" data-confirm="Êtes-vous sûr de vouloir supprimer ce trajet ? Cette action est irréversible.">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                        Supprimer
                    </button>
                </div>
            </div>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasUpcoming): 
        ?>
            <div class="empty-state">
                <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <?php if (!empty($search)): ?>
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                        <line x1="11" y1="8" x2="11" y2="14"/>
                        <line x1="8" y1="11" x2="14" y2="11"/>
                    <?php else: ?>
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    <?php endif; ?>
                </svg>
                <h3 class="empty-state__title">
                    <?php if (!empty($search)): ?>
                        Aucun trajet trouvé
                    <?php else: ?>
                        Aucun trajet à venir
                    <?php endif; ?>
                </h3>
                <p class="empty-state__text">
                    <?php if (!empty($search)): ?>
                        Aucun trajet ne correspond à votre recherche "<?= htmlspecialchars($search) ?>".
                        <br>Essayez avec une autre ville ou <a href="?action=my_trips" style="color: var(--accent); text-decoration: underline;">réinitialisez les filtres</a>.
                    <?php else: ?>
                        Vous n'avez pas encore proposé de trajet futur.
                    <?php endif; ?>
                </p>
                <?php if (empty($search)): ?>
                <a href="?action=create_trip" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Proposer mon premier trajet
                </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Trajets passés -->
    <section class="trips-section">
        <div class="section-header">
            <h2 class="section-title">
                <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Trajets terminés
                <?php 
                $pastCount = 0;
                foreach ($carpoolings as $trip) {
                    if (strtotime($trip['start_date']) <= time()) {
                        $pastCount++;
                    }
                }
                if ($pastCount > 0): 
                ?>
                    <span class="count-badge" style="background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);"><?= $pastCount ?></span>
                <?php endif; ?>
            </h2>
        </div>

        <?php 
        $hasPast = false;
        foreach ($carpoolings as $trip): 
            if (strtotime($trip['start_date']) <= time()): 
                $hasPast = true;
                $tripId = $trip['trip_id'] ?? $trip['id'];
                $isExpired = true;
        ?>
            <div class="trip-card trip-card--past trip-card--expired">
                <div class="trip-card__badge trip-card__badge--expired">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    Expiré
                </div>
                
                <div class="trip-card__main">
                    <div class="trip-card__route trip-card__route--compact">
                        <div class="location location--compact">
                            <span class="location__name"><?= htmlspecialchars($trip['start_location']) ?></span>
                        </div>
                        <div class="trip-card__arrow">→</div>
                        <div class="location location--compact">
                            <span class="location__name"><?= htmlspecialchars($trip['end_location']) ?></span>
                        </div>
                    </div>
                    
                    <div class="trip-card__details">
                        <div class="detail-item">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span><?= date('d/m/Y', strtotime($trip['start_date'])) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span><?= date('H:i', strtotime($trip['start_date'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="trip-card__actions">
                    <span class="btn btn--ghost btn--small btn--disabled" disabled>
                        Détails non accessibles
                    </span>
                </div>
            </div>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasPast): 
        ?>
            <div class="empty-state empty-state--small">
                <p class="empty-state__text">Aucun trajet terminé pour le moment.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
// Auto-submit form when search input changes (with debounce)
let searchTimeout;
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        // Add loading indicator
        const wrapper = this.closest('.search-input-wrapper');
        const icon = wrapper.querySelector('.search-icon');
        
        searchTimeout = setTimeout(() => {
            // Show loading
            if (icon) {
                icon.style.opacity = '0.5';
            }
            this.form.submit();
        }, 800); // Wait 800ms after user stops typing
    });
}

// Update order select label based on sort type
const sortSelect = document.querySelector('select[name="sort"]');
const orderSelect = document.querySelector('select[name="order"]');

if (sortSelect && orderSelect) {
    sortSelect.addEventListener('change', function() {
        updateOrderLabels(this.value, orderSelect);
    });
}

function updateOrderLabels(sortType, orderSelect) {
    const options = orderSelect.options;
    if (sortType === 'price') {
        options[0].text = 'Plus cher';
        options[1].text = 'Moins cher';
    } else {
        options[0].text = 'Plus récent';
        options[1].text = 'Plus ancien';
    }
}

function editTrip(tripId) {
    window.location.href = 'index.php?action=edit_trip&id=' + tripId;
}

async function confirmDeleteTrip(tripId) {
    const confirmed = await customConfirm(
        'Cette action est irréversible et supprimera :\n- Le trajet\n- Toutes les réservations associées\n- Les messages liés au trajet',
        {
            title: 'Êtes-vous sûr de vouloir supprimer ce trajet ?',
            icon: '🗑️',
            confirmText: 'Supprimer',
            confirmClass: 'btn-danger',
            cancelText: 'Annuler'
        }
    );
    
    if (confirmed) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?action=delete_trip';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'trip_id';
        input.value = tripId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
