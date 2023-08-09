<?php

class Database {
    private $host = "mysql.iconsorciosaude5.com.br";
    private $db_name = "iconsorciosau137";
    private $username = "iconsorciosau137";
    private $password = "bdcisasp2020";
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}