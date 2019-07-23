<?php



?>

<ul>
    <li><p><a href="<?php echo $hlpr->core_link('profile') ?>"><small><i class="fa fa-fw fa-user-alt"></i> <?php echo $user->firstname." ".$user->lastname ?></a></small></p></li>
    
    <?php if($user->role == 'admin') : ?>
        <br/>
        <li><p class="text-muted"><small>System settings</small></p></li>
        <hr/>
        <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings') ?>"><small><i class="fa fa-fw fa-toolbox"></i> Site settings</a></small></p></li>
    <?php endif; ?>
    
    <br/>
    <hr>
    <li><p><a href="<?php echo $hlpr->core_link('profile/orders') ?>" class="text-success"><small><i class="fa fa-fw fa-clipboard-list"></i> orders (0)</a></small></p></li>
    <li><p><a href="<?php echo $hlpr->core_link('profile/shoppingcart') ?>" class="text-info"><small><i class="fa fa-fw fa-shopping-cart"></i> sopping Cart (0)</a></small></p></li>
    <br/>
    <li><p class="text-muted"><small>Profile settings</small></p></li>
    <hr/>
    <li><p><a href="<?php echo $hlpr->core_link('profile/edit') ?>"><small><i class="fa fa-fw fa-user-edit"></i> edit profile</a></small></p></li>
    <li><p><a href="<?php echo $hlpr->core_link('profile/change-password') ?>" class="text-danger"><small><i class="fa fa-fw fa-lock"></i> change password</a></small></p></li>
    <li><p><a href="<?php echo $hlpr->core_link('logout') ?>" class="text-warning"><small><i class="fa fa-fw fa-sign-out-alt"></i> logout</a></small></p></li>
    
</ul>

<br><br><br>