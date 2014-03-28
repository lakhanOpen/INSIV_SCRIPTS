  
<?php  $pageid = get_the_ID(); ?> 
<div class="planLeftmenu">
   <div class="grid_3"> <h3>General Options <span class="blueBdr"></span> </h3>
   <ul>
    <li><a href="<?php echo get_permalink( 66 ); ?>" <?php if($pageid == 66 || $pageid == 69){echo 'class="active"';} ?>>Current Plan</a></li>
    <li><a  href="<?php echo get_permalink( 63 ); ?>" <?php if($pageid == 63){echo 'class="active"';} ?>>Edit Profile</a></li>
    <li><a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a></li>
    </ul>
    </div>
   </div>
