<?php

class Agendamento implements JsonSerializable
{
    use Whatsapp;
   
    // database connection and table name
    private $conn;
    private $table_name = "tbagendacons";

    // object properties

   
    public $id;
    public $agendamento_data;
    public $agendamento_status;
    public $solicitacao_status;
    public $protocolo_paciente;
    public $is_confirmed_by_patient;
    public $confirmed_by_patient_at;
    public $is_validated;
    public $verified_by;
    public $verified_at;
    public $fornecedor_nome;
    public $paciente_nome;
    public $paciente_id;
    public $paciente_phone_number;
    public $paciente_data_de_nascimento;
    public $paciente_is_nofiable;
    public $procedimento;
    public $connection_id;
    public $connection_name;
    public $fornecedor_endereco;

    //AUDITORIA
    public $userrel;
    public $dtrel;
    public $hrrel;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function search($conditions, $limit)
    {
        $query = "SELECT 
            sl.CdSolCons AS id,
            cast(concat(DtAgCons, ' ', HoraAgCons) as datetime) as agendamento_data,
            ag.protocolopac AS protocolo_paciente,
            ag.is_confirmed_by_patient,
            ag.confirmed_by_patient_at,
            ag.is_validated,
            ag.tipoatend AS tipoatend,
            ag.starts_at AS starts_at,
            ag.atendimento_ends_at AS atendimento_ends_at,
            ag.ends_at AS ends_at,
            ag.Status AS agendamento_status,
            sl.Status AS solicitacao_status,
            ag.verified_by,
            ag.verified_at,
            pac.is_notifiable,
            pac.NmPaciente AS paciente_nome,
            pac.CdPaciente AS paciente_id,
            pac.Celular as paciente_phone_number,
            pac.email AS paciente_email,
            DATE_FORMAT(pac.DtNasc, '%d/%m/%Y' ) AS paciente_data_de_nascimento,
            forn.NmForn AS fornecedor_nome,
            concat(proc.NmProcedimento, ' - ' , esp.NmEspecProc) AS procedimento,
            concat(forn.Logradouro , ' , nº', forn.Numero ,  ' , ' , forn.Bairro, ' , ', pref.NmCidade , ' - ', st.UF ) AS fornecedor_endereco
            FROM tbagendacons ag 
            JOIN `tbsolcons` sl ON sl.CdSolCons = ag.CdSolCons
            JOIN `tbfornecedor` forn ON forn.CdForn = ag.CdForn
            LEFT JOIN tbprefeitura pref ON pref.CdPref = forn.CdCidade
            LEFT JOIN tbestado st ON st.CdEstado = pref.CdEstado
            JOIN `tbpaciente` pac ON pac.CdPaciente = sl.CdPaciente
            JOIN `tbespecproc` esp ON sl.CdEspecProc = esp.CdEspecProc
            JOIN `tbprocedimento` proc ON proc.CdProcedimento = esp.CdProcedimento
        {$conditions} ORDER BY ag.confirmed_by_patient_at ASC {$limit};";

        return $query;
    }

    function read(
        $conditions = 'WHERE TIMESTAMP(`DtAgCons`,`HoraAgCons`) > NOW() AND ag.verified_at IS NULL 
        AND pac.is_notifiable = 1', 
        $limit = 'LIMIT 1000' )
    {
        // select all query
        $query = $this->search($conditions, $limit);

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $collection = [];

        if ($stmt->execute() && $stmt->rowCount()) {
            while($resource= $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($collection, (object)$resource);
            }
        }

        return $collection;
    }

    // used when filling up the update product form
    function show($condition = "WHERE sl.CdSolCons = ?")
    {
        $query = $this->search(
        $condition, 'LIMIT 0,1');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->tipoatend = $row['tipoatend'];
        $this->agendamento_status = $row['agendamento_status'];
        $this->solicitacao_status = $row['solicitacao_status'];
        $this->starts_at = $row['starts_at'];
        $this->ends_at = $row['ends_at'];
        $this->is_validated = $row['is_validated'];
        $this->atendimento_ends_at = $row['atendimento_ends_at'];
        $this->agendamento_data = $row['agendamento_data'];
        $this->protocolo_paciente = $row['protocolo_paciente'];
        $this->verified_at = $row['verified_at'];
        $this->verified_by = $row['verified_by'];
        $this->is_confirmed_by_patient = $row['is_confirmed_by_patient'];
        $this->confirmed_by_patient_at = $row['confirmed_by_patient_at'];
        $this->fornecedor_nome = $row['fornecedor_nome'];
        $this->paciente_id = $row['paciente_id'];
        $this->paciente_phone_number = $row['paciente_phone_number'];
        $this->is_notifiable = $row['is_notifiable'];
        $this->paciente_nome = $row['paciente_nome'];
        $this->paciente_email = $row['paciente_email'];
        $this->paciente_data_de_nascimento = $row['paciente_data_de_nascimento'];
        $this->procedimento = $row['procedimento'];
        $this->fornecedor_endereco = $row['fornecedor_endereco'];

        $this->userrel = $_SESSION['CdUsuario'];
        $this->dtrel = date('Y-m-d');
        $this->hrrel = date('H-i-s');
    }

