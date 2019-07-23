
<?php
session_start();

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/Solutions/QualitySouvenirs.v.1.3/app/";

include($path."config/database.php");
include($path."config/table.php");
include($path."config/helpers.php");
include($path."models/authentication.php");
include($path."models/user.php");


$database = new Database();
$db_conn = $database->connection();

$auth = new Authentication($db_conn);
$hlpr = new Helpers();

$table = new Table($db_conn);

if($auth->access())
{
    $user = new User($db_conn);
    $tbl_shoppingcart_lngth = $table->_length('shoppingcart');
    $tbl_orders_lngth = $table->_length('orders');
    $tbl_souvenirs_lngth = $table->_length('souvenirs');
    $tbl_categories_lngth = $table->_length('categories');
    $tbl_suppliers_lngth = $table->_length('suppliers');
}

?>

<html>
<head>
    
    <meta charset="utf-8">
    <meta name="description" content="Quality Souvenirs">
    <meta name=viewport content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Latest compiled and minified CSS -->
    <link rel="stylesheet" type="text/css" href="/Solutions/QualitySouvenirs.v.1.3/app/src/styles/bootstrap.min.css"  />
    <link rel="stylesheet" type="text/css" href="/Solutions/QualitySouvenirs.v.1.3/app/src/styles/style.css"  />
    
    <script src="/Solutions/QualitySouvenirs.v.1.3/app/src/js/fontawesome.js"></script>
    <script src="/Solutions/QualitySouvenirs.v.1.3/app/src/js/jquery.min.js"></script>
    <script src="/Solutions/QualitySouvenirs.v.1.3/app/src/js/popper.min.js"></script>
    <script src="/Solutions/QualitySouvenirs.v.1.3/app/src/js/bootstrap.min.js"></script>

    

    <title>Quality Souvenirs </title>

</head>
<body>

<header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">

                <div class="row">
                    <div class="col-sm-6">
                        <h3><a href="/solutions/qualitySouvenirs.v.1.3" class="site-title">Quality <span>Souvenirs</span></a></h3>
                    </div>
                    <div class="col-sm-6 text-right" style="margin:20px 0;">
                    <?php 
                        if($auth->access())
                        {
                            echo '<p class="header-user-menu">';
                            echo '<small>';
                            echo '<a href="profile">';
                            echo 'Hello, ' .$user->full_name(). '</a> ';
                            echo '<a href="'.$hlpr->core_link('logout').'" class="text-warning"><i class="fa fa-fw fa-sign-out-alt"></i> logout </a>';
                            echo '</small>';
                            echo '</p>';
                        }
                        else
                        {
                            echo '<p class="header-user-menu">';
                            echo '<small>';
                            echo '<a href="'.$hlpr->core_link('login').'" class="text-warning"><i class="fa fa-fw fa-sign-in-alt"></i> login</a>';
                            echo 'or';
                            echo '<a href="'.$hlpr->core_link('registration').'">Create an account</a>';
                            echo '</small>';
                            echo '</p>';
                        }
                    ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-10">
                        <ul style="list-style: none; padding:0; margin:0;">
                            <li><small><small><i class="fa fa-fw fa-location-arrow"></i></small> 133 Sydney st., Hauraki, Auckland 0622</small></li>
                            <li><small><small><i class="fa fa-fw fa-phone"></i></small> +6421 02453 447</small></li>
                            <li><small><small><i class="fa fa-fw fa-envelope"></i></small> info@qs.co.nz</small></li>
                        </ul>
                    </div>
                    <div class="col-sm-2 text-right" style="margin:20px 0;">
                    <?php 
                        echo '<p class="header-user-menu">';
                        echo '<small>';
                        echo '<a href="'.$hlpr->core_link('profile/shoppingcart').'" class="text-info">';
                        echo 'Shopping cart (0)</a>';
                        echo '</small>';
                        echo '</p>';
                    ?>
                    </div>
                </div>

            </div>
            <div class="col-sm-2"></div>
        </div>     
    </div> 
</header>