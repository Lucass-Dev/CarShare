<?php
    class TripView{
        public static function display_trip_infos($details){
            ?>
            <!-- L'ancien CSS trip_infos.css n'est plus utilisé, remplacé par trip-details-enhanced.css -->
            <div>
                <h2>Détails du trajet</h2>

                <div class="trajet-info">
                    <div>
                    <h2><?php echo $details["start_name"]?> → <?php echo $details["end_name"]?></h2>
                    <span><?php echo $details["start_date"]?></span>
                    </div>
                </div>

                <div class="card">
                    <div class="left">
                    <img src="./assets/img/avatar.jpg" alt="Photo du conducteur">
                    <a href="?controller=user&uid=<?php echo $details["provider_id"]?>"><strong><?php echo $details["provider_name"]?></strong></a>
                    <span><?php echo $details["global_rating"]?> ⭐</span>
                    </div>

                    <div class="right">
                    <table>
                        <tr><td><strong>Départ</strong></td><td>Lieu : <?php echo $details["start_name"]?></td><td>Date : <?php echo explode(" ", $details["start_date"])[0]?></td><td>Heure : <?php echo explode(" ", $details["start_date"])[1]?></td></tr>
                        <tr><td><strong>Arrivée</strong></td><td>Lieu : <?php echo $details["end_name"]?></td><td>Date : Insérer calcul</td><td>Heure : Insérer calcul</td></tr>
                    </table>

                    <div class="details-bottom">
                        <div>
                        <!-- Les informations sur bagages/fumeurs/animaux ne sont pas disponibles dans la base de données -->
                        </div>
                        <div>
                        <span class="price"><?php echo $details["price"]?> € TTC</span><br>
                        <br>
                        <a class="btn-reserver" href="?controller=trip&action=payment&trip_id=<?php echo $details["trip_id"]?>">Réserver</a>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            <?php
        }

        public static function display_trip_payment($details){
            ?>
            <link rel="stylesheet" href="./assets/styles/trip_payment.css">
            <div class="payment-card">
                <div class="payment-info">
                <div class="section-title">Récapitulatif du trajet</div>

                <table>
                    <tr><td><strong>Trajet :</strong></td><td>Paris → Marseille</td></tr>
                    <tr><td><strong>Date :</strong></td><td>24/10/2025</td></tr>
                    <tr><td><strong>Heure :</strong></td><td>8h00</td></tr>
                    <tr><td><strong>Conducteur :</strong></td><td>Noé (⭐ 4)</td></tr>
                </table>

                <div class="total-price">
                    Total : 15 € TTC
                </div>
                </div>

                <div class="payment-form">
                <div class="section-title">Informations de paiement</div>

                <div>
                    <label>Nom sur la carte</label>
                    <input type="text" placeholder="Ex : Marie Dupont">
                </div>

                <div>
                    <label>Numéro de carte</label>
                    <input type="text" placeholder="1234 5678 9012 3456">
                </div>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;">
                    <label>Expiration</label>
                    <input type="text" placeholder="MM/AA">
                    </div>
                    <div style="flex:1;">
                    <label>CVV</label>
                    <input type="text" placeholder="123">
                    </div>
                </div>

                <a class="btn-payer" href="./index.html">Payer maintenant</a>

                <div class="details-resume">
                    En cliquant sur "Payer maintenant", vous confirmez votre réservation.
                </div>
                </div>

            </div>
            <?php
        }
        public static function display_search_filters(){
            ?>
            <link rel="stylesheet" href="./assets/styles/searchPage.css">
            <div class="search-filters-container">
                <div class="sort-by-filters">
                    <h2>Trier par</h2>

                    <input form="search-form" type="radio" id="sort_by_price_radio" name="sort_by" value="price" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "price") echo 'checked'; ?>>
                    <label for="sort_by_price_radio">Prix</label><br>
                    <input form="search-form" type="radio" id="sort_by_date" name="sort_by" value="date" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "date") echo 'checked'; ?>>
                    <label for="sort_by_date">Date de départ</label><br>
                    <input form="search-form" type="radio" id="sort_by_seats_radio" name="sort_by" value="seats" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "seats") echo 'checked'; ?>>
                    <label for="sort_by_seats_radio">Places</label><br>
                    <input form="search-form" type="radio" id="sort_by_note_radio" name="sort_by" value="rating" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "rating") echo 'checked'; ?>>
                    <label for="sort_by_note_radio">Note</label><br>
                    <div class="order-type-container">
                        <h3>Ordre :</h3>
                        <div class="order-type-inputs">
                            <input form="search-form" type="radio" name="order_type" id="order_asc" value="asc" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == "asc") echo 'checked'; ?>>
                            <label for="order_asc">Le plus petit d'abord</label><br>
                            <input form="search-form" type="radio" name="order_type" id="order_desc" value="desc" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == "desc") echo 'checked'; ?>>
                            <label for="order_desc">Le plus grand d'abord</label><br>
                        </div>
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
                    </svg>
                    <input form="search-form" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_before']) ? htmlspecialchars($_GET['start_time_range_before']) : '0' ?>" id="start_time_range_before" name="start_time_range_before" >
                    <label for="start_time_range_before">Partir plus tôt</label>
                </div>

                <div class="user-filters">
                    <h2>Utilisateurs</h2>

                    <input form="search-form" type="checkbox" id="user_verified_checkbox" name="is_verified_user" <?php if (isset($_GET['is_verified_user'])) echo 'checked'; ?>>
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

        public static function display_search_results(array $carpoolings){
            ?>
            <div class="search-result-container">
                <?php
                if (empty($carpoolings)) {
                    echo "<p>Aucun résultat ne correspond à votre recherche.</p>";
                } else {
                    foreach ($carpoolings as $carpooling) {
                        ?>
                            <div class="search-result-card">
                                
                                <div class="trip-info">
                                    <div class="top">
                                        <div class="resume-card">
                                            <img src="./assets/img/avatar.jpg" alt="user_photo">
                                            <div class="resume-card-details">
                                                <a href="&controller=user_info/<?php echo $carpooling['provider_id']; ?>">
                                                    <?php echo htmlspecialchars($carpooling['provider_name']); ?>
                                                </a>
                                                <span>(<?php echo $carpooling['global_rating'] ?> ⭐)</span>
                                            </div>
                                        </div>

                                        <div class="trip-road">
                                            <span class="location start"><?php echo $carpooling['start_name']; ?></span>

                                            <div class="road">
                                                <div class="line"></div>
                                                <div class="dot start-dot"></div>
                                                <div class="dot end-dot"></div>
                                            </div>

                                            <span class="location end"><?php echo $carpooling['end_name']; ?></span>
                                        </div>

                                    </div>

                                    <div class="trip-details">
                                        <p>Date: <?php echo htmlspecialchars($carpooling['start_date']); ?></p>
                                        <p>Places: <?php echo htmlspecialchars($carpooling['available_places']); ?></p>
                                    
                                        <P>Prix : <span><?php echo $carpooling["price"];?> €</span></P>
                                        <a href="?controller=trip&action=details&trip_id=<?php echo $carpooling['id'];?>">Voir plus ></a>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }

        public static function display_search_bar($start_name, $start_id, $end_name, $end_id, $start_date, $start_hour, $requested_seats) {
            ?>
                <link rel="stylesheet" href="./assets/styles/searchPage.css">
                <script src="./script/searchPage.js" defer></script>
                <div class="search-bar-container">
                    <form id="search-form" method="GET" action="?controller=trip" class="search-form">
                        <input type="hidden" name="controller" value="trip"/>
                        <input type="hidden" name="action" value="display_search"/>
                        <input type="hidden" id="form_start_input" name="form_start_input" value="<?php echo ($start_id != null && $start_id != "") ? $start_id : ""?>">
                        <input type="hidden" id="form_end_input" name="form_end_input" value="<?php echo ($end_id != null && $end_id != "") ? $end_id : ""?>">

                        <div class="field">
                            <label for="start_place">Start place</label>
                            <input type="text" id="start_place" placeholder="City or address" required value=<?php echo ($start_name != null && $start_name != "") ? $start_name : ""?>>

                            <div id="start-suggestion-box">

                            </div>
                        </div>

                        <div class="field">
                            <label for="end_place">End place</label>
                            <input type="text" id="end_place" placeholder="City or address" value=<?php echo ($end_name != null && $end_name != "") ? $end_name : ""?>>
                            <div id="end-suggestion-box">

                            </div>
                        </div>

                        <div class="field date-fields">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" required value=<?php echo ($start_date != null && $start_date != "") ? $start_date : ""?>>
                            <label for="hour"></label>
                            <input type="time" name="hour" id="hour" value="<?php echo ($start_hour != null && $start_hour != "") ? $start_hour : ""?>">
                        </div>

                        <div class="field">
                            <label for="seats">Available seats</label>
                            <input type="number" id="seats" name="seats" min="1" max="10" required value="<?php echo ($requested_seats != null && $requested_seats != "") ? $requested_seats : 1?>">
                        </div>

                        <div class="controllers">
                            <button type="submit">Search</button>
                        </div>
                    </form>
                </div>

            <?php
        }

        public static function display_publish_form(){
            $success = $success ?? false; 
            ?>
            <link rel="stylesheet" href="<?= asset('styles/trip-form-modern.css') ?>">
            <link rel="stylesheet" href="<?= asset('styles/create-trip-enhanced.css') ?>">
            <script src="<?= asset('js/security-validator.js') ?>"></script>

            <div class="trip-publish-container">
              <!-- Compact Header -->
              <div class="trip-compact-header">
                <div class="compact-header-badge">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                  </svg>
                  <span>Nouveau trajet</span>
                </div>
                <h1 class="compact-header-title">Publier un trajet</h1>
              </div>

              <?php if ($success): ?>
                <div class="alert alert-success">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                  </svg>
                  <span>Trajet créé avec succès ! Il est maintenant visible par tous les utilisateurs.</span>
                </div>
              <?php endif; ?>

              <?php 
              $errors = $_SESSION['trip_form_errors'] ?? [];
              $formData = $_SESSION['trip_form_data'] ?? [];
              if (!empty($errors)): 
              ?>
                <div class="alert alert-error">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                  </svg>
                  <div>
                    <strong>Erreurs détectées :</strong>
                    <ul>
                      <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              <?php 
                unset($_SESSION['trip_form_errors']);
              endif; 
              ?>

              <div class="trip-form-centered">

                <!-- Progress Steps -->
                <div class="progress-steps">
                  <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Itinéraire</div>
                  </div>
                  <div class="step-connector"></div>
                  <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Horaires & Prix</div>
                  </div>
                  <div class="step-connector"></div>
                  <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Options</div>
                  </div>
                </div>

                <form class="trip-form trip-form-modern" method="POST" action="<?= url('index.php?action=create_trip_submit') ?>" novalidate data-persist="true" data-persist-key="publish-trip-form">
                  
                  <!-- Conseil contextuel étape 1 -->
                  <div class="contextual-tip" data-step="1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5"/>
                    </svg>
                    <p><strong>Astuce :</strong> Soyez précis sur votre itinéraire pour attirer plus de passagers</p>
                  </div>

                  <!-- Section 1: Départ -->
                  <div class="form-section" data-section="1">
                    <div class="section-header">
                      <div class="section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="10" r="3"/>
                          <path d="M12 2a8 8 0 00-8 8.2C4 15.5 12 22 12 22s8-6.5 8-11.8A8 8 0 0012 2z"/>
                        </svg>
                      </div>
                      <div>
                        <h2>Point de départ</h2>
                        <p>Où commencera votre trajet ?</p>
                      </div>
                    </div>

                    <div class="form-grid">
                      <div class="form-field form-field-small">
                        <label for="dep-num">N° voie</label>
                        <input 
                          id="dep-num" 
                          name="dep-num"
                          class="form-input" 
                          placeholder="Ex: 10"
!                          value="<?= htmlspecialchars($formData['dep-num'] ?? '') ?>"
                        />
                      </div>

                      <div class="form-field">
                        <label for="dep-street">Rue</label>
                        <input 
                          id="dep-street" 
                          name="dep-street"
                          type="text"
                          class="form-input" 
                          placeholder="Ex: Rue de la République"
                          value="<?= htmlspecialchars($formData['dep-street'] ?? '') ?>"
                          pattern="^[a-zA-Z0-9À-ÿ\s\-'.,/]+$"
                          maxlength="150"
                          title="Format: Rue de la République, Avenue Victor Hugo"
                        />
                      </div>

                      <div class="form-field form-field-full">
                        <label for="dep-city">Ville de départ <span class="required">*</span></label>
                        <input 
                          id="dep-city" 
                          name="dep-city"
                          type="text"
                          class="form-input city-autocomplete" 
                          placeholder="Recherchez une ville ou code postal"
                          value="<?= htmlspecialchars($formData['dep-city'] ?? '') ?>"
                          autocomplete="off"
                          required
                          pattern="^[a-zA-Z0-9À-ÿ\s\-']+$"
                          maxlength="100"
                          minlength="2"
                          title="Ville ou code postal (lettres et chiffres autorisés)"
                        />
                        <div class="autocomplete-dropdown" id="dep-city-dropdown"></div>
                      </div>
                    </div>
                  </div>

                  <div class="section-divider" data-section="1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="5" x2="12" y2="19"/>
                      <polyline points="19 12 12 19 5 12"/>
                    </svg>
                  </div>

                  <!-- Section 2: Arrivée -->
                  <div class="form-section" data-section="1">
                    <div class="section-header">
                      <div class="section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                          <circle cx="12" cy="10" r="3"/>
                        </svg>
                      </div>
                      <div>
                        <h2>Point d'arrivée</h2>
                        <p>Où se terminera votre trajet ?</p>
                      </div>
                    </div>

                    <div class="form-grid">
                      <div class="form-field form-field-small">
                        <label for="arr-num">N° voie</label>
                        <input 
                          id="arr-num" 
                          name="arr-num"
                          type="text"
                          class="form-input" 
                          placeholder="Ex: 25"
                          value="<?= htmlspecialchars($formData['arr-num'] ?? '') ?>"
                          pattern="^[0-9]+\s*(bis|ter|quater|[A-Za-z])?\s*(-[0-9]+)?$"
                          maxlength="10"
                          inputmode="text"
                          title="Format: 25, 25bis, 25B ou 25-27 (pas de point ni virgule)"
                        />
                      </div>

                      <div class="form-field">
                        <label for="arr-street">Rue</label>
                        <input 
                          id="arr-street" 
                          name="arr-street"
                          type="text"
                          class="form-input" 
                          placeholder="Ex: Avenue des Champs-Élysées"
                          value="<?= htmlspecialchars($formData['arr-street'] ?? '') ?>"
                          pattern="^[a-zA-Z0-9À-ÿ\s\-'.,/]+$"
                          maxlength="150"
                          title="Format: Avenue Victor Hugo, Boulevard de la Liberté"
                        />
                      </div>

                      <div class="form-field form-field-full">
                        <label for="arr-city">Ville d'arrivée <span class="required">*</span></label>
                        <input 
                          id="arr-city" 
                          name="arr-city"
                          type="text"
                          class="form-input city-autocomplete" 
                          placeholder="Recherchez une ville ou code postal"
                          value="<?= htmlspecialchars($formData['arr-city'] ?? '') ?>"
                          autocomplete="off"
                          required
                          pattern="^[a-zA-Z0-9À-ÿ\s\-']+$"
                          maxlength="100"
                          minlength="2"
                          title="Ville ou code postal (lettres et chiffres autorisés)"
                        />
                        <div class="autocomplete-dropdown" id="arr-city-dropdown"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Conseil contextuel étape 2 -->
                  <div class="contextual-tip" data-step="2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="12" r="10"/>
                      <path d="M12 6v6l4 2"/>
                    </svg>
                    <p><strong>Astuce :</strong> Prix recommandé : 0,05-0,08€/km. Soyez flexible sur l'horaire pour plus de réservations</p>
                  </div>

                  <!-- Section 3: Date & Prix -->
                  <div class="form-section" data-section="2">
                    <div class="section-header">
                      <div class="section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                          <line x1="16" y1="2" x2="16" y2="6"/>
                          <line x1="8" y1="2" x2="8" y2="6"/>
                          <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                      </div>
                      <div>
                        <h2>Date et tarif</h2>
                        <p>Quand partez-vous et à quel prix ?</p>
                      </div>
                    </div>

                    <div class="form-grid">
                      <div class="form-field">
                        <label for="date">Date du trajet <span class="required">*</span></label>
                        <input 
                          id="date" 
                          name="date"
                          class="form-input" 
                          type="date"
                          value="<?= htmlspecialchars($formData['date'] ?? '') ?>"
                          required
                        />
                      </div>

                      <div class="form-field">
                        <label for="time">Heure de départ</label>
                        <input 
                          id="time" 
                          name="time"
                          class="form-input" 
                          type="time"
                          value="<?= htmlspecialchars($formData['time'] ?? '') ?>"
                        />
                      </div>

                      <div class="form-field">
                        <label for="price">Prix par passager</label>
                        <div class="input-with-icon">
                          <input 
                            id="price" 
                            name="price"
                            class="form-input" 
                            type="number"
                            step="0.01"
                            min="0"
                            max="250"
                            placeholder="0.00"
                            inputmode="decimal"
                            pattern="^\d+(\.\d{1,2})?$"
                            title="Format: 15.50 (2 décimales max)"
                            value="<?= htmlspecialchars($formData['price'] ?? '') ?>"
                          />
                          <span class="input-icon">€</span>
                        </div>
                        <small class="field-hint">Maximum 250€ (participation aux frais, 2 décimales max)</small>
                      </div>
                    </div>
                  </div>

                  <!-- Conseil contextuel étape 3 -->
                  <div class="contextual-tip" data-step="3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M9 12l2 2 4-4"/>
                      <circle cx="12" cy="12" r="10"/>
                    </svg>
                    <p><strong>Astuce :</strong> Plus vous acceptez d'options, plus vous élargissez votre audience de passagers</p>
                  </div>

                  <!-- Section 4: Options -->
                  <div class="form-section" data-section="3">
                    <div class="section-header">
                      <div class="section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <line x1="12" y1="16" x2="12" y2="12"/>
                          <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                      </div>
                      <div>
                        <h2>Préférences du trajet</h2>
                        <p>Personnalisez les conditions de votre covoiturage</p>
                      </div>
                    </div>

                    <div class="form-grid">
                      <div class="form-field">
                        <label for="places">Nombre de places disponibles <span class="required">*</span></label>
                        <select id="places" name="places" class="form-input" required>
                          <option value="">Choisir...</option>
                          <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= (isset($formData['places']) && $formData['places'] == $i) ? 'selected' : '' ?>>
                              <?= $i ?> place<?= $i > 1 ? 's' : '' ?>
                            </option>
                          <?php endfor; ?>
                        </select>
                      </div>

                      <div class="form-field form-field-full">
                        <label>Options de confort</label>
                        <div class="checkbox-group">
                          <label class="checkbox-label">
                            <input type="checkbox" name="animals" <?= isset($formData['animals']) ? 'checked' : '' ?>>
                            <span class="checkbox-icon">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 19l7-7 3 3-7 7-3-3z"/>
                                <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
                                <path d="M2 2l7.586 7.586"/>
                                <circle cx="11" cy="11" r="2"/>
                              </svg>
                            </span>
                            <span class="checkbox-text">Animaux acceptés</span>
                          </label>

                          <label class="checkbox-label">
                            <input type="checkbox" name="smoking" <?= isset($formData['smoking']) ? 'checked' : '' ?>>
                            <span class="checkbox-icon">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8c0-3.3-2.7-6-6-6"/>
                                <path d="M12 2C9.6 2 7.6 3.8 7.1 6.2"/>
                                <line x1="2" y1="16" x2="22" y2="16"/>
                                <line x1="2" y1="20" x2="22" y2="20"/>
                              </svg>
                            </span>
                            <span class="checkbox-text">Fumeur accepté</span>
                          </label>

                          <label class="checkbox-label">
                            <input type="checkbox" name="luggage" <?= isset($formData['luggage']) ? 'checked' : '' ?>>
                            <span class="checkbox-icon">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <polyline points="17 11 19 13 23 9"/>
                              </svg>
                            </span>
                            <span class="checkbox-text">Bagages volumineux possibles</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-actions">
                    <button type="button" class="btn btn-secondary btn-prev" style="display: none;">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                      </svg>
                      Précédent
                    </button>
                    
                    <button type="button" class="btn btn-primary btn-next">
                      <span>Suivant</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                      </svg>
                    </button>
                    
                    <button type="submit" class="btn btn-primary btn-submit" style="display: none;">
                      <span>Publier mon trajet</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                      </svg>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <script src="<?= asset('js/city-autocomplete-enhanced.js') ?>"></script>
            <script src="<?= asset('js/create-trip-enhanced.js') ?>"></script>
            <?php
        }

        /**
         * Display the edit trip form with pre-filled data
         */
        public static function display_edit_form($trip){
            ?>
            <link rel="stylesheet" href="./assets/styles/create-trip-enhanced.css">
            <?php 
            $success = isset($_GET['success']) && $_GET['success'] == 1;
            $hasError = isset($_GET['error']) && $_GET['error'] == 1;
            
            // Parse the trip date/time
            $startDateTime = new DateTime($trip['start_date']);
            $tripDate = $startDateTime->format('Y-m-d');
            $tripTime = $startDateTime->format('H:i');
            
            // Get form data from session or use trip data
            $errors = $_SESSION['trip_form_errors'] ?? [];
            $formData = $_SESSION['trip_form_data'] ?? [];
            
            // Use trip data as defaults if no form data
            $depCity = $formData['dep-city'] ?? $trip['start_location_name'];
            $arrCity = $formData['arr-city'] ?? $trip['end_location_name'];
            $date = $formData['date'] ?? $tripDate;
            $time = $formData['time'] ?? $tripTime;
            $price = $formData['price'] ?? $trip['price'];
            $places = $formData['places'] ?? $trip['available_places'];
            ?>
            <section class="hero">
            <div class="hero__overlay">
            <h1 class="hero__title">Modifier mon trajet</h1>

            <?php if ($success): ?>
                <div class="server-message server-message--success">
                    <div class="server-message__icon">✅</div>
                    <div class="server-message__content">
                        <strong>Trajet modifié avec succès !</strong>
                        Les modifications ont été enregistrées.
                    </div>
                </div>
            <?php endif; ?>

            <?php 
            if (!empty($errors)): 
            ?>
                <div class="server-message server-message--error">
                    <div class="server-message__icon">⚠️</div>
                    <div class="server-message__content">
                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php 
                // Clear errors after displaying
                unset($_SESSION['trip_form_errors']);
            endif; 
            ?>

            <form class="trip-form" method="POST" action="<?= url('index.php?action=edit_trip_submit') ?>" novalidate>
                <input type="hidden" name="trip_id" value="<?= htmlspecialchars($trip['id']) ?>">
                
                <div class="trip-form__row">
                <div class="form__group form__group--small">
                    <label class="form__label" for="dep-num">N° voie</label>
                    <input 
                    id="dep-num" 
                    name="dep-num"
                    class="form__input" 
                    placeholder="N° voie"
                    value="<?= htmlspecialchars($formData['dep-num'] ?? '') ?>"
                    />
                </div>

                <div class="form__group">
                    <label class="form__label" for="dep-street">Rue</label>
                    <input 
                    id="dep-street" 
                    name="dep-street"
                    class="form__input" 
                    placeholder="Rue"
                    value="<?= htmlspecialchars($formData['dep-street'] ?? '') ?>"
                    />
                </div>

                <div class="form__group">
                    <label class="form__label" for="dep-city">Ville de départ <span class="form__required">*</span></label>
                    <input 
                    id="dep-city" 
                    name="dep-city"
                    class="form__input city-autocomplete" 
                    placeholder="Ville (France)"
                    value="<?= htmlspecialchars($depCity) ?>"
                    autocomplete="off"
                    required
                    />
                    <div class="autocomplete-dropdown" id="dep-city-dropdown"></div>
                </div>
                </div>

                <div class="trip-form__row">
                <div class="form__group form__group--small">
                    <label class="form__label" for="arr-num">N° voie</label>
                    <input 
                    id="arr-num" 
                    name="arr-num"
                    class="form__input" 
                    placeholder="N° voie"
                    value="<?= htmlspecialchars($formData['arr-num'] ?? '') ?>"
                    />
                </div>

                <div class="form__group">
                    <label class="form__label" for="arr-street">Rue</label>
                    <input 
                    id="arr-street" 
                    name="arr-street"
                    class="form__input" 
                    placeholder="Rue"
                    value="<?= htmlspecialchars($formData['arr-street'] ?? '') ?>"
                    />
                </div>

                <div class="form__group">
                    <label class="form__label" for="arr-city">Ville d'arrivée <span class="form__required">*</span></label>
                    <input 
                    id="arr-city" 
                    name="arr-city"
                    class="form__input city-autocomplete" 
                    placeholder="Ville (France)"
                    value="<?= htmlspecialchars($arrCity) ?>"
                    autocomplete="off"
                    required
                    />
                    <div class="autocomplete-dropdown" id="arr-city-dropdown"></div>
                </div>
                </div>

                <div class="trip-form__row trip-form__row--compact">
                <div class="form__group form__group--small">
                    <label class="form__label" for="date">Date <span class="form__required">*</span></label>
                    <input 
                    id="date" 
                    name="date"
                    class="form__input" 
                    type="date"
                    value="<?= htmlspecialchars($date) ?>"
                    required
                    />
                </div>

                <div class="form__group form__group--small">
                    <label class="form__label" for="time">Heure</label>
                    <input 
                    id="time" 
                    name="time"
                    class="form__input" 
                    type="time"
                    value="<?= htmlspecialchars($time) ?>"
                    />
                </div>

                <div class="form__group form__group--small">
                    <label class="form__label" for="price">Prix (€)</label>
                    <input 
                    id="price" 
                    name="price"
                    class="form__input" 
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    value="<?= htmlspecialchars($price ?? '') ?>"
                    />
                </div>
                </div>

                <div class="trip-form__row trip-form__row--options">
                    <fieldset class="options">
                        <legend class="options__title">Options</legend>
                        
                        <div class="form__group form__group--small">
                            <label class="form__label" for="places">Nombre de place(s) <span class="form__required">*</span></label>
                            <select id="places" name="places" class="form__input" required>
                                <option value="0">0</option>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?= $i ?>" <?= ($places == $i) ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <label class="options__item"><input type="checkbox" name="animals" <?= isset($formData['animals']) ? 'checked' : '' ?>> Animaux</label>
                        <label class="options__item"><input type="checkbox" name="smoking" <?= isset($formData['smoking']) ? 'checked' : '' ?>> Fumeur</label>
                    </fieldset>

                    <div class="trip-form__actions trip-form__actions--side">
                        <a href="<?= url('index.php?action=my_trips') ?>" class="btn btn--outline btn--pill" style="text-decoration: none;">Annuler</a>
                        <button type="submit" class="btn btn--primary btn--pill">Enregistrer les modifications</button>
                    </div>
                </div>
                </div>
            </form>

            <!-- Datalist for location autocomplete -->
            <datalist id="locations-list">
                <?php 
                // Fetch locations for autocomplete
                require_once __DIR__ . '/../model/TripFormModel.php';
                $model = new TripFormModel();
                $locations = $model->getAllLocations();
                foreach ($locations as $location): 
                ?>
                <option value="<?= htmlspecialchars($location['name']) ?>">
                    <?= htmlspecialchars($location['postal_code']) ?>
                </option>
                <?php endforeach; ?>
            </datalist>
            </div>
        </section>
        <script src="./assets/js/create-trip-enhanced.js"></script>
            <?php
        }

        public static function display_rate_form($trip_infos, $driver){
            ?>
            <link rel="stylesheet" href="./assets/styles/rating.css">
            <main>
            <section class="container">
                <section class="rating">
                <h1 class="rating__title">Merci d'avoir choisi CarShare</h1>

                <?php if (isset($_GET['success'])): ?>
                    <p style="margin:12px 0; padding:10px; border-radius:8px; background:#d4edda; color:#155724; text-align:center;">
                    ✅ Merci pour votre avis !
                    </p>
                <?php elseif (isset($_GET['error'])): ?>
                    <?php 
                    $errorMsg = 'Une erreur est survenue';
                    if ($_GET['error'] === 'user_not_found') $errorMsg = 'Utilisateur non trouvé';
                    elseif ($_GET['error'] === 'carpooling_not_found') $errorMsg = 'Trajet non trouvé';
                    elseif ($_GET['error'] === 'save_failed') $errorMsg = 'Erreur lors de l\'enregistrement';
                    elseif ($_GET['error'] === 'self_rating') $errorMsg = 'Vous ne pouvez pas vous évaluer vous-même';
                    ?>
                    <p style="margin:12px 0; padding:10px; border-radius:8px; background:#f8d7da; color:#721c24; text-align:center;">
                    ❌ <?= htmlspecialchars($errorMsg) ?>
                    </p>
                <?php endif; ?>

                <div class="rating__card">
                    <div class="rating__card-inner">
                    <div class="rating__profile">
                        <div class="avatar avatar--large" aria-hidden="true">
                        <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="36" cy="36" r="36" fill="#9fc0ff"/>
                            <circle cx="36" cy="28" r="13" fill="#fff"/>
                            <path d="M14 58c8-10 30-10 44 0" fill="#fff"/>
                        </svg>
                        </div>
                        <div class="rating__profile-meta">
                        <div class="rating__name"><?= htmlspecialchars($driver['name']) ?></div>
                        <div class="rating__avg">Moy. <span class="rating__avg-value"><?= htmlspecialchars((string)$driver['avg']) ?></span></div>
                        <div class="rating__avg" style="display:flex; gap:8px; color:#4a5568; font-size:14px;">
                            <span><?= htmlspecialchars((string)$driver['trips']) ?> trajets</span>
                            <span>•</span>
                            <span><?= htmlspecialchars((string)$driver['reviews']) ?> avis</span>
                        </div>
                        </div>
                    </div>

                    <div class="rating__form">
                        <form method="POST" action="index.php?controller=trip&action=rating_submit">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($driver['id']) ?>">
                        <?php if (!empty($driver['carpooling_id'])): ?>
                        <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($driver['carpooling_id']) ?>">
                        <?php endif; ?>

                        <label class="form__label" for="comment">Laissez un commentaire</label>
                        <textarea
                            id="comment"
                            name="comment"
                            class="form__textarea"
                            placeholder="Comment s'est passé votre voyage avec <?= htmlspecialchars($driver['name']) ?> ?"
                        ></textarea>

                        <div style="margin-top:10px;">
                            <label class="form__label" for="stars">Note</label>
                            <select id="stars" name="stars" class="form__textarea" style="height:44px;">
                            <option value="5">★★★★★ (5)</option>
                            <option value="4" selected>★★★★☆ (4)</option>
                            <option value="3">★★★☆☆ (3)</option>
                            <option value="2">★★☆☆☆ (2)</option>
                            <option value="1">★☆☆☆☆ (1)</option>
                            </select>
                        </div>

                        <div class="rating__stars-row" style="margin-top:12px;">
                            <div class="stars stars--big" aria-hidden="true" id="star-display">
                            <span class="star star--on">★</span>
                            <span class="star star--on">★</span>
                            <span class="star star--on">★</span>
                            <span class="star star--on">★</span>
                            <span class="star">☆</span>
                            </div>

                            <button type="submit" class="btn btn--primary rating__submit">Donner un avis</button>
                        </div>
                        </form>
                    </div>
                    </div>

                    <div class="rating__footer-stars" aria-hidden="true">
                    <div class="stars stars--footer" id="footer-stars">
                        <?php 
                        $avgRating = is_numeric($driver['avg']) ? (int)round($driver['avg']) : 0;
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                        <span class="star <?= $i <= $avgRating ? 'star--on' : '' ?>">
                            <?= $i <= $avgRating ? '★' : '☆' ?>
                        </span>
                        <?php endfor; ?>
                    </div>
                    </div>
                </div>
                </section>
            </section>
            </main>

            <script src="./assets/js/rating-form.js"></script>
            <?php
        }

        public static function display_report_form($tripInfo, $userData) {
            ?>
            <link rel="stylesheet" href="./assets/styles/report-user.css">
            <main class="report-main">
                <section class="report-container">
                    <h1 class="report-title">Signaler un utilisateur</h1>

                    <?php if (isset($_GET['success'])): ?>
                        <p class="report-hint" style="color:#155724; background:#d4edda; padding:15px; border-radius:8px; margin:15px 0;">
                            ✅ Signalement reçu ! Merci, votre signalement a bien été transmis à l'équipe CarShare.
                        </p>
                    <?php elseif (isset($_GET['error'])): ?>
                        <?php 
                        $errorMsg = 'Une erreur est survenue';
                        if ($_GET['error'] === 'user_not_found') $errorMsg = 'Utilisateur non trouvé';
                        elseif ($_GET['error'] === 'carpooling_not_found') $errorMsg = 'Trajet non trouvé';
                        elseif ($_GET['error'] === 'save_failed') $errorMsg = 'Erreur lors de l\'enregistrement';
                        elseif ($_GET['error'] === 'empty_description') $errorMsg = 'La description est obligatoire';
                        elseif ($_GET['error'] === 'empty_reason') $errorMsg = 'Le motif est obligatoire';
                        elseif ($_GET['error'] === 'self_reporting') $errorMsg = 'Vous ne pouvez pas vous signaler vous-même';
                        ?>
                        <p style="margin:12px 0; padding:15px; border-radius:8px; background:#f8d7da; color:#721c24;">
                            ❌ <?= htmlspecialchars($errorMsg) ?>
                        </p>
                    <?php endif; ?>

                    <!-- Résumé du voyage -->
                    <div class="profile-card" style="margin-bottom:12px;">
                        <div class="profile-info">
                            <div class="profile-line">
                                <span class="label">Trajet :</span>
                                <span class="value"><?= htmlspecialchars($tripInfo['start']) ?> → <?= htmlspecialchars($tripInfo['end']) ?></span>
                            </div>
                            <div class="profile-line">
                                <span class="label">Date :</span>
                                <span class="value"><?= htmlspecialchars($tripInfo['date']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé du profil -->
                    <div class="profile-card">
                        <div class="profile-avatar" aria-hidden="true">
                            <span><?= strtoupper(substr($userData['name'], 0, 1)) ?></span>
                        </div>

                        <div class="profile-info">
                            <div class="profile-line">
                                <span class="label">Utilisateur :</span>
                                <span class="value"><?= htmlspecialchars($userData['name']) ?></span>
                            </div>
                            <div class="profile-line">
                                <span class="label">Note moyenne :</span>
                                <span class="value"><?= htmlspecialchars($userData['avg']) ?></span>
                            </div>
                            <div class="profile-line">
                                <span class="label">Avis reçus :</span>
                                <span class="value"><?= htmlspecialchars($userData['reviews']) ?></span>
                            </div>
                            <div class="profile-line">
                                <span class="label">Nombre de trajets :</span>
                                <span class="value"><?= htmlspecialchars($userData['count']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire -->
                    <form method="POST" action="index.php?controller=trip&action=signalement_submit" class="report-form">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userData['id']) ?>">
                        <?php if (!empty($userData['carpooling_id'])): ?>
                        <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($userData['carpooling_id']) ?>">
                        <?php endif; ?>

                        <div class="form-row">
                            <label for="reason" class="form-label">Motif du signalement</label>
                            <select id="reason" name="reason" class="form-select" required>
                                <option value="">Sélectionnez un motif</option>
                                <option value="behavior">Comportement inapproprié</option>
                                <option value="security">Problème de sécurité</option>
                                <option value="payment">Problème de paiement</option>
                                <option value="vehicle">Problème avec le véhicule</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="description" class="form-label">
                                Décrivez le problème <span class="required">*</span>
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                class="form-textarea"
                                required
                                placeholder="Expliquez ce qu'il s'est passé de manière précise et factuelle."
                            ></textarea>
                        </div>

                        <p class="report-hint">
                            ⚠ Les signalements abusifs peuvent entraîner des sanctions sur votre compte.
                        </p>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                Envoyer le signalement
                            </button>
                        </div>
                    </form>
                </section>
            </main>

            <script src="./assets/js/signalement-form.js"></script>
            <?php
        }
    }
?>