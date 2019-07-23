<?php 
    require "app/_app-layout/header.php";
?>


<?php
    // define variables and set to empty values
    include_once 'app/config/database.php';
    include_once 'app/models/authentication.php';
    
    $database = new Database();
    $db_conn = $database->connection();

    $auth = new Authentication($db_conn);

    $emailErr = $passwordErr = '';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        
        if (!empty($_POST["email"]) && !empty($_POST["password"])) 
        {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
            {
                $emailErr = "invalid email format"; 
            }
            else
            {
                $auth->email = test_input($_POST["email"]);
                $auth->password = test_input($_POST["password"]);
                $auth->login();
                echo $auth->db_email;
                echo $auth->db_passw;
            }
            
        }
        else 
        {
            if (empty($_POST["email"])) 
            {
                $emailErr = "email address is required";
            }
            if (empty($_POST["password"])) 
            {
                $passwordErr = "password is required";
            }
        }
    }
    
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        
        return $data;
    }
?>

<main>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>

</main>

<?php 
    require "app/_app-layout/footer.php";
?>