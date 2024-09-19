<?php

namespace Connection;

use PDO;
use PDOException;

class Connection
{
    private String $host = "localhost";
    private String $username = "root";
    private String $password = "";
    private String $database = "auth";

    public function connect(){
        try {
            return new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
        }catch (PDOException $e){
            die('Connection failed: ' . $e->getMessage());
        }
    }


}


