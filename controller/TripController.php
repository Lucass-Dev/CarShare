<?php
class TripController {
    public function render(){

        $action = "search";
        if (isset($_GET["action"]) && !empty($_GET["action"])) {
            $action = $_GET["action"];
        }

        switch ($action) {
            case 'search':
            case 'display_search':
                $this->display_search($action);
                break;
            case "publish":
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /index.php?controller=profile&action=login');
                    exit();
                }
                $success = false;
                if (sizeof($_POST) > 0) {
                    $success = TripModel::createTrip($_POST);
                }
                TripView::display_publish_form($success);
                break;
            case 'details':
                $this->display_trip_details();
                break;
            case 'payment':
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /index.php?controller=profile&action=login');
                    exit();
                }
                $trip = TripModel::getCarpoolingById($_GET["trip_id"]);
                $this->display_trip_payment();
                break;
            case "report":
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /index.php?controller=profile&action=login');
                    exit();
                }
                TripView::display_report_form();
                break;
            case "rate":
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /index.php?controller=profile&action=login');
                    exit();
                }
                $test = TripModel::getCarpoolingById($_GET['trip_id']);
                $driver = UserModel::getUserById($test['provider_id']);
                TripView::display_rate_form();
                break;
            case 'rating':
                $this->display_rating();
                break;
            case 'signalement':
                $this->display_signalement();
                break;
            case 'rating_submit':
                // Redirection vers RatingController pour le submit
                $ratingController = new RatingController();
                $ratingController->submit();
                break;
            case 'signalement_submit':
                // Redirection vers SignalementController pour le submit
                $signalementController = new SignalementController();
                $signalementController->submit();
                break;
            case 'confirmation':
                $carpooling = TripModel::getCarpoolingById($_GET['trip_id']);
                if (isset($carpooling["trip_id"]) && !TripModel::hasAlreadyBooked($_SESSION["user_id"], $carpooling["trip_id"])) {
                    $status = UserModel::book($carpooling, $_SESSION["user_id"]);
                    TripView::display_confirmation_page($carpooling, $status);
                }else {
                    echo "trajet déjà réservé";
                }
                
                break;
            default:
            echo "404";
            break;
        }
    }
    private function display_trip_payment(){
        if (isset($_GET['trip_id']) && !empty($_GET['trip_id'])){
            $details = [];
            $details = TripModel::getCarpoolingById($_GET['trip_id']);
            TripView::display_trip_payment($details);
        }
    }
    private function display_trip_details(){
        if (isset($_GET['trip_id']) && !empty($_GET['trip_id'])){
            $details = [];
            $details = TripModel::getCarpoolingById($_GET['trip_id']);
            TripView::display_trip_infos($details);
        }
    }
    private function display_search($action){
        $filters = array();
        $filters["pets_allowed"] = "";
        $filters["smoker_allowed"] = "";
        $filters["luggage_allowed"] = "";
        $filters["user_verified"] = "";
        $filters["start_time_range"] = "";
        $filters["is_verified_user"] = "";
        $start_name = null;
        $start_id = null;
        $end_name = null;
        $end_id = null;
        $requested_date = null;
        $requested_hour = null;
        $requested_seats = null;

        if (isset($_GET["form_start_input"]) && $_GET["form_start_input"] != "") {
            $start_id = $_GET["form_start_input"];
            
            $start_name = TripModel::getCityNameWithPostalCode($start_id);
        }
        if (isset($_GET["form_end_input"]) && $_GET["form_end_input"] != ""){
            $end_id = $_GET["form_end_input"];
            $end_name = TripModel::getCityNameWithPostalCode($end_id);
        }
        if (isset($_GET["date"]) && $_GET["date"] != ""){
            $requested_date = $_GET["date"];
        }
        if (isset($_GET["hour"]) && $_GET["hour"] != ""){
            $requested_hour = $_GET["hour"];
        }
        if (isset($_GET["seats"]) && $_GET["seats"] != ""){
            $requested_seats = $_GET["seats"];
        }
        

        TripView::display_search_bar($start_name, $start_id,$end_name, $end_id,$requested_date, $requested_hour, $requested_seats);

        if ($action == "display_search") {
            ?>
            <div class="search-page-container">
                <?php
                    TripView::display_search_filters();
                    if (isset($_GET['action']) && $_GET['action'] === 'display_search') {
                        $filters = array();
                        if (isset($_GET['pets_allowed'])) {
                            $filters['pets_allowed'] = true;
                        } else {
                            $filters['pets_allowed'] = false;
                        }
                        if (isset($_GET['smoker_allowed'])) {
                            $filters['smoker_allowed'] = true;
                        } else {
                            $filters['smoker_allowed'] = false;
                        }
                        if (isset($_GET['luggage_allowed'])) {
                            $filters['luggage_allowed'] = true;
                        } else {
                            $filters['luggage_allowed'] = false;
                        }
                        if (isset($_GET['user_is_verified'])) {
                            $filters['user_verified'] = true;
                        } else {
                            $filters['user_verified'] = false;
                        }
                        if (isset($_GET['start_time_range_before'])) {
                            $filters['start_time_range_before'] = $_GET['start_time_range_before'];
                        } else {
                            $filters['start_time_range_before'] = 1;
                        }
                        if (isset($_GET['start_time_range_after'])) {
                            $filters['start_time_range_after'] = $_GET['start_time_range_after'];
                        } else {
                            $filters['start_time_range_after'] = 1;
                        }
                        if (isset($_GET["sort_by"]) && $_GET["sort_by"] != ""){
                            $filters['sort_by'] = $_GET["sort_by"];
                        }
                        if (isset($_GET["order_type"]) && $_GET["order_type"] != ""){
                            $filters['order_type'] = $_GET["order_type"];
                        }
                        if (isset($_GET["is_verified_user"]) && $_GET["is_verified_user"] != ""){
                            $filters['is_verified_user'] = $_GET["is_verified_user"];
                        }
                        $carpoolings = TripModel::getCarpooling($start_id, $end_id, $requested_date, $requested_hour, $requested_seats, $filters);
                        TripView::display_search_results($carpoolings);
                    }
                ?>
            </div>
            <?php
        }
    }

}