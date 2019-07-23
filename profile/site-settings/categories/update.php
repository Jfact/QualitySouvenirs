<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/category.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: ../categories");
    exit;
}

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/categories/update?id=".$_GET['id']."";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$category = new Category($db_conn, $auth);

$input_title = "";
$titleErr= "";
$errMessage = "";

$category->id = $_GET['id'];
$details = $category->read_single();

$input_title = $details['title'];

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    $input_title = $_POST['title'];
    if(isset($_POST['title']) && !empty($_POST['title']))
    {
        $category->title = $_POST['title'];

        if($category->update())
        {
            header("Location: ../categories");
            exit;
        }
        else
        {
            $errMessage = "Woops! Something went wrong, try gain later.";
        }
            
    }
    else
    {
        $errMessage = "Fields errors, check provided data and try again.";
        $titleErr="title is required";
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
                        
                        <h4>Update category</h4>
                        <hr/>
                        <br/>
                        <p>Title: <?php echo $details['title'] ?></p>
                        <br/>
                        
                        <form method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"])."?id=".$_GET['id']; ?>">
                            <span class="text-danger form-error"><small><?php echo $errMessage;?></small></span>
                            <br/><br/>
                            <div class="form-group">
                                <label for="category-title"><small><span class="text-muted">category title</span></small></label>
                                <input type="text" name="title" id="category-title" class="form-control" placeholder="title" value="<?php echo $input_title; ?>">
                                <br/>
                                <span class="text-danger form-error"><small><?php echo $titleErr;?></small></span>
                            </div>
                           
                            <button type="submit" class="btn btn-primary"> Update </button>
                        </form>
                        <br/>
                        <hr/>

                        <div class="float-left">
                            <a href="../categories"><i class="fa fa-fw fa-long-arrow-alt-left"></i> go back</a>
                        </div>
                        
                        <div class="float-right text-right">
                        
                            <a href="<?php echo "category?id=".$_GET["id"]."" ?>" class="text-primary">Details</a> 
                            <a href="<?php echo "delete?id=".$_GET["id"]."" ?>" class="text-danger"><i class="fa fa-fw fa-trash-alt"></i></a> 

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