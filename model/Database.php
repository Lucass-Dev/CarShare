<?php
    class Database{
        
        public static $db = null;

        public static function instanciateDb(){
            if (Database::$db == null) {
                require_once("C:\Users\LUCAS\Dev\web\CarShare\config.php");
                Database::$db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
                Database::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }

        
    }

    Database::instanciateDb();


?>