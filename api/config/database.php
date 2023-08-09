<?php
define('CONNECTION_ID', 120);
define('CONNECTION_NAME', 'demonstracao');

class Database{
  
    // specify your own database credentials
    private $host = "db-cismetro-demo.cp2misfrgznr.us-east-1.rds.amazonaws.com";
    private $db_name = "cismetro";
    private $username = "admin";
    private $password = 'XY$OAPzpAiKs';
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
?>