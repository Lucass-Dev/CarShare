<?php
    class SearchPageView {
        public function display_search_bar($start_name, $start_id, $end_name, $end_id, $start_date, $requested_seats) {
            ?>
                <div class="search-bar-container">
                    <form id="search-form" method="GET" action="" class="search-form">
                        <input type="hidden" name="action" value="display_search" />
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

                        <div class="field">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" required value=<?php echo ($start_date != null && $start_date != "") ? $start_date : ""?>>
                        </div>

                        <div class="field">
                            <label for="seats">Available seats</label>
                            <input type="number" id="seats" name="seats" min="1" max="10" required value="<?php echo ($requested_seats != null && $requested_seats != "") ? $requested_seats : 1?>">
                        </div>

                        <div class="actions">
                            <button type="submit">Search</button>
                        </div>
                    </form>
                </div>

            <?php
        }

        public function display_search_results(array $carpoolings){
            ?>
            <div class="search-result-container">
                <?php
                if (empty($carpoolings)) {
                    echo "<p>Aucun résultat ne correspond à votre recherche.</p>";
                } else {
                    foreach ($carpoolings as $carpooling) {
                        ?>
                            <div class="search-result-card">
                                <h3><?php echo htmlspecialchars($carpooling['start_name'] . " to " . $carpooling['end_name']); ?></h3>
                                <div class="trip-info">
                                    <p>Date: <?php echo htmlspecialchars($carpooling['start_date']); ?></p>
                                    <p>Available Seats: <?php echo htmlspecialchars($carpooling['available_places']); ?></p>
                                    <p>Voyage proposé par : <a href="&action=user_info"><?php echo htmlspecialchars($carpooling['provider_name'])?></a></p>
                                    <a href="&action=carpooling_details">Voir plus ></a>
                                </div>
                            </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }

        public function display_search_filters(){
            require_once("./view/components/search_filters.html");
        }
    }
?>