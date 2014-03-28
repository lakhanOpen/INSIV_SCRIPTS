<?php 
/*
Template Name: Select Product Strength
*/
if ( is_user_logged_in()  || !empty($_SESSION['nowuserid'])) {   
	
} else{
    wp_redirect(home_url());
   exit();    
}
get_header('brown'); ?>

 <?php 
 
 $planid = (is_numeric($_GET['plan']) ? (int)$_GET['plan'] : 0);
 if(!empty($planid)){
  $planid =  toInternalId($planid);
     $term = get_term( $planid, 'plan' );
    $query_args ='';
     if(!empty($term)){
     $query_args=array(
            'taxonomy' => 'plan',
            'term'=> $term->slug,
            'post_type' => 'strength',
            'post_status' => 'publish'); 
         }
 
 ?>
 

  <div class="planPage">
 <div class="container_12 greylight">

   <div class="stepTop"><h4>Product Strength</h4>
                        <p>Choose your level nicotine</p>
                        <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow3.png"></p>
   </div>
   
   <div class="stepStrenth">
    <div class="barStranth">
        
        <?php 
        if(!empty($query_args)){
        $query = new WP_Query( $query_args );
                if ( $query->have_posts() ) {
                       echo '<ul>';
                       while ( $query->have_posts() ) {
                               $query->the_post();
                               
                               $meta_values ='';
                             $meta_values = get_post_meta( get_the_ID(), '_cmb_strength_display_status', 'true ' ); 
                              
                             if($meta_values =='on'){
                             echo '<li><a href="'.get_permalink( 60 ).'?plan='.toPublicId($planid).'&strenth='.toPublicId(get_the_ID()).'">' . get_the_title() . '</a></li>';
                             }
                       }
                       echo '</ul>';
               } else {
                       echo " no product strength.";
               }
              
               wp_reset_postdata();
        } else{
             echo " no product strength.";
        }
?>
<!--     <ul>
     <li><a href="#">0mg</a></li>
     <li><a href="#">3mg</a></li>
     <li><a class="activeBR" href="#">6mg</a></li>
     <li><a href="#">12mg</a></li>
     <li><a href="#">24mg</a></li>
     </ul>-->
    </div>
    
    <div class="Strenthtext"><p>Intended for sale to adults 18 years or older. If you are not legally able to purchase
tobacco products in the state where you live, please exit insiv.com immediately.</p>

<p>CALIFORNIA PROPOSITION 65 - WARNING: This product contains nicotine, a chemical 
known to the state of California to cause birth defects or other reproductive harm.</p></div>
   
   </div>
  
 </div>
 <div class="clear"></div>
   </div>
   
   <?php }else{?>
       
  <div class="planPage">
 <div class="container_12 greylight">
     <div class="stepTop"><h4>Plan not exits.</h4> </div>
 </div>
 <div class="clear"></div>
   </div>
   
  <?php  } ?> 
<?php get_footer(); ?>