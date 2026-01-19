<?php
/**
 * Script pour exécuter carshare.sql sur la base Aiven distante
 */

// Configuration Aiven
$host = 'mysq-carshare-mailsacrifice14-49e2.k.aivencloud.com';
$port = '12919';
$dbname = 'defaultdb';
$user = 'avnadmin';
$pass = 'AVNS_XNovxzBfxwaL50YjpsJ';

echo "🔄 Connexion à Aiven Cloud MySQL...\n";
echo "Host: {$host}:{$port}\n";
echo "Database: {$dbname}\n\n";

try {
    // Connexion PDO avec SSL
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => null
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✅ Connexion établie!\n\n";
    
    // Lecture du fichier SQL
    $sqlFile = __DIR__ . '/sql/carshare.sql';
    echo "📄 Lecture de: {$sqlFile}\n";
    
    if (!file_exists($sqlFile)) {
        die("❌ Fichier SQL introuvable!\n");
    }
    
    $sql = file_get_contents($sqlFile);
    $fileSize = round(strlen($sql) / 1024, 2);
    echo "📦 Taille: {$fileSize} KB\n\n";
    
    // Désactiver les vérifications temporairement
    echo "🔧 Configuration de la session...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdo->exec("SET UNIQUE_CHECKS=0");
    $pdo->exec("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");
    $pdo->exec("SET SESSION sql_require_primary_key=0"); // Aiven Cloud fix
    echo "✅ Session configurée\n\n";
    
    // Découper le SQL en requêtes individuelles
    echo "⚙️ Parsing du fichier SQL...\n";
    
    // Nettoyer le SQL
    $sql = preg_replace('/\/\*!40\d{3}.*?\*\/;?/s', '', $sql); // Enlever directives MySQL
    $sql = preg_replace('/--[^\n]*\n/m', "\n", $sql); // Enlever commentaires --
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Enlever commentaires /* */
    
    // Découper par point-virgule mais préserver ceux dans les chaînes
    $queries = [];
    $currentQuery = '';
    $inString = false;
    $stringChar = '';
    
    for ($i = 0; $i < strlen($sql); $i++) {
        $char = $sql[$i];
        
        // Détection des guillemets
        if (($char === "'" || $char === '"') && ($i === 0 || $sql[$i-1] !== '\\')) {
            if (!$inString) {
                $inString = true;
                $stringChar = $char;
            } elseif ($char === $stringChar) {
                $inString = false;
            }
        }
        
        // Découpage sur point-virgule hors chaîne
        if ($char === ';' && !$inString) {
            $currentQuery = trim($currentQuery);
            if (!empty($currentQuery) && 
                !preg_match('/^(SET|LOCK|UNLOCK)/i', $currentQuery)) {
                $queries[] = $currentQuery;
            }
            $currentQuery = '';
        } else {
            $currentQuery .= $char;
        }
    }
    
    // Ajouter la dernière requête
    $currentQuery = trim($currentQuery);
    if (!empty($currentQuery)) {
        $queries[] = $currentQuery;
    }
    
    $totalQueries = count($queries);
    echo "📊 Nombre de requêtes à exécuter: {$totalQueries}\n\n";
    
    // Exécution des requêtes
    echo "🚀 Exécution des requêtes...\n";
    $executed = 0;
    $errors = 0;
    
    foreach ($queries as $index => $query) {
        $queryNum = $index + 1;
        
        try {
            // Afficher progression tous les 100 requêtes
            if ($queryNum % 100 == 0) {
                $percent = round(($queryNum / $totalQueries) * 100);
                echo "   [{$percent}%] {$queryNum}/{$totalQueries} requêtes exécutées\n";
            }
            
            $pdo->exec($query);
            $executed++;
            
        } catch (PDOException $e) {
            $errors++;
            $errorMsg = $e->getMessage();
            
            // Afficher seulement les erreurs importantes
            if (!preg_match('/(already exists|Unknown table|Duplicate column)/i', $errorMsg)) {
                echo "⚠️  Requête #{$queryNum}: {$errorMsg}\n";
            }
        }
    }
    
    // Réactiver les vérifications
    echo "\n🔧 Restauration des paramètres...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    $pdo->exec("SET UNIQUE_CHECKS=1");
    echo "✅ Paramètres restaurés\n\n";
    
    // Résumé
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 RÉSUMÉ\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Requêtes exécutées: {$executed}\n";
    echo "⚠️  Erreurs (ignorables): {$errors}\n";
    echo "📈 Taux de succès: " . round(($executed / $totalQueries) * 100) . "%\n\n";
    
    // Vérification des tables
    echo "🔍 Vérification des tables créées...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📋 Tables présentes (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        // Compter les lignes
        try {
            $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
            $count = $countStmt->fetch()['count'];
            echo "   • {$table}: {$count} lignes\n";
        } catch (PDOException $e) {
            echo "   • {$table}: (erreur de comptage)\n";
        }
    }
    
    echo "\n✅ Import SQL terminé avec succès!\n";
    
} catch (PDOException $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    exit(1);
}
