<?php

class Survey
{    
    // database connection and table name
    private $conn;
    private $table_name = "survey";

    // object properties

    public $id;
    public $connection_id;
    public $agendamento_id;
    public $connection_name;
    public $text;
    public $rating;
    public $created_at;
    public $updated_at;


    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function search($select,$condition, $limit)
    {
        $query = "SELECT 
            {$select}
            s.*,
            c.NmCliente as connection_name
        FROM
        survey s
        JOIN tbcliente c ON c.cdCliente = s.connection_id
        {$condition}
        ORDER BY s.created_at ASC {$limit};";

        return $query;
    }

    function all($select = '', $condition = '', $limit = '' )
    {
        // select all query
        $query = $this->search($select, $condition, $limit);

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
    function show()
    {
        $query = $this->search($select = '', 'id = ? AND', 'LIMIT 0,1');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->connection_id = $row['connection_id'];
        $this->agendamento_id = $row['agendamento_id'];
        $this->text = $row['text'];
        $this->rating = $row['rating'];
        $this->connection_name = $row['connection_name'];
        $this->updated_at = $row['updated_at'];
        $this->created_at = $row['created_at'];

    }

    function hasSurvey()
    {
        $query = $this->search("",'WHERE agendamento_id = ? AND connection_id = ?', 'LIMIT 0,1');

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->connection_id = htmlspecialchars(strip_tags($this->connection_id));
        $stmt->bindParam(1, $this->agendamento_id);
        $stmt->bindParam(2, $this->connection_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->connection_id = $row['connection_id'];
        $this->agendamento_id = $row['agendamento_id'];
        $this->text = $row['text'];
        $this->rating = $row['rating'];
        $this->connection_name = $row['connection_name'];
        $this->updated_at = $row['updated_at'];
        $this->created_at = $row['created_at'];

        return $row['created_at'] ? true :false;
    }

    public function store()
    {
        $sql = "INSERT INTO 
        {$this->table_name}
        (connection_id, agendamento_id, `text`, rating)
        VALUES(?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->connection_id = htmlspecialchars(strip_tags($this->connection_id));
        $this->agendamento_id = htmlspecialchars(strip_tags($this->agendamento_id));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
  
        $stmt->bindParam('1', $this->connection_id);
        $stmt->bindParam('2', $this->agendamento_id);
        $stmt->bindParam('3', $this->text);
        $stmt->bindParam('4', $this->rating);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }   
}
?>
