<?php
// URLs Google Maps
$mapsUrl = "https://www.google.com/maps/embed/v1/directions";
$mapsUrl .= "?key=" . API_MAPS;
$mapsUrl .= "&origin=" . urlencode($carpooling['start_location']);
$mapsUrl .= "&destination=" . urlencode($carpooling['end_location']);
$mapsUrl .= "&mode=driving&language=fr";

$externalMapsUrl = "https://www.google.com/maps/dir/" . urlencode($carpooling['start_location']) . "/" . urlencode($carpooling['end_location']);

// Format dates
$frenchDays = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$frenchMonths = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
$dayNum = date('w', strtotime($carpooling['start_date']));
$monthNum = date('n', strtotime($carpooling['start_date']));
$formattedDate = $frenchDays[$dayNum] . ' ' . date('j', strtotime($carpooling['start_date'])) . ' ' . $frenchMonths[$monthNum] . ' ' . date('Y', strtotime($carpooling['start_date']));
?>
<link rel="stylesheet" href="<?= asset('styles/trip-details-pro.css') ?>">

<div class="trip-page">
    <!-- En-tête simplifié -->
    <div class="trip-header">
        <a href="javascript:history.back()" class="back-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Retour
        </a>
        <div class="trip-header-route">
            <span class="city"><?= htmlspecialchars($carpooling['start_location']) ?></span>
            <svg class="arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14m-7-7l7 7-7 7"/>
            </svg>
            <span class="city"><?= htmlspecialchars($carpooling['end_location']) ?></span>
        </div>
        <div class="trip-header-date">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <?= $formattedDate ?> • <?= date('H:i', strtotime($carpooling['start_date'])) ?>
        </div>
    </div>

    <!-- Grid 2 colonnes -->
    <div class="trip-grid">
        <!-- Colonne gauche -->
        <div class="trip-content">
            <!-- Itinéraire -->
            <div class="card">
                <div class="card-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="10" r="3"/>
                        <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"/>
                    </svg>
                    <h2>Itinéraire</h2>
                </div>
                <div class="locations">
                    <div class="location">
                        <div class="location-marker start"></div>
                        <div class="location-text">
                            <div class="location-label">Départ</div>
                            <div class="location-name"><?= htmlspecialchars($carpooling['start_location']) ?></div>
                        </div>
                    </div>
                    <div class="location-line"></div>
                    <div class="location">
                        <div class="location-marker end"></div>
                        <div class="location-text">
                            <div class="location-label">Arrivée</div>
                            <div class="location-name"><?= htmlspecialchars($carpooling['end_location']) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails -->
            <div class="card">
                <div class="card-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <h2>Détails du trajet</h2>
                </div>
                <div class="details-list">
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value"><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Heure</span>
                        <span class="detail-value"><?= date('H:i', strtotime($carpooling['start_date'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Places disponibles</span>
                        <span class="detail-value badge-places">
                            <?= $carpooling['available_places'] ?> <?= $carpooling['available_places'] > 1 ? 'places' : 'place' ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Options -->
            <?php if (!empty($carpooling['pets_allowed']) || !empty($carpooling['smoker_allowed']) || !empty($carpooling['luggage_allowed'])): ?>
            <div class="card">
                <div class="card-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        <path d="M21 21v-2a4 4 0 0 0-3-3.85"/>
                    </svg>
                    <h2>Préférences</h2>
                </div>
                <div class="options">
                    <?php if (!empty($carpooling['pets_allowed'])): ?>
                    <span class="option-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="4" r="2"/>
                            <circle cx="18" cy="8" r="2"/>
                            <circle cx="20" cy="16" r="2"/>
                            <path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/>
                        </svg>
                        Animaux acceptés
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($carpooling['smoker_allowed'])): ?>
                    <span class="option-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 12H3M20 12h-1M6 8a5 5 0 0 1 6.5-4.8M21 14V10M18 14V10"/>
                        </svg>
                        Fumeur
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($carpooling['luggage_allowed'])): ?>
                    <span class="option-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="6" width="18" height="15" rx="2"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Bagages autorisés
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Prix -->
            <div class="card price-card">
                <div class="price-content">
                    <div class="price-info">
                        <div class="price-label">Prix par passager</div>
                        <div class="price-amount"><?= number_format($carpooling['price'], 2) ?> €</div>
                    </div>
                    <?php if (isset($_SESSION['user_id']) && $carpooling['provider_id'] == $_SESSION['user_id']): ?>
                        <button class="btn-book disabled" disabled>
                            Votre trajet
                        </button>
                    <?php elseif (strtotime($carpooling['start_date']) > time() && $carpooling['available_places'] > 0): ?>
                        <a href="<?= url('index.php?action=payment&carpooling_id=' . $carpooling['id']) ?>" class="btn-book">
                            Réserver ce trajet
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <button class="btn-book disabled" disabled>
                            <?= strtotime($carpooling['start_date']) <= time() ? 'Trajet terminé' : 'Complet' ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="trip-sidebar">
            <!-- Carte -->
            <div class="card map-card">
                <div class="map-header">
                    <h3>Itinéraire</h3>
                    <a href="<?= $externalMapsUrl ?>" target="_blank" class="map-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <polyline points="15 3 21 3 21 9"/>
                            <line x1="10" y1="14" x2="21" y2="3"/>
                        </svg>
                        Ouvrir
                    </a>
                </div>
                <div class="map-container">
                    <iframe 
                        src="<?= $mapsUrl ?>" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        style="border:0" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- Conducteur -->
            <div class="card driver-card">
                <div class="driver-header">
                    <h3>Conducteur</h3>
                </div>
                <div class="driver-content">
                    <img src="<?= asset('img/avatar.jpg') ?>" alt="Avatar" class="driver-avatar">
                    <div class="driver-info">
                        <div class="driver-name">
                            <?= htmlspecialchars($carpooling['first_name']) ?> 
                            <?= isset($carpooling['last_name']) ? strtoupper(substr($carpooling['last_name'], 0, 1)) . '.' : '' ?>
                        </div>
                        <div class="driver-rating">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <span><?= $carpooling['global_rating'] ? number_format($carpooling['global_rating'], 1) : 'Nouveau' ?></span>
                        </div>
                    </div>
                </div>
                <div class="driver-stats">
                    <div class="stat">
                        <div class="stat-value"><?= isset($carpooling['total_trips']) ? $carpooling['total_trips'] : '0' ?></div>
                        <div class="stat-label">Trajets</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <div class="stat-value"><?= $carpooling['global_rating'] ? number_format($carpooling['global_rating'], 1) : 'N/A' ?></div>
                        <div class="stat-label">Note</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
