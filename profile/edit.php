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
    $page = "profile/edit";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$cur_passwordErr = $emailErr = $mobileErr = "";
$update_status = "";

$input_firstname = $input_lastname = $input_email = $input_mobile = $input_address = ""; 

$user = new User($db_conn);
$user->read();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $input_firstname =  $helpers->test_input($_POST["firstname"]);
    $input_lastname =  $helpers->test_input($_POST["lastname"]);
    $input_email =  $helpers->test_input($_POST["email"]);
    $input_mobile =  $helpers->test_input($_POST["mobile"]);
    $input_address =  $helpers->test_input($_POST["address"]);

    if(empty($_POST["current-password"]))
    {
        $cur_passwordErr = "your current password is required, before we can update your data";
        $update_status = "Fields errors, check provided data and try again.";
    }
    else
    {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
        {
            $update_status = "Fields errors, check provided data and try again.";
            $emailErr = "invalid email format";
            if (!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) 
            {   
                $mobileErr = "invalid phone format";
            } 
        }
        else
        {
            if (!$user->validate_phone_number($input_mobile)) 
            {
                $update_status = "Fields errors, check provided data and try again.";
                $mobileErr = "invalid phone format";
            } 
            else
            {
                $user->firstname =  $helpers->test_input($_POST["firstname"]);
                $user->lastname =  $helpers->test_input($_POST["lastname"]);
                $user->email =  $helpers->test_input($_POST["email"]);
                $user->mobile =  $helpers->test_input($_POST["mobile"]);
                $user->address =  $helpers->test_input($_POST["address"]);

                $update_status = $user->update($_POST['current-password']);
                
                if($update_status == 1)
                {
                    header("Location: profile");
                    exit;
                }
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
                        <form method="POST" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"])?>">
                            <span class="text-danger form-error"><small><?php echo $update_status;?></small></span> 
                            <br/><br/>   
                            <p>Personal details</p>
                            <hr/>
                            <div class="profile-details-block form-group">
                                <label for="edit-firstname"><small><small><i class="fa fa-fw fa-user"></i></small> <span class="text-muted">first name address</span></small></label>
                                <input type="text" name="firstname" id="edit-firstname" class="form-control" value="<?php echo $input_firstname ?>">
                                <br/>
                            </div>

                            <div class="profile-details-block form-group">
                                <label for="edit-lastname"><small><small><i class="fa fa-fw fa-user"></i></small> <span class="text-muted">last name address</span></small></label>
                                <input type="text" name="lastname" id="edit-lastname" class="form-control" value="<?php echo $input_lastname ?>">
                                <br/><br/>
                            </div>

                            <p>Contact details</p>
                            <hr/>
                            <div class="profile-details-block form-group">
                                <label for="edit-email"><small><small><i class="fa fa-fw fa-envelope"></i></small> <span class="text-muted">email address</span></small></label>
                                <input type="email" name="email" id="edit-email" class="form-control" value="<?php echo $input_email ?>">
                                <br/>
                                <span class="text-danger form-error"><small><?php echo $emailErr;?></small></span>
                            </div>

                            <div class="profile-details-block form-group">
                                <label for="edit-mobile"><small><small><i class="fa fa-fw fa-mobile"></i></small> <span class="text-muted">mobile number</span></small></label>
                                <input type="text" name="mobile" id="edit-mobile" class="form-control" value="<?php echo $input_mobile ?>"> 
                                <br/>
                                <span class="text-danger form-error"><small><?php echo $mobileErr;?></small></span>
                            </div>
                            
                            <div class="profile-details-block form-group">
                                <label for="edit-address"><small><small><i class="fa fa-fw fa-location-arrow"></i></small> <span class="text-muted">physical address</span></small></label>
                                <input type="text" name="address" id="edit-address" class="form-control" value="<?php echo $input_address ?>"> 
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