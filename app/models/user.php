<?php 

class User
{
    private $table = 'users';
    
    public $firstname;
    public $lastname;

    public $email;
    public $mobile;
    public $address;

    public $role;
    public $token;
    public $password;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        
        if(!$this->_exists())
        {
            $query = "INSERT INTO " .$this->table. " SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                pass = :pass,
                token = :token";


            $stmt = $this->conn->prepare($query);
            
            $this->token=bin2hex(random_bytes(64));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
           
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':pass', $password_hash);
            $stmt->bindParam(':token', $this->token);
            
            if($stmt->execute()){
                return true;
            }
            
            return "Unexpected error occured, try again later.";
        }

        return "User with such email already exists in our database.";
        
    }

    public function create_admin()
    {
        if(!$this->_admin_exists())
        {
            $this->role = 'admin';

            if(!$this->_exists())
            {
                $query = "INSERT INTO " .$this->table. " SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    pass = :pass,
                    token = :token,
                    role = :role";


                $stmt = $this->conn->prepare($query);
                
                $this->token = bin2hex(random_bytes(64));
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            
                $stmt->bindParam(':firstname', $this->firstname);
                $stmt->bindParam(':lastname', $this->lastname);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':pass', $password_hash);
                $stmt->bindParam(':token', $this->token);
                $stmt->bindParam(':role', $this->role);
                
                if($stmt->execute()){
                    return true;
                }
                
                return "Unexpected error occured, try again later.";
            }
            else
            {
                $query = "UPDATE " .$this->table. " SET role=".$this->role." WHERE email=".$this->email."";


                $stmt = $this->conn->prepare($query);
                
                $this->token=bin2hex(random_bytes(64));
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            
                $stmt->bindParam(':firstname', $this->firstname);
                $stmt->bindParam(':lastname', $this->lastname);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':pass', $password_hash);
                $stmt->bindParam(':token', $this->token);
                
                if($stmt->execute()){
                    return true;
                }
                
                return "Unexpected error occured, try again later.";
            }
        }

        return "Admin user already exist in our database.";
    }

    public function read(int $id = null)
    {
        if($id == null)
            $id = $_SESSION['id'];

        $query = "SELECT `firstname`, `lastname`, `email`, `mobile`, `address`, `role` FROM " .$this->table. " WHERE id='" .$id. "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->firstname = $response['firstname'];
        $this->lastname = $response['lastname'];
        $this->email = $response['email'];
        $this->mobile = $response['mobile'];
        $this->address = $response['address'];
        $this->role = $response['role'];
    }

    public function update(string $current_password = null)
    {   
        if($current_password != null && $current_password != "")
        {
            $query = "SELECT `pass` FROM " .$this->table. " WHERE id='" .$_SESSION['id']. "'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($current_password, $response['pass']))
            {
                
                $query = "UPDATE " .$this->table. " SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    mobile = :mobile,
                    address = :address
                    WHERE id='".$_SESSION['id']."'";


                $stmt = $this->conn->prepare($query);
                
                $this->token=bin2hex(random_bytes(64));
            
                $stmt->bindParam(':firstname', $this->firstname);
                $stmt->bindParam(':lastname', $this->lastname);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':mobile', $this->mobile);
                $stmt->bindParam(':address', $this->address);
                
               if($stmt->execute()){
                    return true;
                }
                return "Unexpected error occured, try again later.";
            }

            return "Your entered current password doesn't match with your current password.";
        }

        return "Enter your current password.";
        
    }

    public function update_password(string $current_password = null)
    {   
        if($current_password != null && $current_password != "")
        {
            $query = "SELECT `pass` FROM " .$this->table. " WHERE id='" .$_SESSION['id']. "'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($current_password, $response['pass']))
            {
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                
                $query = "UPDATE " .$this->table. " SET
                    pass='".$password_hash."'
                    WHERE id='".$_SESSION['id']."'";

                $stmt = $this->conn->prepare($query);
                
                if($stmt->execute()){
                    return true;
                }
                
                return "Unexpected error occured, try again later.";
            }

            return "Your entered current password doesn't match with your current password.";
        }

        return "Enter your current password.";
        
    }

    public function full_name()
    {   
        $full_name = "";

        $query = "SELECT `firstname`, `lastname` FROM " .$this->table. " WHERE id='" .$_SESSION['id']. "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($response['firstname']) && !empty($response['firstname']) && isset($response['lastname']) && !empty($response['lastname']))
        {
            return $response['firstname'].' '.$response['lastname'];
        }
        elseif(isset($response['firstname']) && !empty($response['firstname']))
        {
            return $response['firstname'];
        }
        elseif(isset($response['lastname']) && !empty($response['lastname']))
        {
            return $response['lastname'];
        }
        else
        {
            return "The name was not specified";
        }
        
    }

    function _exists()
    {
        $query = "SELECT email FROM " .$this->table. " WHERE email='" .$this->email. "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(isset($response['email']) && !empty($response['email']))
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

    function _admin_exists()
    {
        $query = "SELECT * FROM " .$this->table. " WHERE role='admin'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($response == false) 
        { 
            return false;
        } 
        else 
        { 
            return true;
        }  
    }

    // no need in "delete()" function... 
    public function delete()
    {
        return;
    }

}