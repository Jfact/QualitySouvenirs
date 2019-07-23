<?php 

class Category
{
    private $table = 'categories';

    public $id;
    public $title;
    
    public function __construct($db, $auth)
    {
        $this->conn = $db;
        $this->auth = $auth;
    }

    function create()
    {
        if($this->auth->access('admin'))
        {
            $query = "INSERT INTO ".$this->table." (`id`, `title`) VALUES (NULL, '".$this->title."')";
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                return true;
            }

            return false;
        }
        else
        {
            return 'access';
        }
    }

    function read()
    {
        $query = "SELECT * FROM " .$this->table. "";

        $stmt = $this->conn->prepare($query);
        
        
        if($stmt->execute()){
            $response = $stmt->fetchAll();
            return $response;
        }

        return "Unexpected error occured, try again later.";
    }

    function read_single()
    {
        $query = "SELECT * FROM " .$this->table. " WHERE id='".$this->id."'";

        $stmt = $this->conn->prepare($query);
        
        
        if($stmt->execute()){
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            return $response;
        }

        return "Unexpected error occured, try again later.";
    }

    function update()
    {
        if($this->auth->access('admin'))
        {
            $query = "UPDATE " .$this->table. " SET title='".$this->title."' WHERE id=".$this->id."";
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                return true;
            }

            return false;
        }
        else
        {
            return 'access';
        }
    }

    function delete()
    {
        if($this->auth->access('admin'))
        {
            $query = "DELETE FROM " .$this->table. " WHERE id=".$this->id."";
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                return true;
            }

            return false;
        }
        else
        {
            return 'access';
        }
    }

    function _exists()
    {
        $query = "SELECT id FROM " .$this->table. " WHERE title='" .$this->title. "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(isset($response['id']) && !empty($response['id']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}