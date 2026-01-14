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

    <!-- Trajets à venir -->
    <section class="trips-section">
        <div class="section-header">
            <h2 class="section-title">
                <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                Trajets à venir
            </h2>
            <a href="?action=create_trip" class="btn btn--primary btn--small">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Proposer un trajet
            </a>
                Créer un trajet
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
                </div>
            </div>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasUpcoming): 
        ?>
            <div class="empty-state">
                <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
                <h3 class="empty-state__title">Aucun trajet à venir</h3>
                <p class="empty-state__text">Vous n'avez pas encore proposé de trajet futur.</p>
                <a href="?action=create_trip" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Proposer mon premier trajet
                </a>
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
function editTrip(tripId) {
    window.location.href = 'index.php?action=edit_trip&id=' + tripId;
}
</script>
