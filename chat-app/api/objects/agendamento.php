<?php
class Agendamento{
  
    // database connection and table name
    private $conn;
    private $table_name = "tbagendacons";
  
    // object properties
   
  
    public $id;
    public $agendamento_data;
    public $protocolo_paciente;
    public $aprovacao_status;
    public $aprovacao_verificado;
    public $fornecedor_nome;
    public $paciente_nome;
    public $procedimento;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    private function search($conditions, $limit)
    {

        $query = "
        SELECT 
        sl.CdSolCons AS id,
        cast(concat(DtAgCons, ' ', HoraAgCons) as datetime) as agendamento_data,
        ag.protocolopac AS protocolo_paciente,
        ag.aprovacao_status,
        ag.aprovacao_verificado,
        pac.NmPaciente AS paciente_nome,
        forn.NmForn AS fornecedor_nome,
        concat(proc.NmProcedimento, ' - ' , esp.NmEspecProc) AS procedimento
        FROM ".$this->table_name." ag 
        JOIN `tbsolcons` sl ON sl.CdSolCons = ag.CdSolCons
        JOIN `tbfornecedor` forn ON forn.CdForn = ag.CdForn
        JOIN `tbpaciente` pac ON pac.CdPaciente = sl.CdPaciente
        JOIN `tbespecproc` esp ON sl.CdEspecProc = esp.CdEspecProc
        JOIN `tbprocedimento` proc ON proc.CdProcedimento = esp.CdProcedimento
        WHERE {$conditions} AND ag.aprovacao_verificado = 0   ORDER BY ag.aprovacao_status ASC {$limit};";

        return $query;

    }

    // read products
    function read(){
        // select all query
        $query = $this->search('TIMESTAMP(`DtAgCons`,`HoraAgCons`) > NOW()', 'LIMIT 1000');
      
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt;
    }

    // used when filling up the update product form
    function show(){

        $query = $this->search('sl.CdSolCons = ?', 'LIMIT 0,1');
      
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        $this->id = $row['id'];
        $this->agendamento_data = $row['agendamento_data'];
        $this->protocolo_paciente = $row['protocolo_paciente'];
        $this->aprovacao_verificado = $row['aprovacao_verificado'];
        $this->aprovacao_status = $row['aprovacao_status'];
        $this->fornecedor_nome = $row['fornecedor_nome'];
        $this->paciente_nome = $row['paciente_nome'];
        $this->procedimento = $row['procedimento'];
      
    }


       
    
}
?>