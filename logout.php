
<?php 
    require "app/_app-layout/header.php";
?>

<?php
    include_once 'app/config/database.php';
    include_once 'app/models/authentication.php';

    $input_email = $login_status = "";
    $emailErr = $passwordErr = $loginErr = '';


    $database = new Database();
    $db_conn = $database->connection();

    $auth = new Authentication($db_conn);
    
    $auth->logout();
    
    header("Location: /solutions/QualitySouvenirs.v.1.3");
    exit;
?>

<main>

    

</main>


<?php 
    require "app/_app-layout/footer.php";
?>