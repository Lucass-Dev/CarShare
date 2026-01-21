<?php
class Database {
    private static $db = null;
    private static $instance = null;

    private function __construct() {
        // Singleton pattern
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        include_once __DIR__ . '/../config.php';
        if (self::$db === null) {
            try {
                
                if (!defined('DB_HOST')) {
                    die('ERREUR : config.php n\'est pas chargé !');
                }
                // Utiliser les constantes définies dans config.php
                $dsn = "mysql:host=" . DB_HOST . 
                       ";port=" . (defined('DB_PORT') ? DB_PORT : '3306') . 
                       ";dbname=" . DB_NAME . 
                       ";charset=utf8mb4";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                
                // Ajouter SSL si requis (production)
                if (defined('DB_SSL_MODE') && DB_SSL_MODE === 'REQUIRED') {
                    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
                }
                
                self::$db = new PDO($dsn, DB_USER, DB_PASS, $options);
                
            } catch (PDOException $e) {
                error_log("Erreur connexion BD: " . $e->getMessage());
                // Lancer une exception au lieu de die() pour permettre la gestion d'erreur
                throw new Exception("Impossible de se connecter à la base de données. Veuillez vérifier que MySQL est démarré.");
            }
        }
        return self::$db;
    }

    // Méthode legacy pour compatibilité
    public static function getDb() {
        return self::getInstance()->getConnection();
    }
}
