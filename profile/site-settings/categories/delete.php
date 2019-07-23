<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/category.php';
include_once './../../../app/models/souvenir.php';

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
    $page = "profile/site-settings/categories/delete?id=".$_GET['id']."";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$category = new Category($db_conn, $auth);
$category->id = $_GET['id'];
$details = $category->read_single();

$souvenir = new Souvenir($db_conn, $auth);
$souvenirs_list = $souvenir->readByCategory($category->id);

$errMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    if(count($souvenirs_list) > 0)
    {
        $errMessage = "You are trying to delete category that has assigned items.";
    }
    else
    {
        if($category->delete())
        {
            header("Location: ../categories");
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
                        
                        <h4>Delete category</h4>
                        <hr/>
                        <br/>
                        <p>Title: <?php echo $details['title']; ?></p>
                        
                        <p class="text-muted">
                            <small>
                                <?php
                                if(count($souvenirs_list) > 0)
                                {
                                    if(count($souvenirs_list) > 1)
                                    {
                                        echo count($souvenirs_list).' items assigned to category <a href="../souvenirs"><em>"'.$details['title'].'"</a></em>';
                                    }
                                    else
                                    {
                                        echo count($souvenirs_list).' item assigned to category <a href="../souvenirs/category?id='.$category->id.'" target="new" data-toggle="tooltip" title="Open items of category '.$details['title'].' in a new tab"><em>"'.$details['title'].'"</a></em>';
                                    }
                                }
                                else
                                {
                                    echo 'No items assigned to category <a href="souvenirs"><em>"'.$details['title'].'"</a></em>';
                                }

                                ?> 
                            </small>
                        </p>

                        <br/>
                        
                        <form method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"])."?id=".$_GET['id']; ?>">
                            <p class="text-muted"><small>Are you sure you want to delete this item?</small></p>
                            <span class="text-danger form-error"><small><?php echo $errMessage;?></small></span>
                            <br><br>
                            <?php if(count($souvenirs_list) > 0) : ?>
                                <button type="submit" class="btn btn-danger" disabled> Delete </button>
                                <br><br><span class="text-muted form-error"><small><small>Cannot delete category <em>"<?php echo $details['title'] ?>" as it has assigned items. <br> Delete items and try again.</em></small></small></span>
                            <?php else : ?>
                                <button type="submit" class="btn btn-danger"> Delete </button>
                            <?php endif; ?>
                        </form>

                        <hr/>

                        <div class="float-left">
                            <a href="../categories"><i class="fa fa-fw fa-long-arrow-alt-left"></i> go back</a>
                        </div>
                        
                        <div class="float-right text-right">
                        
                            <a href="<?php echo "category?id=".$_GET["id"]."" ?>" class="text-primary">Details</a> 
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