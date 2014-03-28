<?php
  /*
  Template name: Edit Profile
  */
?>
<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
wp_redirect(home_url());
}
    ?>

<?php get_header(); ?>

 <div class="container_12 greylight">
   <div class="policyLogo textCenter"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/231x95Logo.png"></div>
   <div class="clear"></div>
<?php while ( have_posts() ) : the_post(); ?>
   
     <div class="policytext">   <h2><?php the_title(); ?> <span class="blueBdr"></span></h2> 
     <br>

<?php the_content(); ?>
     
     </div>


<?php endwhile; ?>
 </div>
   


<?php get_footer(); ?>