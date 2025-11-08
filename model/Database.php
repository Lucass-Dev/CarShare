<?php
class Database {
    public static $db = null;

    public static function instanciateDb($dbName, $host, $user, $password){
        try {
            // DSN propre avec charset
            $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";
            Database::$db = new PDO($dsn, $user, $password);

            Database::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //echo "Connexion à la BDD réussie !";
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}

// ⚠️ Vérifie bien ces valeurs :
Database::instanciateDb('covoiturage', 'localhost', 'root', 'admin');
// ou, si jamais ça coince, teste aussi :
// Database::instanciateDb('covoiturage', '127.0.0.1', 'root', '');

?>