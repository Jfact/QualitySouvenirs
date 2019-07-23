<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/supplier.php';
include_once './../../../app/models/souvenir.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: ../suppliers");
    exit;
}

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/suppliers/delete?id=".$_GET['id']."";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$supplier = new Supplier($db_conn, $auth);

$supplier->id = $_GET['id'];
$details = $supplier->read_single();

$souvenir = new Souvenir($db_conn, $auth);
$souvenirs_list = $souvenir->readByCategory($supplier->id);

$errMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    if(count($souvenirs_list) > 0)
    {
        $errMessage = "You are trying to delete supplier that has assigned items.";
    }
    else
    {
        if($supplier->delete())
        {
            header("Location: ../suppliers");
            exit;
        }
        else
        {
            $errMessage = "Woops! Couldn't delete this item, try gain later";
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
                    <h4><?php echo $user->firstname." ".$user->lastname." <small class='text-muted'>".$user->role."</small>" ?> | <small class="text-muted"> Site settings | Suppliers</small></h4>
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
                        
                        <h4>Delete supplier</h4>
                        <hr/>
                        <br/>
                        
                        <table class="details">
                        <tbody>
                            <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-tag"></i> Name</small></p></td><td><p><em><?php echo $details['name'] ?></em></p></td></tr>
                            <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-envelope"></i> Email</small></p></td><td><p><em><?php echo $details['email'] ?></em></p></td></tr>
                            <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-location-arrow"></i> Address</small></p></td><td><p><em><?php echo $details['address'] ?></em></p></td></tr>
                            
                            <tr>
                                <td colspan="3">
                                    <br>
                                    <p class="text-muted" style="padding:0; margin:0;"><small>Phone contact details</small></p>
                                </td>
                            </tr>

                            <tr><td colspan="3"><hr></td></tr>

                            <tr>
                            <td style="width:200px;">
                                <p class="text-muted"><small><i class="fa fa-fw fa-mobile-alt"></i> Mobile</small></p></td>
                                <td><p><em><?php if(!empty($details['mobile'])) echo $supplier->cute_phone_number($details["mobile"]); else echo "not specified"; ?></em></p>
                            </td>
                            </tr>

                            <tr>
                            <td style="width:200px;">
                                <p class="text-muted"><small><i class="fa fa-fw fa-phone"></i> Work</small></p></td>
                                <td><p><em><?php if(!empty($details['work'])) echo $supplier->cute_phone_number($details["work"]); else echo "not specified"; ?></em></p>
                            </td>
                            </tr>

                            <tr>
                            <td style="width:200px;">
                                <p class="text-muted"><small><i class="fa fa-fw fa-phone"></i> Home</small></p></td>
                                <td><p><em><?php if(!empty($details['home'])) echo $supplier->cute_phone_number($details["home"]); else echo "not specified"; ?></em></p>
                            </td>
                            </tr>
                        </tbody>
                        </table>

                        <br/>

                        <p class="text-muted">
                            <small>
                                <?php
                                if(count($souvenirs_list) > 0)
                                {
                                    if(count($souvenirs_list) > 1)
                                    {
                                        echo count($souvenirs_list).' items assigned to supplier <a href="../souvenirs"><em>"'.$details['name'].'"</a></em>';
                                    }
                                    else
                                    {
                                        echo count($souvenirs_list).' item assigned to supplier <a href="../souvenirs/supplier?id='.$supplier->id.'" target="new" data-toggle="tooltip" title="Open items of category '.$details['name'].' in a new tab"><em>"'.$details['name'].'"</a></em>';
                                    }
                                }
                                else
                                {
                                    echo 'No items assigned to supplier <a href="souvenirs"><em>"'.$details['name'].'"</a></em>';
                                }

                                ?> 
                            </small>
                        </p>
                        
                        <form method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"])."?id=".$_GET['id']; ?>">
                            <p class="text-muted"><small>Are you sure you want to delete this item?</small></p>
                            <span class="text-danger form-error"><small><?php echo $errMessage;?></small></span>
                            <?php if(count($souvenirs_list) > 0) : ?>
                                <button type="submit" class="btn btn-danger" disabled> Delete </button>
                                <br><br><span class="text-muted form-error"><small><small>Cannot delete supplier <em>"<?php echo $details['name'] ?>" as it has assigned items. <br> Delete items and try again.</em></small></small></span>
                            <?php else : ?>
                                <button type="submit" class="btn btn-danger"> Delete </button>
                            <?php endif; ?>
                        </form>

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