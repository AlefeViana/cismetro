<?php
class MySession{
  
    // database connection and table name
    private $conn;
    private $table_name = "tbsessions";

    public $condition = 'WHERE session.tokbox_session = ?';
    public $subject;
    
    public $id;
    public $tenant_id;
    public $user_id;
    public $agendamento_id;
    public $tokbox_session;    
    public $is_archived;
    public $starts_at;
    public $ends_at;    
    public $created_at;
    public $updated_at;
    public $medico_hash;
    public $paciente_hash;

    public $ip_address;
    public $description;
  
    
    public function __construct($db){
        $this->conn = $db;
    }

    private function search($conditions, $limit)
    {

        $query = "
        SELECT 
            session.id AS id,
            session.tenant_id AS tenant_id,
            session.user_id AS user_id,
            session.agendamento_id AS agendamento_id,
            session.tokbox_session AS tokbox_session,            
            session.paciente_hash AS paciente_hash,
            session.medico_hash AS medico_hash,
            session.is_archived AS is_archived,
            session.starts_at AS starts_at,
            session.ends_at AS ends_at,
            session.created_at AS created_at
           
        FROM 
            {$this->table_name} session
        
        {$conditions} {$limit};";

        return $query;

    }

    function show(){

        $sql = $this->search($this->condition, 'LIMIT 0,1');
      
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(1, $this->subject);
        $stmt->execute();
       
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->agendamento_id = $row['agendamento_id'];
        $this->tenant_id = $row['tenant_id'];
        $this->user_id = $row['user_id'];
        $this->is_archived = $row['is_archived'];
        $this->tokbox_session = $row['tokbox_session'];
        
        $this->medico_hash = $row['medico_hash'];
        $this->paciente_hash = $row['paciente_hash'];
        $this->starts_at = $row['starts_at'];
        $this->ends_at = $row['ends_at'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        
        return $row;
    }

    function log(){
        $sql = "INSERT INTO
        tbsessions_log
        SET 
        session_id = :id,
        ip_address = :ip_address,
        description = :description,
        created_at = NOW()
        ";

        $stmt = $this->conn->prepare($sql);
        
        $this->ip_address=htmlspecialchars(strip_tags($this->ip_address));
        $this->description=htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":ip_address", $this->ip_address);

        if($stmt->execute())
            return true;
            
        return false;


    }

    function store(){
        $sql = 
        "INSERT INTO 
        {$this->table_name} 
        SET 
        tenant_id = :tenant_id,        
        paciente_hash = :paciente_hash,
        medico_hash = :medico_hash,
        user_id = :user_id,
        agendamento_id = :agendamento_id,
        tokbox_session = :tokbox_session,
        starts_at = NOW(),
        created_at = NOW()";

        $stmt = $this->conn->prepare($sql);

        
        $this->medico_hash=htmlspecialchars(strip_tags($this->medico_hash));
        $this->paciente_hash=htmlspecialchars(strip_tags($this->paciente_hash));
        $this->agendamento_id=htmlspecialchars(strip_tags($this->agendamento_id));
        $this->tenant_id=htmlspecialchars(strip_tags($this->tenant_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        
        $stmt->bindParam(":medico_hash", $this->medico_hash);
        $stmt->bindParam(":paciente_hash", $this->paciente_hash);
        $stmt->bindParam(":agendamento_id", $this->agendamento_id);
        $stmt->bindParam(":tokbox_session", $this->tokbox_session);
     
        $stmt->bindParam(":tenant_id", $this->tenant_id);
        $stmt->bindParam(":user_id", $this->user_id);

        

        if($stmt->execute()){

            
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    function close(){
        $sql = 
        "UPDATE  
        {$this->table_name} 
        SET 
        ends_at = NOW()
        WHERE
        id = :id;";

        $stmt = $this->conn->prepare($sql); 
        
        $this->id=htmlspecialchars(strip_tags($this->id));        
        $stmt->bindParam(":id", $this->id);
      
        if($stmt->execute())
            return true;

        return false;
    }


       
    
}
?>