
<?php 
    require "app/_app-layout/header.php";
?>

<?php
    include_once 'app/config/database.php';
    include_once 'app/config/helpers.php';
    include_once 'app/models/authentication.php';

    $input_email = ""; 
    $login_status = "";
    $emailErr = $passwordErr = "";

    $return_to = "";

    $database = new Database();
    $db_conn = $database->connection();

    $auth = new Authentication($db_conn);
    $helpers = new Helpers();
    
    if(isset($_GET['email']))
    {   
        $input_email = $_GET['email'];
    }

    if(isset($_GET['returnto']))
    {   
        $return_to = "returnto=".$_GET['returnto'];
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {   
        $input_email = $_POST["email"];
        
        if (!empty($_POST["email"]) && !empty($_POST["password"])) 
        {
            
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
            {
                $emailErr = "invalid email format"; 
            }
            else
            {
                $auth->email = $helpers->test_input($_POST["email"]);
                $auth->password = $helpers->test_input($_POST["password"]);
                $login_status = $auth->login();

                if($login_status == 1)
                {
                    if(isset($_GET['returnto']))
                    {
                        header("Location: ".$hlpr->core_link($_GET['returnto']));
                        exit;
                    }
                    header("Location: profile");
                    exit;
                }
            }
            
        }
        else 
        {
            $login_status = "Fields errors, check provided data and try again.";;

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
?>

<main>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="auth-form" method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"])."?".$return_to; ?>">
                    <h5>Log-In Form</h5>
                    <hr/>
                    <span class="text-danger form-error"><small><?php echo $login_status;?></small></span>
                    <br/><br/>
                    <div class="form-group">
                        <label for="login-email" class="col-sm-2 col-form-label">Email</label>
                        <input id="login-email" name="email" class="form-control form-control-cstm" type="email" placeholder="enter email address" value="<?php echo $input_email; ?>" required>
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $emailErr;?></small></span>
                    </div>

                    <br/>

                    <div class="form-group">
                        <label for="login-password" class="col-sm-2 col-form-label">Password</label>
                        <input id="login-password" name="password" class="form-control form-control-cstm" type="password" placeholder="enter password" required> 
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $passwordErr;?></small></span>
                    </div>
                    
                    <br/>

                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <button class="btn btn-primary" type="submit">Log in</button>
                        </div>
                        <div class="col-sm-6 text-right">
                            <p><small><a href="assistance/details" >Forgot your details?</a></small></p>
                            <p><small><a href="registration" >Do not have an account?</a></small></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>

</main>


<?php 
    require "app/_app-layout/footer.php";
?>