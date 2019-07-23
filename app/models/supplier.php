<?php 

class Supplier
{
    private $table = 'suppliers';

    public $id;
    public $name;
    public $email;
    public $address;
    public $mobile;
    public $home;
    public $work;
    
    public function __construct($db, $auth)
    {
        $this->conn = $db;
        $this->auth = $auth;
    }

    function create()
    {
        if($this->auth->access('admin'))
        {
            $query = "INSERT INTO " .$this->table. " SET
                name = :name,
                email = :email,
                address = :address,
                mobile = :mobile,
                home = :home,
                work = :work";

            $stmt = $this->conn->prepare($query);
           
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':mobile', $this->mobile);
            $stmt->bindParam(':home', $this->home);
            $stmt->bindParam(':work', $this->work);
            
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
        if($this->auth->access('admin'))
        {
            
            $query = "SELECT * FROM " .$this->table. "";

            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                $response = $stmt->fetchAll();
                return $response;
            }

            return false;
        }
        else
        {
            return 'access';
        }
        
    }

    function read_single()
    {
        if($this->auth->access('admin'))
        {
            $query = "SELECT * FROM " .$this->table. " WHERE id='".$this->id."'";

            $stmt = $this->conn->prepare($query);
            
            
            if($stmt->execute()){
                $response = $stmt->fetch(PDO::FETCH_ASSOC);
                return $response;
            }

            return false;
        }
        else
        {
            return 'access';
        }

        
    }

    function update()
    {
        if($this->auth->access('admin'))
        {
            if(!$this->_exists())
            {
                $query = "UPDATE " .$this->table. " SET
                name = :name,
                email = :email,
                address = :address,
                mobile = :mobile,
                home = :home,
                work = :work 
                WHERE id=".$this->id."";

                $stmt = $this->conn->prepare($query);
            
                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':address', $this->address);
                $stmt->bindParam(':mobile', $this->mobile);
                $stmt->bindParam(':home', $this->home);
                $stmt->bindParam(':work', $this->work);
                
                if($stmt->execute()){
                    return true;
                }

                return false;
            }

            return 2;
            
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

    function cute_phone_number(string $phone = null)
    {
        $result = "";
        if($phone != null)
        {
            if($phone[0] == "+")
            {
                $result = substr_replace($phone, " ", 5, 0);
                $result = substr_replace($result, " ", 11, 0);
            }
            else 
            {
                $result = substr_replace($phone, " ", 3, 0);
                $result = substr_replace($result, " ", 6, 0);
                $result = substr_replace($result, " ", 10, 0);
            }
                
        }

        return $result;
    }

    function _exists()
    {
        $query = "SELECT id FROM " .$this->table. " WHERE email='" .$this->email. "'";

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

    function validate_phone_number($mobile_phone)
    {
        $pattern = '/^[0-9\-\(\)\/\+\s]*$/';

        if (preg_match($pattern, $mobile_phone))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}