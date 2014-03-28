<?php 
/*
Template Name: Select Plan
*/

if ( is_user_logged_in()  || !empty($_SESSION['nowuserid'])) {   
	
} else{
    wp_redirect(home_url());
   exit();    
}


get_header('brown'); ?>


<?php 
// no default values. using these as examples
$taxonomies = array( 
    'plan',
   
);

$args = array(
    'orderby'       => 'name', 
    'order'         => 'ASC',
    'hide_empty'    => true, 
    'fields'        => 'all', 
    'hierarchical'  => true, 
    'child_of'      => 0, 
    'get'           => '', 
    'pad_counts'    => false, 
    'offset'        => '', 
    'search'        => '', 
    'cache_domain'  => 'core'
); 

$planArray = get_terms( $taxonomies, $args );



?>
  <div class="planPage">
    <div class="container_12 greylight">

   <div class="stepTop"><h4>monthly plan</h4>
                        <p>choose a plan to fit your needs</p>
                        <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow1.png"></p>
   </div>
   
   <div class="monthlyfetures">
   <ul>
       <?php if(!empty($planArray)){
                    foreach($planArray as $row){
                        $tax_term_id = $row->term_id;
                                        $images = get_option('taxonomy_image_plugin');
                                         
                        ?>
                                <li>
                           <div class="feturesPic"> <a href="<?php echo get_permalink( 57 ); ?>?plan=<?php echo toPublicId($tax_term_id); ?>"><span class="imgHover"></span><?php echo wp_get_attachment_image( $images[$tax_term_id], 'medium' ); ?></a></div>
                           <h3><?php  echo $row->name; ?></h3>
                           <p>Free Shipping*</p>

                           </li>                 
       
                  <?php   }
           
       } ?>
       
  
<!--   <li>
   <div class="feturesPic"><a href="#"> <span class="imgHover"></span><img src="<?php //bloginfo('stylesheet_directory'); ?>/images/step2-1.png"></a></div>
   <h3>$39/month for 5 Flavors</h3>
   <p>Free Shipping*</p>
   
   </li>-->
   </ul>
   <div class="clear"></div>
   <div class="monthlyBootom">
   <p>Plans automatically renew. You may cancel at anytime.<br>
*$5/month shipping applies outside the lower 48 states.</p>
   </div>
   </div>
  
 </div>
 <div class="clear"></div>
   </div>
   

<?php get_footer(); ?>