    public function getId() 
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return 
        [
            'id'   => $this->getId(),
            'isValidated' => $this->is_validated,
            'status' => $this->status(),
            'is_notifiable' => $this->is_notifiable,
            'paciente_nome' => $this->paciente_nome,
            'agendamento_status' => $this->agendamento_status,
            'solicitacao_status' => $this->solicitacao_status,
            'userrel' => $this->userrel,
            'dtrel' => $this->dtrel,
            'hrrel' => $this->hrrel,
        ];
    }

    /**
     * Get human readable status
     * @return array $status
     */
    public function status()
    {
        $status = [];

        if($this->solicitacao_status === "F"){
            $status['key'] = "absence";
            $status['label'] = "Falta";
        }

        else if($this->agendamento_status === NULL && $this->solicitacao_status === "E"){
            $status['key'] = "on_hold";
            $status['label'] = "Em espera";
        }

        else if($this->agendamento_status === NULL && ($this->solicitacao_status === 1 || $this->solicitacao_status === "A" )){
            $status['key'] = "waiting";
            $status['label'] = "Aguardando";
        }

        else if($this->agendamento_status == 2 && $this->solicitacao_status == 1){
            $status['key'] = "confirmed";
            $status['label'] = "Confirmado";
        }

        else if($this->agendamento_status === 1 && $this->solicitacao_status === 1){
            $status['key'] = "scheduled";
            $status['label'] = "Marcado";
        }

        else if($this->solicitacao_status == 2 && $this->agendamento_status == 1){
            $status['key'] = "cancelled";
            $status['label'] = "Cancelado";
        }

        else {
            $status['key'] = "unknown";
            $status['label'] = "Desconhecido";
        }

        return $status;
    }

    /**
     * Set agendamento is_validated to true
     */

    public function validate()
    {
        $query =
        "UPDATE " .
            $this->table_name .
        "  SET  is_validated = 1 WHERE CdSolCons = :id;";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Set agendamento status to confirmed
     */

    public function confirm()
    {
        $query =
        "UPDATE " .
            $this->table_name .
            "  SET  Status = 2 WHERE CdSolCons = :id;
        UPDATE tbsolcons SET status = 1 WHERE CdSolCons = :id;";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }



    /**
     * Patient's confirms/cancels appointment through confirmation page
     */

    function answer()
    {
        $sql =
            "UPDATE " .
            $this->table_name .
            "  SET  is_confirmed_by_patient = :is_confirmed_by_patient, confirmed_by_patient_at = NOW() WHERE CdSolCons = :id;";

        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(
            ':is_confirmed_by_patient',
            $this->is_confirmed_by_patient,
            PDO::PARAM_INT
        );
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function verify()
    {
        if ($this->is_confirmed_by_patient == 1) {
            $query =
                "UPDATE " .
                $this->table_name .
                " SET verified_at = NOW(), verified_by = :verified_by WHERE CdSolCons = :id;";
        } else {
            $query =
                "
            UPDATE " .
                $this->table_name .
                "  SET  status = 1, verified_at = NOW(), verified_by = :verified_by WHERE CdSolCons = :id;
            UPDATE tbsolcons SET status = 2 WHERE CdSolCons = :id;";
        }

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':verified_by', $this->verified_by);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    function open()
    {
        $query =
            "UPDATE " .
            $this->table_name .
            "  SET  tipoatend = :value , starts_at = NOW() WHERE CdSolCons = :id;";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->value = htmlspecialchars(strip_tags($this->value));

        //$stmt->bindParam(':aprovacao_status', $this->aprovacao_status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':value', $this->value);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function close()
    {
        //$query = "UPDATE ".$this->table_name."  SET  Status = 2 , ends_at = NOW() WHERE CdSolCons = :id;";
        $query =
            "UPDATE " .
            $this->table_name .
            "  SET  ends_at = NOW() WHERE CdSolCons = :id;";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    
}
?>
