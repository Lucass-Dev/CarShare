<?php
class TripDetailsView {
    public function display($carpooling, $provider, $isLoggedIn, $canBook = true, $bookingMessage = '') {
        $startLat = $carpooling['start_latitude'] ?? 0;
        $startLng = $carpooling['start_longitude'] ?? 0;
        $endLat = $carpooling['end_latitude'] ?? 0;
        $endLng = $carpooling['end_longitude'] ?? 0;
        
        // Créer l'URL Google Maps avec itinéraire
        $mapsUrl = "https://www.google.com/maps/embed/v1/directions";
        $mapsUrl .= "?key=" . API_MAPS;
        $mapsUrl .= "&origin=" . urlencode($carpooling['start_location']);
        $mapsUrl .= "&destination=" . urlencode($carpooling['end_location']);
        $mapsUrl .= "&mode=driving";
        $mapsUrl .= "&language=fr";
        
        // URL Google Maps pour ouverture externe
        $externalMapsUrl = "https://www.google.com/maps/dir/" . urlencode($carpooling['start_location']) . "/" . urlencode($carpooling['end_location']);
        
        $providerName = htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']);
        $providerPhoto = !empty($provider['profile_picture']) 
            ? asset('uploads/profile_pictures/' . $provider['profile_picture']) 
            : asset('img/default-avatar.png');
        
        $rating = $provider['global_rating'] ? round($provider['global_rating'], 1) : 'N/A';
        $ratingCount = $provider['rating_count'] ?? 0;
        ?>
        
        <div class="trip-details-container">
            <!-- Header avec titre et bouton retour -->
            <div class="trip-header">
                <a href="javascript:history.back()" class="back-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Retour
                </a>
                <h1>Détails du trajet</h1>
            </div>

            <!-- Message de notification (si réservation, etc.) -->
            <?php if (!empty($bookingMessage)): ?>
            <div class="notification-message <?php echo $canBook ? 'success' : 'info'; ?>">
                <?php echo htmlspecialchars($bookingMessage); ?>
            </div>
            <?php endif; ?>

            <!-- Layout principal : Info à gauche, Carte à droite -->
            <div class="trip-main-layout">
                
                <!-- Colonne gauche : Informations du trajet -->
                <div class="trip-info-column">
                    
                    <!-- Card Itinéraire -->
                    <div class="info-card route-card">
                        <h2 class="card-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="6" r="2" stroke="#8f9bff" stroke-width="2"/>
                                <circle cx="12" cy="18" r="2" stroke="#8f9bff" stroke-width="2"/>
                                <path d="M12 8V16" stroke="#8f9bff" stroke-width="2" stroke-dasharray="2 2"/>
                            </svg>
                            Itinéraire
                        </h2>
                        <div class="route-details">
                            <div class="route-point start">
                                <div class="point-icon">📍</div>
                                <div class="point-info">
                                    <span class="point-label">Départ</span>
                                    <span class="point-location"><?php echo htmlspecialchars($carpooling['start_location']); ?></span>
                                    <span class="point-datetime">
                                        <?php echo date('d/m/Y', strtotime($carpooling['start_date'])); ?> à 
                                        <?php echo date('H:i', strtotime($carpooling['start_date'])); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="route-separator">
                                <div class="route-line"></div>
                                <div class="route-distance">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M3 12h18M3 12l5-5M3 12l5 5" stroke="#a9b2ff" stroke-width="2"/>
                                    </svg>
                                    <?php 
                                    if (isset($carpooling['distance'])) {
                                        echo round($carpooling['distance']) . ' km';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="route-point end">
                                <div class="point-icon">🏁</div>
                                <div class="point-info">
                                    <span class="point-label">Arrivée</span>
                                    <span class="point-location"><?php echo htmlspecialchars($carpooling['end_location']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Conducteur -->
                    <div class="info-card driver-card">
                        <h2 class="card-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="8" r="4" stroke="#8f9bff" stroke-width="2"/>
                                <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" stroke="#8f9bff" stroke-width="2"/>
                            </svg>
                            Conducteur
                        </h2>
                        <div class="driver-info">
                            <div class="driver-profile">
                                <img src="<?php echo $providerPhoto; ?>" alt="Photo de <?php echo $providerName; ?>" class="driver-avatar">
                                <div class="driver-details">
                                    <h3 class="driver-name"><?php echo $providerName; ?></h3>
                                    <div class="driver-rating">
                                        <span class="rating-stars">
                                            <?php 
                                            $fullStars = floor($provider['global_rating'] ?? 0);
                                            for ($i = 0; $i < 5; $i++) {
                                                echo $i < $fullStars ? '⭐' : '☆';
                                            }
                                            ?>
                                        </span>
                                        <span class="rating-value"><?php echo $rating; ?></span>
                                        <span class="rating-count">(<?php echo $ratingCount; ?> avis)</span>
                                    </div>
                                    <a href="<?php echo url('index.php?action=user_profile&id=' . $carpooling['provider_id']); ?>" class="view-profile-btn">
                                        Voir le profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Détails pratiques -->
                    <div class="info-card details-card">
                        <h2 class="card-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="9" stroke="#8f9bff" stroke-width="2"/>
                                <path d="M12 7v5l3 3" stroke="#8f9bff" stroke-width="2"/>
                            </svg>
                            Informations pratiques
                        </h2>
                        <div class="practical-details">
                            <div class="detail-item">
                                <span class="detail-icon">💺</span>
                                <div class="detail-content">
                                    <span class="detail-label">Places disponibles</span>
                                    <span class="detail-value"><?php echo $carpooling['available_places']; ?> place<?php echo $carpooling['available_places'] > 1 ? 's' : ''; ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($carpooling['car_brand']) || !empty($carpooling['car_model'])): ?>
                            <div class="detail-item">
                                <span class="detail-icon">🚗</span>
                                <div class="detail-content">
                                    <span class="detail-label">Véhicule</span>
                                    <span class="detail-value">
                                        <?php echo htmlspecialchars($carpooling['car_brand'] . ' ' . $carpooling['car_model']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($carpooling['comment'])): ?>
                            <div class="detail-item full-width">
                                <span class="detail-icon">💬</span>
                                <div class="detail-content">
                                    <span class="detail-label">Commentaire du conducteur</span>
                                    <span class="detail-value"><?php echo nl2br(htmlspecialchars($carpooling['comment'])); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Prix et réservation -->
                    <div class="info-card booking-card">
                        <div class="price-section">
                            <div class="price-label">Prix par passager</div>
                            <div class="price-value"><?php echo number_format($carpooling['price'], 2); ?> €</div>
                        </div>
                        
                        <?php if ($isLoggedIn): ?>
                            <?php if ($canBook): ?>
                                <a href="<?php echo url('index.php?action=payment&carpooling_id=' . $carpooling['id']); ?>" class="book-btn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 13l4 4L19 7" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    Réserver ce trajet
                                </a>
                            <?php else: ?>
                                <button class="book-btn disabled" disabled>
                                    Réservation non disponible
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo url('index.php?action=login&return_url=' . urlencode('?action=trip_details&id=' . $carpooling['id'])); ?>" class="book-btn">
                                Connectez-vous pour réserver
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Colonne droite : Carte Google Maps -->
                <div class="trip-map-column">
                    <div class="map-container">
                        <div class="map-header">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="#8f9bff" stroke-width="2"/>
                                    <circle cx="12" cy="10" r="3" stroke="#8f9bff" stroke-width="2"/>
                                </svg>
                                Itinéraire sur la carte
                            </h3>
                            <a href="<?php echo $externalMapsUrl; ?>" target="_blank" class="open-maps-btn" title="Ouvrir dans Google Maps">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </a>
                        </div>
                        <div class="map-embed">
                            <iframe 
                                id="trip-map"
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0"
                                referrerpolicy="no-referrer-when-downgrade"
                                src="<?php echo $mapsUrl; ?>"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="map-footer">
                            <span class="map-legend">
                                <span class="legend-item">
                                    <span class="legend-dot start"></span> Départ
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot end"></span> Arrivée
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
}
?>
