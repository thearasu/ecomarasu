<?php
/*
by: Elavarasan
on: 18/02/2023
*/

class User{
    private $connection;

    public function __construct(){
        $db = new Database();
        $this->connection = $db->connection;
    }

    public function userLogIn($email, $password){
        $hash = md5($password);
        $query = "SELECT * FROM users WHERE email=:email AND password=:password";
        $result = $this->connection->prepare($query);
        $result->bindParam('email', $email);
        $result->bindParam('password', $hash);
        $result->execute();
        return $result->fetch();
    }
}