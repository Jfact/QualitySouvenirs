<?php 
    require "./../app/_app-layout/header.php";
?>

<?php

include_once './../app/config/database.php';
include_once './../app/config/helpers.php';
include_once './../app/models/authentication.php';
include_once './../app/models/user.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);
$helpers = new Helpers();

if(!$auth->access())
{
    $page = "profile/change-password";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$cur_passwordErr = $new_passwordErr = $re_new_passwordErr = "";
$update_status = "";

$user = new User($db_conn);
$user->read();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // $input_firstname =  $helpers->test_input($_POST["firstname"]);
    // $input_lastname =  $helpers->test_input($_POST["lastname"]);
    // $input_email =  $helpers->test_input($_POST["email"]);
    // $input_mobile =  $helpers->test_input($_POST["mobile"]);
    // $input_address =  $helpers->test_input($_POST["address"]);

    if(empty($_POST["current-password"]) || empty($_POST["new-password"]) || empty($_POST["re-new-password"]))
    {   
        $update_status = "Fields errors, check provided data and try again.";
        
        if(empty($_POST["current-password"]))
        {
            $cur_passwordErr = "your current password is required, before we can update your data";
        }
        if(empty($_POST["new-password"]))
        {
            $new_passwordErr = "your new password is required";
        }
        if(empty($_POST["re-new-password"]))
        {
            $re_new_passwordErr = "please repeat your new password";
        } 
        if(!empty($_POST["new-password"]) && !empty($_POST["re-new-password"]))
        {
            if ($_POST["new-password"] != $_POST["re-new-password"]) 
            {
                $update_status = "Fields errors, check provided data and try again.";
                $re_new_passwordErr = "new password and repeated password doesn't match";
            }
        }
    }
    else
    {
        
        if ($_POST["new-password"] != $_POST["re-new-password"]) 
        {
            $update_status = "Fields errors, check provided data and try again.";
            $re_new_passwordErr = "new password and repeated password doesn't match";
        }
        else
        {
            
            $user->password =  $_POST["new-password"];

            $update_status = $user->update_password($_POST['current-password']);
            
            if($update_status == 1)
            {
                header("Location: ".$hlpr->core_link("profile"));
                exit;
            }
            
        }
    }
    
}
else
{
    $input_firstname =  $user->firstname;
    $input_lastname =  $user->lastname;
    $input_email =  $user->email;
    $input_mobile =  $user->mobile;
    $input_address =  $user->address;
}

?>

<main>
    <div class="container-fluid">   
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8"> 
                
                <div class="page-title">
                    <h4><?php echo $user->firstname." ".$user->lastname ?> | <small class="text-muted"> edit profile</small></h4>
                    <br/>
                    <p class="text-muted"></p>
                    <br/><br/> 
                </div>    
                        
                <div class="page-content row">
                    <div class="col-sm-auto content-side-menu">
                        <?php 
                            require "./../app/_app-layout/partials/profile/side-menu.php";
                        ?>
                    </div>
                    
                    <div class="col-sm-auto content-data">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <span class="text-danger form-error"><small><?php echo $update_status;?></small></span> 
                            <br/><br/>   
                            <p>Password settings</p>
                            <hr/>
                            <div class="profile-details-block form-group">
                                <label for="new-password"><small><small><i class="fa fa-fw fa-user"></i></small> <span class="text-muted">New password</span></small></label>
                                <input type="password" name="new-password" id="new-password" class="form-control" placeholder="new password">
                                <br/>
                                <span class="text-danger form-error"><small><?php echo $new_passwordErr;?></small></span>
                            </div>

                            <div class="profile-details-block form-group">
                                <label for="re-new-password"><small><small><i class="fa fa-fw fa-user"></i></small> <span class="text-muted">Repeat new password</span></small></label>
                                <input type="password" name="re-new-password" id="re-new-password" class="form-control" placeholder="repeat new password">
                                <br/>
                                <span class="text-danger form-error"><small><?php echo $re_new_passwordErr;?></small></span>
                                <br/>
                            </div>

                            <p>Current password</p>
                            <hr/>
                            
                            <div class="profile-details-block form-group">
                                <label for="edit-current-password"><small><small><i class="fa fa-fw fa-lock"></i></small> <span class="text-muted">Current password</span></small></label>
                                <input type="password" name="current-password" id="edit-current-password" class="form-control" placeholder="current password">
                                <br/>
                                <span class="text-danger form-error"><small><?php echo $cur_passwordErr;?></small></span>
                            </div>
                            <p class="text-muted"><small><small>Enter your current password and press update button, to update your details.</small></small></p>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</main>



<?php 
    require "./../app/_app-layout/footer.php";
?>