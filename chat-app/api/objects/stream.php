<?php
class Stream{
  
    // database connection and table name
    private $conn;
    private $table_name = "tbsessions";
    
    public $id;
    public $agendamento_id;
    public $ip_address;
    public $tokbox_session_id;
    public $token;
    public $created_at;
    public $updated_at;
  
    
    public function __construct($db){
        $this->conn = $db;
    }

    private function search($conditions, $limit)
    {

        $query = "
        SELECT session.*, agendamento.*  
        FROM {$this->table_name} session
        INNER JOIN tbagendacons agendamento ON agendamento.CdSolCons = session.agendamento_id
        {$conditions} {$limit};";

        return $query;

    }

    
    function read(){
      
        $query = $this->search('TIMESTAMP(`DtAgCons`,`HoraAgCons`) > NOW()', 'LIMIT 1000');
      
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt;
    }

    function show(){

        $query = $this->search('WHERE session.agendamento_id = 1', 'LIMIT 0,1');
      
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        $this->id = $row['id'];
        $this->agendamento_id = $row['agendamento_id'];
        $this->ip_address = $row['ip_address'];
        $this->tokbox_session_id = $row['tokbox_session_id'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
      
    }

    function store(){
        $sql = 
        "INSERT INTO 
        {$this->table_name} 
        SET 
        agendamento_id = :agendamento_id, 
        ip_address = :ip_address,
        tokbox_session_id = :tokbox_session_id,
        created_at = NOW()";

        $stmt = $this->conn->prepare($sql);

        $this->agendamento_id=htmlspecialchars(strip_tags($this->agendamento_id));
        $this->ip_address=htmlspecialchars(strip_tags($this->ip_address));
        $this->tokbox_session_id=htmlspecialchars(strip_tags($this->tokbox_session_id));
       
    
        // bind values
        $stmt->bindParam(":agendamento_id", $this->agendamento_id);
        $stmt->bindParam(":ip_address", $this->ip_address);
        $stmt->bindParam(":tokbox_session_id", $this->tokbox_session_id);
      
        if($stmt->execute())
            return true;

        return false;
    }


       
    
}
?>