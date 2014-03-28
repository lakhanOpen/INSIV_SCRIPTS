<?php
ob_start();
  /*
  Template name: View User Plan Detail 
  */
?>
<?php
$current_user = wp_get_current_user();



if ( 0 == $current_user->ID ) {
wp_redirect(home_url());
}
  require_once('rlib/rlib-include.php');
  $errorMsg ='';
if(!empty($_GET['planuuid']))
{
 
     $planuuid = urldecode($_GET['planuuid']);
     try { 
     $subscription = Recurly_Subscription::get($planuuid);
     $subscription->terminateWithoutRefund();
     
    }
    catch (Exception $e) {  
   $errorMsg =  $e->getMessage();
    }
    
    cancelSubscription($planuuid,$current_user->ID );
  
}
  
    ?>

<?php get_header('white'); ?>


  <div class="planPage">
 <div class="container_12 greylight">
  <?php include_once 'userLeftSideBar.php'; ?>
     
     <?php if(!empty($_GET['planCode']))
     {     
       $planCode =  toInternalId(unserialize(urldecode($_GET['planCode'])));
       $currentPlanQuery = "SELECT *FROM ".$wpdb->prefix ."plan_order WHERE user_ID =".$current_user->ID." and( id=".$planCode." and status = 1) order by add_date DESC LIMIT 0 , 1";
     }  else {
           $currentPlanQuery = "SELECT *FROM ".$wpdb->prefix ."plan_order WHERE user_ID =".$current_user->ID." and status = 1 order by add_date DESC LIMIT 0 , 1";
         }
             
        $currentPlanArray = $wpdb->get_results($currentPlanQuery);
        if(!empty($currentPlanArray)){
        $planDetail=  getPlanDetail($currentPlanArray[0]->id,$current_user->ID);
        $planDetail['order']= $currentPlanArray['0'];
        }else{
             $url = get_permalink(6);
             wp_redirect($url);
			 die();
        }
        
?>
     <?php 
     if(!empty($errorMsg))
     {
         echo $errorMsg;
         $errorMsg ='';
     }
     ?>
     
    <div class="planRightpost">
     <div class="grid_8">
        <?php 
   
    if(!empty($_SESSION['checkOutError']))
    {
          
          
          echo "<div id='checkoutErrorMsg'>".$_SESSION['checkOutError']['msg']."</div>";
          $_SESSION['checkOutError'] = '';
    }
    
?>
         
         <select name="planList" id="planList" onchange="checkPlan();">
               
             <?php 
                   $planOrderQuery = "SELECT *FROM ".$wpdb->prefix ."plan_order WHERE user_ID =".$current_user->ID." and status = 1 order by add_date DESC";
                   $planOrderArray = $wpdb->get_results($planOrderQuery);
                   
                   if(!empty($planOrderArray)){
                   foreach($planOrderArray as $row){
                            $codeArray = explode('-',$row->plan_code);      
                            $selectPlan = '';
                            
                            if($row->id == $planDetail['order']->id)
                            {
                               $selectPlan = 'selected="selected"';
                               
                            }
                       ?>
               <option value="<?php echo urlencode(serialize(toPublicId($row->id))); ?>" <?php echo $selectPlan; ?> ><?php echo $codeArray[0]." ".ucfirst($codeArray[1])." ".$codeArray[2]; ?></option>
                   <?php }}else{wp_redirect(home_url());}?>
         </select>
         
         <div class="planPost" id="showPlanPost" ><h4>Order Summary</h4>
             <h5><a href="javascript:void(0);" onclick="callPlanedit('showPlanPost','planEditPost');" >Edit</a></h5>
       <p><strong>Plan:</strong> <?php  $planCodeArray = explode('-',$planDetail['order']->plan_code); echo  $planCodeArray[0]." ".ucfirst($planCodeArray[1]); ?>/month</p>
       <p><strong>Strength:</strong> <?php echo $planCodeArray[2];?></p>
       <?php if($planDetail['order']->is_freeshipping == 1){ ?>
       <p><strong>Shipping:</strong> Free</p>
       <?php }?>
       <div class="skyBluBDR"></div>
       <p>Total: $<?php echo $planDetail['order']->order_price; ?></p>
      </div>
         
         <div class="planPostEdit" id="planEditPost" style="display:none; ">
      <h4>Order Summary</h4>
      <form action="<?php echo get_permalink( 69 ).'?action=orderCode'; ?>" name="editPlanForm" id="editPlanForm" method="post">
        <table class="orderSum" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">Plan<br>
        <span id="planName" ><?php  $planCodeArray = explode('-',$planDetail['order']->plan_code); echo  $planCodeArray[0]." ".ucfirst($planCodeArray[1]); ?>/month</span></td>
    <td width="70%"><div class="optionDrop">
    
