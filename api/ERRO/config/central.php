<?php

class Central{
  
    // specify your own database credentials
    private $host = "mysql.iconsorciosaude5.com.br";
    private $db_name = "iconsorciosaud38";
    private $username = "iconsorciosaud38";
    private $password = "testecisverde";
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