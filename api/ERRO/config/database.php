<?php
define('CONNECTION_ID', 90);
define('CONNECTION_NAME', 'CISMETRO');

class Database{
  
    // specify your own database credentials
    private $host = "mysql.iconsorciosaude19.com.br";
    private $db_name = "iconsorciosau144";
    private $username = "iconsorciosau144";
    private $password = "Pd3rpivk";
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