<?php

class Banner implements JsonSerializable
{
    public $id;
    public $user_id;
    public $user_type;
    public $contact;
    
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function search($conditions = "", $limit = ""){

        $sql = "SELECT ba.cdBanner AS id,ba.titulo AS header ,ba.corpo AS body,ba.tipo  AS type
        FROM tbbannersctrl ctrl
        INNER JOIN tbbanners ba ON ctrl.cdBanner = ba.cdBanner {$conditions} {$limit}";

        return $sql;

    }

    public function all($conditions = "" , $limit = "LIMIT 1000")
    {
        $stmt = $this->conn->prepare($this->search($conditions, $limit));
        $stmt->execute();

        $collection = [];

        if ($stmt->execute() && $stmt->rowCount()) {
            while($resource= $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($collection, (object)$resource);
            }
        }

        return $collection;
    }

    public function show($conditions = "WHERE ba.cdBanner = ?" , $limit = "LIMIT 0,1"){

        $sql = $this->search($conditions,$limit);

        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $collection = [];

        if ($stmt->execute() && $stmt->rowCount()) {
            while($resource= $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($collection, (object)$resource);
            }
        }

        return $collection;

    }

    
    public function getId(){
        return $this->id;
    }

    public function jsonSerialize()
    {
        return 
        [
            'id'   => $this->getId(),
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
  
            
        ];
    }

    public function like()
    {
        $query =
        "INSERT INTO 
        `tbbannersalc` (`cdUsuario`,`cdBanner`,`dataAlc`,`contato`) 
        VALUES ( ? , ?, NOW() , ?)";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->contact = htmlspecialchars(strip_tags($this->contact));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->id);
        $stmt->bindParam(3, $this->contact);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
