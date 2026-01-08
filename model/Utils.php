<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . "/Database.php");

if (Database::getDb() == null) {
    $db = Database::getDb();
}

class Utils {
    /**
     * Convert timestamp to human-readable "time ago" format
     */
    public static function timeAgo($datetime) {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return "À l'instant";
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return "Il y a " . $minutes . " minute" . ($minutes > 1 ? "s" : "");
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "Il y a " . $hours . " heure" . ($hours > 1 ? "s" : "");
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return "Il y a " . $days . " jour" . ($days > 1 ? "s" : "");
        } elseif ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return "Il y a " . $weeks . " semaine" . ($weeks > 1 ? "s" : "");
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return "Il y a " . $months . " mois";
        } else {
            $years = floor($diff / 31536000);
            return "Il y a " . $years . " an" . ($years > 1 ? "s" : "");
        }
    }
}

if(isset($_GET["need"])){
    $action = $_GET["need"];
    switch($action){
        case "fetchCities":
            if(isset($_GET["query"])){
                $strQuery = $_GET["query"];
                fetchCities($strQuery);
            }
            break;
        default:
            break;
    }
}

function fetchCities($strQuery){
    // Use prepared statement to prevent SQL injection
    $query = Database::getDb()->prepare("SELECT * FROM location WHERE name LIKE ? LIMIT 10");
    $searchTerm = '%' . $strQuery . '%';
    $query->execute(array($searchTerm));
    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($query->fetchAll(PDO::FETCH_ASSOC)));
}
?>
