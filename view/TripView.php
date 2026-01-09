<?php
    class TripView{
        public static function display_trip_infos($details){
            ?>
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
                    <img src="<?php echo UPP_BASE_PATH.$details["profile_picture_path"]?>" alt="Photo du conducteur">
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
                        <?php echo $details["luggage_allowed"] ? "<p>Bagages autorisés</p>" : ""?>
                        <?php echo $details["smoker_allowed"] ? "<p>Fumeurs autorisés</p>" : ""?>
                        <?php echo $details["pets_allowed"] ? "<p>Animaux autorisés</p>" : ""?>
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
                                            <img src="<?php echo UPP_BASE_PATH.$carpooling['profile_picture_path'] ?>" alt="user_photo">
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
            ?>
            <section class="create-page-container">
                <div class="hero__overlay">
                <h1 class="hero__title">Publier un nouveau trajet</h1>

                <div class="success-msg">
                ✅ Trajet créé avec succès !
                </div>

                <?php 
                $errors = $_SESSION['trip_form_errors'] ?? [];
                $formData = $_SESSION['trip_form_data'] ?? [];
                if (!empty($errors)): 
                ?>
                    <div style="margin:20px 0; padding:15px; border-radius:8px; background:#f8d7da; color:#721c24;">
                    <strong>Erreurs :</strong>
                    <ul style="margin:10px 0 0 0; padding-left:20px;">
                        <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    </div>
                <?php 
                    // Clear errors after displaying
                    unset($_SESSION['trip_form_errors']);
                endif; 
                ?>

<<<<<<< Updated upstream
                <form class="trip-form" method="POST" action="/CarShare/index.php?action=create_trip_submit" novalidate>
                    <div class="trip-form__row">
                    <div class="form__group">
                        <label class="form__label" for="dep-city">Ville de départ <span class="form__required">*</span></label>
                        <input 
                        id="dep-city" 
                        name="dep-city"
                        class="form__input" 
                        placeholder="Ville (France)"
                        value="<?= htmlspecialchars($formData['dep-city'] ?? '') ?>"
                        list="locations-list"
                        required
                        />
=======
            <form class="trip-form" method="POST" action="/index.php?action=create_trip_submit" novalidate>
                <div class="trip-form__row">
                <div class="form__group">
                    <label class="form__label" for="dep-city">Ville de départ <span class="form__required">*</span></label>
                    <input 
                    id="dep-city" 
                    name="dep-city"
                    class="form__input" 
                    placeholder="Ville (France)"
                    value="<?= htmlspecialchars($formData['dep-city'] ?? '') ?>"
                    list="locations-list"
                    required
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
                </div>

                <div class="trip-form__row">
                <div class="form__group">
                    <label class="form__label" for="arr-city">Ville d'arrivée <span class="form__required">*</span></label>
                    <input 
                    id="arr-city" 
                    name="arr-city"
                    class="form__input" 
                    placeholder="Ville (France)"
                    value="<?= htmlspecialchars($formData['arr-city'] ?? '') ?>"
                    list="locations-list"
                    required
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
                </div>

                <div class="trip-form__row trip-form__row--compact">
                <div class="form__group form__group--small">
                    <label class="form__label" for="date">Date <span class="form__required">*</span></label>
                    <input 
                    id="date" 
                    name="date"
                    class="form__input" 
                    type="date"
                    value="<?= htmlspecialchars($formData['date'] ?? '') ?>"
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
                    value="<?= htmlspecialchars($formData['time'] ?? '') ?>"
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
                    value="<?= htmlspecialchars($formData['price'] ?? '') ?>"
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
                        <option value="<?= $i ?>" <?= (isset($formData['places']) && $formData['places'] == $i) ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                        <?php endfor; ?>
                    </select>
>>>>>>> Stashed changes
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
                    </div>

                    <div class="trip-form__row">
                    <div class="form__group">
                        <label class="form__label" for="arr-city">Ville d'arrivée <span class="form__required">*</span></label>
                        <input 
                        id="arr-city" 
                        name="arr-city"
                        class="form__input" 
                        placeholder="Ville (France)"
                        value="<?= htmlspecialchars($formData['arr-city'] ?? '') ?>"
                        list="locations-list"
                        required
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
                    </div>

                    <div class="trip-form__row trip-form__row--compact">
                    <div class="form__group form__group--small">
                        <label class="form__label" for="date">Date <span class="form__required">*</span></label>
                        <input 
                        id="date" 
                        name="date"
                        class="form__input" 
                        type="date"
                        value="<?= htmlspecialchars($formData['date'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($formData['time'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($formData['price'] ?? '') ?>"
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
                            <option value="<?= $i ?>" <?= (isset($formData['places']) && $formData['places'] == $i) ? 'selected' : '' ?>>
                                <?= $i ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                        </div>

                        <label class="options__item"><input type="checkbox" name="animals" <?= isset($formData['animals']) ? 'checked' : '' ?>> Animaux</label>
                        <label class="options__item"><input type="checkbox" name="smoking" <?= isset($formData['smoking']) ? 'checked' : '' ?>> Fumeur</label>
                    </fieldset>

                    <div class="trip-form__actions">
                        <button type="submit" class="btn btn--primary btn--pill">Publier</button>
                    </div>
                    </div>
                </form>

                <!-- Datalist for location autocomplete -->
                <datalist id="locations-list">
                    <?php foreach ($locations as $location): ?>
                    <option value="<?= htmlspecialchars($location['name']) ?>">
                        <?= htmlspecialchars($location['postal_code']) ?>
                    </option>
                    <?php endforeach; ?>
                </datalist>
                </div>
            </section>  
            <?php
        }

        public static function display_rate_form($trip_infos, $driver){
            ?>
            <section class="main container">
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
                        <form method="POST" action="/CarShare/index.php?action=rating_submit">
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
            <?php
        }

        public static function display_report_form() {
            ?>
            <section class="report-main">
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
                    <form method="POST" action="/CarShare/index.php?action=signalement_submit" class="report-form">
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

            <script src="/CarShare/assets/js/signalement-form.js"></script>
            <?php
        }

        public static function display_confirmation_page($carpooling) {
            ?>
            <link rel="stylesheet" href="./assets/styles/confirmation.css">
            <section class="confirmation-section">
                <div class="confirmation-card">
                    <h1>✅ Trajet réservé avec succès !</h1>
                    <p>Merci d'avoir choisi CarShare. Votre réservation a été confirmée.</p>
                    <span>Votre trajet : <?php echo $carpooling['start_name'] ?> → <?php echo $carpooling['end_name'] ?></span>
                    <a href="?controller=trip&action=display_search" class="btn-back">Retour à la recherche de trajets</a>
                </div>
            </section>
            <?php
        }
    }
?>