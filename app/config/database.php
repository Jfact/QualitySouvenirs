<?php

class Database{
 
    // specify database credentials
    private $dbhost = "localhost";
    private $dbname = "api_souvenirs";
    private $dbuser = "root";
    private $dbpassword = "";
    public $conn;
 
    // get the database connection
    public function connection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->dbhost . ";dbname=" . $this->dbname, $this->dbuser, $this->dbpassword);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            $this->error = $exception->getMessage();
        }
 
        return $this->conn;
    }

}

?>