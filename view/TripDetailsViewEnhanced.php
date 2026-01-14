<link rel="stylesheet" href="/CarShare/assets/styles/trip-details-enhanced.css">

<div class="trip-details-container">
    <!-- Header avec route -->
    <div class="trip-details-header">
        <div class="trip-route">
            <span class="trip-route__city"><?= htmlspecialchars($carpooling['start_location']) ?></span>
            <span class="trip-route__arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </span>
            <span class="trip-route__city"><?= htmlspecialchars($carpooling['end_location']) ?></span>
        </div>
        
        <div class="trip-date">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <?= date('l j F Y', strtotime($carpooling['start_date'])) ?> à <?= date('H:i', strtotime($carpooling['start_date'])) ?>
        </div>
    </div>

    <!-- Grid principale -->
    <div class="trip-details-grid">
        <!-- Informations du trajet -->
        <div class="trip-info-card">
            <!-- Itinéraire -->
            <div class="trip-info-section">
                <h3 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="10" r="3"/>
                        <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"/>
                    </svg>
                    Itinéraire
                </h3>
                
                <div class="location-details">
                    <div class="location-item location-item--start">
                        <div class="location-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </div>
                        <div class="location-content">
                            <h4>Point de départ</h4>
                            <p><?= htmlspecialchars($carpooling['start_location']) ?></p>
                        </div>
                    </div>
                    
                    <div class="location-item location-item--end">
                        <div class="location-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="location-content">
                            <h4>Destination</h4>
                            <p><?= htmlspecialchars($carpooling['end_location']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du trajet -->
            <div class="trip-info-section">
                <h3 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    Détails
                </h3>
                
                <div class="info-row">
                    <span class="info-label">Date de départ</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Heure de départ</span>
                    <span class="info-value"><?= date('H:i', strtotime($carpooling['start_date'])) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Places disponibles</span>
                    <span class="info-value info-value--highlight">
                        <?= $carpooling['available_places'] ?> <?= $carpooling['available_places'] > 1 ? 'places' : 'place' ?>
                    </span>
                </div>
            </div>

            <!-- Options -->
            <?php if (!empty($carpooling['animals']) || !empty($carpooling['smoking']) || !empty($carpooling['luggage'])): ?>
            <div class="trip-info-section">
                <h3 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M12 1v6m0 6v6m5.3-13L12 11m0 0L6.7 6M18 12l-6 5.7M12 12l-5.3 5.7"/>
                    </svg>
                    Options
                </h3>
                
                <div class="trip-options">
                    <?php if (!empty($carpooling['animals'])): ?>
                    <span class="option-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="10" r="3"/>
                            <circle cx="12" cy="10" r="7"/>
                        </svg>
                        Animaux acceptés
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($carpooling['smoking'])): ?>
                    <span class="option-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Fumeur
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($carpooling['luggage'])): ?>
                    <span class="option-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="6" width="18" height="15" rx="2"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Bagages
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Prix -->
            <div class="price-section">
                <div class="price-label">Prix par passager</div>
                <div class="price-value"><?= number_format($carpooling['price'], 2) ?> €</div>
                <div class="price-note">TTC - Paiement sécurisé</div>
            </div>
        </div>

        <!-- Carte conducteur -->
        <div class="driver-card">
            <div class="driver-header">
                <img src="/CarShare/assets/img/avatar.jpg" alt="<?= htmlspecialchars($carpooling['first_name']) ?>" class="driver-avatar">
                <div class="driver-info">
                    <h3><?= htmlspecialchars($carpooling['first_name']) ?> <?= isset($carpooling['last_name']) ? strtoupper(substr($carpooling['last_name'], 0, 1)) . '.' : '' ?></h3>
                    <div class="driver-rating">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <?= $carpooling['global_rating'] ? number_format($carpooling['global_rating'], 1) : 'Nouveau' ?>
                    </div>
                </div>
            </div>

            <div class="driver-stats">
                <div class="stat-item">
                    <span class="stat-value">
                        <?= isset($carpooling['total_trips']) ? $carpooling['total_trips'] : '?' ?>
                    </span>
                    <span class="stat-label">Trajets</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">
                        <?= $carpooling['global_rating'] ? number_format($carpooling['global_rating'], 1) : 'N/A' ?>
                    </span>
                    <span class="stat-label">Note</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="trip-actions" style="margin-top: 24px;">
                <?php if (strtotime($carpooling['start_date']) > time() && $carpooling['available_places'] > 0): ?>
                    <a href="/CarShare/index.php?action=payment&carpooling_id=<?= $carpooling['id'] ?>" class="btn-reserve">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Réserver
                    </a>
                <?php else: ?>
                    <button class="btn-reserve" disabled style="opacity: 0.6; cursor: not-allowed;">
                        <?= strtotime($carpooling['start_date']) <= time() ? 'Trajet terminé' : 'Complet' ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bouton retour -->
    <div style="margin-top: 32px; text-align: center;">
        <a href="javascript:history.back()" class="btn-contact">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Retour
        </a>
    </div>
</div>
