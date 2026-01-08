<?php
/**
 * Test de la recherche - Debug
 */

require_once __DIR__ . '/model/Database.php';

echo "<h1>Test de Recherche CarShare</h1>";
echo "<style>
body { font-family: Arial; padding: 20px; }
.success { color: green; }
.error { color: red; }
.info { background: #e3f2fd; padding: 10px; margin: 10px 0; border-radius: 5px; }
pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
</style>";

$db = Database::getDb();

// Test 1 : Recherche d'utilisateurs
echo "<h2>Test 1 : Recherche d'utilisateurs avec 'A'</h2>";
$searchTerm = '%A%';
try {
    $stmt = $db->prepare("
        SELECT id, first_name, last_name, global_rating, car_brand, car_model
        FROM users
        WHERE CONCAT(first_name, ' ', last_name) LIKE ?
           OR email LIKE ?
        LIMIT 5
    ");
    $stmt->execute([$searchTerm, $searchTerm]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='success'>✅ Requête réussie ! " . count($users) . " utilisateurs trouvés</div>";
    echo "<pre>" . print_r($users, true) . "</pre>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
}

// Test 2 : Recherche de trajets
echo "<h2>Test 2 : Recherche de trajets avec 'Paris'</h2>";
$searchTerm = '%Paris%';
try {
    $stmt = $db->prepare("
        SELECT c.id, l1.name as start_location, l2.name as end_location, 
               c.start_date, c.price, c.available_places,
               u.first_name, u.last_name
        FROM carpoolings c
        JOIN users u ON c.provider_id = u.id
        LEFT JOIN location l1 ON c.start_id = l1.id
        LEFT JOIN location l2 ON c.end_id = l2.id
        WHERE (l1.name LIKE ? OR l2.name LIKE ?)
          AND c.start_date >= NOW()
          AND c.available_places > 0
        ORDER BY c.start_date ASC
        LIMIT 5
    ");
    $stmt->execute([$searchTerm, $searchTerm]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='success'>✅ Requête réussie ! " . count($trips) . " trajets trouvés</div>";
    echo "<pre>" . print_r($trips, true) . "</pre>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
}

// Test 3 : Vérifier la structure de la table location
echo "<h2>Test 3 : Structure de la table location</h2>";
try {
    $stmt = $db->query("SELECT * FROM location LIMIT 10");
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='success'>✅ Table location accessible ! " . count($locations) . " villes trouvées</div>";
    echo "<pre>" . print_r($locations, true) . "</pre>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
}

// Test 4 : Vérifier les utilisateurs commençant par A
echo "<h2>Test 4 : Tous les utilisateurs dont le prénom commence par A, E, ou T</h2>";
try {
    $stmt = $db->query("
        SELECT id, first_name, last_name, email, car_brand 
        FROM users 
        WHERE first_name LIKE 'A%' OR first_name LIKE 'E%' OR first_name LIKE 'T%'
        LIMIT 15
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='success'>✅ " . count($users) . " utilisateurs trouvés</div>";
    echo "<pre>" . print_r($users, true) . "</pre>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
}

echo "<hr>";
echo "<h2>Test de l'API de recherche</h2>";
echo "<div class='info'>";
echo "<p>Pour tester l'API directement :</p>";
echo "<p><a href='/CarShare/api/search.php?q=Alice' target='_blank'>Test : /CarShare/api/search.php?q=Alice</a></p>";
echo "<p><a href='/CarShare/api/search.php?q=Eva' target='_blank'>Test : /CarShare/api/search.php?q=Eva</a></p>";
echo "<p><a href='/CarShare/api/search.php?q=Tina' target='_blank'>Test : /CarShare/api/search.php?q=Tina</a></p>";
echo "</div>";
?>
