<?php
/**
 * caio - 2020-07-18
 */
class Information
{    
    // database connection and table name
    private $conn;
    private $table_name = "tbnoticia";


    public $id;
    public $title;
    public $author;
    public $date;
    public $time;
    public $deleted_at;
    public $text;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function search($condition, $limit)
    {
        $query = "SELECT 
        cdnoticia AS id,
        titulo AS title,
        corpo AS text,
        `data` AS date, 
        hora AS time, 
        autor AS author,
        deleted_at
        FROM {$this->table_name}
         {$condition} {$limit};";

        return $query;
    }

    function all( $condition = '', $limit = '' )
    {
        // select all query
        $query = $this->search( $condition, $limit);

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

    public function lastInsertedId(){
        // select all query
        $query = "SELECT MAX(cdnoticia ) AS last_inserted_id FROM tbnoticia; ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['last_inserted_id'];
    }

    // used when filling up the update product form
    function show($condition = 'WHERE cdnoticia = ?')
    {
        $sql = $this->search($condition, 'LIMIT 0,1');

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->author = $row['author'];
        $this->date = $row['date'];
        $this->time = $row['time'];
        $this->text = $row['text'];
        $this->deleted_at = $row['deleted_at'];
        return $this;

    }

    public function store()
    {
        $sql = 
        "INSERT INTO {$this->table_name}
        (titulo, corpo, `data`, hora, autor)
        VALUES(?, ?, ?, ?, ?);";

        $stmt = $this->conn->prepare($sql);

        $this->title = htmlspecialchars(strip_tags($this->title));
      
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->author = htmlspecialchars(strip_tags($this->author));
      
        
  
        $stmt->bindParam('1', $this->title);
        $stmt->bindParam('2', $this->text);
        $stmt->bindParam('3', $this->date);
        $stmt->bindParam('4', $this->time);
        $stmt->bindParam('5', $this->author);
  

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }   

    public function update()
    {
        $sql = 
        "UPDATE  
        {$this->table_name}
        SET titulo = ? , corpo = ? , autor = ? WHERE cdnoticia = ?;";

        $stmt = $this->conn->prepare($sql);

        $this->title = htmlspecialchars(strip_tags($this->title));
       
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));
  
        $stmt->bindParam('1', $this->title);
        $stmt->bindParam('2', $this->text);
        $stmt->bindParam('3', $this->author);
        $stmt->bindParam('4', $this->id);
  

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }   

    public function activate()
    {
        $sql = 
        "UPDATE  
        {$this->table_name}
        SET deleted_at = NULL WHERE cdnoticia = ?;";

        $stmt = $this->conn->prepare($sql);
       
        $this->id = htmlspecialchars(strip_tags($this->id));  
    
        $stmt->bindParam('1', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    public function deactivate()
    {
        $sql = 
        "UPDATE  
        {$this->table_name}
        SET deleted_at = NOW() WHERE cdnoticia = ?;";

        $stmt = $this->conn->prepare($sql);
       
        $this->id = htmlspecialchars(strip_tags($this->id));  
    
        $stmt->bindParam('1', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
        
    }
    
    /**
     * Toggle this entry status (activate/deactivate)
     */
    public function toggle(){
        return $this->deleted_at ? $this->activate() : $this->deactivate();
    }

    public function destroy()
    {
        $sql = 
        "DELETE FROM  
        {$this->table_name}
        WHERE cdnoticia = ?;";

        $stmt = $this->conn->prepare($sql);
       
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam('1', $this->id);
  

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }   
}
?>
