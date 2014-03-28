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
         
      <div class="planPost"><h4>Order Summary</h4>
      <h5><a href="<?php echo get_permalink( 69 ); ?>">Edit</a></h5>
       <p><strong>Plan:</strong> <?php  $planCodeArray = explode('-',$planDetail['order']->plan_code); echo  $planCodeArray[0]." ".ucfirst($planCodeArray[1]); ?>/month</p>
       <p><strong>Strength:</strong> <?php echo $planCodeArray[2];?></p>
       <?php if($planDetail['order']->is_freeshipping == 1){ ?>
       <p><strong>Shipping:</strong> Free</p>
       <?php }?>
       <div class="skyBluBDR"></div>
       <p>Total: $<?php echo $planDetail['order']->order_price; ?></p>
      </div>
      <div class="planPost"><h4>Shipping Address</h4>
      <h5><a href="<?php echo get_permalink( 69 ); ?>">Edit</a></h5>
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
      <div class="planPost"><h4>Billing Information</h4>
      <h5><a href="<?php echo get_permalink( 69 ); ?>">Edit</a></h5>
       <p>Credit Card</p>
       <p><?php echo $new_card = "XXXX-XXXX-XXXX-" . substr($planDetail['card']->card_no,-4,4); ?></p>
       <div class="skyBluBDR"></div>
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
  <h5><a href="<?php echo get_permalink( 66 ).'?planuuid='.urlencode($planDetail['order']->recurly_uuid); ?>">Cancel Subscription</a></h5>
      
      </div>
         
         
     </div>
    </div>
   
 </div>
 <div class="clear"></div>
   </div>
   
<script type="text/javascript">
    function checkPlan()
    {
    
    var listID = $("#planList").val();
    
    window.location.href="<?php echo get_permalink( 66 ).'?planCode='; ?>"+listID;
    }

</script>

<?php get_footer(); ?>