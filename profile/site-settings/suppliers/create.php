<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/supplier.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/suppliers/create";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$supplier = new Supplier($db_conn, $auth);

//$supplier_details = $supplier->read_single();

$input_name = $input_email = $input_address = $input_mobile = $input_home = $input_work = "";

$nameErr= $emailErr= $addressErr= $mobileErr= $homeErr= $workErr= "";

$errMessage = $phone_requiredErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    $input_name = $_POST['name'];
    $input_email = $_POST['email'];
    $input_address = $_POST['address'];
    $input_mobile = $_POST['mobile'];
    $input_home = $_POST['home'];
    $input_work = $_POST['work'];

    if(
        !empty($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['address']) &&
        (!empty($_POST['mobile']) || !empty($_POST['home']) || !empty($_POST['work']))
    )
    {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
        {
            $errMessage = "Fields errors, check provided data and try again.";
            $emailErr = "invalid email format";

            if(!empty($_POST['mobile']) && !$supplier->validate_phone_number($_POST['mobile']))
            {
                $mobileErr = "invalid mobile phone format"; 
            }
            if(!empty($_POST['home']) && !$supplier->validate_phone_number($_POST['home']))
            {
                $homeErr = "invalid home phone format"; 
            }  
            if(!empty($_POST['work']) && !$supplier->validate_phone_number($_POST['work']))
            {
                $workErr = "invalid work phone format"; 
            } 
        }
        else
        { 
            if(!empty($_POST['mobile']) && !$supplier->validate_phone_number($_POST['mobile']))
            {
                $mobileErr = "invalid mobile phone format"; 
            }
            else if(!empty($_POST['home']) && !$supplier->validate_phone_number($_POST['home']))
            {
                $homeErr = "invalid home phone format"; 
            }  
            else if(!empty($_POST['work']) && !$supplier->validate_phone_number($_POST['work']))
            {
                $workErr = "invalid work phone format"; 
            }
            else
            {
                $supplier->name = $_POST['name'];
                $supplier->email = $_POST['email'];
                $supplier->address = $_POST['address'];
                $supplier->mobile = $_POST['mobile'];
                $supplier->home = $_POST['home'];
                $supplier->work = $_POST['work'];
        
                if($supplier->_exists())
                {
                    $errMessage = "The supplier with such email address already exists.";
                }
                else
                {
                    if($supplier->create())
                    {
                        header("Location: ../suppliers");
                        exit;
                    }
                    else
                    {
                        $errMessage = "Woops! Something went wrong, try gain later.";
                    }
                }
            }
        }
    }
    else
    {
        $errMessage = "Fields errors, check provided data and try again.";
        if(empty($_POST['name']))
        {
            $nameErr="name is required";
        }
        if(empty($_POST['email']))
        {
            $emailErr="email is required";
        }
        if(empty($_POST['address']))
        {
            $addressErr="address is required";
        }
        if(empty($_POST['mobile']) && empty($_POST['home']) && empty($_POST['work']))
        {
            $phone_requiredErr = "At least one phone number is required"; 
        }
    }
}

?>

<main>
    <div class="container-fluid">   
        <div class="row">
            <div class="col-sm-2"></div>

            <div class="col-sm-8"> 
                
                <div class="page-title">
                    <h4><?php echo $user->firstname." ".$user->lastname." <small class='text-muted'>".$user->role."</small>" ?> | <small class="text-muted"> Site settings | Categories</small></h4>
                    <br/>
                    <p class="text-muted"></p>
                    <br/><br/> 
                </div>    
                        
                <div class="page-content row">
                    <div class="col-sm-auto content-side-menu">
                    <?php 
                        require "./../../../app/_app-layout/partials/profile/site-settings/side-menu.php";
                    ?>
                    </div>

                    <div class="col-sm content-data">
                        
                        <h4>Create supplier</h4>
                        <hr/>
                        <br/>
                        
                        <form method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"]); ?>">
                            <span class="text-danger form-error"><small><?php echo $errMessage;?></small></span>
                            <br/><br/>
                            <div class="form-group">
                                <label for="supplier-name"><small><span class="text-muted">name</span></small></label>
                                <input type="text" name="name" id="supplier-name" class="form-control" placeholder="supplier name" value="<?php echo $input_name; ?>">
                                <span class="text-danger form-error"><small><?php echo $nameErr;?></small></span>
                                <br/>
                            </div>

                            <div class="form-group">
                                <label for="supplier-email"><small><span class="text-muted">email</span></small></label>
                                <input type="email" name="email" id="supplier-email" class="form-control" placeholder="supplier email" value="<?php echo $input_email; ?>">
                                <span class="text-danger form-error"><small><?php echo $emailErr;?></small></span>
                                <br/>
                            </div>

                            <div class="form-group">
                                <label for="supplier-address"><small><span class="text-muted">address</span></small></label>
                                <input type="text" name="address" id="supplier-address" class="form-control" placeholder="supplier address" value="<?php echo $input_address; ?>">
                                <span class="text-danger form-error"><small><?php echo $addressErr;?></small></span>
                                <br/><br/>
                            </div>

                            <p class="text-muted">phone details</p>
                            <p class="text-muted"><small>atleast one number should be entered</small></p>
                            <hr/>

                            <div class="form-group">
                                <label for="supplier-mobile"><small><span class="text-muted">mobile</span></small></label>
                                <input type="text" name="mobile" id="supplier-mobile" class="form-control" placeholder="supplier mobile phone" value="<?php echo $input_mobile; ?>">
                                <span class="text-danger form-error"><small><?php echo $mobileErr;?></small></span>
                                <span class="text-danger form-error"><small><?php echo $phone_requiredErr;?></small></span>
                                <br/>
                            </div>

                            <div class="form-group">
                                <label for="supplier-home"><small><span class="text-muted">home</span></small></label>
                                <input type="text" name="home" id="supplier-home" class="form-control" placeholder="supplier home phone" value="<?php echo $input_home; ?>">
                                <span class="text-danger form-error"><small><?php echo $homeErr;?></small></span>
                                <span class="text-danger form-error"><small><?php echo $phone_requiredErr;?></small></span>
                                <br/>
                            </div>

                            <div class="form-group">
                                <label for="supplier-work"><small><span class="text-muted">work</span></small></label>
                                <input type="text" name="work" id="supplier-work" class="form-control" placeholder="supplier work phone" value="<?php echo $input_work; ?>">
                                <span class="text-danger form-error"><small><?php echo $workErr;?></small></span>
                                <span class="text-danger form-error"><small><?php echo $phone_requiredErr;?></small></span>
                                <br/><br/>
                            </div>

                            <button type="submit" class="btn btn-primary"> Create </button>
                        </form>
                        <br/>
                        <hr/>

                        <div class="float-left">
                            <a href="../suppliers"><i class="fa fa-fw fa-long-arrow-alt-left"></i> go back</a>
                        </div>
                        
                        <div class="float-right text-right">
                        
                            <a href="<?php echo "supplier?id=".$_GET["id"]."" ?>" class="text-primary">Details</a> 
                            <a href="<?php echo "update?id=".$_GET["id"]."" ?>" class="text-info"><i class="fa fa-fw fa-edit"></i></a>

                        </div>
                        
                    </div>
                </div>

            </div>

            <div class="col-sm-2"></div>
        </div>
    </div>
</main>

<?php 
    require "./../../../app/_app-layout/footer.php";
?>