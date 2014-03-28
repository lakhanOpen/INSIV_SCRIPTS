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
 
   $strenthPlanCode =   get_post_meta($strenth, 'recurly_plan_code', true );
}


get_header('brown'); ?>

<?php 

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
                        <p><img  src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow5-4.png"></p>
   </div>
    <div class="clear"></div>
    
    <?php 
   
    if(!empty($_SESSION['checkOutError']))
    {
          
          
          echo "<div id='checkoutErrorMsg'>".$_SESSION['checkOutError']['msg']."</div>";
          $_SESSION['checkOutError'] = '';
    }
    
?>
    
    
    <div class="clear"></div>
   
    <form action="<?php echo get_permalink( 124 ); ?>" method="post" id="checkOutForm" name="checkOutForm" onsubmit="return checkCheckoutform(this);">
    
            <div class="grid_5  ">  
              <div class="step5leftMenu">
              <h3>Order Summary<span class="blueBdr"></span></h3>
              <ul>
              <li> 
               
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
                    
               <div class="stepLeft"><h4>Strength</h4>
                   <p id="strengthTitleText"><?php echo $strenthDetail['post_title']; ?></p>
                      <input type="hidden" id="strengthtext" name="strengthtext" value="<?php  echo $strenth ;?>">
                       <input type="hidden" name="strenthPlanCode" id="strenthPlanCode" value="<?php echo $strenthPlanCode; ?>">
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
                                        $planCode =   get_post_meta( get_the_ID(), 'recurly_plan_code', true );
                                    ?>
                                      <li><a href="javascript:void(0);" onclick="getStrength('<?php echo get_the_title().'~'.get_the_ID().'~'.$planCode; ?>');" ><?php echo get_the_title(); ?></a></li>
                                   <?php      
                                              }
                                           }
                                            wp_reset_postdata();
                                        } ?>

                                  </ul>
                          </li></ul>
                  </div></span>
                  
               </li>
                <li><a href="javascript:void(0);">
               <div class="stepLeft"><h4>Shipping</h4>
                <p>Free!</p> 
                   <input type="hidden" name="planIdText" id="planIdText" value="<?php echo $planid; ?>">
                  
                   <input type="hidden" name="planPricetText" id="planPricetText" value="<?php echo $planPrice; ?>">
                 
                   
                   <input type="hidden" id="shippingCountry" name="shippingCountry" value="">
                   <input type="hidden" id="shippingState" name="shippingState" value="">
                   
                   
               </div>
                </a><div class="couponCode">
                    Have a <a href="javascript:void(0);" onclick="$('#couponBox').toggle();">coupon code?</a> 
                    <div id="couponBox" style="display: none;">
                        <div class="clear"></div>
                        <div id="couponMsg"></div>
                        <div class="">
                            <input type="text" class="boxSizing" id="couponCodeText" name="couponCodeText" value="" maxlength="50"> 
                         
                            <a href="javascript:void(0);" onclick="return checkCouponCode();" class="blueBtn boxSizing applyBut" >Apply</a>
                            
                        <div class="clear"></div>
                    </div>
                        
  </div> 
