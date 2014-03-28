<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

       $userPlanArray = array();
       $as =0; 
       $subscriptions = Recurly_SubscriptionList::getForAccount($current_user->ID);
       
   
   foreach ($subscriptions as $subscription) {
    $subscription = (array) $subscription;
  foreach($subscription as $row1)
  {
        if(!empty($row1['plan']))
        {
             $planArray = (array) $row1['plan'];
       
             foreach ($planArray as $key=>$row2)
             {                                    
                 if(is_array($row2) && count($row2)>0){                                       
                    $userPlanArray[$as]['plan_code'] = $row2['plan_code'];                
                 }                
             }              
        } 
        
         if(is_array($row1) && (count($row1)>0 && $row1['uuid'])){
         $userPlanArray[$as]['uuid'] = $row1['uuid'];        
         $as++; 
        }          
  }
}

pr($userPlanArray);
?>
    <div class="shiping">
      <ul>
          <li><input type="text" placeholder="First Name" name="shipFName" id="shipFName" value="<?php  echo $shippingArray['shippingFirstName']; ?>"></li>
          <li><input type="text" placeholder="Last name" name="shiplname" id="shiplname" value="<?php  echo $shippingArray['shippingLastName']; ?>" ></li>
          <li class="bigArea"><input type="text" placeholder="Address" name="shipAddress" id="shipAddress" value="<?php  echo $shippingArray['shippingAddress']; ?>"></li>
          <li class="smallArea"><input type="text" placeholder="Apt/Suit" name="shipAptSuite" id="shipAptSuite" value="<?php  echo $shippingArray['shippingAptSuite']; ?>" ></li>
          <li class="smallArea"><input type="text" placeholder="City" name="shipCity" id="shipCity" value="<?php  echo $shippingArray['shippingCity']; ?>"></li>
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
        <li class="smallArea"><input type="text" placeholder="Zip code" name="shipZipCode" id="shipZipCode" value="<?php echo $shippingArray['shippingZipCode']; ?>"  ></li>
      <div class="clear"></div>
      <li class="bigArea fLeft" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">Country</a>
			<ul class="boxSizing">
            <li><a href="#">Country 1</a></li>
            <li><a href="#">Country 2</a></li>
            <li><a href="#">Country 3</a></li>
             
      			</ul>
		</li></ul>
        </div> </li>
        <div class="clear"></div>
      <div class="btnSet">
<ul>
<li> <a class="blackBtn" href="javascript:void(0);" onclick="callPlanedit('editPlanShippingAddress','planShippingAddress');">Cancel</a></li>
 <li>  <a class="blueBtn" href="#">Save changes</a>
</li>
<li></li>
</ul>

</div>
      </ul>  
      
      </div>

<?php 

 wp_delete_post($_POST['ID']);
            $sendToStrength =  get_site_url().'/wp-admin/edit.php?post_type=strength';
            wp_redirect($sendToStrength); exit;
?>