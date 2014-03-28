<?php
  /*
  Template name: Edit View User Plan Detail 
  */
?>
<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
wp_redirect(home_url());
}



if(!empty($_POST)&& !empty($_GET['action']))
{
	
        $checkedPoat = checkInputValue($_POST);
        extract ($checkedPoat);
        $checkApi = 0;
        require_once('rlib/rlib-include.php');
   if($_GET['action'] == 'orderCode') 
   {
       
       if( !empty($planUuid) && (!empty($strenthPlanCode) && !empty($planPricetText)) ){
             
          
    $sql = "SELECT * FROM ".$wpdb->prefix ."plan_order WHERE recurly_uuid = '".$planUuid."' and user_ID = '".$current_user->ID."'";
    $queryArray = $wpdb->get_results($sql);   
     $orderID = $queryArray[0]->id;
   
           if(!empty($orderID)){
               
                     try {
                $subscription                        = Recurly_Subscription::get($planUuid);
                $subscription->plan_code             = $strenthPlanCode;
                $subscription->unit_amount_in_cents  = ($planPricetText * 100);
                $subscription->updateImmediately(); 
                $checkApi = 1;
                }catch (Recurly_NotFoundError $e) {
                         
                       $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Record could not be found!');
                       $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                       wp_redirect($rtustr);  
                        exit();
                      }
                      catch (Recurly_ValidationError $e) {
                          $messages = explode(',', $e->getMessage());                                                                   
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Validation problems:'. implode("\n", $messages)); 
                        $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                        wp_redirect($rtustr);  
                         exit();
                      }
                      catch (Recurly_ServerError $e) {
                       
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Oops, Something wrong!');                        
                        $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                        wp_redirect($rtustr);  
                        exit();
                      }
                      catch (Exception $e) {   
                          
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'<p>'.get_class($e) . ': ' . $e->getMessage().'</p>'); 
                        $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                        wp_redirect($rtustr);  
                        exit();
                      } 
                      
                      if($checkApi  == 1)
                      {
                         
                          global $wpdb;
                           $moddate = time();
                           $order   = "UPDATE ".$wpdb->prefix ."plan_order SET plan_code='".$strenthPlanCode."',order_price=$planPricetText,mod_date=$moddate WHERE recurly_uuid = '".$planUuid."'";
                           $wpdb->query($order);
                           
                         $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                         wp_redirect($rtustr);  
                         exit();
                      }
       }        
       }else{
           wp_redirect(home_url());
       }
   }
   elseif($_GET['action'] == 'palnShipping')
   {     
    if(((!empty($shippingFirstName) && !empty($shippingLastName)) && (!empty($shippingAddress) && !empty($shipAddressId))) &&(((!empty($shippingCity) && !empty($shippingZipCode)) && (!empty($shippingCountry) && !empty($shippingState))) ))
    {
             $shipAddressId =  toInternalId(unserialize(urldecode($shipAddressId)));
         $rtustr = home_url().'/plan-detail/';
         $sql = "SELECT * FROM ".$wpdb->prefix ."plan_order_address WHERE id = '".$shipAddressId."' and user_Id = '".$current_user->ID."'";
         $queryArray = $wpdb->get_results($sql);     
            if(!empty($queryArray))
            {
                $shippingAddressArray = array('shippingFirstName'=>$shippingFirstName,'shippingLastName'=>$shippingLastName,'shippingAddress'=>$shippingAddress,'shippingAptSuite'=>$shippingAptSuite,'shippingCity'=>$shippingCity,'shippingZipCode'=>$shippingZipCode,'shippingCountry'=>$shippingCountry,'shippingState'=>$shippingState); 
                $moddate = time();
                
                 $order = "UPDATE ".$wpdb->prefix ."plan_order_address SET value='".serialize($shippingAddressArray)."',mod_date=$moddate WHERE id = '".$shipAddressId."' and user_Id = '".$current_user->ID."' and type = 'shipping'";
                $wpdb->query($order); 
                
               $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($queryArray[0]->order_Id)));  
            }        
            wp_redirect($rtustr);  
            exit();
    }
   }
   elseif($_GET['action'] == 'palnBilling')
   {   
	  
    if(!empty($billingAddress) && !empty($billingCity)  &&!empty($billingCountry) && !empty($billingState) )
    {
         $shipAddressId =  toInternalId(unserialize(urldecode($billAddressId)));
         $rtustr = home_url().'/plan-detail/';
         $sql = "SELECT * FROM ".$wpdb->prefix ."plan_order_address WHERE id = '".$shipAddressId."' and user_Id = '".$current_user->ID."'";
         $queryArray = $wpdb->get_results($sql);     
            if(!empty($queryArray))
            {
				$orderID=$queryArray[0]->order_Id;
				
				try 
				{  
				
					$all_meta_for_user  = get_user_meta( $current_user->ID );

					$firstName          = (!empty($all_meta_for_user['first_name'][0])) ? $all_meta_for_user['first_name'][0] : 'New User';
					$lastName           = (!empty($all_meta_for_user['last_name'][0])) ? $all_meta_for_user['last_name'][0] : $current_user_array->user_email;
					$ipAddress          = get_client_ip();
					$countryCode        = getCountryCode($billingCountry);
					$stateCode          = getStateCode($billingState); 
					
					$billing_info = new Recurly_BillingInfo();
					$billing_info->account_code = $current_user->ID;
					$billing_info->first_name = $firstName;
					$billing_info->last_name = $lastName;
					$billing_info->address1         = $billingAddress;
					$billing_info->address2         = $billingAptSuite;
					//$billing_info->city             = 'Montgomery';
					$billing_info->city             = $billingCity;
					$billing_info->state            = $stateCode->State_Name;
					$billing_info->country          = $countryCode->Short_Name;
					$billing_info->zip              = $billingZipCode;
					$billing_info->ip_address       = $ipAddress;
					$billing_info->update();
					$checkApi = 1;
				}
				catch (Recurly_NotFoundError $e) {  
					$_SESSION['checkOutError']=array('type'=>'error','msg'=>'Record could not be found!');
					$rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
					wp_redirect($rtustr);  
					exit();
				}
				catch (Recurly_ValidationError $e) {
					$messages = explode(',', $e->getMessage());                                                                   
					$_SESSION['checkOutError']=array('type'=>'error','msg'=>'Validation problems:'. implode("\n", $messages)); 
					$rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
					wp_redirect($rtustr);  
					exit();
				}
				catch (Recurly_ServerError $e) {
					$_SESSION['checkOutError']=array('type'=>'error','msg'=>'Oops, Something wrong!');                        
					$rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
					wp_redirect($rtustr);  
					exit();
				}
				catch (Exception $e) {   
					$_SESSION['checkOutError']=array('type'=>'error','msg'=>'<p>'.get_class($e) . ': ' . $e->getMessage().'</p>'); 
					$rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
					wp_redirect($rtustr);  
					exit();
				} 
				
				if($checkApi == 1)
				{
					$shippingAddressArray = array('billingAddress'=>$billingAddress,'billingAptSuite'=>$billingAptSuite,'billingCity'=>$billingCity,'billingZipCode'=>$billingZipCode,'billingCountry'=>$billingCountry,'billingState'=>$billingState); 
                	$moddate = time();
				
					$order = "UPDATE ".$wpdb->prefix ."plan_order_address SET value='".serialize($shippingAddressArray)."',mod_date=$moddate WHERE id = '".$shipAddressId."' and user_Id = '".$current_user->ID."' and type = 'billing'";
					$wpdb->query($order); 
					
					$rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($queryArray[0]->order_Id)));  
				}
			 
                
                 
            }        
            wp_redirect($rtustr);  
            exit();
    }
   }

   elseif ($_GET['action']=='planBillform') {
   
       if((!empty($cardNumber)&& !empty($cardCVV)) &&((!empty($cardExpMonth) && !empty($cardExpYear))&& !empty($carddetail))){
            
            $carddetail =  toInternalId(unserialize(urldecode($carddetail)));
            
             $sql = "SELECT * FROM ".$wpdb->prefix ."plan_card WHERE id = '".$carddetail."' and user_ID = '".$current_user->ID."'";
         $queryArray = $wpdb->get_results($sql); 
         if(!empty($queryArray))
         {
             $orderID            = $queryArray[0]->order_id; 
             $newCardnumber      = chunk_split($cardNumber, 4, '-'); 
             $newCardnumber      = trim($newCardnumber, "-");
             $all_meta_for_user  = get_user_meta( $current_user->ID );
             $firstName          = (!empty($all_meta_for_user['first_name'][0])) ? $all_meta_for_user['first_name'][0] : 'New User';
             $lastName           = (!empty($all_meta_for_user['last_name'][0])) ? $all_meta_for_user['last_name'][0] : $current_user_array->user_email;
            
              
             try {  
            $billing_info = new Recurly_BillingInfo();
            $billing_info->account_code = $current_user->ID;
            $billing_info->first_name = $firstName;
            $billing_info->last_name = $lastName;
            $billing_info->number = $newCardnumber;
            $billing_info->verification_value = $cardCVV;
            $billing_info->month = $cardExpMonth;
            $billing_info->year = $cardExpYear;
            $billing_info->update();
            $checkApi = 1;
             }catch (Recurly_NotFoundError $e) {
                         
                       $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Record could not be found!');
                       $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                       wp_redirect($rtustr);  
                        exit();
                      }
                      catch (Recurly_ValidationError $e) {
                          $messages = explode(',', $e->getMessage());                                                                   
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Validation problems:'. implode("\n", $messages)); 
                        $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                        wp_redirect($rtustr);  
                         exit();
                      }
                      catch (Recurly_ServerError $e) {
                       
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Oops, Something wrong!');                        
                        $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                        wp_redirect($rtustr);  
                        exit();
                      }
                      catch (Exception $e) {   
                          
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'<p>'.get_class($e) . ': ' . $e->getMessage().'</p>'); 
                        $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                        wp_redirect($rtustr);  
                        exit();
                      } 
                      
                      if($checkApi == 1)
                      {
                           $card = "UPDATE ".$wpdb->prefix ."plan_card SET card_no='".$cardNumber."',cvv_no='".$cardCVV."' ,exp_month='".$cardExpMonth."',exp_year='".$cardExpYear."' WHERE id = '".$carddetail."' and user_ID = '".$current_user->ID."'and order_id = $orderID";
                           $wpdb->query($card);
                           $rtustr = home_url().'/plan-detail/?planCode='.urlencode(serialize(toPublicId($orderID)));
                           wp_redirect($rtustr);  
                           exit();
                      }                     
         }        
        
       }
       
      
}else{
      wp_redirect(home_url()); 
      exit();
   }
}
wp_redirect(home_url());
exit();
?>
