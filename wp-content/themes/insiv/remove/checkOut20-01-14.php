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
     
      $planPrice = get_term_meta( $planDetail->term_id, 'priceText', true);
      
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

        
 $meta_query = array();
$meta_query[] = array(
'key' => '_cmb_strength_display_status',
'value' 	=> 'on',
'compare' 	=> '=',
'type'		=> 'CHAR'
); 

     $query_args=array(
            'taxonomy' => 'plan',
            'term'=> $planDetail->slug,
            'posts_per_page'=>5,
            'post_type' => 'strength',
            'post_status' => 'publish',
            'meta_query'=>$meta_query,
            'meta_key' => '_cmb_Strength_position',
            'orderby' => 'meta_value_num',
            'order' => 'ASC' 
); 

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
         <p id="plan-name" ><?php echo $planDetail->name; ?>/month</p>
         
     </div>
      <span class="menuDrop"><div class="rightbarDrop">
    
<ul id="rightbarDrop">
		<li><a href="javascript:void(0);" class="Drop boxSizing"></a>
			<ul class="boxSizing">
                          <?php 
                         // pr($planArray,1);
                          if(!empty($planArray)){  foreach($planArray as $row){
                        $tax_term_id = $row->term_id;
                        $pprice = get_term_meta( $tax_term_id, 'priceText', true);
                        ?>  
                            <li><a href="javascript:void(0);" onclick="getPlanName('<?php echo $row->name.'~'.$tax_term_id.'~'.$pprice; ?>');"><?php echo $row->name; ?></a></li>
                          <?php }}?>
             
      			</ul>
		</li></ul>
        </div></span>
     </li>
     <li id="strengthBox">
          <?php //pr( $strenthDetail);?>
     <div class="stepLeft"><h4>Strength</h4>
         <p id="strengthTitleText"><?php echo $strenthDetail['post_title']; ?></p>
       
     </div>
      <span class="menuDrop"><div class="rightbarDrop">
    
