<?php
class Database {
    private static $db = null;

    
    private static $dbName   = 'carshare';
    private static $host     = 'localhost';
    private static $user     = 'root';
    private static $password = ''; 

    public static function getDb() {
        if (self::$db === null) {
            try {
                self::$db = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=utf8mb4",
                    self::$user,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion MySQL : " . $e->getMessage());
            }
        }
        return self::$db;
    }
}
