

<ul>
    <li><p><a href="<?php echo $hlpr->core_link('profile') ?>"><small><i class="fa fa-fw fa-user-alt"></i> <?php echo $user->firstname." ".$user->lastname ?></a></small></p></li>
    
    <?php if($user->role == 'admin') : ?>
        <br/>
        <li><p class="text-muted"><small>System settings</small></p></li>
        <hr/>
        <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings') ?>"><small><i class="fa fa-fw fa-toolbox"></i> Site settings</a></small></p></li>
    <?php endif; ?>
    
    <br/>
    <li><p class="text-muted"><small>Manage customer's orders</small></p></li>
    <hr>
    <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings/orders') ?>"><small><i class="fa fa-fw fa-clipboard-list"></i> Orders</a></small></p></li>
    <br/>
    
    <li><p class="text-muted"><small>Manage products details</small></p></li>
    
    <hr/>
    <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings/souvenirs') ?>" class="text-info"><small><i class="fa fa-fw fa-long-arrow-alt-right"></i> Souvenirs (<?php echo $tbl_souvenirs_lngth ?>)</a></small></p></li>
    <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings/categories') ?>" class="text-info"><small><i class="fa fa-fw fa-long-arrow-alt-right"></i> Categories (<?php echo $tbl_categories_lngth ?>)</a></small></p></li>
    <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings/suppliers') ?>" class="text-info"><small><i class="fa fa-fw fa-long-arrow-alt-right"></i> Suppliers (<?php echo $tbl_suppliers_lngth ?>)</a></small></p></li>
    <br/>

    <li><p class="text-muted"><small>Manage admin users</small></p></li>
    <hr>
    <li><p><a href="<?php echo $hlpr->core_link('profile/site-settings/orders') ?>"><small><i class="fa fa-fw fa-user-shield"></i> Admin users</a></small></p></li>
    <br/>

    <hr/>
    <li><p><a href="<?php echo $hlpr->core_link('profile/change-password') ?>" class="text-danger"><small><i class="fa fa-fw fa-lock"></i> change password</a></small></p></li>
    <li><p><a href="<?php echo $hlpr->core_link('logout') ?>" class="text-warning"><small><i class="fa fa-fw fa-sign-out-alt"></i> logout</a></small></p></li>
    
</ul>
<br><br><br>