<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Configuration CarShare</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        h1 { color: #2c3e50; }
        .test { padding: 15px; margin: 10px 0; border-radius: 8px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔍 Test de Configuration CarShare</h1>
    
    <?php
    require_once __DIR__ . '/config.php';
    
    echo '<div class="info test">';
    echo '<h2>📍 Environnement détecté</h2>';
    echo '<p><strong>Environnement:</strong> ' . ($isProduction ? '🌐 PRODUCTION' : '💻 LOCAL') . '</p>';
    echo '<p><strong>Hôte:</strong> ' . ($_SERVER['HTTP_HOST'] ?? 'N/A (CLI)') . '</p>';
    echo '<p><strong>Script:</strong> ' . ($_SERVER['SCRIPT_NAME'] ?? __FILE__) . '</p>';
    echo '</div>';
    
    echo '<h2>🔧 Configuration Chemins</h2>';
    echo '<table>';
    echo '<tr><th>Constante</th><th>Valeur</th></tr>';
    echo '<tr><td><code>BASE_PATH</code></td><td><code>' . BASE_PATH . '</code></td></tr>';
    echo '<tr><td><code>BASE_URL</code></td><td><code>' . BASE_URL . '</code></td></tr>';
    echo '<tr><td><code>PRODUCTION_URL</code></td><td><code>' . PRODUCTION_URL . '</code></td></tr>';
    echo '</table>';
    
    echo '<h2>🧪 Test des fonctions Helper</h2>';
    echo '<table>';
    echo '<tr><th>Fonction</th><th>Résultat</th></tr>';
    echo '<tr><td><code>url(\'index.php\')</code></td><td><code>' . url('index.php') . '</code></td></tr>';
    echo '<tr><td><code>url(\'index.php?action=home\')</code></td><td><code>' . url('index.php?action=home') . '</code></td></tr>';
    echo '<tr><td><code>asset(\'styles/main.css\')</code></td><td><code>' . asset('styles/main.css') . '</code></td></tr>';
    echo '<tr><td><code>asset(\'js/script.js\')</code></td><td><code>' . asset('js/script.js') . '</code></td></tr>';
    echo '</table>';
    
    echo '<h2>💾 Connexion Base de Données</h2>';
    echo '<table>';
    echo '<tr><th>Paramètre</th><th>Valeur</th></tr>';
    echo '<tr><td>Host</td><td><code>' . DB_HOST . '</code></td></tr>';
    echo '<tr><td>Port</td><td><code>' . DB_PORT . '</code></td></tr>';
    echo '<tr><td>Database</td><td><code>' . DB_NAME . '</code></td></tr>';
    echo '<tr><td>User</td><td><code>' . DB_USER . '</code></td></tr>';
    echo '<tr><td>SSL Mode</td><td><code>' . DB_SSL_MODE . '</code></td></tr>';
    echo '</table>';
    
    // Test connexion BDD
    try {
        require_once __DIR__ . '/model/Database.php';
        $db = Database::getInstance()->getConnection();
        
        echo '<div class="success test">';
        echo '<h3>✅ Connexion à la base de données réussie!</h3>';
        
        // Compter les tables
        $stmt = $db->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo '<p><strong>Tables trouvées:</strong> ' . count($tables) . '</p>';
        echo '<ul>';
        foreach ($tables as $table) {
            $countStmt = $db->query("SELECT COUNT(*) as count FROM `{$table}`");
            $count = $countStmt->fetch()['count'];
            echo "<li><code>{$table}</code>: {$count} enregistrements</li>";
        }
        echo '</ul>';
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="error test">';
        echo '<h3>❌ Erreur de connexion à la base de données</h3>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Vérifications:</strong></p>';
        echo '<ul>';
        echo '<li>Les credentials dans config.php sont-ils corrects?</li>';
        echo '<li>La base de données existe-t-elle?</li>';
        echo '<li>L\'utilisateur a-t-il les droits d\'accès?</li>';
        echo '</ul>';
        echo '</div>';
    }
    
    // Test des dossiers
    echo '<h2>📁 Vérification des Dossiers</h2>';
    $folders = [
        'assets/styles' => 'Styles CSS',
        'assets/js' => 'Scripts JavaScript',
        'assets/img' => 'Images',
        'model' => 'Modèles',
        'view' => 'Vues',
        'controller' => 'Contrôleurs',
        'uploads' => 'Uploads',
        'uploads/profile_pictures' => 'Photos de profil'
    ];
    
    echo '<table>';
    echo '<tr><th>Dossier</th><th>Description</th><th>Statut</th></tr>';
    foreach ($folders as $folder => $desc) {
        $exists = is_dir(__DIR__ . '/' . $folder);
        $writable = $exists && is_writable(__DIR__ . '/' . $folder);
        
        $status = $exists 
            ? ($writable ? '✅ OK (écriture autorisée)' : '⚠️ Existe (lecture seule)') 
            : '❌ Manquant';
        
        $class = $exists ? ($writable ? 'success' : 'info') : 'error';
        
        echo "<tr style='background: " . ($exists ? ($writable ? '#d4edda' : '#fff3cd') : '#f8d7da') . "'>";
        echo "<td><code>{$folder}/</code></td>";
        echo "<td>{$desc}</td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    echo '</table>';
    
    // Test des fichiers critiques
    echo '<h2>📄 Fichiers Critiques</h2>';
    $files = [
        'index.php' => 'Point d\'entrée',
        'config.php' => 'Configuration',
        '.htaccess' => 'Configuration Apache',
        'model/Database.php' => 'Connexion BDD',
        'model/Utils.php' => 'Utilitaires'
    ];
    
    echo '<table>';
    echo '<tr><th>Fichier</th><th>Description</th><th>Statut</th></tr>';
    foreach ($files as $file => $desc) {
        $exists = file_exists(__DIR__ . '/' . $file);
        $status = $exists ? '✅ Présent' : '❌ Manquant';
        $class = $exists ? 'success' : 'error';
        
        echo "<tr style='background: " . ($exists ? '#d4edda' : '#f8d7da') . "'>";
        echo "<td><code>{$file}</code></td>";
        echo "<td>{$desc}</td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    echo '</table>';
    
    // Recommandations
    echo '<div class="info test">';
    echo '<h2>💡 Recommandations</h2>';
    echo '<ul>';
    if (!$isProduction) {
        echo '<li>Vous êtes en LOCAL. Pour tester en production, uploadez sur AlwaysData.</li>';
    } else {
        echo '<li>✅ Environnement PRODUCTION détecté.</li>';
        echo '<li>🔒 Assurez-vous que HTTPS est activé (certificat SSL).</li>';
        echo '<li>📊 Activez Opcache dans le panneau AlwaysData pour de meilleures performances.</li>';
    }
    echo '<li>🔐 Supprimez ce fichier <code>test_config.php</code> en production pour la sécurité.</li>';
    echo '<li>📝 Consultez <code>DEPLOIEMENT_ALWAYSDATA.md</code> pour le guide complet.</li>';
    echo '</ul>';
    echo '</div>';
    ?>
    
    <footer style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e9ecef; text-align: center; color: #6c757d;">
        <p>CarShare - Test de Configuration</p>
        <p>Version: <?= date('Y-m-d H:i:s') ?></p>
    </footer>
</body>
</html>