<ul id="optionDrop">
		<li><a href="javascript:void(0);" class="Drop boxSizing"></a>
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
                    
                    ?>
                    
			<ul class="boxSizing">
                            <?php if(!empty($planArray)){
                        foreach ($planArray as $row){
                             $tax_term_id = $row->term_id;
                             $pprice = get_term_meta( $tax_term_id, 'priceText', true);
                             $planSlug = $planCodeArray[0].'-'.$planCodeArray[1];
                            if($planSlug == $row->slug)  {
                                $planid =$tax_term_id ;
                            }
                             ?>
                                <li><a href="javascript:void(0);" onclick="getPlanName('<?php echo $row->name.'~'.$tax_term_id.'~'.$pprice; ?>');"><?php echo $row->name; ?></a></li>    
     <?php } }?>
      			</ul>
		</li></ul>
        </div></td>
  </tr>
  <tr id="strengthBox">
      <?php 
      $meta_query     = array();
            $meta_query[] = array(
            'key'       => '_cmb_strength_display_status',
            'value' 	=> 'on',
            'compare' 	=> '=',
            'type'      => 'CHAR'
            ); 
     $query_args=array(
            'taxonomy'      => 'plan',
            'term'          => $planSlug,
            'posts_per_page'=> 5,
            'post_type'     => 'strength',
            'post_status'   => 'publish',
            'meta_query'    => $meta_query,
            'meta_key'      => '_cmb_Strength_position',
            'orderby'       => 'meta_value_num',
            'order'         => 'ASC' 
        ); 
      
      ?>
    <td>Strength<br>
        <span id="strengthTitleText"><?php echo  $planCodeArray[2]; ?></span></td>
    <td><div class="optionDrop">
    
<ul id="optionDrop">
		<li><a href="javascript:void(0);" class="Drop boxSizing"></a>
			<ul class="boxSizing">
                         <?php    if(!empty($query_args)){
                  $query = new WP_Query( $query_args );
                          if ( $query->have_posts() ) {
                                      while ( $query->have_posts() ) {
                                    $query->the_post();  
                                        $planCode =   get_post_meta( get_the_ID(), 'recurly_plan_code', true );
                                   $planPostSlug = get_the_title();
                                     if($planCodeArray[2] == $planPostSlug )  {
                                          $strenth = get_the_ID();
                                      }   
                                        
                                    ?>
                                      <li><a href="javascript:void(0);" onclick="getStrength('<?php echo get_the_title().'~'.get_the_ID().'~'.$planCode; ?>');" ><?php echo get_the_title(); ?></a></li>
                                   <?php      
                                              }
                                           }
                                            wp_reset_postdata();
                                        } ?>
             
      			</ul>
		</li></ul>
        </div></td>
         <input type="hidden" id="strengthtext" name="strengthtext" value="<?php  echo $strenth ;?>">
         <input type="hidden" name="strenthPlanCode" id="strenthPlanCode" value="<?php echo $planDetail['order']->plan_code; ?>">
  </tr>
   
</table>
        <div class="btnSet">