<ul id="rightbarDrop">
		<li><a href="javascript:void(0);" class="Drop boxSizing"></a>
			<ul class="boxSizing">
                            <?php    if(!empty($query_args)){
        $query = new WP_Query( $query_args );
                if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                          $query->the_post();  
                          
                          ?>
                            <li><a href="javascript:void(0);" onclick="getStrength('<?php echo get_the_title().'~'.get_the_ID(); ?>');" ><?php echo get_the_title(); ?></a></li>
                         <?php      
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
      <p>Free!</p> 
         <input type="hidden" name="planIdText" id="planIdText" value="<?php echo $planid; ?>">
         <input type="hidden" id="strengthtext" name="strengthtext" value="<?php  echo $strenth ;?>">
         <input type="hidden" name="planPricetText" id="planPricetText" value="<?php echo $planPrice; ?>">
         <input type="hidden" id="shippingCountry" name="shippingCountry" value="">
         <input type="hidden" id="shippingState" name="shippingState" value="">
     </div>
      </a></li>
    </ul>
   <div class="clear"></div>

   <h5>Total   $<span id="planPrice"><?php echo  $planPrice; ?></span></h5>
    <div class="leftComplte"><a href="javascript:void(0);">Complete Purchase</a> <div class="clear"></div> </div>
    
  </div> 
  </div>   
  
   <div class="grid_10">
    <div class="step5edit"><h4>Shipping Address</h4>
       <div class="shiping">
      <ul>
          <li><input type="text" placeholder="First Name" name="shippingFirstName" id="shippingFirstName" ></li>
          <li><input type="text" placeholder="Last Name" name="shippingLastName" id="shippingLastName" ></li>
          <li class="bigArea"><input type="text" placeholder="Address" name="shippingAddress" id="shippingAddress"></li>
          <li class="smallArea"><input type="text" placeholder="Apt/Suite" name="shippingAptSuite" id="shippingAptSuite" ></li>
      <li class="smallArea"><input type="text" placeholder="City" name="shippingCity" id="shippingCity" ></li>
      <li class="smallArea"><div class="stateDrop">
    
<ul id="stateDrop">
    <li><a href="javascript:void(0);" class="Drop boxSizing" id="shipState" >State</a>
			<ul class="boxSizing" id="stateBox">
                                     <li><a href="javascript:void(0);">Select State</a></li>
           
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea"><input type="text" placeholder="Zip code" name="shippingZipCode" id="shippingZipCode"></li>
      <li class="bigArea" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
    <li><a href="javascript:void(0);" class="Drop boxSizing" id="shipCountry">Country</a>
			<ul class="boxSizing" id="scroll">
                            <?php 
                            $countryQuery = "SELECT * FROM  ".$wpdb->prefix . "world_countries ";
                             $countryArray = $wpdb->get_results($countryQuery);
                             //pr($countryArray);
                            if(!empty($countryArray))
                            {
                                foreach($countryArray as $row){
                             ?>
                            <li><a href="javascript:void(0);" onclick="getCountryId('<?php echo $row->Country_Name.'~'.$row->id; ?>');"><?php echo $row->Country_Name; ?></a></li>
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
          <li class="bigArea"><input type="text" placeholder="Credit card number" name="cardNumber" id="cardNumber" ></li>
      <li class="smallArea"><input type="text" placeholder="CVV" name="cardCVV" id="cardCVV" ></li>
      <input type="hidden"  name="cardExpMonth" id="cardExpMonth" value="" >
      <input type="hidden"  name="cardExpYear"  id="cardExpYear"  value="" >
      <li class="smallArea"><p>Credit Card<br>
      Expiration date</p></li>
<!--      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">12</a>
			<ul class="boxSizing">
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>-->
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
    <li><a href="javascript:void(0);" class="Drop boxSizing" id="cardMonthBox" >01</a>
                    <ul class="boxSizing" id="cardMonth">
                            <?php for($i =1;$i<=12 ;$i++){ ?>
                        <li><a href="javascript:void(0);" onclick="callCardMonth('<?php echo sprintf("%02s", $i); ?>');" ><?php echo sprintf("%02s", $i); ?></a></li>
                            <?php }?>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
    <?php $year = date('Y'); 
          $maxYear = $year+15;
        ?>
    <li><a href="javascript:void(0);" class="Drop boxSizing" id="cardYearBox"><?php echo $year; ?></a>
                    <ul class="boxSizing" id="cardYear">
                            <?php  for($i=$year ;$i <= $maxYear; $i++ ){ ?>
                        <li><a href="javascript:void(0);" onclick="callCardYear('<?php echo $i; ?>');"><?php echo $i; ?></a></li>
                                    <?php }?>
             
      			</ul>
		</li></ul>
        </div></li>     
          <div class="clear"></div>

          <li><input id="default"  type="checkbox" name="default" onchange="callSameAddress();" value="default"> <p>Billing address is the same as shipping address</p> </li>
      
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>  
       
       <div class="step5edit" id="billingBox"><h4>Billing Address</h4>
       <div class="shiping">
      <ul>
          <li class="bigArea"><input type="text" placeholder="Address" name="billingAddress" id="billingAddress" ></li>
          <li class="smallArea"><input type="text" placeholder="Apt/Suite" name="billingAptSuite" id="billingAptSuite" ></li>
          <li class="smallArea"><input type="text" placeholder="City" name="billingCity" id="billingCity" ></li>
          <input type="hidden"  name="billingState"   id="billingState"     value="" >
          <input type="hidden"  name="billingCountry" id="billingCountry"   value="" >
      <li class="smallArea"><div class="stateDrop">
    
<ul id="stateDrop">
    <li><a href="javascript:void(0);" class="Drop boxSizing" id="billstateName">State</a>
                    <ul class="boxSizing" id="billingStateText">
                                 <li><a href="#">State 1</a></li>
            
             
      			</ul>
		</li></ul>
        </div></li>
        <li class="smallArea"><input type="text" placeholder="Zip code" name="billingZipCode" id="billingZipCode" ></li>
      <li class="bigArea" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
    <li><a href="javascript:void(0);" class="Drop boxSizing" id="billingCountryText">Country</a>
			<ul class="boxSizing" id="billScroll">
                      <?php 
                            $countryQuery = "SELECT * FROM  ".$wpdb->prefix . "world_countries ";
                             $countryArray = $wpdb->get_results($countryQuery);
                             //pr($countryArray);
                            if(!empty($countryArray))
                            {
                                foreach($countryArray as $row){
                             ?>
                            <li><a href="javascript:void(0);" onclick="getbillingCountryId('<?php echo $row->Country_Name.'~'.$row->id; ?>');"><?php echo $row->Country_Name; ?></a></li>
                                <?php } } ?>
             
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
      <li class="bigArea"><input type="password" placeholder="Password"></li>
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
                
               // $("#billAddresssame").tiCheckbox();
		//	$(".no-label").tiCheckbox({label: false});
		
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
  #stateBox
  {
     border: 1px solid gray;
	height:250px;
	width: 100%;
	overflow: hidden;
	position: absolute; 
  }
  #cardYear{
      border: 1px solid gray;
	height:200px;
	width: 50px;
	overflow: hidden;
	position: absolute; 
  }
  #cardMonth{
        border: 1px solid gray;
	height:200px;
	width: 50px;
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
        $('#stateBox').perfectScrollbar({
          wheelSpeed: 20,
          wheelPropagation: false
        });
        $('#cardYear').perfectScrollbar({
          wheelSpeed: 20,
          wheelPropagation: false
        });
        $('#cardMonth').perfectScrollbar({
          wheelSpeed: 20,
          wheelPropagation: false
        });
      });
      
      function getPlanName(getplan)
      {
         // alert(getplan);
          var planaaray = getplan.split('~');
          var pname = planaaray[0]+'/month';
         $('#plan-name').text(pname);
         $('#planIdText').val(planaaray[1]);
         $('#planPrice').text(planaaray[2]);
         $('#planPricetText').val(planaaray[2]);
         
         var data = $('#planIdText').serialize();
	$.ajax({
            type	: "POST",
            cache	: false,
            url     : ajaxurl,
            dataType : 'json',
            data: {
			'action' : 'getPlanStrength',
			'datalist' : data
	  },
                success: function(data) {
                    
                    $('#strengthBox').html(data.response);

                                       }
          });	
      }
      function getStrength(getstrength){
         // alert(getstrength);
           var getstrengtharray = getstrength.split('~');
            $('#strengthTitleText').text(getstrengtharray[0])
          $('#strengthtext').val(getstrengtharray[1]);
      }
      
      function getCountryId(countryid)
      {
            var countryidarray = countryid.split('~');
             $('#shipState').text('State');
          $('#shipCountry').text(countryidarray[0]);
          //alert(countryid);
          $('#shippingCountry').val(countryidarray[1]);
          var data = { countryid: countryidarray[1], divid: "shipState" };
	$.ajax({
            type	: "POST",
            cache	: false,
            url     : ajaxurl,
            dataType : 'json',
            data: {
			'action' : 'getStateByCountryId',
			'datalist' : data
	  },
                success: function(data) {
                    
                    $('#stateBox').html(data.response);
                        
                                       }
          });	
         
      }
      
      function callShipState(getShipState)
      {
          
         getShipState = getShipState.replace("~apos~", "'");
             
         var getShipStatearray = getShipState.split('~');
           
           if(getShipStatearray[2] == 'billingStateText'){
                 $('#billstateName').text(getShipStatearray[0]);
                    $('#billingState').val(getShipStatearray[1]);
               
           }else
               {
                     $('#shipState').text(getShipStatearray[0]);
                    $('#shippingState').val(getShipStatearray[1]);
               }
        
      }
      function callCardMonth(monthid)
      {
         $('#cardMonthBox').text(monthid); 
         $('#cardExpMonth').val(monthid);
      }
        function callCardYear(expyear)
      {
         $('#cardYearBox').text(expyear); 
         $('#cardExpYear').val(expyear);
      }
      
    function getbillingCountryId(countryid)
    {
        var countryidarray = countryid.split('~');
             $('#billstateName').text('State');
          $('#billingCountryText').text(countryidarray[0]);
          //alert(countryid);
          $('#billingCountry').val(countryidarray[1]);
          var data ={ countryid: countryidarray[1], divid: "billingStateText" };
	$.ajax({
            type	: "POST",
            cache	: false,
            url     : ajaxurl,
            dataType : 'json',
            data: {
			'action' : 'getStateByCountryId',
			'datalist' : data
	  },
                success: function(data) {
                    
                    $('#billingStateText').html(data.response);
                        
                                       }
          });	
    }
    function callSameAddress()
    {
        alert('aashish');
        $('#billingBox').toggle();
    }
    </script>


<?php get_footer(); ?>