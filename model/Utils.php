<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("./Database.php");

// --- Connexion unique à la base de données ---
if (Database::$db == null) {
    // Vérifie bien le nom exact de ta base : "covoiturage" ou "carshare"
    Database::instanciateDb("covoiturage", "localhost", "root", "admin");
}

// --- Routage des actions via $_GET["need"] ---
if (isset($_GET["need"])) {
    $action = $_GET["need"];
    switch ($action) {
        case "fetchCities":
            if (isset($_GET["query"])) {
                $strQuery = $_GET["query"];
                fetchCities($strQuery);
            }
            break;

        default:
            // Aucune action correspondante
            http_response_code(400);
            echo json_encode(["error" => "Action inconnue"]);
            break;
    }
}

// --- Fonction pour récupérer les villes ---
function fetchCities($strQuery)
{
    try {
        $query = Database::$db->prepare("
            SELECT * 
            FROM location 
            WHERE name LIKE :search 
            LIMIT 10
        ");

        // Protection contre l’injection SQL
        $query->execute([
            ':search' => "%{$strQuery}%"
        ]);

        // Réponse en JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
