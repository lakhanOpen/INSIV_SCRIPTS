<?php
if ( function_exists('register_sidebar') )
    register_sidebar();


add_action( 'init', 'register_my_menus' );
function register_my_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' ),
			'secondary-menu' => __( 'Secondary Menu' ),
		)
	);
}

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 960, 260 );
	}


if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'featImg', 600, 260, true );
	add_image_size( 'realImg', 200, 120, true );
	}


function custom_excerpt_length( $length ) {
    return 65;
	}
	
add_filter( 'excerpt_length', 'custom_excerpt_length');
function trim_excerpt($text) {
	global $post;
	$moreLink = ' <a href="' . get_permalink($post->ID) . '" class="readMore">Read More &raquo;</a>';
	$text = str_replace('[...]', $moreLink, $text);
  	return $text;
	}
add_filter('get_the_excerpt', 'trim_excerpt');

function add_ajaxurl_cdata_to_front(){
?>
<script type="text/javascript">
//<![CDATA[
ajaxurl='<?php echo admin_url( 'admin-ajax.php' ) ?>';
//]]>

//<![CDATA[
siteurl='<?php echo site_url(); ?>';
//]]>
</script>
<?php
}
add_action( 'wp_head', 'add_ajaxurl_cdata_to_front', 1);

function userEmailRegister()
{
	global $wpdb;
	$response = array();
	parse_str($_POST['signdata']);
	if ( is_email( $getEmail ) ) {
		
		$userStatus = "notSend"; 
		$rcode = wp_generate_password(12);
		
			if(username_exists($getEmail) || email_exists($getEmail))
				{ 	
					 	    $userData = get_user_by( 'email', $getEmail); 
							  $userValidateStatus = get_user_meta($userData->ID, '_user_Validate_Status','true');
							  if($userValidateStatus == 0 )
							  {		
							  	update_user_meta($userData->ID, '_user_Validate_code', $rcode);
								$userStatus = "Send"; 
							  }
							  else
							  {
								  $response["message"]="E-mail id already exists! Please use forget Password .";
								  $response["response"] = 'error';
							   }
												
				}else{
					
					$userdata = array( 'user_login' => $getEmail, 'user_email' => $getEmail);	
					$uid = wp_insert_user($userdata);
					add_user_meta($uid, '_user_Validate_code',$rcode);
				        add_user_meta($uid, '_user_Validate_Status','0');						
					$userStatus = "Send"; 
				}
				if($userStatus == "Send" ){	
											
					$to = $getEmail; 
					$subject = 'INSIV CODE';
					$message = 'Hi,<br>';
					$message .= sprintf(__('Code: %s'), $rcode) . "<br>";
					$message .=get_bloginfo('url');
					$message .="<br><br> Thanks & Regards<br> Insiv Team";
					$headers =  array( 'Content-type: text/html' );
					
					wp_mail( $to, $subject, $message, $headers ); 		
					$response = array('response'=>'sucess', 'message'=> 'We have sent email with code.');	 			 
		   }
	
	}else{
		$response["message"]="email address is not valid.";
		$response["response"] = 'error';
	}
	
	 echo json_encode($response); 
	 exit();
}
add_action( 'wp_ajax_nopriv_userEmailRegister', 'userEmailRegister' );
add_action( 'wp_ajax_userEmailRegister', 'userEmailRegister' );


function get_user_by_meta_data( $meta_key, $meta_value ) {

	// Query for users based on the meta data
	$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	$meta_key,
			'meta_value'	=>	$meta_value
		)
	);

	// Get the results from the query, returning the first user
	$users = $user_query->get_results();

	return $users[0];

} // end get_user_by_meta_data

function get_user_detail( $userArray ) {

	// Query for users based on the meta data
	$user_query = new WP_User_Query($userArray);

	// Get the results from the query, returning the first user
	$users = $user_query->get_results();

	return $users[0];

} // end get_user_by_meta_data

