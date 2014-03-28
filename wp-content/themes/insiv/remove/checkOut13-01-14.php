<?php 
/*
Template Name: Plan Checkout
*/
if ( is_user_logged_in()  || !empty($_SESSION['nowuserid'])) {   
	
} else{
    wp_redirect(home_url());
   exit();    
}


$planid = (is_numeric($_GET['plan']) ? (int)$_GET['plan'] : 0);
$strenth = (is_numeric($_GET['strenth']) ? (int)$_GET['strenth'] : 0);

if((empty($planid) && empty($strenth)) || (empty($planid) || empty($strenth)))
{
    wp_redirect(home_url());
}
if(!empty($planid) && !empty($strenth))
{
    global $wpdb;
    $planid     =  toInternalId($planid);
    $strenth    =  toInternalId($strenth);
    
     $planDetail    = get_term( $planid, 'plan' );
     $strenthDetail = get_post($strenth, ARRAY_A);
   
}


get_header('brown'); ?>

<?php 
// no default values. using these as examples
$taxonomies = array( 'plan');

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

     
$query_args=array(
            'taxonomy' => 'plan',
            'term'=> $planDetail->slug,
            'post_type' => 'strength',
            'post_status' => 'publish'); 

?>
 
  <div class="planPage">
  
 <div class="container_16 greylight">

   <div class="stepTop"><h4>Check Out</h4>
                        <p>Review your purchase</p>
                        <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow5-4.png"></p>
   </div>
    <div class="clear"></div>
    
  <div class="grid_5  ">  
    <div class="step5leftMenu">
    <h3>Order Summary<span class="blueBdr"></span></h3>
    <ul>
    <li> 
        <?php //pr($planDetail); ?>
     <div class="stepLeft"><h4>Plan</h4>
      <p><?php echo $planDetail->name; ?>/month</p> </div>
      <span class="menuDrop"><div class="rightbarDrop">
    
<ul id="rightbarDrop">
		<li><a href="javascript:void(0);" class="Drop boxSizing"></a>
			<ul class="boxSizing">
                          <?php if(!empty($planArray)){  foreach($planArray as $row){
                        $tax_term_id = $row->term_id;?>  
                                              <li><a href="javascript:void(0);"><?php echo $row->name; ?></a></li>
                          <?php }}?>
             
      			</ul>
		</li></ul>
        </div></span>
     </li>
      <li>
          <?php //pr( $strenthDetail);?>
     <div class="stepLeft"><h4>Strength</h4>
      <p><?php echo $strenthDetail['post_title']; ?></p> </div>
      <span class="menuDrop"><div class="rightbarDrop">
    
