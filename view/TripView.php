<?php
class TripView{
    public static function display_trip_infos($details, $reserved, $reported){
        ?>
        <div class="trajet-wrapper">

            <h2 class="section-title">Détails du trajet</h2>

            <div class="trajet-info">
                <div class="trajet-header">
                    <h3><?php echo $details["start_name"] ?> → <?php echo $details["end_name"] ?></h3>
                    <span><?php echo $details["start_date"] ?></span>
                </div>
            </div>

            <div class="card">
                <div class="left">
                    <input type="text" hidden value="<?php echo $details["start_name"]?>" id="start_place">
                    <input type="text" hidden value="<?php echo $details["end_name"]?>" id="end_place">


                    <?php $profilePath = file_exists(UPP_BASE_PATH . $details["profile_picture_path"]) ? UPP_BASE_PATH . $details["profile_picture_path"] : "assets/images/default_pp.svg"; ?>
                    <img src="<?php echo $profilePath ?>"
                        alt="Photo du conducteur">

                    <a class="driver-name"
                        href="?controller=user&uid=<?php echo $details["provider_id"] ?>">
                        <?php echo $details["provider_name"] ?>
                    </a>

                    <span class="rating">
                        <?php echo $details["global_rating"] ?> ⭐
                    </span>
                </div>


                <div class="right">

                    <table class="trip-table">
                        <tr>
                            <td>Départ</td>
                            <td><?php echo $details["start_name"] ?></td>
                            <td><?php echo explode(" ", $details["start_date"])[0] ?></td>
                            <td><?php echo explode(" ", $details["start_date"])[1] ?></td>
                        </tr>
                        <tr>
                            <td>Arrivée</td>
                            <td><?php echo $details["end_name"] ?></td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                    </table>

                    <div class="details-bottom">

                        <div class="options">
                        <?php echo $details["luggage_allowed"] ? "<span>🧳 Bagages</span>" : "" ?>
                        <?php echo $details["smoker_allowed"]  ? "<span>🚬 Fumeurs</span>" : "" ?>
                        <?php echo $details["pets_allowed"]    ? "<span>🐾 Animaux</span>" : "" ?>
                        </div>

                        <div class="action">
                            <span class="price">
                                <?php echo $details["price"] ?> €
                            </span>

                            <?php
                                if ($details["status"] == "0" && !$reserved) {
                                    ?>
                                        <a class="btn-reserver"
                                            href="?controller=trip&action=payment&trip_id=<?php echo $details["trip_id"] ?>">
                                            Réserver
                                        </a>
                                    <?php
                                }else if($_SESSION["user_id"] !== $details["provider_id"] && !$reported){
                                    ?>
                                    <a class="btn-signaler"
                                        href="?controller=trip&action=report&trip_id=<?php echo $details["trip_id"] ?>">
                                        Signaler
                                    </a>
                                    <?php
                                }else if ($reported == true) {
                                    ?>
                                    <a class="btn-signaler"">
                                        Trajet signalé
                                    </a>
                                    <?php
                                }

                            ?>
                        </div>
                    </div>
                </div>
                
            </div>
        <?php
        if ($details["status"] == "0") {
            ?>
            <iframe class="publish-card map-preview-details" id="map-preview-link" src="" style="margin-left: auto;margin-right: auto;">    
            </iframe>
            <?php
        }
    }