<ul>
<li> <a class="blackBtn" href="javascript:void(0);" onclick="callPlanedit('planEditPost','showPlanPost');">Cancel</a></li>
                    <input type="hidden" name="planIdText" id="planIdText" value="<?php echo $planid; ?>">
                    <input type="hidden" name="planUuid" id="planUuid" value="<?php echo $planDetail['order']->recurly_uuid; ?>"/>
                    
                   <input type="hidden" name="planPricetText" id="planPricetText" value="<?php echo $planDetail['order']->order_price; ?>">
                  

<li> <input type="submit" class="blueBtn" value="Save changes" name="editPlanSubmit" style="padding: 15px 50px;"> 
</li>
<li></li>
</ul>

</div>
      </form>
      </div>
         
    
         
         <div class="planPost" id="planShippingAddress"><h4>Shipping Address</h4>
      <h5><a href="javascript:void(0);" onclick="callPlanedit('planShippingAddress','editPlanShippingAddress');">Edit</a></h5>
      <?php  $shippingArray = unserialize ($planDetail['address']['0']->value );     ?>
       <p><?php  echo $shippingArray['shippingFirstName'].' '.$shippingArray['shippingLastName']; ?></p>
       <p><?php  echo $shippingArray['shippingAddress']; ?></p>
       <p><?php  echo $shippingArray['shippingAptSuite']; ?></p>
       <p><?php  echo $shippingArray['shippingCity'].','.$shippingArray['shippingZipCode']; ?></p>
       <p><?php $stateName = getStateCode($shippingArray['shippingState']);
           echo $stateName->State_Name;
       ?></p>
       <p><?php $countryName = getCountryCode($shippingArray['shippingCountry']);
           echo $countryName->Country_Name;
       ?></p>
      </div>
         
         
         <div class="planPostEdit" id="editPlanShippingAddress" style="display: none;"><h4>Shipping Address</h4>
             
             <form  name="planshipform"  id="planshipform"   action="<?php echo get_permalink( 69 ).'?action=palnShipping'; ?>" method="post" onsubmit="return checkPlanShipDetail(this);">
       <div class="shiping">
      <ul>
          <li><input type="text" placeholder="First Name" name="shippingFirstName" id="shippingFirstName" value="<?php  echo $shippingArray['shippingFirstName']; ?>"></li>
          <li><input type="text" placeholder="Last name" name="shippingLastName" id="shippingLastName" value="<?php  echo $shippingArray['shippingLastName']; ?>" ></li>
          <li class="bigArea"><input type="text" placeholder="Address" name="shippingAddress" id="shippingAddress" value="<?php  echo $shippingArray['shippingAddress']; ?>"></li>
          <li class="smallArea"><input type="text" placeholder="Apt/Suit" name="shippingAptSuite" id="shippingAptSuite" value="<?php  echo $shippingArray['shippingAptSuite']; ?>" ></li>
          <li class="smallArea"><input type="text" placeholder="City" name="shippingCity" id="shippingCity" value="<?php  echo $shippingArray['shippingCity']; ?>"></li>
          <input type="hidden" id="shippingCountry" name="shippingCountry" value="<?php echo $shippingArray['shippingCountry']; ?>">
          <input type="hidden" id="shippingState" name="shippingState" value="<?php echo $shippingArray['shippingState']; ?>">
          <input type="hidden" id="shipAddressId" name="shipAddressId" value="<?php  echo urlencode(serialize(toPublicId($planDetail['address']['0']->id)));?>">
          <li class="smallArea"><!--<div class="stateDrop">
    
   <ul id="stateDrop">
              <li><a href="javascript:void(0);" class="Drop boxSizing" id="shipState" >State</a>
                                  <ul class="boxSizing" id="stateBox">
                <?php 
                    $stateSql= "SELECT * FROM ".$wpdb->prefix . "world_states WHERE Country_ID ='".$shippingArray['shippingCountry']."' ORDER BY State_Name ASC";
                    $stateArray = $wpdb->get_results($stateSql);
                    if(!empty($stateArray))
                        {
                            $currentShipState ='';
                            foreach ($stateArray as $row)
                                {   
                                    $getstr = "'".str_replace("'",'~apos~',$row->State_Name)."~".$row->id."~".$_POST['datalist']['divid']."'";
									
                                            if($shippingArray['shippingState'] == $row->id )
                                            {
                                                $currentShipState = $getstr;
                                            }
             
                                     echo ' <li><a href="javascript:void(0);" onclick="callShipState('.$getstr.');">'.$row->State_Name.'</a></li>';
                                }
                        
                    }
                                               ?>
                          </li></ul>
        </div>-->
        
        <select id="statedrop_select">
                <?php 
                    $stateSql= "SELECT * FROM ".$wpdb->prefix . "world_states WHERE Country_ID ='".$shippingArray['shippingCountry']."' ORDER BY State_Name ASC";
                    $stateArray = $wpdb->get_results($stateSql);
                    if(!empty($stateArray))
                        {
                            $currentShipState ='';
                            foreach ($stateArray as $row)
                                {   
                                    $getstr = "".str_replace("'",'~apos~',$row->State_Name)."~".$row->id."~".$_POST['datalist']['divid']."";
									$selected="";
                                            if($shippingArray['shippingState'] == $row->id )
                                            {
                                                $currentShipState = $getstr;
												$selected="selected";
                                            }
             
                                     echo ' <option onchange="callShipState(\''.$getstr.'\');" value="'.$getstr.'" '.$selected.'>'.$row->State_Name.'</option>';
                                }
                        
                    }
                                               ?>
                          </select>
        </li>
        <li class="smallArea"><input type="text" placeholder="Zip code" name="shippingZipCode" id="shippingZipCode" value="<?php echo $shippingArray['shippingZipCode']; ?>"  ></li>
      <div class="clear"></div>
      <li class="bigArea fLeft" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
		   <li><a href="javascript:void(0);" class="Drop boxSizing" id="shipCountry">Country</a>
		<ul class="boxSizing" id="scroll">
                                      <?php 
                                      $countryQuery = "SELECT * FROM  ".$wpdb->prefix . "world_countries order by  Country_Name asc";
                                       $countryArray = $wpdb->get_results($countryQuery);
                                      $currentShipCountry = 'United States~184';
                                      if(!empty($countryArray))
                                      {
                                          foreach($countryArray as $row){
                                              if('184' == $row->id){
                                                        if($row->id ==$shippingArray['shippingCountry'] )
                                                        {
                                                            $currentShipCountry =$row->Country_Name.'~'.$row->id;
                                                        }
                                       ?>
                                      <li><a href="javascript:void(0);" onclick="getCountryId('<?php echo $row->Country_Name.'~'.$row->id; ?>');"><?php echo $row->Country_Name; ?></a></li>
                                              <?php } } } ?>

                                  </ul>
		</li></ul>
        </div> </li>
        <div class="clear"></div>
      <div class="btnSet">