<ul id="rightbarDrop">
		<li><a href="javascript:void(0);" class="Drop boxSizing"></a>
			<ul class="boxSizing">
                            <?php    if(!empty($query_args)){
        $query = new WP_Query( $query_args );
                if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                          $query->the_post();
                              $meta_values ='';
                             $meta_values = get_post_meta( get_the_ID(), '_cmb_strength_display_status', 'true ' ); 
                              
                             if($meta_values =='on'){
                          ?>
            <li><a href="javascript:void(0);"><?php echo get_the_title(); ?></a></li>
                         <?php        }
                                    }
                                 }
                                  wp_reset_postdata();
                              } ?>
             
      			</ul>
		</li></ul>
        </div></span>
     </li>
      <li><a href="#">
     <div class="stepLeft"><h4>Shipping</h4>
      <p>Free!</p> </div>
      </a></li>
    </ul>
   <div class="clear"></div>

    <h5>Total   $<?php echo  get_post_meta( $strenth, '_cmb_strength_price', 'true ' ); ?></h5>
    <div class="leftComplte"><a href="#">Complete Purchase</a> <div class="clear"></div> </div>
    
  </div> 
  </div>   
  
   <div class="grid_10">
    <div class="step5edit"><h4>Shipping Address</h4>
       <div class="shiping">
      <ul>
      <li><input type="text" placeholder="First Name"></li>
      <li><input type="text" placeholder="Last name"></li>
      <li class="bigArea"><input type="text" placeholder="Address"></li>
      <li class="smallArea"><input type="text" placeholder="Apt/Suit"></li>
      <li class="smallArea"><input type="text" placeholder="City"></li>
      <li class="smallArea"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">State</a>
			<ul class="boxSizing">
            <li><a href="#">State 1</a></li>
            <li><a href="#">State 2</a></li>
            <li><a href="#">State 3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea"><input type="text" placeholder="Zip code"></li>
      <li class="bigArea" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">Country</a>
			<ul class="boxSizing" id="scroll">
                            <?php 
                            $countryQuery = "SELECT * FROM  in_world_countries ";
                             $countryArray = $wpdb->get_results($countryQuery);
                             //pr($countryArray);
                            if(!empty($countryArray))
                            {
                                foreach($countryArray as $row){
                             ?>
            <li><a href="javascript:void(0);"><?php echo $row->Country_Name; ?></a></li>
                                <?php } } ?>
             
      			</ul>
		</li></ul>
        </div> </li>
      
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>
    
    <div class="step5edit"><h4>Billing Information</h4>
       <div class="shiping">
      <ul>
      <li class="bigArea"><input type="text" placeholder="Credit card number"></li>
      <li class="smallArea"><input type="text" placeholder="CVV"></li>
      <li class="smallArea"><p>Credit Card<br>
      Expiration date</p></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">12</a>
			<ul class="boxSizing">
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">Nove</a>
			<ul class="boxSizing">
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">2013</a>
			<ul class="boxSizing">
            <li><a href="#">2014</a></li>
            <li><a href="#">2015</a></li>
            <li><a href="#">2016</a></li>
             
      			</ul>
		</li></ul>
        </div></li>     
          <div class="clear"></div>

      <li><input id="default" type="checkbox" name="default" value="default"> <p>Billing address is the same as shipping address</p> </li>
      
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>  
       
    <div class="step5edit"><h4>Billing Address</h4>
       <div class="shiping">
      <ul>
      <li class="bigArea"><input type="text" placeholder="Address"></li>
      <li class="smallArea"><input type="text" placeholder="Apt/Suit"></li>
      <li class="smallArea"><input type="text" placeholder="City"></li>
      <li class="smallArea"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">State</a>
			<ul class="boxSizing">
            <li><a href="#">State 1</a></li>
            <li><a href="#">State 2</a></li>
            <li><a href="#">State 3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea"><input type="text" placeholder="Zip code"></li>
      <li class="bigArea" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">Country</a>
			<ul class="boxSizing" id="billScroll">
            <li><a href="#">Country 1</a></li>
            <li><a href="#">Country 2</a></li>
            <li><a href="#">Country 3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>  
       
    <div class="step5edit"><h4>Account Information</h4>
       <div class="shiping">
      <ul>
      <li class="bigArea"><input type="text" placeholder="Email Address"></li>
      <li class="bigArea"><input type="password" placeholder="password"></li>
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>
       
    </div>
  
 </div>
 
 <div class="clear"></div>
   </div>
   
  
    <!--CheckBox-->
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/checkbox.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function(){ 
		$('#default').betterCheckbox();
		$('#default-dis').betterCheckbox();
		$('#default-dis').betterCheckbox('disable');
		
 	});
	</script>
    
    
<style>
  #scroll {
	border: 1px solid gray;
	height:250px;
	width: 100%;
	overflow: hidden;
	position: absolute;
  }
  #billScroll {
	border: 1px solid gray;
	height:250px;
	width: 100%;
	overflow: hidden;
	position: absolute;
  }
</style>
    <script type="text/javascript">
      $(document).ready(function ($) {
        $('#scroll').perfectScrollbar({
          wheelSpeed: 20,
          wheelPropagation: false
        });
        $('#billScroll').perfectScrollbar({
          wheelSpeed: 20,
          wheelPropagation: false
        });
      });
    </script>


<?php get_footer(); ?>