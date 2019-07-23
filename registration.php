
<?php 
    require "app/_app-layout/header.php";
?>

<?php
    include_once 'app/config/database.php';
    include_once 'app/config/helpers.php';
    include_once 'app/models/authentication.php';
    include_once 'app/models/user.php';

    $input_fname = $input_lname = $input_email = "";
    $fnameErr = $lnameErr = $emailErr = $passwordErr = $re_passwordErr = "";
    $registration_status = "";


    $database = new Database();
    $db_conn = $database->connection();

    $auth = new Authentication($db_conn);
    $user = new User($db_conn);
    $helpers = new Helpers();

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {   
        $input_fname = $_POST["firstname"];
        $input_lname = $_POST["lastname"];
        $input_email = $_POST["email"];

        if (
            !empty($_POST["firstname"]) && 
            !empty($_POST["lastname"]) && 
            !empty($_POST["email"]) && 
            !empty($_POST["password"]) && 
            !empty($_POST["repeated-password"])
        ) 
        {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || ($_POST["password"] != $_POST["repeated-password"])) 
            {
                $registration_status = "Fields errors, check provided data and try again.";
                
                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                {
                    $emailErr = "invalid email format";
                }
                if($_POST["password"] != $_POST["repeated-password"])
                {
                    $re_passwordErr = "repeated password doesn't match with password"; 
                }
                
            }
            else
            {

                $user->firstname = $helpers->test_input($_POST["firstname"]);
                $user->lastname = $helpers->test_input($_POST["lastname"]);
                $user->email = $helpers->test_input($_POST["email"]);
                $user->password = $helpers->test_input($_POST["password"]);
                $registration_status = $user->create();
                
                if($registration_status == 1)
                {
                    header("Location: login?email=".$user->email."");
                    exit;
                }
            }
            
        }
        else 
        {
            $registration_status = "Fields errors, check provided data and try again.";

            if (empty($_POST["firstname"])) 
            {
                $fnameErr = "first name is required";
            }
            if (empty($_POST["lastname"])) 
            {
                $lnameErr = "last name is required";
            }
            if (empty($_POST["email"])) 
            {
                $emailErr = "email address is required";
            }
            if (empty($_POST["password"])) 
            {
                $passwordErr = "password is required";
            }
            if (empty($_POST["repeated-password"])) 
            {
                $re_passwordErr = "repeat your password please";
            }
        }
        
    }
?>

<main>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="auth-form" method="post" action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h5>Registration Form</h5>
                    <hr/>
                    
                    <?php 
                    if(!$user->_admin_exists()) 
                    {
                    ?> 
                        <p class="text-muted"><small>No admin user was found, please create admin user <a href="app/config/add-admin">here</a></small></p> 
                    <?php 
                    }
                    ?>
                    
                    <span class="text-danger form-error"><small><?php echo $registration_status;?></small></span>
                    <br/><br/>
                    <div class="form-group">
                        <label for="register-fname" class="col-sm-3 col-form-label">First name</label>
                        <input id="register-fname" name="firstname" class="form-control form-control-cstm" type="text" placeholder="John" value="<?php echo $input_fname; ?>" >
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $fnameErr;?></small></span>
                    </div>
                    <div class="form-group">
                        <label for="register-lname" class="col-sm-3 col-form-label">Last name</label>
                        <input id="register-lname" name="lastname" class="form-control form-control-cstm" type="text" placeholder="Smith" value="<?php echo $input_lname; ?>" >
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $lnameErr;?></small></span>
                    </div>
                    <div class="form-group">
                        <label for="register-email" class="col-sm-3 col-form-label">Email</label>
                        <input id="register-email" name="email" class="form-control form-control-cstm" type="email" placeholder="john.smith@qs.co.nz" value="<?php echo $input_email; ?>" >
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $emailErr;?></small></span>
                    </div>

                    <br/>

                    <div class="form-group">
                        <label for="register-password" class="col-sm-3 col-form-label">Password</label>
                        <input id="register-password" name="password" class="form-control form-control-cstm" type="password" placeholder="password" >
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $passwordErr;?></small></span>
                    </div>

                    <div class="form-group">
                        <label for="register-re-password" class="col-sm-3 col-form-label">Repeat password</label>
                        <input id="register-re-password" name="repeated-password" class="form-control form-control-cstm" type="password" placeholder="repeat-password" >
                        <br/>
                        <span class="text-danger form-error"><small><?php echo $re_passwordErr;?></small></span>
                    </div>
                    
                    <br/>

                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <button class="btn btn-primary" type="submit">Register</button>
                        </div>
                        <div class="col-sm-6 text-right">
                            <p><small><a href="login">Already have an account?</a></small></p>
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