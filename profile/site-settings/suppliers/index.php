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

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/suppliers";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$supplier = new Supplier($db_conn, $auth);
$suppliers_list = $supplier->read();

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
                        <h4>All suppliers</h4>
                        
                        <?php if(empty($suppliers_list)) : ?>
                            <hr/>
                            <p>There is no suppliers in our database, would you like to <a href="create">add one</a>?
                        <?php else : ?>
                        <br/>

                        <p><small><a href="create">Add new supplier</a></small></p>

                        <div class="table-responsive">
                            <style>
                                table{
                                    width:100%;
                                    height: 100%;
                                    overflow: hidden;
                                }
                                table tr{
                                    height: 100%;
                                }
                                table tr:hover a{
                                    color: rgba(0, 0, 0, 1);
                                }
                                table td{
                                    padding: 0 !important;
                                }
                                table td div{
                                    width:100%; 
                                    height: 100%;
                                }
                                table a.row-link{
                                    display: inline-block;
                                    height: 100%;
                                    width: 100%;
                                    padding: .75em;
                                    color: rgba(0, 0, 0, .7);
                                }
                              
                                td.tr-name{
                                    width: 200px;
                                }
                                td.tr-address {
                                    width: 250px;
                                }
                                td.tr-actions{
                                    width:100px;
                                }
                                small.line-clamp{
                                    max-height: 100px;
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;
                                }
                            </style>

                            <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($suppliers_list as $row) : ?>
                                <tr class="items-row">
                                    <!-- Supplier ID -->
                                    <th>
                                        <?php echo $row["id"]; ?>
                                    </th>

                                    <!-- Supplier Name -->
                                    <td class="tr-name"> 
                                        
                                        <div>
                                            <a href="<?php echo "supplier?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["name"]; ?>">
                                            <small class="line-clamp"><?php echo $row["name"]; ?></small>
                                            </a>
                                        </div> 
                                        
                                    </td>

                                    <!-- Supplier Email -->
                                    <td > 
                                        
                                        <div>
                                            <a href="<?php echo "supplier?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["name"]; ?>">
                                            <small><?php echo $row["email"]; ?></small>
                                            </a>
                                        </div> 
                                        
                                    </td>

                                    <!-- Supplier Address -->
                                    <td class="tr-address"> 
                                        
                                        <div>
                                            <a href="<?php echo "supplier?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["name"]; ?>">
                                            <small class="line-clamp"><?php echo $row["address"]; ?></small>
                                            </a>
                                        </div> 
                                        
                                    </td>

                                    <!-- Supplier Phone -->
                                    <td> 
                                        
                                        <div>
                                            <a href="<?php echo "supplier?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["name"]; ?>">
                                                <?php if(!empty($row["mobile"])) : ?>
                                                    <small><?php echo $supplier->cute_phone_number($row["mobile"]); ?><br><small>mobile</small></small>
                                                <?php elseif(!empty($row["work"])) : ?>
                                                    <small><?php echo $supplier->cute_phone_number($row["work"]); ?><br><small>work</small></small>
                                                <?php elseif(!empty($row["home"])) : ?>
                                                    <small><?php echo $supplier->cute_phone_number($row["home"]); ?><br><small>home</small></small>
                                                <?php endif; ?>
                                            </a>
                                        </div> 
                                        
                                    </td>

                                    <!-- Souvenir Actions -->
                                    <td class="tr-actions">
                                        <div style="padding: 10px;">
                                            <a href="<?php echo "delete?id=".$row["id"].""; ?>" class="text-danger"><i class="fa fa-fw fa-trash-alt"></i></a>     
                                            <a href="<?php echo "update?id=".$row["id"].""; ?>" class="text-info"><i class="fa fa-fw fa-edit"></i></a>
                                        </div> 
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            </table>

                            

                        </div>
                        <?php endif; ?>

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