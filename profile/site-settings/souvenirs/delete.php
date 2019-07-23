<?php 
    require "./../../../app/_app-layout/header.php";
?>

<?php

include_once './../../../app/config/database.php';
include_once './../../../app/models/authentication.php';
include_once './../../../app/models/user.php';
include_once './../../../app/models/souvenir.php';

$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: ../souvenirs");
    exit;
}

if(!$auth->access('admin'))
{
    $page = "profile/site-settings/souvenirs/delete?id=".$_GET['id']."";
    header("Location: ".$hlpr->core_link("login?returnto=".$page)."");
    exit;
}

$user = new User($db_conn);
$user->read();

$souvenir = new souvenir($db_conn, $auth);

$souvenir->id = $_GET['id'];
$souvenir_details = $souvenir->read_single();

$errMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
    if($souvenir->delete())
    {
        header("Location: ../souvenirs");
        exit;
    }
    else
    {
        $errMessage = "Woops! Couldn't delete this item, try gain later";
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
                        
                        <h4>Delete souvenir</h4>
                        <hr/>
                        <br/>
                        
                        <div class="row">
                            <div class="col-sm-auto">
                                <img width="230" height="230" class="souvenir-details-image"
                                    <?php if(!isset($souvenir_details['image']) || !empty($souvenir_details['image'])) : ?>
                                        src="<?php echo $hlpr->core_link('app/src/imgs/souvenirs/').$souvenir_details['image']; ?>"
                                    <?php else : ?>
                                        src="<?php echo $hlpr->core_link('app/src/imgs/souvenirs/default.png'); ?>"
                                    <?php endif; ?>
                                    alt="Souvenir Image" >
                            </div>
                            <div class="col-sm-8">
                            <table class="details">
                            <tbody>
                                <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-tag"></i> Title</small></p></td><td><p><em><?php echo $souvenir_details['title'] ?></em></p></td></tr>
                                <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-dollar-sign"></i> Price</small></p></td><td><p><em><?php echo $souvenir->cute_price($souvenir_details['price']); ?></em></p></td></tr>
                                <tr><td style="width:200px;"><p class="text-muted"><small><i class="fa fa-fw fa-boxes"></i> Stock</small></p></td><td><p><em><?php echo $souvenir_details['stock'] ?></em></p></td></tr>
                                
                                <tr>
                                    <td colspan="3" s>
                                        <br><br>
                                        <p class="text-muted" style="padding:0; margin:0;"><small>Description</small></p>
                                        <hr>
                                        <p><em><?php if(!empty($souvenir_details['description'])) echo htmlspecialchars($souvenir_details['description']); else echo "not specified"; ?></em></p>

                                    </td>
                                </tr>

                                <tr><td colspan="3"></td></tr>

                                <tr>
                                <td colspan="3">
                                </td>
                                </tr>
                            </tbody>
                            </table>
                            </div>
                        </div>

                        <br/>
                        
                        <form method="post" action="<?php echo $hlpr->form_action($_SERVER["PHP_SELF"])."?id=".$_GET['id']; ?>">
                            <p class="text-muted"><small>Are you sure you want to delete this item?</small></p>
                            <span class="text-danger form-error"><small><?php echo $errMessage;?></small></span>
                            <button type="submit" class="btn btn-danger"> Delete </button>
                        </form>

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