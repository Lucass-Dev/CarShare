<?php
class Database {
    private static $db = null;

    public static function instanciateDb(){
        if (Database::$db == null) {
            require_once(__DIR__."/../config.php");
            Database::$db = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            Database::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    return self::$db;
    }

    public static function getDb(){
       if (Database::$db == null) {
        Database::instanciateDb();
       }
       return Database::$db;
    }
}
