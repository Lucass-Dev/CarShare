<?php
    class SearchPageView {
        public function display_search_bar($start_name, $start_id, $end_name, $end_id, $start_date, $start_hour, $requested_seats) {
            ?>
                <div class="search-bar-container">
                    <h2>Rechercher un trajet</h2>
                    
                    <form id="search-form" method="GET" action="" class="search-form">
                        <input type="hidden" name="action" value="display_search" />
                        <input type="hidden" id="form_start_input" name="form_start_input" value="<?php echo ($start_id != null && $start_id != "") ? $start_id : ""?>">
                        <input type="hidden" id="form_end_input" name="form_end_input" value="<?php echo ($end_id != null && $end_id != "") ? $end_id : ""?>">

                        <div class="search-fields-row">
                            <div class="field">
                                <label for="start_place">Départ</label>
                                <input type="text" id="start_place" placeholder="Ville de départ" required value="<?php echo ($start_name != null && $start_name != "") ? htmlspecialchars($start_name) : ""?>">
                                <div id="start-suggestion-box" class="suggestion-box"></div>
                            </div>

                            <div class="field">
                                <label for="end_place">Arrivée</label>
                                <input type="text" id="end_place" placeholder="Ville d'arrivée" value="<?php echo ($end_name != null && $end_name != "") ? htmlspecialchars($end_name) : ""?>">
                                <div id="end-suggestion-box" class="suggestion-box"></div>
                            </div>

                            <div class="field">
                                <label for="date">Date</label>
                                <input type="date" id="date" name="date" required value="<?php echo ($start_date != null && $start_date != "") ? $start_date : ""?>">
                            </div>
                            
                            <div class="field">
                                <label for="hour">Heure</label>
                                <input type="time" name="hour" id="hour" value="<?php echo ($start_hour != null && $start_hour != "") ? $start_hour : ""?>">
                            </div>

                            <div class="field">
                                <label for="seats">Passagers</label>
                                <input type="number" id="seats" name="seats" min="1" max="10" placeholder="Nombre" required value="<?php echo ($requested_seats != null && $requested_seats != "") ? $requested_seats : 1?>">
                            </div>

                            <div class="actions">
                                <button type="submit" class="search-btn">Rechercher</button>
                            </div>
                        </div>
                    </form>
                </div>

            <?php
        }

        public function display_search_results(array $carpoolings){
            ?>
            <!-- Layout avec carte Maps intégrée -->
            <div class="search-results-with-map">
                
                <!-- Colonne gauche: Résultats de recherche -->
                <div class="search-result-container">
                    <?php
                    if (empty($carpoolings)) {
                        ?>
                        <div class="no-results">
                            <div class="no-results-icon">🔍</div>
                            <h3>Aucun trajet trouvé</h3>
                            <p>Aucun résultat ne correspond à votre recherche.</p>
                            <p>Essayez de modifier vos critères ou de rechercher à une autre date.</p>
                        </div>
                        <?php
                    } else {
                        echo '<div class="results-header">';
                        echo '<h2>' . count($carpoolings) . ' trajet' . (count($carpoolings) > 1 ? 's' : '') . ' disponible' . (count($carpoolings) > 1 ? 's' : '') . '</h2>';
                        echo '<button id="toggle-map-btn" class="toggle-map-btn" onclick="toggleMapView()">';
                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/></svg>';
                        echo '<span id="map-toggle-text">Afficher la carte</span>';
                        echo '</button>';
                        echo '</div>';
                        
                        // Stocker les données pour la carte Maps
                        $mapData = [];
                        
                        foreach ($carpoolings as $index => $carpooling) {
                            $providerName = htmlspecialchars($carpooling['provider_name']);
                            $providerId = htmlspecialchars($carpooling['provider_id']);
                            $tripId = htmlspecialchars($carpooling['id']);
                            
                            // Préparer les données pour la carte
                            $mapData[] = [
                                'id' => $tripId,
                                'start_name' => $carpooling['start_name'],
                                'end_name' => $carpooling['end_name'],
                                'start_lat' => $carpooling['start_latitude'] ?? 0,
                                'start_lng' => $carpooling['start_longitude'] ?? 0,
                                'end_lat' => $carpooling['end_latitude'] ?? 0,
                                'end_lng' => $carpooling['end_longitude'] ?? 0,
                                'price' => $carpooling['price'],
                                'available_places' => $carpooling['available_places']
                            ];
                            ?>
                                <div class="search-result-card" data-trip-id="<?php echo $tripId; ?>" 
                                     onmouseenter="highlightTripOnMap('<?php echo $tripId; ?>')"
                                     onmouseleave="unhighlightTripOnMap()">
                                    <div class="card-left">
                                        <div class="trip-route">
                                            <div class="route-point start">
                                                <span class="location"><?php echo htmlspecialchars($carpooling['start_name']); ?></span>
                                            </div>
                                            <div class="route-arrow">→</div>
                                            <div class="route-point end">
                                                <span class="location"><?php echo htmlspecialchars($carpooling['end_name']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-middle">
                                        <div class="trip-info">
                                            <div class="info-item">
                                                <span class="info-label">Date:</span>
                                                <span><?php echo date('d/m/Y à H:i', strtotime($carpooling['start_date'])); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Places:</span>
                                                <span><?php echo htmlspecialchars($carpooling['available_places']); ?> disponible<?php echo $carpooling['available_places'] > 1 ? 's' : ''; ?></span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Conducteur:</span>
                                                <a href="index.php?action=user_profile&id=<?php echo $providerId; ?>" class="provider-link">
                                                    <?php echo $providerName; ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-right">
                                        <div class="price">
                                            <?php echo number_format($carpooling['price'], 2); ?> €
                                        </div>
                                        <a href="index.php?action=trip_details&id=<?php echo $tripId; ?>" class="details-btn">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            <?php
                        }
                        
                        // Passer les données à JavaScript pour la carte
                        ?>
                        <script>
                            window.tripsMapData = <?php echo json_encode($mapData); ?>;
                        </script>
                        <?php
                    }
                    ?>
                </div>
                
                <!-- Colonne droite: Carte Google Maps (masquée par défaut sur mobile) -->
                <div class="search-map-container" id="search-map-container" style="display: none;">
                    <div class="map-header">
                        <h3>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="#8f9bff" stroke-width="2"/>
                                <circle cx="12" cy="10" r="3" stroke="#8f9bff" stroke-width="2"/>
                            </svg>
                            Carte des trajets
                        </h3>
                        <span class="map-info">Survolez une carte pour voir l'itinéraire</span>
                    </div>
                    <div id="search-trips-map" class="map-embed"></div>
                    <div class="map-legend">
                        <div class="legend-item">
                            <span class="legend-icon" style="background: #4CAF50;">📍</span>
                            <span>Départs</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-icon" style="background: #FF9800;">🏁</span>
                            <span>Arrivées</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        public function display_search_filters(){
            ?>
            <div class="search-filters-container">
                <div class="sort-by-filters">
                    <h2>Trier par</h2>

                    <input form="search-form" type="radio" id="sort_by_price_radio" name="sort_by" value="price" <?php if (isset($_GET['sort_by_price'])) echo 'checked'; ?>>
                    <label for="sort_by_price_radio">Prix</label><br>
                    <input form="search-form" type="radio" id="sort_by_date_radio" name="sort_by" value="date" <?php if (isset($_GET['sort_by_date'])) echo 'checked'; ?>>
                    <label for="sort_by_date_radio">Date</label><br>
                    <input form="search-form" type="radio" id="sort_by_seats_radio" name="sort_by" value="seats" <?php if (isset($_GET['sort_by_seats'])) echo 'checked'; ?>>
                    <label for="sort_by_seats_radio">Places</label><br>
                    <input form="search-form" type="radio" id="sort_by_rating_radio" name="sort_by" value="rating" <?php if (isset($_GET['sort_by_rating'])) echo 'checked'; ?>>
                    <label for="sort_by_rating_radio">Note</label><br>
                    <div>
                        <input form="search-form" type="radio" id="sort_order_radio" name="sort_by" value="rating" <?php if (isset($_GET['sort_by_rating'])) echo 'checked'; ?>>
                        <label for="sort_order_radio">Note</label><br>
                    </div>
                </div>

                <div class="date-filters">
                    <h2>Horaires</h2>
                    <svg viewBox="0 0 240 1" width="100%" height="15" style="padding-left: 2%;">
                        <g fill="#444">
                            <rect x="0" y="0" width="1" height="10" />
                            <rect x="10" y="0" width="1" height="10" />
                            <rect x="20" y="0" width="1" height="10" />
                            <rect x="30" y="0" width="1" height="10" />
                            <rect x="40" y="0" width="1" height="10" />
                            <rect x="50" y="0" width="1" height="10" />
                            <rect x="60" y="0" width="1" height="10" />
                            <rect x="70" y="0" width="1" height="10" />
                            <rect x="80" y="0" width="1" height="10" />
                            <rect x="90" y="0" width="1" height="10" />
                            <rect x="100" y="0" width="1" height="10" />
                            <rect x="110" y="0" width="1" height="10" />
                            <rect x="120" y="0" width="1" height="10" />
                            <rect x="130" y="0" width="1" height="10" />
                            <rect x="140" y="0" width="1" height="10" />
                            <rect x="150" y="0" width="1" height="10" />
                            <rect x="160" y="0" width="1" height="10" />
                            <rect x="170" y="0" width="1" height="10" />
                            <rect x="180" y="0" width="1" height="10" />
                            <rect x="190" y="0" width="1" height="10" />
                            <rect x="200" y="0" width="1" height="10" />
                            <rect x="210" y="0" width="1" height="10" />
                            <rect x="220" y="0" width="1" height="10" />
                            <rect x="230" y="0" width="1" height="10" />
                        </g>
                    </svg>
                    <input form="search-form" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_after']) ? htmlspecialchars($_GET['start_time_range_after']) : '0' ?>" id="start_time_range_after" name="start_time_range_after" >
                    <label for="start_time_range_after">Partir plus tard</label>
                    <br>
                    <br>
                    <svg viewBox="0 0 240 1" width="100%" height="15" style="padding-left: 2%;">
                        <g fill="#444">
                            <rect x="0" y="0" width="1" height="10" />
                            <rect x="10" y="0" width="1" height="10" />
                            <rect x="20" y="0" width="1" height="10" />
                            <rect x="30" y="0" width="1" height="10" />
                            <rect x="40" y="0" width="1" height="10" />
                            <rect x="50" y="0" width="1" height="10" />
                            <rect x="60" y="0" width="1" height="10" />
                            <rect x="70" y="0" width="1" height="10" />
                            <rect x="80" y="0" width="1" height="10" />
                            <rect x="90" y="0" width="1" height="10" />
                            <rect x="100" y="0" width="1" height="10" />
                            <rect x="110" y="0" width="1" height="10" />
                            <rect x="120" y="0" width="1" height="10" />
                            <rect x="130" y="0" width="1" height="10" />
                            <rect x="140" y="0" width="1" height="10" />
                            <rect x="150" y="0" width="1" height="10" />
                            <rect x="160" y="0" width="1" height="10" />
                            <rect x="170" y="0" width="1" height="10" />
                            <rect x="180" y="0" width="1" height="10" />
                            <rect x="190" y="0" width="1" height="10" />
                            <rect x="200" y="0" width="1" height="10" />
                            <rect x="210" y="0" width="1" height="10" />
                            <rect x="220" y="0" width="1" height="10" />
                            <rect x="230" y="0" width="1" height="10" />
                        </g>
                    </svg>                    <input form="search-form" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_before']) ? htmlspecialchars($_GET['start_time_range_before']) : '0' ?>" id="start_time_range_before" name="start_time_range_before" >
                    <label for="start_time_range_before">Partir plus tôt</label>
                </div>

                <div class="user-filters">
                    <h2>Utilisateurs</h2>

                    <input form="search-form" type="checkbox" id="user_verified_checkbox" name="user_is_verified" <?php if (isset($_GET['user_is_verified'])) echo 'checked'; ?>>
                    <label for="user_verified_checkbox">Utilisateur vérifié</label><br>
                </div>

                <div class="contraintes-filters">
                    <h2>Services</h2>

                    <input form="search-form" type="checkbox" id="pets-chechbox" name="pets_allowed" <?php if (isset($_GET['pets_allowed'])) echo 'checked'; ?>>
                    <label for="pets-chechbox">Animaux autorisés</label><br>
                    <input form="search-form" type="checkbox" id="smoker-chechbox" name="smoker_allowed" <?php if (isset($_GET['smoker_allowed'])) echo 'checked'; ?>>
                    <label for="smoker-chechbox">Fumeurs autorisés</label><br>
                    <input form="search-form" type="checkbox" id="luggage-checkbox" name="luggage_allowed" <?php if (isset($_GET['luggage_allowed'])) echo 'checked'; ?>>
                    <label for="luggage-checkbox">Bagages autorisés</label><br>
                </div>
            </div>
            <?php
        }
    }
?>