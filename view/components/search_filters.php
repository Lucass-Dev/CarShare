<div class="search-filters-container">
    <div class="sort-by-filters filter-group">
        <h3>Trier par</h3>

        <input form="search-form" type="checkbox" id="sort_by_price_checkbox" name="sort_by_price">
        <label for="sort_by_price_checkbox">Prix</label><br>

        <input form="search-form" type="checkbox" id="sort_by_date_checkbox" name="sort_by_date">
        <label for="sort_by_date_checkbox">Date</label><br>

        <input form="search-form" type="checkbox" id="sort_by_seats_checkbox" name="sort_by_seats">
        <label for="sort_by_seats_checkbox">Places</label><br>

        <input form="search-form" type="checkbox" id="sort_by_rating_checkbox" name="sort_by_rating">
        <label for="sort_by_rating_checkbox">Note</label><br>
    </div>

    <div class="date-filters filter-group">
        <h3>Horaires</h3>
        <input form="search-form" type="range" min="0" max="24" step="1" value="0" id="start_time_range" name="start_time">
        <label for="start_time_range">Heure de début</label><br>
    </div>

    <div class="user-filters filter-group">
        <h3>Utilisateurs</h3>

        <input form="search-form" type="checkbox" id="user_verified_checkbox" name="user_verified">
        <label for="user_verified_checkbox">Utilisateur vérifié</label><br>
    </div>

    <div class="contraintes-filters filter-group">
        <h3>Services</h3>

        <input form="search-form" type="checkbox" id="pets-checkbox" name="pets_allowed">
        <label for="pets-checkbox">Animaux autorisés</label><br>

        <input form="search-form" type="checkbox" id="smoker-checkbox" name="smoker_allowed">
        <label for="smoker-checkbox">Fumeurs autorisés</label><br>

        <input form="search-form" type="checkbox" id="luggage-checkbox" name="luggage_allowed">
        <label for="luggage-checkbox">Bagages autorisés</label><br>
    </div>
</div>
