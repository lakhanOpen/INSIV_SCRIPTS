<?php
ob_start();
/*
Plugin Name: Syon Recurly Cron
Plugin URI: http://syoninfomeida.com/
Description: Get recurly subscriptions detail. 
Author: Aashish Scripter
Version: 1.0
Author URI: http://syoninfomeida.com/
*/



register_activation_hook( __FILE__, 'syon_create_recurly_schedule' );
register_deactivation_hook( __FILE__, 'syon_remove_recurly_schedule' );

function syon_create_recurly_schedule(){
  

    wp_schedule_event(time(),'twicedaily','syon_create_daily_backup' );
 
}

function syon_remove_recurly_schedule(){
  wp_clear_scheduled_hook( 'syon_create_daily_backup' );
}


add_action( 'syon_create_daily_backup', 'syon_get_subscription_detail' );

/*
add_action('admin_menu', 'syon_recurly_cron');
function syon_recurly_cron()
{
       add_menu_page(__('Recurly Cron','syon_recurly_cron'), __('Recurly Cron','menu-test'), 'manage_options','syon_get_subscription_detail','syon_get_subscription_detail');
}
*/

function syon_get_subscription_detail(){
require_once( ABSPATH . 'wp-content/themes/insiv/rlib/rlib-include.php' );

global $wpdb;
$currentDate = date("Y-m-d");
$sql = "SELECT * FROM ".$wpdb->prefix ."plan_order where status =1 and nextdate ='".$currentDate."' ";


$queryArray = $wpdb->get_results($sql);   

$planDate ='';
$as=0;
    if(!empty($queryArray)){
        foreach($queryArray as $row)
        {     
            $subscription = Recurly_Subscription::get($row->recurly_uuid);    

            $subscriptions = (array) $subscription;

                    foreach($subscriptions as $row1)
                    {                          
                        if(is_array($row1) && (count($row1)>0 && $row1['current_period_started_at'])){  

                            $activatedStmp = (array) $row1['activated_at'];                    
                            $timestamp = strtotime($activatedStmp['date']);
                            $activateDate = date("Y-m-d",$timestamp);


                            if($activateDate != $currentDate){
                               
                                        $planDate[$as]['uid'] = $row1['uuid'];
                                        $planDate[$as]['current']= $row1['current_period_started_at'];   
                                        $planDate[$as]['ends'] =$row1['current_period_ends_at'];
                                        $planDate[$as]['order'] = $row;
                                        $as++;


                            }
                          }  
                    }       
        }

        if(!empty($planDate)){
            foreach($planDate as $row)
            { 
                      
                $planID =$row['order']->id;
                $userID = $row['order']->user_ID;
                $payAmount = $row['order']->order_price;


                    $planHistoryQuery       = "SELECT *FROM ".$wpdb->prefix ."plan_payment_history WHERE (user_Id =$userID and order_Id =$planID ) and (status = 1) order by id Desc limit 0,1 ";
                    $planDetail             = $wpdb->get_results($planHistoryQuery);


              $planCardID       =   $planDetail[0]->card_id;
              $planBillingID    =   $planDetail[0]->billing_address_id;
              $planShippingID   =   $planDetail[0]->shipping_address_id;

                $currenttime = (array) $row['current'];                    
                $timestamp = strtotime($currenttime['date']);


              $sqlPay_history = "INSERT INTO ".$wpdb->prefix . "plan_payment_history (order_id,user_ID,card_id,payAmount,billing_address_id,shipping_address_id,add_date,status,is_shipping) 
                                                    VALUES('".$planID."','".$userID."','".$payAmount."','".$planCardID."','".$planBillingID."','".$planShippingID."','".$timestamp."','1','1')";

              if($wpdb->query($sqlPay_history)){

                $endstime = (array) $row['ends'];                    
                $endtimestamp = strtotime($endstime['date']);
                $endDate = date("Y-m-d",$endtimestamp);

                 $order = "UPDATE ".$wpdb->prefix ."plan_order SET nextdate='".$endDate."' WHERE user_ID = $userID and id = $planID";
                 $wpdb->query($order);    

                 wp_mail('ashish.singhal@syoninfomedia.com','insiv Cron' , 'Done');
              }


            }
        }
    }
}
?>