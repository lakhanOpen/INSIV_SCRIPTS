<?php 
/*
    Template Name: Update User Profile
*/
ob_start();
if ( is_user_logged_in()  || !empty($_SESSION['nowuserid'])) {   
	
} else{
    wp_redirect(home_url());
   exit();    
}
$_SESSION['errorMsg']='';
if(!empty($_POST)){
         $userPoat = checkInputValue($_POST);
         extract ($userPoat);
         if(!empty($currentPassword))
         {
            $current_user = wp_get_current_user();

            if(wp_check_password( $currentPassword, $current_user->data->user_pass, $current_user->ID))
            {
                $niceName = (!empty($firstName)) ? $firstName : 'Username';
                if(!empty($newPassword) && !empty($confirmPassword))
                {
                    if($newPassword == $confirmPassword){
                                            $_SESSION['errorMsg'] = "Update Successfully .";
                                        wp_update_user( array ( 'ID' => $current_user->ID,'user_nicename'=>$niceName, 'display_name'=>$niceName,'nickname' => $niceName,'first_name' => $firstName ,'last_name'=>$lastName,'user_pass'=>$newPassword ) ) ;
                                        }
                                        else{
                                            
                                             $_SESSION['errorMsg'] = "New Password And Confirm Password Do Not Match.";
                                        }
                    }else{
                        $_SESSION['errorMsg'] = "Update Successfully .";
                     wp_update_user( array ( 'ID' => $current_user->ID,'display_name'=>$niceName, 'user_nicename'=>$niceName,'nickname' => $niceName,'first_name' => $firstName ,'last_name'=>$lastName ) ) ;
                }
            }
            else{
                $_SESSION['errorMsg'] = "Current Password not Match.";
            }
         }else{
             $_SESSION['errorMsg'] ='Please Try again!';
         }
}

$rtustr = get_permalink( 63 );
wp_redirect($rtustr);  
exit();