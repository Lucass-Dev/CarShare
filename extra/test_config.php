<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Database Connection - CarShare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .test-result {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚗 CarShare - Test de Configuration</h1>
        
        <?php
        require_once __DIR__ . '/model/Database.php';
        
        $allTestsPassed = true;
        
        // Test 1: Database Connection
        echo '<h2>1. Test de connexion à la base de données</h2>';
        try {
            $db = Database::getDb();
            echo '<div class="test-result success">✅ Connexion à la base de données réussie !</div>';
        } catch (Exception $e) {
            echo '<div class="test-result error">❌ Erreur de connexion : ' . htmlspecialchars($e->getMessage()) . '</div>';
            $allTestsPassed = false;
        }
        
        // Test 2: Check Tables
        if (isset($db)) {
            echo '<h2>2. Vérification des tables</h2>';
            $tables = ['users', 'location', 'carpoolings', 'bookings'];
            $missingTables = [];
            
            foreach ($tables as $table) {
                try {
                    $stmt = $db->query("SELECT 1 FROM $table LIMIT 1");
                    echo '<div class="test-result success">✅ Table <strong>' . $table . '</strong> existe</div>';
                } catch (PDOException $e) {
                    echo '<div class="test-result error">❌ Table <strong>' . $table . '</strong> manquante</div>';
                    $missingTables[] = $table;
                    $allTestsPassed = false;
                }
            }
            
            // Test 3: Check Locations
            echo '<h2>3. Vérification des données de localisation</h2>';
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM location");
                $result = $stmt->fetch();
                $locationCount = $result['count'];
                
                if ($locationCount > 0) {
                    echo '<div class="test-result success">✅ ' . $locationCount . ' localisation(s) trouvée(s)</div>';
                    
                    // Show some locations
                    $stmt = $db->query("SELECT * FROM location LIMIT 5");
                    $locations = $stmt->fetchAll();
                    
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Nom</th><th>Code Postal</th></tr>';
                    foreach ($locations as $loc) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($loc['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($loc['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($loc['postal_code']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="test-result error">❌ Aucune localisation trouvée. Veuillez exécuter le script init_locations.sql</div>';
                    $allTestsPassed = false;
                }
            } catch (PDOException $e) {
                echo '<div class="test-result error">❌ Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
                $allTestsPassed = false;
            }
            
            // Test 4: Check Users
            echo '<h2>4. Vérification des utilisateurs</h2>';
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM users");
                $result = $stmt->fetch();
                $userCount = $result['count'];
                
                echo '<div class="test-result info">ℹ️ ' . $userCount . ' utilisateur(s) enregistré(s)</div>';
                
                if ($userCount > 0) {
                    $stmt = $db->query("SELECT id, first_name, last_name, email, is_admin FROM users LIMIT 5");
                    $users = $stmt->fetchAll();
                    
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Nom</th><th>Email</th><th>Admin</th></tr>';
                    foreach ($users as $user) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($user['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                        echo '<td>' . ($user['is_admin'] ? '✓' : '✗') . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            } catch (PDOException $e) {
                echo '<div class="test-result error">❌ Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            
            // Test 5: PHP Version
            echo '<h2>5. Informations système</h2>';
            $phpVersion = phpversion();
            if (version_compare($phpVersion, '7.4.0', '>=')) {
                echo '<div class="test-result success">✅ Version PHP: ' . $phpVersion . '</div>';
            } else {
                echo '<div class="test-result error">❌ Version PHP: ' . $phpVersion . ' (minimum requis: 7.4.0)</div>';
                $allTestsPassed = false;
            }
            
            // Check PDO
            if (extension_loaded('pdo_mysql')) {
                echo '<div class="test-result success">✅ Extension PDO MySQL activée</div>';
            } else {
                echo '<div class="test-result error">❌ Extension PDO MySQL non trouvée</div>';
                $allTestsPassed = false;
            }
        }
        
        // Final Result
        echo '<h2>Résumé</h2>';
        if ($allTestsPassed) {
            echo '<div class="test-result success">';
            echo '<h3>🎉 Tous les tests sont passés avec succès !</h3>';
            echo '<p>Votre installation CarShare est correctement configurée.</p>';
            echo '</div>';
        } else {
            echo '<div class="test-result error">';
            echo '<h3>⚠️ Certains tests ont échoué</h3>';
            echo '<p>Veuillez corriger les erreurs ci-dessus avant de continuer.</p>';
            echo '</div>';
        }
        
        echo '<div style="margin-top: 30px;">';
        echo '<a href="index.php?action=home" class="btn">🏠 Aller à l\'accueil</a>';
        echo '<a href="SETUP.md" class="btn" style="background-color: #2196F3;">📖 Voir la documentation</a>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