<ul>
<li> <a class="blackBtn" href="javascript:void(0);" onclick="callPlanedit('editPlanShippingAddress','planShippingAddress');">Cancel</a></li>
 <li>  <input type="submit" class="blueBtn" value="Save changes" name="editPlanShipSubmit" style="padding: 15px 50px;">
</li>
<li></li>
</ul>

</div>
      </ul>  
      
      </div>
             </form> 
       <div class="clear"></div>
       </div>
       
         <div class="planPost" id="planBilling"><h4>Billing Information</h4>
      <h5><a href="javascript:void(0);" onclick="callPlanedit('planBilling','editPlanBilling');">Edit</a></h5>
       <p>Credit Card</p>
       <p><?php echo $new_card = "XXXX-XXXX-XXXX-" . substr($planDetail['card']->card_no,-4,4); ?></p>
       <div class="skyBluBDR"></div>
        <h5><a href="javascript:void(0);" onclick="callPlanedit('planBilling','editPlanBillingAddress');">Edit</a></h5>
       <?php  $addressArray = unserialize ($planDetail['address']['1']->value );   ?>
        <p><?php echo $addressArray['billingAddress']; ?></p>
       <p><?php  echo $addressArray['billingAptSuite']; ?></p>
       <p><?php  echo $addressArray['billingCity'].','.$addressArray['billingZipCode']; ?></p>
       <p><?php $stateName = getStateCode($addressArray['billingState']);
           echo $stateName->State_Name;
       ?></p>
       <p><?php $countryName = getCountryCode($addressArray['billingCountry']);
           echo $countryName->Country_Name;
       ?></p>
      </div>
         
         <div class="planPostEdit" id="editPlanBilling" style="display: none;"><h4>Billing Information</h4>
          <form  name="planBillform"  id="planBillform"   action="<?php echo get_permalink( 69 ).'?action=planBillform'; ?>" method="post" onsubmit="return checkPlanBillDetail(this);">    
       <div class="shiping">
      <ul>
      <li class="bigArea"><input type="text" placeholder="Credit card number" name="cardNumber" id="cardNumber" maxlength="19"></li>
      <li class="smallArea"><input type="text" placeholder="CVV" name="cardCVV" id="cardCVV" maxlength="4"></li>
        <input type="hidden"  name="cardExpMonth" id="cardExpMonth" value="1" >
        <input type="hidden"  name="cardExpYear"  id="cardExpYear"  value="<?php echo date('Y'); ?>" >
        <input type="hidden" name="carddetail" value="<?php  echo urlencode(serialize(toPublicId($planDetail['card']->id)));?>">
      <li class="smallArea creditCardTab"><p>Credit Card<br>
      Expiration date</p></li>

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

    
      <div class="btnSet">
