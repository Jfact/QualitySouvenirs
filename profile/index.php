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
    $page = "profile";
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
                    <h4><?php echo $user->firstname." ".$user->lastname ?> | <small class="text-muted"> account details</small></h4>
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
                        <div class="profile-details-block">
                            <p><small><small><i class="fa fa-fw fa-envelope"></i></small> <span class="text-muted">email address</span></small></p>
                            <hr/>
                            <p style="margin-left: 26px;"><?php echo $user->email ?></p>
                            <br/><br/>
                        </div>
                        
                        <div class="profile-details-block">
                            <p><small><small><i class="fa fa-fw fa-mobile"></i></small> <span class="text-muted">mobile number</span></small></p>
                            <hr/>
                            <?php 
                            if(!empty($user->mobile)) 
                            { 
                            ?> 
                                <p style="margin-left: 26px;"><?php echo $user->mobile ?></p>
                            <?php 
                            }
                            else
                            {
                            ?>
                                <p style="margin-left: 26px;">Please, go to <a href="edit">"Edit profile"</a> page and add your mobile number. </p>
                            <?php
                            }
                            ?>
                            
                            <br/><br/>
                        </div>
                        
                        <div class="profile-details-block">
                            <p><small><small><i class="fa fa-fw fa-location-arrow"></i></small> <span class="text-muted">physical address</span></small></p>
                            <hr/>
                            <?php 
                            if(!empty($user->address)) 
                            { 
                            ?> 
                                <p style="margin-left: 26px;"><?php echo $user->address ?></p>
                            <?php 
                            }
                            else
                            {
                            ?>
                                <p style="margin-left: 26px;">Please, go to <a href="edit">"Edit profile"</a> page and add your physical address.</a></p>
                            <?php
                            }
                            ?>
                        </div>
                        
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