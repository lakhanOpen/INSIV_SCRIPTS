<?php
/*
Plugin Name: Code Generator
Plugin URI: http://syoninfomeida.com/
Description: Get Start Code Generator
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

add_action('admin_menu', 'code_generator');


function code_generator()
{
       add_menu_page(__('Code Generator','code_generator'), __('Code Generator','menu-test'), 'manage_options','callCodeGenerator','callCodeGenerator',WP_CUSTOM_PLUGIN_PATH.'images/1389271887_Settings.png');
	 add_submenu_page( 'callCodeGenerator', 'Add Code', 'Add Code', 'manage_options', 'callAddCode', 'callAddCode' );
}


function code_generator_install()
{
       global $wpdb;
       $codeTable = $wpdb->prefix . "code_generator";        
   	
        if($wpdb->get_var("show tables like '$codeTable'") != $codeTable) 
	{
	
                $codeSql ="CREATE TABLE IF NOT EXISTS ". $codeTable ." (
                            `id` int(15) NOT NULL AUTO_INCREMENT,
                            `codetext` varchar(50) NOT NULL,
                            `uselimit` int(15) NOT NULL,
                            `codeused` int(15) NOT NULL,
                            `status` int(2) NOT NULL,
                            `adddate` bigint(15) NOT NULL,
                             PRIMARY KEY (`id`)
                          ) ;";
                
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($codeSql);
        }
}
register_activation_hook(__FILE__,'code_generator_install');

if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


function callCodeGenerator()
{ 
    
    include_once 'codeManager.php';    
} 

 function callAddCode()
 {
     include_once 'addCode.php';
 }

 function getNewCode()
 {
     $response =wp_generate_password(12);    
     echo json_encode($response); 
	 exit();
 }
add_action( 'wp_ajax_nopriv_getNewCode', 'getNewCode' );
add_action( 'wp_ajax_getNewCode', 'getNewCode' );
?>