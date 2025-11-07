
<h1>Search Page</h1>

<?php
    class SearchPageView {
        static function render_data($array){
            for ($i = 0; $i < sizeof($array); $i++) {
                echo $array[$i]."<br>";
            }
        }
    }
?>