<ul>
<li> <a class="blackBtn" href="javascript:void(0);" onclick="callPlanedit('editPlanBilling','planBilling');">Cancel</a></li>
 <li>  <input type="submit" class="blueBtn" value="Save changes" name="editPlanBillSubmit" style="padding: 15px 50px;">
</li>
<li></li>
</ul>

</div>
      </ul>  
      
      </div>
          </form>   
       <div class="clear"></div>
       </div>
       
       <div class="planPostEdit" id="editPlanBillingAddress" style="display: none;"><h4>Billing Address</h4>
             
			<form  name="planbillform"  id="planbillform"   action="<?php echo get_permalink( 69 ).'?action=palnBilling'; ?>" method="post" onsubmit="return checkPlanBillAddressDetail(this);">
       			<div class="shiping">
      				<ul>
                            <li><input id="default"  type="checkbox" name="default" onclick="callSameAddress();" value="default"> <p>Billing address is the same as shipping address</p></li>
                            <input id="checkBothAddress" type="hidden" value="0" name="checkBothAddress">
                          <li class="bigArea"><input type="text" placeholder="Address" name="billingAddress" id="billingAddress" value="<?php  echo $addressArray['billingAddress']; ?>"></li>
                          <li class="smallArea"><input type="text" placeholder="Apt/Suit" name="billingAptSuite" id="billingAptSuite" value="<?php  echo $addressArray['billingAptSuite']; ?>" ></li>
                          <li class="smallArea"><input type="text" placeholder="City" name="billingCity" id="billingCity" value="<?php  echo $addressArray['billingCity']; ?>"></li>
                          <input type="hidden" id="billingCountry" name="billingCountry" value="<?php echo $addressArray['billingCountry']; ?>">
                          <input type="hidden" id="billingState" name="billingState" value="<?php echo $addressArray['billingState']; ?>">
                          <input type="hidden" id="billAddressId" name="billAddressId" value="<?php  echo urlencode(serialize(toPublicId($planDetail['address']['1']->id)));?>">
                          
          				  <li class="smallArea">
						
                            <select id="billingstatedrop_select">
                                    <?php 
                                        $stateSql= "SELECT * FROM ".$wpdb->prefix . "world_states WHERE Country_ID ='".$addressArray['billingCountry']."' ORDER BY State_Name ASC";
                                        $stateArray = $wpdb->get_results($stateSql);
                                        if(!empty($stateArray))
                                            {
                                                $currentBillState ='';
                                                foreach ($stateArray as $row)
                                                    {   
                                                        $getstr = "".str_replace("'",'~apos~',$row->State_Name)."~".$row->id."~".$_POST['datalist']['divid']."";
                                                        $selected="";
                                                                if($addressArray['billingState'] == $row->id )
                                                                {
                                                                    $currentBillState = $getstr;
                                                                    $selected="selected";
                                                                }
                                 
                                                         echo ' <option onchange="callBillState(\''.$getstr.'\');" value="'.$getstr.'" '.$selected.'>'.$row->State_Name.'</option>';
                                                    }
                                            
                                        }
									?>
								</select>
       					  </li>
                          <li class="smallArea"><input type="text" placeholder="Zip code" name="billingZipCode" id="billingZipCode" value="<?php echo $addressArray['billingZipCode']; ?>"  ></li>
                          <div class="clear"></div>
                          
      					  <li class="bigArea fLeft" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
		   <li><a href="javascript:void(0);" class="Drop boxSizing" id="billingCountryText">United States</a>
		<!--<ul class="boxSizing" id="scroll">
                                      <?php 
                                      $countryQuery = "SELECT * FROM  ".$wpdb->prefix . "world_countries order by  Country_Name asc";
                                       $countryArray = $wpdb->get_results($countryQuery);
                                      $currentShipCountry = 'United States~184';
                                      if(!empty($countryArray))
                                      {
                                          foreach($countryArray as $row){
                                              if('184' == $row->id){
                                                        if($row->id ==$addressArray['billingCountry'] )
                                                        {
                                                            $currentShipCountry =$row->Country_Name.'~'.$row->id;
                                                        }
                                       ?>
                                      <li><a href="javascript:void(0);" onclick="getCountryId('<?php echo $row->Country_Name.'~'.$row->id; ?>');"><?php echo $row->Country_Name; ?></a></li>
                                              <?php } } } ?>

                                  </ul>-->
		</li></ul>
        </div> </li>
        				  <div class="clear"></div>
                          
                          <input type="hidden" name="billingAddress_hidden" id="billingAddress_hidden" value="<?php  echo $addressArray['billingAddress']; ?>" />
                          <input type="hidden" name="billingAptSuite_hidden" id="billingAptSuite_hidden" value="<?php  echo $addressArray['billingAptSuite']; ?>" />
                          <input type="hidden" name="billingCity_hidden" id="billingCity_hidden" value="<?php  echo $addressArray['billingCity']; ?>" />
                          <input type="hidden" name="billingCountry_hidden" id="billingCountry_hidden" value="<?php  echo $addressArray['billingCountry']; ?>" />
                          <input type="hidden" name="billingState_hidden" id="billingState_hidden" value="<?php  echo $addressArray['billingState']; ?>" />
                          <input type="hidden" name="billingZipCode_hidden" id="billingZipCode_hidden" value="<?php  echo $addressArray['billingZipCode']; ?>" />
                          
      					  <div class="btnSet">
                                <ul>
                                	<li> <a class="blackBtn" href="javascript:void(0);" onclick="callPlanedit('editPlanBillingAddress','planBilling');">Cancel</a></li>
                                 	<li>  <input type="submit" class="blueBtn" value="Save changes" name="editPlanShipSubmit" style="padding: 15px 50px;"></li>
                                
                                	<li></li>
                                </ul>
                            </div>
     				 </ul>  
     			</div>
             </form> 
       		<div class="clear"></div>
       </div>
       
      <div class="planPost"><h4>Subscription Status</h4>
         
      <?php 
      global $wpdb;       
        $invoicSql=  "SELECT * FROM  ".$wpdb->prefix . "plan_payment_history WHERE  order_Id =". $planDetail['order']->id." AND  user_Id =$current_user->ID limit 0,1";        
        $invoicArray = $wpdb->get_results($invoicSql);        
       if(!empty($invoicArray))
       {
           $as =0;
           foreach($invoicArray as $row)
           {
              
               if($as ==1)
               {
                    echo "<p>Active |  Subscription: ".date('d - M -Y ', $row->add_date)."|  Charged Monthly</p>";
               }else{
                    echo "<p>Active |  Purchased: ".date('d - M -Y ', $row->add_date)."|  Charged Monthly</p>";
                    $as++;
               }
               
             
           }
       } 
      
   ?>
          <h5><a href="javascript:void(0);" onclick="checkCancelUserPlan();" >Cancel Subscription</a></h5>
      
      </div>
         
         
     </div>
    </div>
   
 </div>
 <div class="clear"></div>
   </div>
   <style>
  #scroll {
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
   </style>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/minimalect.css" media="screen" />
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/minimalect.js"></script>
<script>
  $(document).ready(function(){
	$("#statedrop_select").minimalect();
  });
  $(document).ready(function(){
	$("#billingstatedrop_select").minimalect();
  });
  $( "body" ).delegate( "div.minict_wrapper > input", "keypress", function(e) { return e.keyCode != 13;}); 
