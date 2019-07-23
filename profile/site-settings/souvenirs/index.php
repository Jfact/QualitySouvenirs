<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/config/helpers.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/souvenir.php';
include_once './../../../app/models/category.php';
include_once './../../../app/models/supplier.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);
$helpers = new Helpers();

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/souvenirs";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$category = new Category($db_conn, $auth);
$categories_list = $category->read();
$category_details;

if(isset($_GET['category']))
{
    $category->id = $_GET['category'];
    $category_details = $category->read_single();
}

$supplier = new Supplier($db_conn, $auth);
$suppliers_list = $supplier->read();
$supplier_details;

if(isset($_GET['supplier']))
{
    $supplier->id = $_GET['supplier'];
    $supplier_details = $supplier->read_single();
}

$souvenir = new Souvenir($db_conn, $auth);
$parameter_link = "?";

if(!empty($category_details) && !empty($supplier_details))
{
    $souvenirs_list = $souvenir->readByCategoryAndSupplier($category->id, $supplier->id);
}
else if(!empty($category_details))
{
    $souvenirs_list = $souvenir->readByCategory($category->id);
}
else if(!empty($supplier_details))
{
    $souvenirs_list = $souvenir->readBySupplier($supplier->id);
}
else
{
    $souvenirs_list = $souvenir->read();
}

$input_search = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    $input_search = $_POST['search'];
}

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
                        <h4>Souvenirs List</h4>
                        <p><small><a href="create">Add new souvenir</a></small></p>
                        <hr>
                        <br>
                        <p><small>FILTERS:</small></p>
                        <style>
                            div.col-filter .dropdown{
                                margin-top: 5px;
                            }
                            /* padding and background are removed globally in style.css */
                            form.admin-search button{
                                width:100%;
                            }

                            
                            
                        </style>
                        <div class="row">
                            <div class="col-auto col-filter">
                                <span class="text-muted"><small>select category</small></span>
                                <div class="dropdown">
                                    <a class="btn btn-link dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-left: -9px;">
                                        Category
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <?php foreach ($categories_list as $row) : ?>
                                            <a class="dropdown-item" href="./"><?php echo $row['title']; ?></a>
                                        <?php endforeach; ?> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto col-filter" style="margin-left: 40px;">
                                <span class="text-muted"><small>select supplier</small></span>
                                <div class="dropdown">
                                    <a class="btn btn-link dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-left: -9px;">
                                        Supplier
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <?php foreach ($suppliers_list as $row) : ?>
                                            <a class="dropdown-item" href="../souvenirs?supplier=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                                        <?php endforeach; ?> 
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm" style="margin-left: 40px;">
                                <span class="text-muted"><small>search souvenir</small></span>

                                <form class="admin-search" method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" style="">
                                
                                
                                <div class="form-row">
                                    <div class="form-group col-sm">
                                        <input type="text" name="search" class="form-control" placeholder="search" value="<?php echo $input_search; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <button type="submit" class="btn btn-primary"> search </button>
                                    </div>
                                </div>
                                </form> 
                            </div>
                        </div>

                        

                        <?php if(!empty($category_details) || !empty($supplier_details)) : ?>
                            
                            <style>
                                div.param-block p{
                                    margin-top:10px;

                                }
                                span.filter-title{
                                    
                                }
                                span.filter-label{
                                    padding: 10px;
                                    border-radius: 2px;
                                    background-color: rgba(40, 118, 181, 0.15);
                                }
                            </style>    
                           

                            
                            <?php if(!empty($category_details))  : ?>
                                <div class="param-block">
                                    <span class="filter-title text-muted"><small>category</small></span>
                                    <p>
                                        <span class="filter-label"><small><?php echo $category_details['title'] ?>
                                            <?php if(!empty($supplier_details)) : ?> 
                                                <a href="./?supplier=<?php echo $supplier_details['id']; ?>" class="text-danger"><i class="fas fa-times"></i></a>
                                            <?php else : ?>
                                                <a href="./" class="text-danger"><i class="fas fa-times"></i></a> 
                                            <?php endif; ?> 
                                        </small></span>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($supplier_details))  : ?>
                                <div class="param-block">
                                    <span class="filter-title text-muted"><small>supplier</small></span>
                                    <p>
                                        <span class="filter-label"><small><?php echo $supplier_details['name'] ?>
                                            <?php if(!empty($category_details)) : ?> 
                                                <a href="./?category=<?php echo $category_details['id']; ?>" class="text-danger"><i class="fas fa-times"></i></a>
                                            <?php else : ?>
                                                <a href="./" class="text-danger"><i class="fas fa-times"></i></a> 
                                            <?php endif; ?> 
                                        </small></span>
                                    </p>
                                </div>
                               
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if(empty($souvenirs_list)) : ?>
                            <hr/>
                            <p><small>Woops! Nothing to show...</small></p>
                        <?php else : ?>
                        <br/>

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
                                                        <?php 
                                                            foreach ($categories_list as $cat)
                                                            {
                                                                if($cat['id'] == $row['category_id'])
                                                                {
                                                                    echo $cat['title'];
                                                                } 
                                                            }
                                                        ?>
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
                        
                        <div class="row">
                            
                            <div class="col-sm text-center">
                            <hr>
                            < 1 2 3 >    
                            </div>
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