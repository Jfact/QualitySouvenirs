<?php 

class Authentication
{
    private $table = 'users';

    public $email;
    public $password;
    public $role;

    public $user_id;
    public $token;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login()
    {
        $query = "SELECT id, email, pass, token FROM " .$this->table. " WHERE email='" .$this->email. "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($response['email']) && isset($response['pass']))
        {
            if(password_verify($this->password, $response['pass']))
            {
                
                $_SESSION['id'] = $response['id'];
                $_SESSION['token'] = $response['token'];
                return true;
            }
            else
            {
                return "Password and email address doesn't match.";
            }           
        }

        return "User with such email address doen't exist in our database.";
    }

    public function logout()
    {
        unset($_SESSION['id']);
        unset($_SESSION['token']);

        session_destroy();
    }

    public function access(string $restrict = null)
    {
        if(isset($_SESSION['id']) && isset($_SESSION['token']))
        {
            $query = "SELECT `id`, `token`, `role` FROM " .$this->table. " WHERE id='" .$_SESSION['id']. "'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($response['id']) && isset($response['token']) && isset($response['role']))
            {
                if($response['token'] == $_SESSION['token'])
                {
                    $this->role = $response['role'];

                    if($restrict == 'admin')
                    {
                        if($this->role == 'admin')
                        {
                            return true;
                        }

                        return false;
                    }

                    return true;
                }
            }
        }
       
        return false;
    }


    



    // private function set()
    // {
    //     if(isset($_GET['id']) && isset($_GET['token']))
    //         {
    //             return true;
    //         }
    //     else
    //     {
    //         return false;
    //     }      
    // }
}