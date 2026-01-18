<link rel="stylesheet" href="<?= asset('styles/history-enhanced.css') ?>">

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Mes réservations</h1>
        <p class="page-subtitle">Gérez vos trajets réservés en tant que passager</p>
    </div>

    <!-- Trajets à venir en tant que passager -->
    <section class="trips-section">
        <div class="section-header">
            <h2 class="section-title">
                <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                Trajets à venir
            </h2>
        </div>

        <?php 
        $hasUpcoming = false;
        foreach ($bookings as $booking): 
            if (strtotime($booking['start_date']) > time()): 
                $hasUpcoming = true;
                $bookingTripId = $booking['trip_id'] ?? $booking['id'];
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
                    <div class="driver-info">
                        <div class="driver-avatar"></div>
                        <div class="driver-details">
                            <span class="driver-label">Conducteur</span>
                            <a href="index.php?action=user_profile&id=<?= $booking['provider_id'] ?>" class="driver-name">
                                <?= htmlspecialchars($booking['provider_first_name']) ?>
                            </a>
                        </div>
                    </div>
                    
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
                                <span class="location__name"><?= htmlspecialchars($booking['start_location']) ?></span>
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
                                <span class="location__name"><?= htmlspecialchars($booking['end_location']) ?></span>
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
                            <span><?= date('d/m/Y', strtotime($booking['start_date'])) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span><?= date('H:i', strtotime($booking['start_date'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="trip-card__actions">
                    <a href="index.php?action=trip_details&id=<?= $bookingTripId ?>" class="btn btn--secondary btn--small">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        Détails
                    </a>
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
                <h3 class="empty-state__title">Aucune réservation à venir</h3>
                <p class="empty-state__text">Vous n'avez pas encore de trajet réservé.</p>
                <a href="?action=search" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    Rechercher un trajet
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
        foreach ($bookings as $booking): 
            if (strtotime($booking['start_date']) <= time()): 
                $hasPast = true;
                $bookingTripId = $booking['trip_id'] ?? $booking['id'];
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
                    <div class="driver-info driver-info--compact">
                        <div class="driver-avatar driver-avatar--small"></div>
                        <a href="index.php?action=user_profile&id=<?= $booking['provider_id'] ?>" class="driver-name">
                            <?= htmlspecialchars($booking['provider_first_name']) ?>
                        </a>
                    </div>
                    
                    <div class="trip-card__route trip-card__route--compact">
                        <div class="location location--compact">
                            <span class="location__name"><?= htmlspecialchars($booking['start_location']) ?></span>
                        </div>
                        <div class="trip-card__arrow">→</div>
                        <div class="location location--compact">
                            <span class="location__name"><?= htmlspecialchars($booking['end_location']) ?></span>
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
                            <span><?= date('d/m/Y', strtotime($booking['start_date'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="trip-card__actions">
                    <button class="btn btn--secondary btn--small rate-btn" 
                            data-action="rate-user" 
                            data-user-id="<?= $booking['provider_id'] ?>" 
                            data-user-name="<?= htmlspecialchars($booking['provider_first_name']) ?>" 
                            title="Noter ce conducteur">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        Noter
                    </button>
                    <button class="btn btn--outline btn--small report-btn" 
                            data-action="report-user" 
                            data-user-id="<?= $booking['provider_id'] ?>" 
                            data-user-name="<?= htmlspecialchars($booking['provider_first_name']) ?>" 
                            title="Signaler un problème">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        Signaler
                    </button>
                    <a href="?action=trip_details&id=<?= $bookingTripId ?>" class="btn btn--ghost btn--small">
                        Détails
                    </a>
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

<script src="<?= asset('js/rating-report-modals.js') ?>"></script>