    public static function display_trip_payment($trip){
        ?>
        <div class="payment-card">
            <div class="payment-info">
                <div class="section-title">Récapitulatif du trajet</div>

                <table>
                    <tr>
                        <td><strong>Trajet :</strong></td>
                        <td><?= htmlspecialchars($trip['start_name']) ?> → <?= htmlspecialchars($trip['end_name']) ?></td>
                    </tr>

                    <tr>
                        <td><strong>Date :</strong></td>
                        <td><?= date('d/m/Y', strtotime($trip['start_date'])) ?></td>
                    </tr>

                    <tr>
                        <td><strong>Heure :</strong></td>
                        <td><?= date('H\hi', strtotime($trip['start_date'])) ?></td>
                    </tr>

                    <tr>
                        <td><strong>Conducteur :</strong></td>
                        <td>
                            <?= htmlspecialchars($trip['provider_name']) ?>
                            (⭐ <?= $trip['global_rating'] ?>)
                        </td>
                    </tr>
                </table>

                <div class="total-price">
                    Total : <?= number_format($trip['price'], 2, ',', ' ') ?> € TTC
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

                <a class="btn-payer" href="index.php?controller=trip&action=confirmation&trip_id=<?= $trip['trip_id'] ?>">
                    Payer maintenant
                </a>

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

                    <input form="search-form" class="sort-input" type="radio" id="sort_by_price_radio" name="sort_by" value="price" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "price") echo 'checked'; ?>>
                    <label for="sort_by_price_radio">Prix</label><br>
                    <input form="search-form" class="sort-input" type="radio" id="sort_by_date" name="sort_by" value="date" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "date") echo 'checked'; ?>>
                    <label for="sort_by_date">Date de départ</label><br>
                    <input form="search-form" class="sort-input" type="radio" id="sort_by_seats_radio" name="sort_by" value="seats" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "seats") echo 'checked'; ?>>
                    <label for="sort_by_seats_radio">Places</label><br>
                    <input form="search-form" class="sort-input" type="radio" id="sort_by_note_radio" name="sort_by" value="rating" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "rating") echo 'checked'; ?>>
                    <label for="sort_by_note_radio">Note</label><br>
                    <div class="order-type-container">
                        <h3>Ordre :</h3>
                        <div class="order-type-inputs">
                            <input form="search-form" class="sort-input" type="radio" name="order_type" id="order_asc" value="asc" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == "asc") echo 'checked'; ?>>
                            <label for="order_asc">Le plus petit d'abord</label><br>
                            <input form="search-form" class="sort-input" type="radio" name="order_type" id="order_desc" value="desc" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == "desc") echo 'checked'; ?>>
                            <label for="order_desc">Le plus grand d'abord</label><br>
                        </div>
                    </div>
                    <button class="remove-filters-btn" id="remove-sort-filters">Enlever les filtres</button>
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
                    <input form="search-form" class="date-input" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_after']) ? htmlspecialchars($_GET['start_time_range_after']) : '0' ?>" id="start_time_range_after" name="start_time_range_after" >
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
                    <div class="">
                        <input form="search-form" class="date-input" type="range" min="0" max="24" step="1" value="<?= isset($_GET['start_time_range_before']) ? htmlspecialchars($_GET['start_time_range_before']) : '0' ?>" id="start_time_range_before" name="start_time_range_before" >
                        <label for="start_time_range_before">Partir plus tôt</label>
                    </div>
                    <button class="remove-filters-btn" id="remove-date-filters">Enlever les filtres</button>
                </div>

                <div class="user-filters">
                    <h2>Utilisateurs</h2>

                    <input form="search-form" class="user-input" type="checkbox" id="user_verified_checkbox" name="is_verified_user" <?php if (isset($_GET['is_verified_user'])) echo 'checked'; ?>>
                    <label for="user_verified_checkbox">Utilisateur vérifié</label><br>
                </div>

                <div class="contraintes-filters">
                    <h2>Services</h2>
                    <input form="search-form" class="constraints-input" type="checkbox" id="pets-chechbox" name="pets_allowed" <?php if (isset($_GET['pets_allowed'])) echo 'checked'; ?>>
                    <label for="pets-chechbox">Animaux autorisés</label><br>
                    <input form="search-form" class="constraints-input" type="checkbox" id="smoker-chechbox" name="smoker_allowed" <?php if (isset($_GET['smoker_allowed'])) echo 'checked'; ?>>
                    <label for="smoker-chechbox">Fumeurs autorisés</label><br>
                    <input form="search-form" class="constraints-input" type="checkbox" id="luggage-checkbox" name="luggage_allowed" <?php if (isset($_GET['luggage_allowed'])) echo 'checked'; ?>>
                    <label for="luggage-checkbox">Bagages autorisés</label><br>
                    <button class="remove-filters-btn" id="remove-constraints-filters">Enlever les filtres</button>
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
                                        <?php $profilePath = file_exists(UPP_BASE_PATH . $carpooling["profile_picture_path"]) ? UPP_BASE_PATH . $carpooling["profile_picture_path"] : "assets/images/default_pp.svg"; ?>
                                        <img src="<?php echo $profilePath ?>" alt="user_photo">
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
                                    <p>Places: <?php echo htmlspecialchars($carpooling['remaining_places']); ?></p>
                                
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

    public static function display_publish_form($success){
        ?>
        <section class="create-trip-page">
            <div class="create-trip">
            <div class="publish-card publish-form">
                <div class="publish-header">
                <div>
                    <h2 class="publish-title">Publier un nouveau trajet</h2>
                    <div class="publish-sub">Remplissez les informations du trajet</div>
                </div>
                </div>

                <?php if($success): ?>
                <div class="form-success">Trajet publié avec succès.</div>
                <?php endif; ?>

                <form class="publish-form" method="POST" action="/index.php?controller=trip&action=publish" novalidate>
                    <input type="hidden" id="form_start_input" name="form_start_input">
                    <input type="hidden" id="form_end_input" name="form_end_input">
                <div class="form-grid cols-2">
                    <div class="form-row">
                        <label class="form-label" for="dep-city">Ville de départ <span class="form-hint">*</span></label>
                        <input id="start_place" class="input" placeholder="Ville (France)" value="<?php echo htmlspecialchars($formData['dep-city'] ?? '') ?>" list="locations-list" required>
                        <div id="start-suggestion-box"></div>
                </div>

                    <div class="form-row">
                    <label class="form-label" for="arr-city">Ville d'arrivée <span class="form-hint">*</span></label>
                    <input id="end_place" class="input" placeholder="Ville (France)" required>
                        <div id="end-suggestion-box"></div>
                        </div>
                </div>

                <div class="form-grid cols-3">
                    <div class="form-row">
                    <label class="form-label" for="date">Date <span class="form-hint">*</span></label>
                    <input id="date" name="date" class="input small" type="date" required>
                    </div>

                    <div class="form-row">
                    <label class="form-label" for="time">Heure</label>
                    <input id="time" name="time" class="input small" type="time" >
                    </div>

                    <div class="form-row">
                    <label class="form-label" for="seats">Places disponibles</label>
                    <input id="seats" name="seats" class="input small" type="number" min="1" max="10">
                    </div>
                </div>

                <div class="form-row">
                    <label class="form-label" for="price">Prix par passager (€)</label>
                    <input id="price" name="price" class="input" type="number" step="0.5">
                </div>

                <div class="form-row">
                    <label class="form-label">Services autorisés</label>
                    <div class="inline-row" style="gap:12px; align-items:center;">
                    <label class="toggle">
                        <input type="checkbox" name="luggage_allowed" value="1" <?php echo !empty($formData['luggage_allowed']) ? 'checked' : '' ?>>
                        <span class="help-inline">🧳 Bagages</span>
                    </label>

                    <label class="toggle">
                        <input type="checkbox" name="smoker_allowed" value="1" <?php echo !empty($formData['smoker_allowed']) ? 'checked' : '' ?>>
                        <span class="help-inline">🚬 Fumeurs</span>
                    </label>

                    <label class="toggle">
                        <input type="checkbox" name="pets_allowed" value="1" <?php echo !empty($formData['pets_allowed']) ? 'checked' : '' ?>>
                        <span class="help-inline">🐾 Animaux</span>
                    </label>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary"/>
                    <a href="/index.php?controller=profile&action=history" class="btn btn-secondary">Annuler</a>
                </div>
                </form>
            </div>

            <aside class="publish-sidebar">
                   <iframe class="publish-card map-preview" id="map-preview-link" src="">    
                    </iframe>


                <div class="publish-card">
                <h3 class="publish-title" style="font-size:16px">Conseils</h3>
                <p class="form-hint">Vérifiez l'heure et le lieu de départ exacts. Indiquez si vous acceptez des bagages.</p>
                </div>
            </aside>
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

    public static function display_report_form($userData, $result) {
        ?>
        <?php
        if ($result && $userData == null) {
            ?>
            <div class="success-message-container">
                <div class="success-message-card">
                    <div class="success-icon">✅</div>
                    <h2 class="success-title">Signalement envoyé</h2>
                    <p class="success-text">Merci ! Votre signalement a été reçu par l'équipe CarShare. Nous l'examinons dans les plus brefs délais.</p>
                    <a href="?controller=trip&action=search" class="btn btn-primary">Retour à la recherche</a>
                </div>
            </div>
        <?php
        } else {
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
                        <span><?php echo $userData->first_name ?></span>
                    </div>

                    <div class="profile-info">
                        <div class="profile-line">
                            <span class="label">Utilisateur :</span>
                            <span class="value"><?= htmlspecialchars($userData->first_name) ?></span>
                        </div>
                        <div class="profile-line">
                            <span class="label">Note moyenne :</span>
                            <span class="value"><?= htmlspecialchars($userData->global_rating) ?></span>
                        </div>
                        <div class="profile-line">
                            <span class="label">Avis reçus :</span>
                        </div>
                        <div class="profile-line">
                            <span class="label">Nombre de trajets :</span>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="/index.php?controller=trip&action=signalement_submit" class="report-form">
                    <input type="hidden" name="user_id" value="<?= $userData->id ?>">
                    <input type="hidden" name="carpooling_id" value="<?= $_GET['trip_id']?>">

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
    }

    public static function display_confirmation_page($carpooling, $status) {
        ?>
        <style>
            .confirmation-section {
                min-height: 80vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 20px;
                background: #ffffff;
                font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            .confirmation-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(74, 144, 226, 0.12);
                padding: 48px 40px;
                max-width: 600px;
                width: 100%;
                text-align: center;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .confirmation-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 6px;
                background: linear-gradient(90deg, #4a90e2 0%, #0b78d1 100%);
            }

            .confirmation-card:hover {
                box-shadow: 0 15px 50px rgba(74, 144, 226, 0.18);
                transform: translateY(-4px);
            }

            .confirmation-card h1 {
                color: #0b78d1;
                font-size: 32px;
                font-weight: 700;
                margin: 0 0 20px 0;
                line-height: 1.3;
            }

            .confirmation-card h1 br {
                display: none;
            }



            .confirmation-card p {
                color: #6b7280;
                font-size: 18px;
                line-height: 1.6;
                margin: 0 0 28px 0;
            }

            .confirmation-card span {
                display: inline-block;
                background: #c4ddf8;
                color: #0b78d1;
                padding: 14px 24px;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 32px;
                border: 2px solid rgba(74, 144, 226, 0.2);
            }

            .confirmation-actions {
                display: flex;
                gap: 16px;
                justify-content: center;
                flex-wrap: wrap;
                margin-top: 8px;
            }

            .btn-back, .btn-message {
                display: inline-block;
                padding: 16px 32px;
                border-radius: 10px;
                text-decoration: none;
                font-size: 16px;
                font-weight: 600;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-back {
                background: #ffffff;
                color: #4a90e2;
                border: 2px solid #4a90e2;
                box-shadow: 0 4px 12px rgba(74, 144, 226, 0.15);
            }

            .btn-message {
                background: #ffffff;
                color: #4a90e2;
                border: 2px solid #4a90e2;
                box-shadow: 0 4px 12px rgba(74, 144, 226, 0.15);
            }

            .btn-back::before, .btn-message::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.2);
                transition: left 0.5s ease;
            }

            .btn-back:hover::before, .btn-message:hover::before {
                left: 100%;
            }

            .btn-back:hover {
                background: #0b78d1;
                box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
                transform: translateY(-2px);
            }

            .btn-message:hover {
                background: #4a90e2;
                color: white;
                box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
                transform: translateY(-2px);
            }

            .btn-back:active, .btn-message:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
            }



            @media (max-width: 640px) {
                .confirmation-card {
                    padding: 36px 24px;
                }

                .confirmation-card h1 {
                    font-size: 26px;
                }

                .confirmation-card p {
                    font-size: 16px;
                }

                .confirmation-card span {
                    font-size: 14px;
                    padding: 12px 20px;
                }

                .confirmation-actions {
                    flex-direction: column;
                    gap: 12px;
                }

                .btn-back, .btn-message {
                    padding: 14px 28px;
                    font-size: 15px;
                    width: 100%;
                }
            }
        </style>
        <section class="confirmation-section">
            <div class="confirmation-card">
                <h1>✅ Trajet réservé avec succès !</h1>
                <p>Merci d'avoir choisi CarShare. <br> Un récapitulatif de votre trajet a été envoyé dans votre messagerie CarShare.</p>
                <span>Votre trajet : <?php echo $carpooling['start_name'] ?> → <?php echo $carpooling['end_name'] ?></span>
                <div class="confirmation-actions">
                    <a href="?controller=profile&action=mp" class="btn-message">Voir mes messages</a>
                    <a href="?controller=trip&action=search" class="btn-back">Retour à la recherche</a>
                </div>
            </div>
        </section>
        <?php
    }
}
?>