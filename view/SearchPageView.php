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
                                'remaining_places' => $carpooling['remaining_places'],
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
                                                <span><?php echo htmlspecialchars($carpooling['remaining_places']); ?> disponible<?php echo $carpooling['remaining_places'] > 1 ? 's' : ''; ?></span>
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
            </div>
            <?php
        }

        public function display_search_filters(){
            ?>
            <div class="search-filters-container">
                <div class="sort-by-filters">
                    <h2>Trier par</h2>
                    <div class="filter-group">
                        <div class="filter-option">
                            <input type="radio" id="sort-price" name="sort" value="price">
                            <label for="sort-price">Prix</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-date" name="sort" value="date">
                            <label for="sort-date">Date de départ</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-places" name="sort" value="places">
                            <label for="sort-places">Places</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-rating" name="sort" value="rating">
                            <label for="sort-rating">Note</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="filter-group-title">Ordre :</span>
                        <div class="filter-option">
                            <input type="radio" id="order-asc" name="order" value="asc">
                            <label for="order-asc">Le plus petit d'abord</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="order-desc" name="order" value="desc">
                            <label for="order-desc">Le plus grand d'abord</label>
                        </div>
                    </div>
                    <button class="remove-filters-btn" id="remove-sort-filters">Enlever les filtres</button>
                </div>

                <div class="date-filters">
                    <h2>Horaires</h2>
                    
                    <div class="filter-group">
                        <span class="filter-group-title">Partir plus tard</span>
                        <div class="range-wrapper">
                            <input form="search-form" class="date-input" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_after']) ? htmlspecialchars($_GET['start_time_range_after']) : '0' ?>" id="start_time_range_after" name="start_time_range_after">
                            <span class="range-value" id="after-value">0h</span>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="filter-group-title">Partir plus tôt</span>
                        <div class="range-wrapper">
                            <input form="search-form" class="date-input" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_before']) ? htmlspecialchars($_GET['start_time_range_before']) : '0' ?>" id="start_time_range_before" name="start_time_range_before">
                            <span class="range-value" id="before-value">0h</span>
                        </div>
                    </div>

                    <button class="remove-filters-btn" id="remove-date-filters">Enlever les filtres</button>
                </div>

                <div class="user-filters">
                    <h2>Utilisateurs</h2>
                    <div class="filter-group">
                        <div class="filter-option">
                            <input form="search-form" class="user-input" type="checkbox" id="user_verified_checkbox" name="is_verified_user" <?php if (isset($_GET['is_verified_user'])) echo 'checked'; ?>>
                            <label for="user_verified_checkbox">Utilisateur vérifié</label>
                        </div>
                    </div>
                </div>

                <div class="contraintes-filters">
                    <h2>Services</h2>
                    <div class="filter-group">
                        <div class="filter-option">
                            <input form="search-form" class="constraints-input" type="checkbox" id="pets-checkbox" name="pets_allowed" <?php if (isset($_GET['pets_allowed'])) echo 'checked'; ?>>
                            <label for="pets-checkbox">🐾 Animaux autorisés</label>
                        </div>
                        <div class="filter-option">
                            <input form="search-form" class="constraints-input" type="checkbox" id="smoker-checkbox" name="smoker_allowed" <?php if (isset($_GET['smoker_allowed'])) echo 'checked'; ?>>
                            <label for="smoker-checkbox">🚬 Fumeurs autorisés</label>
                        </div>
                        <div class="filter-option">
                            <input form="search-form" class="constraints-input" type="checkbox" id="luggage-checkbox" name="luggage_allowed" <?php if (isset($_GET['luggage_allowed'])) echo 'checked'; ?>>
                            <label for="luggage-checkbox">🧳 Bagages autorisés</label>
                        </div>
                    </div>
                    <button class="remove-filters-btn" id="remove-constraints-filters">Enlever les filtres</button>
                </div>
            </div>
            <?php
        }
    }
?>