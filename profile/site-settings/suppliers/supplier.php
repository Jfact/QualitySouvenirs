<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/config/helpers.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/supplier.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);
$helpers = new Helpers();

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: ../suppliers");
    exit;
}

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/suppliers/supplier?id=".$_GET['id']."";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$supplier = new Supplier($db_conn, $auth);

$supplier->id = $_GET['id'];
$supplier_details = $supplier->read_single();

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
                        
                        <h4>Supplier</h4>
                        <hr/>
                        <br/>

                        <table class="details">
                        <tbody>
                            <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-tag"></i> Name</small></p></td><td><p><em><?php echo $supplier_details['name'] ?></em></p></td></tr>
                            <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-envelope"></i> Email</small></p></td><td><p><em><?php echo $supplier_details['email'] ?></em></p></td></tr>
                            <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-location-arrow"></i> Address</small></p></td><td><p><em><?php echo $supplier_details['address'] ?></em></p></td></tr>
                            
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
                                <td><p><em><?php if(!empty($supplier_details['mobile'])) echo $supplier->cute_phone_number($supplier_details["mobile"]); else echo "not specified"; ?></em></p>
                            </td>
                            </tr>

                            <tr>
                            <td style="width:200px;">
                                <p class="text-muted"><small><i class="fa fa-fw fa-phone"></i> Work</small></p></td>
                                <td><p><em><?php if(!empty($supplier_details['work'])) echo $supplier->cute_phone_number($supplier_details["work"]); else echo "not specified"; ?></em></p>
                            </td>
                            </tr>

                            <tr>
                            <td style="width:200px;">
                                <p class="text-muted"><small><i class="fa fa-fw fa-phone"></i> Home</small></p></td>
                                <td><p><em><?php if(!empty($supplier_details['home'])) echo $supplier->cute_phone_number($supplier_details["home"]); else echo "not specified"; ?></em></p>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                            
                        <br/>
                        <hr/>

                        <div class="float-left">
                            <a href="../suppliers"><i class="fa fa-fw fa-long-arrow-alt-left"></i> go back</a>
                        </div>
                        
                        <div class="float-right text-right">
                        
                            <a href="<?php echo "delete?id=".$_GET["id"]."" ?>" class="text-danger"><i class="fa fa-fw fa-trash-alt"></i></a> 
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