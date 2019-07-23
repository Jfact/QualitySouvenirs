<?php 
    require "./../../app/_app-layout/header.php";
?>

<?php

include_once './../../app/config/database.php';
include_once './../../app/config/helpers.php';
include_once './../../app/models/authentication.php';
include_once './../../app/models/user.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);
$helpers = new Helpers();

if(!$auth->access('admin'))
{
    $page = "profile/site-settings";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

?>

<main>
    <div class="container-fluid">   
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8"> 
                
                <div class="page-title">
                    <h4><?php echo $user->firstname." ".$user->lastname." <small class='text-muted'>".$user->role."</small>" ?> | <small class="text-muted"> Site settings</small></h4>
                    <br/>
                    <p class="text-muted"></p>
                    <br/><br/> 
                </div>    
                        
                <div class="page-content row">
                    <div class="col-sm-auto content-side-menu">
                    <?php 
                        require "./../../app/_app-layout/partials/profile/site-settings/side-menu.php";
                    ?>
                    </div>
                    
                    <div class="col-sm-auto content-data">
                        
                        
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</main>



<?php 
    require "./../../app/_app-layout/footer.php";
?>