<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/config/helpers.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/souvenir.php';
include_once './../../../app/models/supplier.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);
$helpers = new Helpers();

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: ../souvenirs");
    exit;
}

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/souvenirs";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$supplier = new Supplier($db_conn, $auth);
$supplier->id = $_GET['id'];
$supplier = $supplier->read_single();

$souvenir = new Souvenir($db_conn, $auth);
$souvenirs_list = $souvenir->readBySupplier($supplier['id']);

?>

<main>
    <div class="container-fluid">   
        <div class="row">
            <div class="col-sm-2"></div>

            <div class="col-sm-8"> 
                
                <div class="page-title">
                    <h4><?php echo $user->firstname." ".$user->lastname." <small class='text-muted'>".$user->role."</small>" ?> | <small class="text-muted"> Site settings | Souvenirs</small></h4>
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
                        <h4>"<?php echo $supplier['name']; ?>" souvenirs</h4>
                        
                        <?php if(empty($souvenirs_list)) : ?>
                            <hr/>
                            <p>There is no souvenirs in our database, would you like to <a href="create">add one</a>?
                        <?php else : ?>
                        <br/>

                        <p><small><a href="create">Add new souvenir</a></small></p>

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

                                td.tr-image{
                                    width:100px;
                                }
                                td.tr-title{
                                    width: 200px;
                                }
                                td.tr-price {
                                   width: 150px;
                                }
                                td.tr-stock{
                                    width: 110px;
                                } 
                                td.tr-desc{
                                    max-height: 100px;
                                }
                                td.tr-actions{
                                    width:100px;
                                }
                               
                                small.line-clamp{
                                    max-height: 100px;
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 6;
                                    -webkit-box-orient: vertical;
                                }
                                small.line-clamp-5{
                                    max-height: 100px;
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 5;
                                    -webkit-box-orient: vertical;
                                }
                                
                            </style>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($souvenirs_list as $row) : ?>
                                    <tr class="items-row">
                                         <!-- Souvenir ID -->
                                        <th>
                                            <?php echo $row["id"]; ?>
                                        </th>

                                        <!-- Souvenir Image -->
                                        <td class="tr-image"> 
                                            
                                            <div>
                                                <a href="<?php echo "souvenir?id=".$row["id"]."" ?>" class="row-link">
                                                <img width="100" height="100" class="image-table"
                                                        <?php if(!isset($row['image']) || !empty($row['image'])) : ?>
                                                            src="<?php echo $hlpr->core_link('app/src/imgs/souvenirs/').$row['image']; ?>"
                                                        <?php else : ?>
                                                            src="<?php echo $hlpr->core_link('app/src/imgs/souvenirs/default.png'); ?>"
                                                        <?php endif; ?>
                                                        alt="Souvenir Image" >
                                                </a>
                                            </div> 
                                            
                                        </td>

                                        <!-- Souvenir Title -->
                                        <td class="tr-ellipsis tr-title">
                                            
                                            <div>
                                                <a href="<?php echo "souvenir?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["title"]; ?>">
                                                <small class="line-clamp-5">
                                                    <strong><?php echo $row["title"]; ?></strong>
                                                    <br>
                                                    <small>
                                                        <?php echo $supplier['name']; ?>
                                                    </small>
                                                </small> 
                                                </a>
                                            </div> 
                                            
                                        </td>

                                        <!-- Souvenir Price -->
                                        <td class="tr-price">
                                            <div>
                                                <a href="<?php echo "souvenir?id=".$row["id"]."" ?>" class="row-link">
                                                <small><?php echo $souvenir->cute_price($row["price"]); ?></small>
                                                </a>
                                            </div> 
                                        </td>

                                        <!-- Souvenir Stock -->
                                        <td class="tr-stock">
                                            <div>
                                                <a href="<?php echo "souvenir?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["title"]; ?>">
                                                <small><i class="fa fa-fw fa-boxes"></i> <?php echo $row["stock"]; ?></small>
                                                </a>
                                            </div> 
                                        </td>

                                        <!-- Souvenir Description -->
                                        <td class="tr-desc">
                                            <div>
                                                <a href="<?php echo "souvenir?id=".$row["id"]."" ?>" class="row-link" data-toggle="tooltip" title="<?php echo $row["title"]; ?>">
                                                <small class="line-clamp"><?php echo htmlspecialchars($row["description"]); ?></small>
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