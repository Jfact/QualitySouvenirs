<?php 
class Table
{

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function _length(string $table = null)
    {
        if($table != null)
        {
            $query = "SELECT id FROM " .$table. "";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->rowCount();
        }
        else
        {
            return false;
        }
    }

}