function userCodeData()
{
	global $wpdb;
	$response = array();
	parse_str($_POST['codedata']);
$codeResult = get_user_by_meta_data('_user_Validate_code', $getCode );
if(!empty($codeResult))
{
	 							$userValidateStatus = get_user_meta($codeResult->ID, '_user_Validate_Status','true');
							  if($userValidateStatus == 0 )
							  {								  
								//pr($codeResult->data->user_login);
								
								$newpassword = wp_generate_password(9);
								wp_update_user( array ( 'ID' => $codeResult->ID, 'user_pass' => $newpassword ) ) ;
								update_user_meta($codeResult->ID, '_user_Validate_Status', '1');
								
								$login_data = array();  
								$login_data['user_login'] = $codeResult->data->user_login;  
								$login_data['user_password'] = $newpassword;  
								$user_verify = wp_signon($login_data,true);  
								//wp_set_auth_cookie($codeResult->ID,true); 
								
									$to = $codeResult->data->user_email; 
									$subject = 'Welcome Insiv';
									$message = 'Hi,<br>';
									$message .= sprintf(__('Username: %s'), $codeResult->data->user_login) . "<br>";
									$message .= sprintf(__('Password: %s'), $newpassword) . "<br>";
									$message .=get_bloginfo('url');
									$message .="<br><br> Thanks & Regards<br> Insiv Team";
									$headers =  array( 'Content-type: text/html' );
									
									wp_mail( $to, $subject, $message, $headers ); 		
					
								$response = array('response'=>'sucess', 'message'=> 'successfully activated.');	
							   }else{
								   $response = array('response'=>'error', 'message'=> 'user already authenticated.');	
								   }
	
}else{
$response = array('response'=>'error', 'message'=> 'code not exists.');	
}

	
	 echo json_encode($response); 
	 exit();
}
add_action( 'wp_ajax_nopriv_userCodeData', 'userCodeData' );
add_action( 'wp_ajax_userCodeData', 'userCodeData' );

function pr($array,$isDie = 0)
{
	echo "<pre>";
	print_r($array);
	echo "</pre>";
	
	if($isDie > 0 ) 
	{	die();	}
}

function ajaxUserLogin(){
	
global $wpdb;
	$response = array();
	parse_str($_POST['signupdata']);
	if ( is_email( $username ) ) {
		if(username_exists($username) || email_exists($username))
				{ 
				$login_data = array();  
				$login_data['user_login'] = $username;  
				$login_data['user_password'] = $password;  
				$user_verify = wp_signon($login_data,true);  
				  if(is_wp_error($user_verify))   
					{ 
					$response["message"] = "Invalid username / E-mail or Password!";
					$response["response"] = 'error';
					}
					else 
					{ 					
					$response["message"] = "Login Successful, please wait!";
					$response["response"] = 'sucess';
					}
				
				}else{
					$response = array('response'=>'error', 'message'=> 'E-mail id not exists!!');	
				}
	}else{
		$response = array('response'=>'error', 'message'=> 'Email address not valid.');	
	}
	 echo json_encode($response); 
	 exit();
}
add_action( 'wp_ajax_nopriv_ajaxUserLogin', 'ajaxUserLogin' );
add_action( 'wp_ajax_ajaxUserLogin', 'ajaxUserLogin' );


function userEmailForget()
{
	global $wpdb;
	$response = array();
	parse_str($_POST['signdata']);
	if ( is_email( $getEmail ) ) {
		
		$userStatus = "notSend"; 
			if(username_exists($getEmail) || email_exists($getEmail))
				{ 	
					 	    $userData = get_user_by( 'email', $getEmail); 
							  $userValidateStatus = get_user_meta($userData->ID, '_user_Validate_Status','true');
							  if($userValidateStatus == 0 )
							  {		
							  	$response["message"]="Please first validate E-mail ID.";
								$response["response"] = 'error';
								
							  }
							  else
							  {
								  $newpassword = wp_generate_password(9);
								  wp_update_user( array ( 'ID' => $userData->ID, 'user_pass' => $newpassword ) ) ;
                                                                  $userStatus = "Send"; 
							   }
												
				}else{
					$response["message"]="E-mail id not exists!";
					$response["response"] = 'error';
					
				}
				if($userStatus == "Send" ){	
											
                                        $to      = $userData->data->user_email; 
                                        $subject = 'Insiv Reset Password';
                                        $message = 'Hi,<br>';
                                        $message .= sprintf(__('Username: %s'), $userData->data->user_login) . "<br>";
                                        $message .= sprintf(__('Password: %s'), $newpassword) . "<br>";
                                        $message .= get_bloginfo('url');
                                        $message .="<br><br> Thanks & Regards<br> Insiv Team";
                                        $headers =  array( 'Content-type: text/html' );

                                        wp_mail( $to, $subject, $message, $headers ); 		
					$response = array('response'=>'sucess', 'message'=> 'We have sent email with code.');	 			 
		   }
	
	}else{
		$response["message"]="email address is not valid.";
		$response["response"] = 'error';
	}
	
	 echo json_encode($response); 
	 exit();
}
add_action( 'wp_ajax_nopriv_userEmailForget', 'userEmailForget' );
add_action( 'wp_ajax_userEmailForget', 'userEmailForget' );

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}
?>