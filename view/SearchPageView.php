<?php
    class SearchPageView {
        public function display_search_bar(){
            require_once("./view/components/search_bar.html");
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
                            <h3><?php echo htmlspecialchars($carpooling['start_id'] . " to " . $carpooling['end_id']); ?></h3>
                            <p>Date: <?php echo htmlspecialchars($carpooling['start_date']); ?></p>
                            <p>Available Seats: <?php echo htmlspecialchars($carpooling['available_places']); ?></p>
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