</script>   
<script type="text/javascript">
    function checkPlan()
    {
    
    var listID = $("#planList").val();
    
    window.location.href="<?php echo get_permalink( 66 ).'?planCode='; ?>"+listID;
    }
    
 function callPlanedit(hideDivId,showDivId)
{
    $('#'+hideDivId).hide();
    $('#'+showDivId).show();
}

   function getPlanName(getplan)
      {        
          var planaaray = getplan.split('~');
          var pname = planaaray[0]+'/month';
         $('#planName').text(pname);
         $('#planIdText').val(planaaray[1]);         
         $('#planPricetText').val(planaaray[2]);
         
         var data = $('#planIdText').serialize();
	$.ajax({
            type	: "POST",
            cache	: false,
            url         : ajaxurl,
            dataType    : 'json',
            data: {
			'action' : 'getEditPlanStrength',
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
			'action' : 'getEditStateByCountryId',
			'datalist' : data
	  },
                success: function(data) {
                    
                    $('#stateBox').html(data.response);
                        
                                       }
          });	
         
      }
      
      function setEditCountry(countryid){
      var countryidarray = countryid.split('~');
      $('#shipCountry').text(countryidarray[0]);
      $('#shippingCountry').val(countryidarray[1]);
    }
      
      <?php  echo "setEditCountry('".$currentShipCountry."');";?>
        
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
        
    function callShipState(getShipState)
	{     
	
		getShipState = getShipState.replace("~apos~", "'");
		
		var getShipStatearray = getShipState.split('~');           
		
		$('#shipState').text(getShipStatearray[0]);
		$('#shippingState').val(getShipStatearray[1]);  
		//alert($('#shippingState').val());
	
	} 
	
	function callBillState(getShipState)
	{     
	
		getShipState = getShipState.replace("~apos~", "'");
		
		var getShipStatearray = getShipState.split('~');           
		
		//$('#shipState').text(getShipStatearray[0]);
		$('#billingState').val(getShipStatearray[1]);  
		//alert($('#shippingState').val());
	
	} 
	
      <?php 
	  		if(!empty($currentShipState))
	  		{
          		echo "callShipState('".$currentShipState."');";
      		}
	  ?>  
    
	
	function callSameAddress()
	{
		var checkAdress = $('#checkBothAddress').val();
		if(checkAdress == 0)
		{
			$('#billingAddress').val($('#shippingAddress').val());
			$('#billingAptSuite').val($('#shippingAptSuite').val());
			$('#billingCity').val($('#shippingCity').val());
			$('#billingZipCode').val($('#shippingZipCode').val());
			
			$('#billingCountry').val($('#shippingCountry').val());
			$('#billingState').val($('#shippingState').val());
			
			var getShipState = $('#statedrop_select').val().replace("~apos~", "'");
				 
			var getShipStatearray = getShipState.split('~');
			
			$('#billingstatedrop_select').val(getShipStatearray[0]+"~"+getShipStatearray[1]+"~").change();
			
			$('#checkBothAddress').val(1);
		}
		else
		{
			$('#billingAddress').val($('#billingAddress_hidden').val());
			$('#billingAptSuite').val($('#billingAptSuite_hidden').val());
			$('#billingCity').val($('#billingCity_hidden').val());
			$('#billingZipCode').val($('#billingZipCode_hidden').val());
			
			$('#billingCountry').val($('#billingCountry_hidden').val());
			$('#billingState').val($('#billingState').val());
			
			$('#billingstatedrop_select').val('<?php echo $currentBillState; ?>').change();
			
			
			$('#checkBothAddress').val(0);
		}
		
		
		//$('#billstateName').text($('#shipState').text());
	}
	
	function checkPlanShipDetail(frm){
            $(".errorMsg").remove();
        $('.errorIco').remove();
        
        var shippingFirstName   = $('#shippingFirstName').val().trim();
        var shippingLastName    = $('#shippingLastName').val().trim();
        var shippingAddress     = $('#shippingAddress').val().trim();
        var shippingAptSuite    = $('#shippingAptSuite').val().trim();
        var shippingCity        = $('#shippingCity').val().trim();
        var shippingZipCode     = $('#shippingZipCode').val().trim();
        var shippingCountry     = $('#shippingCountry').val().trim();
		
		callShipState($('#statedrop_select').val().trim())
        var shippingState       = $('#shippingState').val().trim();
		
		//alert(shippingState+":"+$('#statedrop_select').val().trim()+":"+$('#shippingState').val().trim());
        
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
          
           if (errors.length > 0) {           
                    return false;
                }
            return true;
      }
      
	  
	
	function checkPlanBillAddressDetail(frm){
           
		$(".errorMsg").remove();
        $('.errorIco').remove();
		
        var shippingAddress     = $('#billingAddress').val().trim();
        var shippingAptSuite    = $('#billingAptSuite').val().trim();
        var shippingCity        = $('#billingCity').val().trim();
        var shippingZipCode     = $('#billingZipCode').val().trim();
        var shippingCountry     = $('#billingCountry').val().trim();
		
		callBillState($('#billingstatedrop_select').val().trim())
        var shippingState       = $('#billingState').val().trim();
		
		//alert(shippingState+":"+$('#statedrop_select').val().trim()+":"+$('#shippingState').val().trim());
        
        var errors = [];
        var as =1;
        
        if(shippingAddress == "")
         {
     
              showCheckOutMsg('billingAddress','Enter Address');                 
                 errors[errors.length] =as++;
         }  
          if(shippingCity == "")
          {
                 showCheckOutMsg('billingCity','Enter City');                 
                 errors[errors.length] =as++;
          } 
          if(shippingCountry == "")
          {
                showCheckOutMsg('billingCountry','Enter Country');                 
                errors[errors.length] =as++;   
          
          }  
          if(shippingState == "")
          {
                 showCheckOutMsg('billingState','Enter State');                 
                 errors[errors.length] =as++;   
          }  
          if(shippingZipCode == "")
          {
                showCheckOutMsg('billingZipCode','Enter Zip Code');                 
                errors[errors.length] =as++; 
          } 
          
           if (errors.length > 0) {           
                    return false;
                }
            return true;
      }
	  	  
   function checkPlanBillDetail(form)
   {
         $(".errorMsg").remove();
        $('.errorIco').remove();
        
        var cardNumber       = $('#cardNumber').val().trim();
        var cardCVV          = $('#cardCVV').val().trim();
        var cardExpMonth     = $('#cardExpMonth').val().trim();
        var cardExpYear      = $('#cardExpYear').val().trim();
        
         var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
         var errors = [];
         var as =1;
       
          
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
							    
							if(cardNumber.length>19)
							{
								
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
				  //if(cardCVV.length != 3  )
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

function checkCancelUserPlan()
{
var r=confirm("Are you sure you want to cancel?");
if (r==true)
  {
      window.location = "<?php echo get_permalink( 66 ).'?planuuid='.urlencode($planDetail['order']->recurly_uuid); ?>";
  }

}
</script>


<script type="text/javascript" >
$(document).ready(function ($) {
        $('#scroll').perfectScrollbar({
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
</script>

<?php get_footer(); ?>