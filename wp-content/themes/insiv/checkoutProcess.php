<?php 
/*
Template Name: Checkout Process
*/
ob_start();
if ( is_user_logged_in()  || !empty($_SESSION['nowuserid'])) {   
	
} else{
    wp_redirect(home_url());
   exit();    
}
    if(!empty($_POST))
    {
/*		
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		//die("syon ");*/
     
        $checkedPoat = checkInputValue($_POST);
       extract ($checkedPoat);
      
      
       $checkApi = 0;
        require_once('rlib/rlib-include.php');

    $_SESSION['userdata']='';
    $_SESSION['planCheckout'] ='';
    $_SESSION['planCheckout'] = $checkedPoat;
    $runOtherQuery = 0;
    
  
     
   if(!empty($userEmailAdress) && !empty($userPasswordtext))
   {
  
       if ( is_email( $userEmailAdress ) ) {
           
           if(!username_exists($userEmailAdress) || !email_exists($userEmailAdress))
				{                     
                                    	$niceName = (!empty($shippingFirstName)) ? $shippingFirstName : 'Username';
                                        $userlastName =(!empty($shippingLastName)) ? $shippingLastName : ''; 
                                            $userdata = array( 'user_login' => $userEmailAdress, 'user_email' => $userEmailAdress,'user_pass'=>$userPasswordtext,'user_nicename'=>$niceName,'display_name'=>$niceName,'nickname'=>$niceName,'first_name' => $niceName ,'last_name'=>$userlastName);	
					 
                                            $uid = wp_insert_user($userdata);
                                            add_user_meta($uid, '_user_Validate_Status','1');                                                
                                }
                                      
                                     
                                        $login_data = array();  
                                        $login_data['user_login']       = $userEmailAdress;  
                                        $login_data['user_password']    = $userPasswordtext;  
                                        $user_verify = wp_signon($login_data,true);  
                                       
                                         
                                           if ( is_wp_error($user_verify) ){
											    $_SESSION['userdata']=$_POST;
                                                $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Invalid email or password. Please Try Again!');
                                                $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
												
                                                wp_redirect($rtustr);  
                                                exit();
                                           }else
                                           {
                                              $runOtherQuery =1; 
                                              $current_userID       = $user_verify->data->ID;
                                              $current_user_array   = $user_verify->data;
                                           }      
                                                          
           
       }else{
		   $_SESSION['userdata']=$_POST;
           $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Invalid Email Address!');
           wp_redirect(home_url()); 
           exit();
       }
       
   }
   else{
   
         $current_user = wp_get_current_user();
         
         if(!empty($current_user)){             
            $runOtherQuery =1; 
            $current_userID     = $current_user->data->ID;      
            $current_user_array = $current_user->data;
         }
         
   }
   
   
        if($runOtherQuery == 1) {
        
               if((!empty($cardNumber) && !empty($cardCVV)) && (!empty($cardExpMonth)&& !empty($cardExpYear))){                   
                        
            try {
             
             $newCardnumber = chunk_split($cardNumber, 4, '-'); 
              $newCardnumber=   trim($newCardnumber, "-");
             
                $all_meta_for_user  = get_user_meta( $current_userID );
                $firstName          = (!empty($all_meta_for_user['first_name'][0])) ? $all_meta_for_user['first_name'][0] : 'New User';
                $lastName           = (!empty($all_meta_for_user['last_name'][0])) ? $all_meta_for_user['last_name'][0] : $current_user_array->user_email;
                $ipAddress          = get_client_ip();
                $countryCode        = getCountryCode($billingCountry);
                $stateCode          = getStateCode($billingState); 
        
            $subscription = new Recurly_Subscription();
            $subscription->plan_code                = $strenthPlanCode;
            $subscription->unit_amount_in_cents     = ($planPricetText * 100);
            $subscription->currency                 = 'USD';
            if(!empty($couponCodeText)){
               $subscription->coupon_code                 = $couponCodeText;
            }    

            $account = new Recurly_Account();
            $account->account_code  = $current_user_array->ID;
            $account->username      = $current_user_array->user_nicename;       
            $account->email         = $current_user_array->user_email;
            $account->first_name    = $firstName;
            $account->last_name     = $lastName;

            $billing_info = new Recurly_BillingInfo();
            $billing_info->account_code     = $current_user_array->ID;
            $billing_info->first_name       = $firstName;
            $billing_info->last_name        = $lastName;
            $billing_info->address1         = $billingAddress;
            $billing_info->address2         = $billingAptSuite;
            //$billing_info->city             = 'Montgomery';
			$billing_info->city             = $billingCity;
            $billing_info->state            = $stateCode->State_Name;
            $billing_info->country          = $countryCode->Short_Name;
            $billing_info->zip              = $billingZipCode;
            $billing_info->ip_address       = $ipAddress;

            $billing_info->number               = $newCardnumber;
            $billing_info->verification_value   = $cardCVV;
            $billing_info->month                = $cardExpMonth;
            $billing_info->year                 = $cardExpYear;

            $account->billing_info = $billing_info;
            $subscription->account = $account;

            $subscription->create();
             
            $checkApi=1;
            }catch (Recurly_NotFoundError $e) {
                         
                       $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Record could not be found!');
                       $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
                       wp_redirect($rtustr);  
                        exit();
                      }
                      catch (Recurly_ValidationError $e) {
                          $messages = explode(',', $e->getMessage());                         
						  $_SESSION['userdata']=$_POST;                                          
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Validation problems:'. implode("\n", $messages)); 
                        $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
                        wp_redirect($rtustr);  
                         exit();
                      }
                      catch (Recurly_ServerError $e) {
                       	$_SESSION['userdata']=$_POST;
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Oops, Something wrong!');                        
                        $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
                        wp_redirect($rtustr);  
                        exit();
                      }
                      catch (Exception $e) {   
                        $_SESSION['userdata']=$_POST;  
                        $_SESSION['checkOutError']=array('type'=>'error','msg'=>'<p>'.get_class($e) . ': ' . $e->getMessage().'</p>'); 
                        $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
                        wp_redirect($rtustr);  
                        exit();
                      } 
            }
            else{
				$_SESSION['userdata']=$_POST;
                $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Please Check Billing Detail!');                
                $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
                wp_redirect($rtustr);  
                exit();
            }
            
        }
        
        if($checkApi == 1){
                               
                              global $wpdb;
                              $planUuid = '';                             
                               $endDate = '';
                            $subscriptions = (array) $subscription;

                            foreach($subscriptions as $row1)
                            {    
                                if(is_array($row1) && (count($row1)>0 && $row1['uuid'])){
                                   $planUuid= $row1['uuid'];  
                                   
                                    $datetimeStmp = (array) $row1['current_period_ends_at'];                    
                                    $timestamp = strtotime($datetimeStmp['date']);
                                     $endDate = date("Y-m-d",$timestamp);
                                  
                                  }  

                            }                                          


                    $planShippingValue =  get_term_meta($planIdText, 'planShipping', true);
                    $freeShipping = 0; 
                    $adddate = time();
                    if($planShippingValue == 'on'){$freeShipping = 1;}
                                               /* Insert In plan_order Table */
                    $discount =0;
                    $paybalAmount = $planPricetText;
                                               if(!empty($couponCodeText)){
                                                $stateSql       = "SELECT * FROM ".$wpdb->prefix . "coupon_generator WHERE coupontext = '".$couponCodeText."' and status='1'  LIMIT 1";
                                                $resultArray    = $wpdb->get_results($stateSql);
                                                $discount       = $resultArray[0]->discount;
                                                $discountamout  = (($planPricetText * $resultArray[0]->discount)/100);                    
                                                $paybalAmount   = $planPricetText - $discountamout;
                                                $paybalAmount   = number_format((float)$paybalAmount, 2, '.', '');
                                               }
                                              $sql = "INSERT INTO ".$wpdb->prefix . "plan_order (user_ID,plan_code,order_price,is_freeshipping,recurly_uuid,discount,status,add_date,mod_date,nextdate) 
					VALUES('".$current_userID."','".esc_sql(trim($strenthPlanCode))."','".$paybalAmount."','".$freeShipping."','".$planUuid."','".$discount."','1','".$adddate."','".$adddate."','".$endDate."')";
      
                                        if($wpdb->query($sql)){
                                            
                                                      $planOrderId = $wpdb->insert_id;
                                                      
                                                    /* Insert shipping Address In plan_order_address Table */
                                                      
                                                      $shippingAddressArray = array('shippingFirstName'=>$shippingFirstName,'shippingLastName'=>$shippingLastName,'shippingAddress'=>$shippingAddress,'shippingAptSuite'=>$shippingAptSuite,'shippingCity'=>$shippingCity,'shippingZipCode'=>$shippingZipCode,'shippingCountry'=>$shippingCountry,'shippingState'=>$shippingState);
                                                
                                                      $billingAddressArray = array('billingAddress'=>$billingAddress,'billingAptSuite'=>$billingAptSuite,'billingCity'=>$billingCity,'billingState'=>$billingState,'billingCountry'=>$billingCountry,'billingZipCode'=>$billingZipCode);
                                                       
                                                       $sqlShippingAddress = "INSERT INTO ".$wpdb->prefix . "plan_order_address (order_Id,user_Id,type,value,add_date,mod_date) 
					VALUES('".$planOrderId."','".$current_userID."','shipping','".serialize($shippingAddressArray)."','".$adddate."','".$adddate."')";
    
                                                      $wpdb->query($sqlShippingAddress);
                                                      $planShippingID = $wpdb->insert_id;
                                                      
                                                        /* Insert Billing Address In plan_order_address Table */
                                                      
                                        $sqlBillingAddress = "INSERT INTO ".$wpdb->prefix . "plan_order_address (order_Id,user_Id,type,value,add_date,mod_date) 
					VALUES('".$planOrderId."','".$current_userID."','billing','".serialize($billingAddressArray)."','".$adddate."','".$adddate."')";
    
                                                      $wpdb->query($sqlBillingAddress);
                                                      $planBillingID = $wpdb->insert_id;
                                                      
                                                /* Insert In plan_card Table */
                                                    
                                        $sqlplan_card = "INSERT INTO ".$wpdb->prefix . "plan_card (order_id,user_ID,card_no,cvv_no,exp_month,exp_year,add_date,status) 
					VALUES('".$planOrderId."','".$current_userID."','".$cardNumber."','".$cardCVV."','".$cardExpMonth."','".$cardExpYear."','".$adddate."','1')";
    
                                                      $wpdb->query($sqlplan_card);
                                                      $planCardID = $wpdb->insert_id;               
                                                     
                                                      
                                                    /* Insert In plan_payment_history Table */
                                                      
                                $sqlPay_history = "INSERT INTO ".$wpdb->prefix . "plan_payment_history (order_id,user_ID,payAmount,card_id,billing_address_id,shipping_address_id,add_date,status,is_shipping) 
					VALUES('".$planOrderId."','".$current_userID."','".$paybalAmount."','".$planCardID."','".$planBillingID."','".$planShippingID."','".$adddate."','1','1')";
    
                                                      $wpdb->query($sqlPay_history);
                                                      
                                                      
                                                       /*pr($user_verify);
                                                       pr($checkedPoat,1);*/
                                                    
                                                    /* Update Code Generator Table */  
                                                      if(!empty($_SESSION['nowuserid'])){
                                                      $codeArray = explode('~',$_SESSION['nowuserid']);
                                                      $updateCount = "UPDATE ".$wpdb->prefix . "code_generator SET codeused = codeused+1  WHERE id =$codeArray[1];";
                                                      $wpdb->query($updateCount);
                                                      
                                                      $chnageCodeStatus = "UPDATE ".$wpdb->prefix ."code_generator SET status = '0' WHERE uselimit = codeused";
                                                        $wpdb->query($chnageCodeStatus);
                                                      }
                                                      $_SESSION['nowuserid'] ='';
                                                     
                                                      wp_redirect( get_permalink( 66 )); 
                                                      exit();
                                        }  else {
												$_SESSION['userdata']=$_POST;
                                                 $_SESSION['checkOutError']=array('type'=>'error','msg'=>'Query error!');
                                                $rtustr = home_url().'/checkout/?plan='.toPublicId($planIdText).'&strenth='.toPublicId($strengthtext);
                                                wp_redirect($rtustr);  
                                                exit();
                                        }
                                    }     
                                    
                                    
    }  
	else {
            wp_redirect(home_url()); 
            exit();
            }
    
    ?>
