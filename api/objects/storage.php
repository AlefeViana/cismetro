<?php

class Storage
{    
    // database connection and table name
    private $conn;
    private $table_name = "survey";

    // object properties
    public $id;
    public $owner_type;
    public $owner_id;
    public $subject;
    public $file_name;
    public $created_at;
    public $updated_at;
    public $file_original_name;
    public $password;
    public $path;
    public $item_title;
    public $item_id;


    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function search($condition, $limit)
    {
        $query = "SELECT 
            *
        FROM
        storage {$condition} {$limit};";

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

    // used when filling up the update product form
    function show($condition = 'WHERE id = ?')
    {
        $sql = $this->search($condition, 'LIMIT 0,1');

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->owner_type = $row['owner_type'];
        $this->owner_id = $row['owner_id'];
        $this->subject = $row['subject'];
        $this->file_name = $row['file_name'];
        $this->file_original_name = $row['file_original_name'];
        $this->password = $row['password'];
        $this->path = $row['path'];
        $this->item_title = $row['item_title'];

        return $this;

    }

    public function store()
    {
        $sql = 
        "INSERT INTO storage
        (owner_type, owner_id, subject, file_name, file_original_name, password, `path`, item_title, item_id)
        VALUES(?, ? , ?, ?, ?, ?, ?, ?, ?);
        ";

        $stmt = $this->conn->prepare($sql);

        $this->owner_type = htmlspecialchars(strip_tags($this->owner_type));
        $this->owner_id = htmlspecialchars(strip_tags($this->owner_id));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->file_original_name = htmlspecialchars(strip_tags($this->file_original_name));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->path = htmlspecialchars(strip_tags($this->path));
        $this->item_title = htmlspecialchars(strip_tags($this->item_title));
        
        
  
        $stmt->bindParam('1', $this->owner_type);
        $stmt->bindParam('2', $this->owner_id);
        $stmt->bindParam('3', $this->subject);
        $stmt->bindParam('4', $this->file_name);
        $stmt->bindParam('5', $this->file_original_name);
        $stmt->bindParam('6', $this->password);
        $stmt->bindParam('7', $this->path);
        $stmt->bindParam('8', $this->item_title);
        $stmt->bindParam('9', $this->item_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }  
    
    /**
     * Storage 
     * @param array $file
     * @return array $result
     */

    public function storeFile($file)
    {
        $root = $_SERVER['DOCUMENT_ROOT'];

        $folder_name =strtolower(CONNECTION_NAME);

        $MAX_FILE_SIZE = 1048576;

        $status = [
            'uploaded' => false,
            'reason' => 'processing'
        ];

        if ($file['size'] > 0 && $file['error'] == 0)
        {
            
            if($file['size'] > $MAX_FILE_SIZE){
                $status['reason'] = 'Arquivo não pode ser maior que 1MB';
                return $status;
            }
            $this->path =  'storage/' . $folder_name . "/noticias";
            $fullPath = $root . $this->path;

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            $this->file_original_name = $file['name'];

            $fileData = pathinfo($file['name']);

            $ext = $fileData['extension'];

            $acceptedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($ext, $acceptedExtensions)) { 
                $status['reason'] =  'Extensão do arquivo não é permitida';
                return $status;
            }

            $filename = $fileData['filename'];

            $this->file_name = md5($filename . '-' . implode('-', explode(':', date('H:i:s')))) . '.' . $ext;

            move_uploaded_file( $file['tmp_name'], $fullPath . "/" . $this->file_name);

            $status['uploaded'] = true;
            $status['reason'] =  'O arquivo foi armazenado no sistema.';
            return $status;

        }

        $status['reason'] = 'O arquivo não pôde ser carregado. Por favor, tente novamente';
        return $status;

    }
}
?>
