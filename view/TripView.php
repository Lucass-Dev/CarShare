<?php
    class TripView{
        public static function display_trip_infos(){
            ?>
            <div>
                <h1>Détails du trajet</h1>

                <div class="trajet-info">
                    <div>
                    <h2>Paris → Marseille</h2>
                    <span>24/10/2025</span>
                    </div>
                </div>

                <div class="card">
                    <div class="left">
                    <img src="assets/img/avatar.jpg" alt="Photo du conducteur">
                    <p><strong>Noé</strong><br>4⭐</p>
                    </div>

                    <div class="right">
                    <table>
                        <tr><td><strong>Départ</strong></td><td>Lieu : Paris</td><td>Date : 24/10/2025</td><td>Heure : 8h00</td></tr>
                        <tr><td><strong>Arrivée</strong></td><td>Lieu : Marseille</td><td>Date : 24/10/2025</td><td>Heure : 17h30</td></tr>
                    </table>

                    <div class="details-bottom">
                        <div>
                        <p>Animaux acceptés</p>
                        <p>1 place disponible</p>
                        <p>1 bagage inclu</p>
                        </div>
                        <div>
                        <span class="price">15 € TTC</span><br>
                        <br>
                        <a class="btn-reserver" href="./payment.html">Réserver</a>
                        </div>
                    </div>
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
                                        <a href="&controller=carpooling_details/<?php echo $carpooling['id'];?>">Voir plus ></a>
                                        
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
    }
?>