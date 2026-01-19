<?php
session_start();
require_once __DIR__ . '/model/BookingModel.php';

if (!isset($_SESSION['user_id'])) {
    die("Non connecté");
}

$model = new BookingModel();
$bookings = $model->getBookingsByUser($_SESSION['user_id']);

echo "<h1>Debug Réservations</h1>";
echo "<p>User ID: " . $_SESSION['user_id'] . "</p>";
echo "<p>Timestamp actuel: " . time() . "</p>";
echo "<p>Date actuelle: " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

echo "<h2>Total réservations: " . count($bookings) . "</h2>";

if (empty($bookings)) {
    echo "<p style='color: red;'>AUCUNE RÉSERVATION TROUVÉE</p>";
} else {
    foreach ($bookings as $booking) {
        echo "<div style='border: 2px solid blue; padding: 10px; margin: 10px 0;'>";
        echo "<h3>Booking ID: " . $booking['id'] . "</h3>";
        echo "<p><strong>Trajet:</strong> " . htmlspecialchars($booking['start_location']) . " → " . htmlspecialchars($booking['end_location']) . "</p>";
        echo "<p><strong>Date trajet:</strong> " . $booking['start_date'] . "</p>";
        echo "<p><strong>Timestamp trajet:</strong> " . strtotime($booking['start_date']) . "</p>";
        echo "<p><strong>Prix:</strong> " . $booking['price'] . " €</p>";
        echo "<p><strong>Places dispo:</strong> " . $booking['available_places'] . "</p>";
        
        $tripTime = strtotime($booking['start_date']);
        $currentTime = time();
        $diff = $tripTime - $currentTime;
        
        echo "<p style='color: " . ($diff > 0 ? 'green' : 'red') . ";'>";
        echo "<strong>Status:</strong> ";
        if ($diff > 0) {
            echo "À VENIR (dans " . round($diff / 3600, 1) . " heures)";
        } else {
            echo "EXPIRÉ (il y a " . round(abs($diff) / 3600, 1) . " heures)";
        }
        echo "</p>";
        
        echo "<hr style='margin: 10px 0;'>";
        echo "<p><strong>Comparaison:</strong></p>";
        echo "<p>strtotime(start_date) = " . $tripTime . "</p>";
        echo "<p>time() = " . $currentTime . "</p>";
        echo "<p>Différence = " . $diff . " secondes</p>";
        echo "<p>Test: strtotime(start_date) > time() = " . ($tripTime > $currentTime ? 'TRUE (À venir)' : 'FALSE (Expiré)') . "</p>";
        
        echo "</div>";
    }
}

echo "<hr>";
echo "<h2>Test requête SQL directe</h2>";
require_once __DIR__ . '/model/Database.php';
$db = Database::getDb();
$stmt = $db->prepare("SELECT b.id, b.carpooling_id, c.start_date FROM bookings b JOIN carpoolings c ON b.carpooling_id = c.id WHERE b.booker_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$directResults = $stmt->fetchAll();
echo "<p>Résultats directs: " . count($directResults) . "</p>";
foreach ($directResults as $row) {
    echo "<p>Booking {$row['id']}: Carpooling {$row['carpooling_id']} - Date: {$row['start_date']}</p>";
}
?>
