<?php

class County implements JsonSerializable
{
    // database connection and table name
    private $conn;
    private $table_name = "tbprefeitura";

    // object properties
   
    public $id;
    public $name;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function search($conditions, $sort ,$limit)
    {
        $query = "SELECT 
        CdPref AS id,
        NmCidade AS name
        FROM
        {$this->table_name}            
        {$conditions} {$sort} {$limit};";

        return $query;
    }

    function all(
        $conditions = '', 
        $sort = "" , 
        $limit = ""
        )
    {
        // select all query
        $query = $this->search($conditions,$sort , $limit);

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
    function show($condition = "WHERE CdPref = ?" , $sort = "" , $limit = "")
    {
        $query = $this->search(
        $condition, '','LIMIT 0,1');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];     
        $this->name = $row['name'];

        return $row;
       
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
            'name' => $this->name,
        ];
    }
}
?>
