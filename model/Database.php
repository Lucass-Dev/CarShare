<?php
    class Database{
        private $db = null;
        public function __construct($dbName, $host, $user, $password){
            $this->dbName = $dbName;
            $this->host = $host;
            $this->user = $user;
            $this->password = $password;
            $this->db = new PDO("mysql:dbname={$dbName};host={$host}", $user, $password);
        }

        public function getDataBase(){
            return $this->db;
        }

    }


?>