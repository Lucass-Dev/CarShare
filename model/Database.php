<?php
    class Database{
        public static $db = null;
        public static function instanciateDb($dbName, $host, $user, $password){
            Database::$db = new PDO("mysql:dbname={$dbName};host={$host}", $user, $password);
        }
    }


?>