</li>
              </ul>
             <div class="clear"></div>

             <h5>Total   $<span id="planPrice"><?php echo  $planPrice; ?></span></h5>
              <div class="leftComplte">

                  
                  <input type="submit" value="Complete Purchase" class="blueBtn" name="checkoutSubmit" id="checkoutSubmit">
                  
                  <div class="clear"></div> </div>

            </div> 
            </div>   

             <div class="grid_10">
              <div class="step5edit"><h4>Shipping Address</h4>
                 <div class="shiping">
                <ul>
                    <li><input type="text" placeholder="First Name" name="shippingFirstName" id="shippingFirstName" >
                            
                    </li>
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
                <li class="smallArea"><input type="text" placeholder="Zip Code" name="shippingZipCode" id="shippingZipCode"></li>
                <li class="bigArea" style="margin-left:10px;"><div class="stateDrop">

          <ul id="stateDrop">
              <li><a href="javascript:void(0);" class="Drop boxSizing" id="shipCountry">Country</a>
                                  <ul class="boxSizing" id="scroll">
                                      <?php 
                                      $countryQuery = "SELECT * FROM  ".$wpdb->prefix . "world_countries order by  Country_Name asc";
                                       $countryArray = $wpdb->get_results($countryQuery);
                                      
                                      if(!empty($countryArray))
                                      {
                                          foreach($countryArray as $row){
                                              if('184' == $row->id){
                                       ?>
                                      <li><a href="javascript:void(0);" onclick="getCountryId('<?php echo $row->Country_Name.'~'.$row->id; ?>');"><?php echo $row->Country_Name; ?></a></li>
                                              <?php }} } ?>

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
                    <li class="bigArea"><input type="text" placeholder="Credit Card Number" name="cardNumber" id="cardNumber" ></li>
                <li class="smallArea"><input type="text" placeholder="CVV" name="cardCVV" id="cardCVV" ></li>
                <input type="hidden"  name="cardExpMonth" id="cardExpMonth" value="" >
                <input type="hidden"  name="cardExpYear"  id="cardExpYear"  value="<?php echo date('Y'); ?>" >
                <li class="smallArea"><p>Credit Card<br>
                Expiration Date</p></li>

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
              <li id="cardYearLi"><a href="javascript:void(0);" class="Drop boxSizing" id="cardYearBox"><?php echo $year; ?></a>
                              <ul class="boxSizing" id="cardYear">
                                      <?php  for($i=$year ;$i <= $maxYear; $i++ ){ ?>
                                  <li><a href="javascript:void(0);" onclick="callCardYear('<?php echo $i; ?>');"><?php echo $i; ?></a></li>
                                              <?php }?>

                                  </ul>
                          </li></ul>
                  </div></li>     
                    <div class="clear"></div>

                    <li><input id="default"  type="checkbox" name="default" onclick="callSameAddress();" value="default"> <p>Billing address is the same as shipping address</p>
                    </li>
                   

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
                  <li class="smallArea"><input type="text" placeholder="Zip Code" name="billingZipCode" id="billingZipCode" ></li>
                <li class="bigArea" style="margin-left:10px;"><div class="stateDrop">

          <ul id="stateDrop">
              <li><a href="javascript:void(0);" class="Drop boxSizing" id="billingCountryText">Country</a>
                                  <ul class="boxSizing" id="billScroll">
                                <?php 
                                      $countryQuery = "SELECT * FROM  ".$wpdb->prefix . "world_countries order by  Country_Name asc ";
                                       $countryArray = $wpdb->get_results($countryQuery);
                                      
                                      if(!empty($countryArray))
                                      {
                                          foreach($countryArray as $row){
                                              if('184' == $row->id){
                                       ?>
                                      <li><a href="javascript:void(0);" onclick="getbillingCountryId('<?php echo $row->Country_Name.'~'.$row->id; ?>');"><?php echo $row->Country_Name; ?></a></li>
                                              <?php }} } ?>

                                  </ul>
                          </li></ul>
                  </div></li>

                </ul>  

                </div>
                 <div class="clear"></div>
                 </div>  

                 	<?php
            $current_user = wp_get_current_user();
            if ( 0 == $current_user->ID ) {
                ?>
              <div class="step5edit"><h4>Account Information</h4>
                 <div class="shiping">
                <ul>
                    <li class="bigArea"><input type="text"     placeholder="Email Address" id="userEmailAdress"  name="userEmailAdress"  value="" ></li>
                    <li class="bigArea"><input type="password" placeholder="Password"      id="userPasswordtext" name="userPasswordtext" value="" ></li>
              
                </ul>  

                </div>
                 <div class="clear"></div>
                 </div>
                 
            <?php } ?>   
                 <input type="submit" value="Complete Purchase" class="blueBtn" name="checkoutSubmit" id="checkoutSubmit" style="width: 280px; margin-bottom:15px;"> 
  <input type="hidden" name="checkBothAddress" id="checkBothAddress" value="0">
              </div>
    </form>
 </div>
 
 <div class="clear"></div>
   </div>
   
  
    <!--CheckBox-->
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.customCheck.js"></script>
<script>
     $(function() {
            $('input:radio, input:checkbox').customCheck();
        });
          </script>
    
