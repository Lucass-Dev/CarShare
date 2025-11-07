<div class="search-bar-container">
    <form id="search-form" method="GET" action="/search" class="search-form">
        <div class="field">
            <label for="start_place">Start place</label>
            <input type="text" id="start_place" name="start_place" placeholder="City or address" required>
        </div>

        <div class="field">
            <label for="end_place">End place</label>
            <input type="text" id="end_place" name="end_place" placeholder="City or address" required>
        </div>

        <div class="field">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="field">
            <label for="seats">Available seats</label>
            <input type="number" id="seats" name="seats" min="1" max="10" value="1" required>
        </div>

        <div class="actions">
            <button type="submit">Search</button>
        </div>
    </form>
</div>

<?php
    class SearchPageView {
        static function render_data($array){
            for ($i = 0; $i < sizeof($array); $i++) {
                echo $array[$i]."<br>";
            }
        }
    }
?>