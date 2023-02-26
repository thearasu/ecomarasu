<?php
/*
by: Elavarasan
on: 16/02/2023
*/

# This file will define the Database class

class Database{
    private $db_host = "localhost";
    private $db_name = "ecomarasu";
    private $db_username = "root";
    private $db_password = "";

    public $connection;
    public $error;

    public function __construct(){
        $this->connectDB();
    }

    private function connectDB(){
        try {
            $this->connection = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_username, $this->db_password);
        } catch (PDOException $e) {
            $this->error = "Connection failed! ".$e->getMessage();
        }
    }
}