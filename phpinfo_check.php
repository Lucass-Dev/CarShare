<?php
/**
 * Informations PHP et environnement
 * Utile pour le débogage
 */

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Info PHP - CarShare</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class='card'>
        <h1>ℹ️ Informations PHP et Environnement</h1>
        
        <div class='info'>
            <strong>PHP Version:</strong> <code>" . PHP_VERSION . "</code><br>
            <strong>Serveur:</strong> <code>" . $_SERVER['SERVER_SOFTWARE'] . "</code><br>
            <strong>Document Root:</strong> <code>" . $_SERVER['DOCUMENT_ROOT'] . "</code><br>
        </div>
        
        <div style='margin-top: 20px;'>
            <a href='test_db_connection.php' class='btn'>Test Connexion DB</a>
            <a href='create_database.php' class='btn'>Créer la DB</a>
            <a href='index.php' class='btn'>Accueil</a>
        </div>
    </div>
    
    <div class='card'>
        <h2>Extensions PHP Importantes</h2>
        <div>";

$extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'gd'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? '✅' : '❌';
    $color = $loaded ? '#28a745' : '#dc3545';
    echo "<div style='padding: 8px; margin: 5px 0;'>
            <span style='color: $color; font-size: 18px;'>$status</span> 
            <code>$ext</code>
          </div>";
}

echo "  </div>
    </div>
    
    <div class='card'>
        <h2>📊 phpinfo() complète</h2>
        <div style='margin-top: 20px;'>";

phpinfo();

echo "  </div>
    </div>
</body>
</html>";
?>
