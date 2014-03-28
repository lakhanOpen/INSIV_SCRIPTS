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
					$response = array('response'=>'sucess', 'message'=> 'We have sent mail with code.');	 			 
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
add_action('init', 'myStartSession', 1);
function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

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
								
                                                                        
                                                              $_SESSION['nowuserid']='userId~'.$codeResult->ID;
                                                            //update_user_meta($codeResult->ID, '_user_Validate_Status', '1');
                                                                        //pr($codeResult->data->user_login);
								/*
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
                                                                        */
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
					$response["message"]="An account does not exist for this e-mail address!";
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

add_action( 'init', 'be_initialize_cmb_meta_boxes', 9999 );
function be_initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'metabox/init.php' );
	}
}

add_action( 'init', 'register_cpt_strength' );
function register_cpt_strength() {

    $labels = array( 
        'name' => _x( 'Strengths', 'strength' ),
        'singular_name' => _x( 'Strengths', 'strength' ),
        'add_new' => _x( 'Add Strength', 'strength' ),
        'add_new_item' => _x( 'Add New Strength', 'strength' ),
        'edit_item' => _x( 'Edit Strength', 'strength' ),
        'new_item' => _x( 'New Strength', 'strength' ),
        'view_item' => _x( 'View Strength', 'strength' ),
        'search_items' => _x( 'Search Strength', 'strength' ),
        'not_found' => _x( 'No Strength found', 'strength' ),
        'not_found_in_trash' => _x( 'No Strength found in Trash', 'strength' ),
        'parent_item_colon' => _x( 'Parent Strength:', 'strength' ),
        'menu_name' => _x( 'Strength', 'strength' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        'supports' => array( 'title' ),
        'taxonomies' => array( 'plan' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'strength', $args );
}
add_action( 'init', 'register_taxonomy_plan' );

function register_taxonomy_plan() {
    $labels = array( 
        'name' => _x( 'Plans', 'plan' ),
        'singular_name' => _x( 'Plan', 'plan' ),
        'search_items' => _x( 'Search Plans', 'plan' ),
        'popular_items' => _x( 'Popular Plans', 'plan' ),
        'all_items' => _x( 'All Plans', 'plan' ),
        'parent_item' => _x( 'Parent Plan', 'plan' ),
        'parent_item_colon' => _x( 'Parent Plan:', 'plan' ),
        'edit_item' => _x( 'Edit Plan', 'plan' ),
        'update_item' => _x( 'Update Plan', 'plan' ),
        'add_new_item' => _x( 'Add New Plan', 'plan' ),
        'new_item_name' => _x( 'New Plan', 'plan' ),
        'separate_items_with_commas' => _x( 'Separate plans with commas', 'plan' ),
        'add_or_remove_items' => _x( 'Add or remove plans', 'plan' ),
        'choose_from_most_used' => _x( 'Choose from the most used plans', 'plan' ),
        'menu_name' => _x( 'Plans', 'plan' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => radio,
        'show_tagcloud' => true,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );
    

   register_taxonomy( 'plan', array('strength'), $args );
}

function be_sample_metaboxes_strength( $meta_boxes ) {
	$prefix = '_cmb_'; // Prefix for all fields
	$posttype = 'strength_';
	$meta_boxes[] = array(
		'id' => 'test_metabox',
		'title' => 'Additional My Strength Info',
		'pages' => array('strength'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			
			 array(
				'name' => 'Diapay Status',
				//'desc' => 'field description (optional)',
				'id'   => $prefix .$posttype.'display_status',
				'type' => 'checkbox',
			),
                    array(
				'name'    => 'Position',
				'desc'    => 'Order of strength',
				'id'      => $prefix . 'Strength_position',
				'type'    => 'select',
				'options' => array(
                                        array( 'name' => 'Select Position', 'value' => '0', ),
					array( 'name' => '1', 'value' => '1', ),
					array( 'name' => '2', 'value' => '2', ),
					array( 'name' => '3', 'value' => '3', ),
                                        array( 'name' => '4', 'value' => '4', ),
                                        array( 'name' => '5', 'value' => '5', ),
                                        
				),
			),
                    
		),
	);
	
	
	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'be_sample_metaboxes_strength' );

function callCreatePlanOnRecurlyAPP($post_id)
{
     
if($_POST['post_type'] == 'strength'){
  
    require_once('rlib/recurly.php');
    
    // Required for the API
        Recurly_Client::$subdomain = 'insiv';
        Recurly_Client::$apiKey = 'f953526bdd7340b785b5d8c293e20e28';

        // Optional for Recurly.js:
        Recurly_js::$privateKey = '3ae450e02a6841e8a1cc69a6b3dd7a08';
        $planName = get_term( $_POST['tax_input']['plan'][1], 'plan' );

    if($_POST['original_publish']== 'Publish' )
    {
        
        $planCode = substr(str_replace(' ','-',$planName->slug.'-'.$_POST['post_title']),0, 45);
        $planName = $planName->name.' '.$_POST['post_title'];
        $planPrice = ($_POST['_cmb_strength_price']* 100);
        $plan = new Recurly_Plan();
        $plan->plan_code        = $planCode;
        $plan->name             = substr($planName, 0, 250);        
        $plan->description      = $_POST['content'];
        $plan->accounting_code  = substr($planCode, 0, 20);        
        $plan->unit_amount_in_cents->addCurrency('USD',$planPrice ); // USD 10.00 month
        $plan->plan_interval_length = 1;
        $plan->plan_interval_unit   = 'months';
        $plan->create();
        
        add_post_meta( $post_id, 'recurly_plan_code', $planCode );
    }
    if($_POST['original_publish']== 'Update' )
    {
     
        $planCode =   get_post_meta( $post_id, 'recurly_plan_code' );
        
        if(!empty($planCode)){
                    $plan = Recurly_Plan::get($planCode[0]);
                     $planPrice = ($_POST['_cmb_strength_price']* 100);
                  //  pr($plan);
                  //  pr($_POST,1);
                     $planName = $planName->name.' '.$_POST['post_title'];
                     $plan->unit_amount_in_cents['USD']  =  $planPrice; // EUR 50.00 monthly fee
                     $plan->description                  =  $_POST['content'];
                     $plan->name                         =  substr($planName, 0, 250); 
                     $plan->update();
        }
        
        
    }
}
}

add_action( 'save_post', 'callCreatePlanOnRecurlyAPP' );

//function add_strength_columns($columns) {
//    unset($columns['author']);
//    return array_merge($columns, 
//              array('plan' => __('Plan')
//                   
//                  ));
//}
//add_filter('manage_strength_posts_columns' , 'add_strength_columns');

function set_strength_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'plan' => __('Plan'),
        'date' => __('Date'),
        
        
    );
}
add_filter('manage_strength_posts_columns' , 'set_strength_columns');

add_action( 'manage_strength_posts_custom_column' , 'custom_columns', 10, 2 );

function custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'plan' :
	    $terms = get_the_term_list( $post_id , 'plan' , '' , ',' , '' );
            if ( is_string( $terms ) )
		    echo $terms;
		else
		    _e( 'Unable to get plan(s)', 'your_text_domain' );
		break;

    }
}

function toPublicId($id) {
  return $id * 21573423 + 82592049882;
}

function toInternalId($publicId) {
  return ($publicId - 82592049882) / 21573423;
} 

//removes quick edit from custom post type list
add_filter('post_row_actions','remove_quick_edit');
function remove_quick_edit( $actions ) {
	
		unset($actions['inline hide-if-no-js']);
	
    return $actions;
}
	

?>