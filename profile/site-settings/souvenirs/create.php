<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/config/file.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/souvenir.php';
include_once './../../../app/models/category.php';
include_once './../../../app/models/supplier.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/souvenirs/create";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$category = new Category($db_conn, $auth);
$supplier = new Supplier($db_conn, $auth);
$user->read();
$category_list = $category->read();
$supplier_list = $supplier->read();

$souvenir = new Souvenir($db_conn, $auth);

$input_title =  $input_desc = $input_image = $input_stock = $input_category = $input_supplier = ""; $input_price = 0.00;

$titleErr = $priceErr = $descErr = $imageErr = $stockErr= $categoryErr = $supplierErr = "";

$errMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    $input_title = $_POST['title'];
    $input_price = $_POST['price'];
    $input_desc = $_POST['desc'];
    //$input_image = $_POST['image'];
    $input_stock = $_POST['stock'];

    // $souvenir->image = $_FILES['image'];
    // echo $souvenir->upload_image();
    

    if(
        !empty($_POST['title']) &&
        !empty($_POST['price']) &&
        !empty($_POST['desc']) &&
        !empty($_POST['stock']) &&
        !empty($_POST['category']) &&
        !empty($_POST['supplier'])
    )
    {
        $souvenir->title = $_POST['title'];
        $souvenir->price = $_POST['price'];
        $souvenir->desc = $_POST['desc'];
        //$souvenir->image = $_POST['image'];
        $souvenir->stock = $_POST['stock'];
        $souvenir->category = $_POST['category'];
        $souvenir->supplier = $_POST['supplier'];

        if(
            $souvenir->validate_price() && 
            $souvenir->validate_stock() && 
            $souvenir->validate_category() && 
            $souvenir->validate_supplier()
        )
        {
            if($souvenir->_exists())
            {
                $errMessage = "The souvenir already exists.";
            }
            else
            {
                $file = new File();
                $file->name = $_FILES['image']['name'];
                $file->tmp = $_FILES['image']['tmp_name'];
                if(empty($file->name))
                {
                    $imageErr="image is required";
                }
                else
                {
                    if($file->upload())
                    {
                        $souvenir->image = $file->name;
                        if($souvenir->create())
                        {
                            header("Location: ../souvenirs");
                            exit;
                        }
                        else
                        {
                            $errMessage = "Woops! Something went wrong, try gain later.";
                        }
                    }
                    else
                    {
                        echo "extension is not allowed, please choose a JPEG or PNG file.";
                    }
                }
            }
        }
        else
        {   
            $errMessage = "Fields errors, check provided data and try again.";
            if(!$souvenir->validate_price())
            {   
                $priceErr = "invalid price format";
            }
            if(!$souvenir->validate_stock())
            {
                $stockErr = "invalid stock format";
            }
            if(!$souvenir->validate_category())
            {
                $categoryErr = "invalid category";
            }
            if(!$souvenir->validate_supplier())
            {
                $supplierErr = "invalid supplier";
            }
        }  
    }
    else
    {
        $errMessage = "Fields errors, check provided data and try again.";
        if(empty($_POST['title']))
        {
            $titleErr="title is required";
        }
        if(empty($_POST['price']))
        {
            $priceErr="price is required";
        }
        if(empty($_POST['desc']))
        {
            $descErr="desc is required";
        }
        if(empty($_FILES['image']))
        {
            $imageErr="image is required";
        }
        if(empty($_POST['stock']))
        {
            $stockErr="stock is required";
        }
        if(empty($_POST['category']))
        {
            $categoryErr="category is required";
        }
        if(empty($_POST['supplier']))
        {
            $supplierErr="supplier is required";
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
                        
                        <h4>Create souvenir</h4>
                        <hr/>
                        <br/>
                        
                        <form method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                            <span class="text-danger form-error"><small><?php echo $errMessage;?></small></span>
                            <br/><br/>

                            <div class="row form-group">
                                <div class="col-auto form-group">
                                    <img class="image-preview" src="https://static.thenounproject.com/png/220984-200.png" alt="" width="230" height="230">
                                    <div class="form-group">
                                        <label for="souvenir-image"><small><span class="text-muted">photo</span></small></label>
                                        <input type="file" name="image" id="souvenir-image" class="form-control-file" accept="image/*">
                                        <span class="text-danger form-error"><small><?php echo $imageErr;?></small></span>
                                    </div>
                                </div>

                                <div class="col-auto form-group">
                                    <div class="form-group">
                                        <label for="souvenir-title"><small><span class="text-muted">title</span></small></label>
                                        <input type="text" name="title" id="souvenir-title" class="form-control" placeholder="souvenir title" value="<?php echo $input_title; ?>">
                                        <span class="text-danger form-error"><small><?php echo $titleErr;?></small></span>
                                        <br/>
                                    </div>
                                    
                                    <div class="row form-group">
                                        <div class="col-6 form-group">
                                            <div class="form-group">
                                                <label for="souvenir-price"><small><span class="text-muted">price $ nzd</span></small></label>
                                                <input type="text" name="price" id="souvenir-price" class="form-control" placeholder="souvenir price" value="<?php echo number_format((float)$input_price, 2) ?>">
                                                <span class="text-danger form-error"><small><?php echo $priceErr;?></small></span>
                                                <br/>
                                            </div>
                                        </div>
                                        <div class="col-6 form-group">
                                            <div class="form-group">
                                                <label for="souvenir-stock"><small><span class="text-muted">stock</span></small></label>
                                                <input type="number" name="stock" id="souvenir-stock" class="form-control" placeholder="souvenir stock" value="<?php echo (int)$input_stock ?>">
                                                <span class="text-danger form-error"><small><?php echo $stockErr;?></small></span>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="form-group col-6">
                                            <label for="souvenir-category"><small><span class="text-muted">category</span></small></label>
                                            <select name="category" id="souvenir-category" class="form-control" >
                                                <?php if(!empty($input_category)) : ?>
                                                    <?php foreach ($category_list as $row) : ?>
                                                        <?php if($input_category == $row['id']) : ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <?php foreach ($category_list as $row) : ?>
                                                        <?php if(!$input_category == $row['id']) : ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                <?php else : ?>
                                                    <?php foreach ($category_list as $row) : ?>
                                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                               
                                            </select>
                                            <span class="text-danger form-error"><small><?php echo $categoryErr;?></small></span>
                                        </div>

                                        <div class="form-group col-6" style="width: 100px;">
                                            <label for="souvenir-supplier"><small><span class="text-muted">supplier</span></small></label>
                                            <select name="supplier" id="souvenir-supplier" class="form-control" >
                                                <?php if(!empty($input_supplier)) : ?>
                                                    <?php foreach ($supplier_list as $row) : ?>
                                                        <?php if($input_supplier == $row['id']) : ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <?php foreach ($supplier_list as $row) : ?>
                                                        <?php if(!$input_supplier == $row['id']) : ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                <?php else : ?>
                                                    <?php foreach ($supplier_list as $row) : ?>
                                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <span class="text-danger form-error"><small><?php echo $supplierErr;?></small></span>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <hr/>
                            <br/>
                            
                            <div class="form-group">
                                <label for="souvenir-desc"><small><span class="text-muted">desc</span></small></label>
                                <textarea name="desc" id="souvenir-desc" rows="10" class="form-control"></textarea>
                                <span class="text-danger form-error"><small><?php echo $descErr;?></small></span>
                                <br/>
                            </div>

                            <script>
                            //set description text area value;
                            document.getElementById("souvenir-desc").value = "<?php echo $input_desc; ?>";
                            
                            //image preview;
                            $(document).ready(function(){
                                function readURL(input) {
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();

                                        reader.onload = function(e) {
                                            $('.image-preview').attr('src', e.target.result);
                                        }

                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                                $("#souvenir-image").change(function(){
                                    readURL(this);
                                });
                            });
                                
                            </script>

                            <button type="submit" class="btn btn-primary"> Create </button>
                        </form>
                        <br/>
                        <hr/>

                        <div class="float-left">
                            <a href="../souvenirs"><i class="fa fa-fw fa-long-arrow-alt-left"></i> go back</a>
                        </div>
                        
                        <div class="float-right text-right">
                        
                            <a href="<?php echo "souvenir?id=".$_GET["id"]."" ?>" class="text-primary">Details</a> 
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