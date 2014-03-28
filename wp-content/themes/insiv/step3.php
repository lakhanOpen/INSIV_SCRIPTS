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
         
 $meta_query = array();
$meta_query[] = array(
'key' => '_cmb_strength_display_status',
'value' 	=> 'on',
'compare' 	=> '=',
'type'		=> 'CHAR'
); 

     $query_args=array(
            'taxonomy' => 'plan',
            'term'=> $term->slug,
            'posts_per_page'=>5,
            'post_type' => 'strength',
            'post_status' => 'publish',
            'meta_query'=>$meta_query,
            'meta_key' => '_cmb_Strength_position',
            'orderby' => 'meta_value_num',
            'order' => 'ASC' 
); 
         }
 
 ?>
 

  <div class="planPage">
 <div class="container_12 greylight" style="height:800px;">

   <div class="stepTop"><h4>Product Strength</h4>
                        <p>Choose your nicotine level</p>
                        <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow3.png"></p>
   </div>
   
   <div class="stepStrenth">
    <div class="barStranth">
        
        <?php   $as = 0; 
		 if(!empty($query_args)){
			 
		query_posts($query_args);
		if ( have_posts() ) {
			echo '<ul>';
		while ( have_posts() ) {
			the_post(); 

$as++; 
                             echo '<li><a href="'.get_permalink( 60 ).'?plan='.toPublicId($planid).'&strenth='.toPublicId(get_the_ID()).'">' . get_the_title() . '</a></li>';


		} // end while
		 echo '</ul>';
	} 
		 }
		 
		 
		wp_reset_query();
		
        /*if(!empty($query_args)){
            $as = 0;
        $query = new WP_Query( $query_args );
                if ( $query->have_posts() ) {
                       echo '<ul>';
                       while ( $query->have_posts() ) {
                               $query->the_post();
                       $as++; 
                             echo '<li><a href="'.get_permalink( 60 ).'?plan='.toPublicId($planid).'&strenth='.toPublicId(get_the_ID()).'">' . get_the_title() . '</a></li>';
                            
                       }
                       echo '</ul>';
               } 
			     else {
                       echo " no product strength.";
               }
              
               //wp_reset_postdata();
        } 
		else{
             echo " no product strength.";
             }
        */
        if($as == 4)
        {
            echo "<style type='text/css'>.barStranth li{float:left; margin:-13px 26px 0 50px;}</style>";
        }
?>

    </div>
       
    <div class="Strenthtext">  <?php echo $term->description; ?> 
</div>
   
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