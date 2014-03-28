<?php
/*
Plugin Name: Syon Add Admin Css
Plugin URI: http://syoninfomeida.com/
Description: Add custom css in admin .
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
	define("ADD_ADMIN_CSS_DIR",'');
else define("ADD_ADMIN_CSS_DIR" , basename(dirname(__FILE__)) . '/');
define("ADD_ADMIN_CSS_PATH", WP_PLUGIN_URL . "/" . ADD_ADMIN_CSS_DIR);


function admin_register_head() {
   //echo $_GET['taxonomy']; die; 
    //$url = ADD_ADMIN_CSS_PATH. 'customAdminCss.css';
   // echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
   echo '<script>';
   if($_GET['taxonomy'] == 'plan' ){
	
	
        
        if($_GET['action'] == 'edit'){
            
             echo 'jQuery("#slug").closest(".form-field").hide();';
             echo 'jQuery("#parent").closest(".form-field").hide();';
        }else
        {
                    echo 'jQuery("#tag-slug").parent(".form-field").hide();';
                    echo 'jQuery("#parent").parent(".form-field").hide();';
                    echo "jQuery('input[name=slug]').closest('label').hide();";
                    echo "jQuery('.view').hide();";
        }
        
       
   }
    if($_GET['post_type']== 'strength')
        {
            echo 'jQuery(".editinline").parent(".inline").hide();';
            echo 'jQuery("a[rel=permalink]").parent(".view").hide();';
        }
        
	echo '</script>';
   $postType = get_post_type( get_the_ID() ); 
   
   if($postType = 'strength' && $_GET['action']=='edit')
   {
      echo '<script>'; 
       echo 'jQuery("#edit-slug-box").hide();';
      echo '</script>';
   }
}

add_action('in_admin_footer', 'admin_register_head');
