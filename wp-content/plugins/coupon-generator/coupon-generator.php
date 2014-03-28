<?php
/*
Plugin Name: Coupon Generator
Plugin URI: http://syoninfomeida.com/
Description: Get Start Coupon Generator
Author: Syon
Version: 1.0
Author URI: http://syoninfomeida.com/
*/

// Define some useful constants that can be used by functions
if ( ! defined( 'WP_CONTENT_URL' ) ) {	
	if ( ! defined( 'WP_SITEURL' ) ) define( 'WP_SITEURL', get_option("siteurl") );
	define( 'WP_CONTENT_URL', WP_SITEURL . '/wp-content' );
}
if ( ! defined( 'WP_SITEURL' ) ) define( 'WP_SITEURL', get_option("siteurl") );
if ( ! defined( 'WP_CONTENT_DIR' ) ) define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) ) define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) ) define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

if ( basename(dirname(__FILE__)) == 'plugins' )
	define("WP_CUSTOM_PLUGIN_DIR",'');
else define("WP_CUSTOM_PLUGIN_DIR" , basename(dirname(__FILE__)) . '/');
define("WP_CUSTOM_PLUGIN_PATH", WP_PLUGIN_URL . "/" . WP_CUSTOM_PLUGIN_DIR);

add_action('admin_menu', 'coupon_generator');


function coupon_generator()
{
       add_menu_page(__('Coupon Generator','coupon_generator'), __('Coupon Generator','menu-test'), 'manage_options','callcouponGenerator','callcouponGenerator',WP_CUSTOM_PLUGIN_PATH.'images/1389271925_Favorites.png');
	 add_submenu_page( 'callcouponGenerator', 'Add Coupon', 'Add Coupon', 'manage_options', 'callAddCoupon', 'callAddCoupon' );
}


function coupon_generator_install()
{
       global $wpdb;
       $couponTable = $wpdb->prefix . "coupon_generator";        
   	
        if($wpdb->get_var("show tables like '$CouponTable'") != $CouponTable) 
	{
	
                $couponSql ="CREATE TABLE IF NOT EXISTS ". $couponTable ." (
                            `id` int(15) NOT NULL AUTO_INCREMENT,
                            `coupontext` varchar(50) NOT NULL,
                           `couponName` VARCHAR( 255 ) ,
                            `redeemDate` DATE NOT NULL ,
                            `discount` INT( 3 ) NOT NULL,
                            `status` int(2) NOT NULL,
                            `adddate` bigint(15) NOT NULL,
                             PRIMARY KEY (`id`)
                          ) ;";
                
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($couponSql);
        }
}
register_activation_hook(__FILE__,'coupon_generator_install');

if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
        require_once( ABSPATH . 'wp-content/themes/insiv/rlib/rlib-include.php' );

function callcouponGenerator()
{ 
    
    include_once 'CouponManager.php';    
} 

 function callAddCoupon()
 {
     include_once 'addCoupon.php';
 }

 function getNewCoupon()
 {
    $random= "";
	
	srand((double)microtime()*1000000);
	
	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
	$data .= "0FGH45OP89";
	
	for($i = 0; $i < 9; $i++)
	{
	$random .= substr($data, (rand()%(strlen($data))), 1);
	}
	
	
     echo json_encode($random); 
	 exit();
 }
add_action( 'wp_ajax_nopriv_getNewCoupon', 'getNewCoupon' );
add_action( 'wp_ajax_getNewCoupon', 'getNewCoupon' );
?>