<style>
  #scroll {
	border: 1px solid gray;
	height:50px;
	width: 100%;
	overflow: hidden;
	position: absolute;
  }
  #billScroll {
	border: 1px solid gray;
	height:50px;
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
  #billingStateText{
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
        $('#billingStateText').perfectScrollbar({
         wheelSpeed: 20,
         wheelPropagation: false
       });
      });
      
      function getPlanName(getplan)
      {        
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
            url         : ajaxurl,
            dataType    : 'json',
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
         
           var getstrengtharray = getstrength.split('~');
            $('#strengthTitleText').text(getstrengtharray[0])
          $('#strengthtext').val(getstrengtharray[1]);
          $('#strenthPlanCode').val(getstrengtharray[2]);
      }
      
      function getCountryId(countryid)
      {
            var countryidarray = countryid.split('~');
        $('#shippingState').val('');   
        $('#shipState').text('State');
          $('#shipCountry').text(countryidarray[0]);
         
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
            $('#billingState').val('');
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

       var checkAdress = $('#checkBothAddress').val();
       if(checkAdress == 0)
        {
            $('#billingBox').hide();
            $('#checkBothAddress').val(1);
        }else
        {
            $('#billingBox').show();
            $('#checkBothAddress').val(0);
        }

        $('#billingAddress').val($('#shippingAddress').val());
        $('#billingAptSuite').val($('#shippingAptSuite').val());
        $('#billingCity').val($('#shippingCity').val());
        $('#billingZipCode').val($('#shippingZipCode').val());

        $('#billingCountry').val($('#shippingCountry').val());
        $('#billingState').val($('#shippingState').val());
        $('#billingCountryText').text($('#shipCountry').text());
        $('#billstateName').text($('#shipState').text());
    }
    
    getCountryId('United States~184');
    getbillingCountryId('United States~184');
    
    function checkCheckoutform(form)
    {
        
        $(".errorMsg").remove();
        $('.errorIco').remove();
        
        var shippingFirstName   = $('#shippingFirstName').val().trim();
        var shippingLastName    = $('#shippingLastName').val().trim();
        var shippingAddress     = $('#shippingAddress').val().trim();
        var shippingAptSuite    = $('#shippingAptSuite').val().trim();
        var shippingCity        = $('#shippingCity').val().trim();
        var shippingZipCode     = $('#shippingZipCode').val().trim();
        var shippingCountry     = $('#shippingCountry').val().trim();
        var shippingState       = $('#shippingState').val().trim();
        
        
        var cardNumber       = $('#cardNumber').val().trim();
        var cardCVV          = $('#cardCVV').val().trim();
        var cardExpMonth     = $('#cardExpMonth').val().trim();
        var cardExpYear      = $('#cardExpYear').val().trim();
        
        
       
        var billingAddress      = $('#billingAddress').val().trim();
        var billingAptSuite     = $('#billingAptSuite').val().trim();
        var billingCity         = $('#billingCity').val().trim();
        var billingState        = $('#billingState').val().trim();
        var billingZipCode      = $('#billingZipCode').val().trim();
        
        <?php if ( 0 == $current_user->ID ) {?>
        var userEmailAdress         = $('#userEmailAdress').val().trim();
        var userPasswordtext        = $('#userPasswordtext').val().trim();
        <?php } ?>   
            
         
        var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
         var errors = [];
         var as =1;
        
        if(shippingFirstName == "")
            {
                 showCheckOutMsg('shippingFirstName','Enter First Name');                 
                 errors[errors.length] =as++;
           }
            
         if(shippingLastName == "")
            {
                 
                 showCheckOutMsg('shippingLastName','Enter Last Name');                 
                  errors[errors.length] =as++;
            }
            
        if(shippingAddress == "")
         {
     
              showCheckOutMsg('shippingAddress','Enter Address');                 
                 errors[errors.length] =as++;
         }  
        /*if(shippingAptSuite == "")
          {
                 showCheckOutMsg('shippingAptSuite','Enter Apt/Suite');                 
                 errors[errors.length] =as++;
          } */
          if(shippingCity == "")
          {
                 showCheckOutMsg('shippingCity','Enter City');                 
                 errors[errors.length] =as++;
          } 
          if(shippingCountry == "")
          {
                showCheckOutMsg('shipCountry','Enter Country');                 
                errors[errors.length] =as++;   
          
          }  
          if(shippingState == "")
          {
                 showCheckOutMsg('shipState','Enter State');                 
                 errors[errors.length] =as++;   
          }  
          if(shippingZipCode == "")
          {
                showCheckOutMsg('shippingZipCode','Enter Zip Code');                 
                errors[errors.length] =as++; 
          }  
          
          
        if( cardNumber == "" )
              {                 
                showCheckOutMsg('cardNumber','Enter Card Number');                 
                errors[errors.length] =as++; 
              }
              
           if(cardNumber != "")
               {
                                        
                            if(!numericReg.test(cardNumber)) {
                                 showCheckOutMsg('cardNumber','Invalid Card Number');                 
                                  errors[errors.length] =as++;  
                            }
        
                           
                                        
               }
              
         if( cardCVV == "")
              {
                  showCheckOutMsg('cardCVV','Enter Card CVV Number');                 
                  errors[errors.length] =as++; 
              }
              
                 if(cardCVV != "")
               {
                   var scripter = 1;
                  if(!numericReg.test(cardCVV)) {
                     scripter = 2;
                  }
                  
                  if(cardCVV.length < 3 || cardCVV.length > 4  )
                        {
                             scripter = 2;
                        } 
                        if(scripter == 2)
                            {
                             showCheckOutMsg('cardCVV','Invalid CVV');                 
                             errors[errors.length] =as++;
                            }
                   
                
               }
         if(cardExpMonth =="")
              {
                  showCheckOutMsg('cardMonthBox','Enter Card Expiration Month');                 
                  errors[errors.length] =as++;       
               
              }
        if(cardExpYear =="")
             {
                  showCheckOutMsg('cardYearBox','Enter Card Expiration Year');                 
                  errors[errors.length] =as++;       
             }
             
             
              
        if(billingAddress =="")
           {
               showCheckOutMsg('billingAddress','Enter Address');                 
               errors[errors.length] =as++;    
           }   
        /*if(billingAptSuite =="")
          {             
              showCheckOutMsg('billingAptSuite','Enter Apt/Suite');                 
              errors[errors.length] =as++;
          }*/
        if(billingCity =="")
          {
              showCheckOutMsg('billingCity','Enter City');                 
              errors[errors.length] =as++;
          }
        if(billingState =="")
        {
              showCheckOutMsg('billstateName','Enter State');                 
              errors[errors.length] =as++;
        } 
        
        if(billingZipCode =="")
        {
              showCheckOutMsg('billingZipCode','Enter Zip Code');                 
              errors[errors.length] =as++;
        } 
        
        <?php if ( 0 == $current_user->ID ) {?>  
        
    if(userEmailAdress == "" )
    {
           showCheckOutMsg('userEmailAdress','Enter Email Address');                 
           errors[errors.length] =as++;
    }

    if(userEmailAdress !="")
    {
        if (!echeck(userEmailAdress)) {
           showCheckOutMsg('userEmailAdress','Enter Vaild Email Address');                 
           errors[errors.length] =as++;
        }
    }

   if (userPasswordtext == "")
    {	
           showCheckOutMsg('userPasswordtext','Enter Password');                 
           errors[errors.length] =as++;
    }

 if (userPasswordtext != "")
    {   
        if(userPasswordtext.length < 7 )
                {
                     showCheckOutMsg('userPasswordtext','Password must be 7 characters');                 
                     errors[errors.length] =as++;
                }
    }
        <?php } ?>
            
       if (errors.length > 0) { 
          
                    return false;
    }

    return true;
    }
    
    function showCheckOutMsg(inputid,ermsg)
    {
        var errormsgbox="<span class='errorIco' style='display: inline;'></span><div class='errorMsg'>"+ermsg+"</div>";
        $('#'+inputid).parent('li').append(errormsgbox);

    }

    function checkCouponCode()
    {
        $('#couponMsg').html('');
        var couponCodeText = $('#couponCodeText').val();
  
         var data = { couponCodeText: couponCodeText,plancode:<?php echo toPublicId($planPrice); ?> };
	$.ajax({
            type	: "POST",
            cache	: false,
            url     : ajaxurl,
            dataType : 'json',
            data: {
			'action' : 'getCheckCouponCode',
			'datalist' : data
	  },
                success: function(data) {                    
              
             
            $('#couponMsg').html(data.couponmsg);
              
             if(data.response !=""){
                   $('#planPrice').text(data.response);                                  
             }
                        
                                       }
          });	
        
    }

    </script>


<?php get_footer(); ?>