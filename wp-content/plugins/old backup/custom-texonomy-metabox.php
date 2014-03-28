<?php
/*
Plugin Name: Syon Custom Texonomy Metabox
Plugin URI: http://syoninfomeida.com/
Description: This is not just a plugin so don't try to deactivate this.
Author: Syon
Version: 1.0
Author URI: http://syoninfomeida.com/
*/

class Taxonomy_Metadata {
	function __construct() {
		add_action( 'init', array($this, 'wpdbfix') );
		add_action( 'switch_blog', array($this, 'wpdbfix') );
		
		add_action('wpmu_new_blog', 'new_blog', 10, 6);
	}

	/*
	 * Quick touchup to wpdb
	 */
	function wpdbfix() {
		global $wpdb;
		$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
	}
	
	/*
	 * TABLE MANAGEMENT
	 */

	function activate( $network_wide = false ) {
		global $wpdb;
	
		// if activated on a particular blog, just set it up there.
		if ( !$network_wide ) {
			$this->setup_blog();
			return;
		}
	
		$blogs = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}'" );
		foreach ( $blogs as $blog_id ) {
			$this->setup_blog( $blog_id );
		}
		// I feel dirty... this line smells like perl.
		do {} while ( restore_current_blog() );
	}
	
	function setup_blog( $id = false ) {
		global $wpdb;
		
		if ( $id !== false)
			switch_to_blog( $id );
	
		$charset_collate = '';	
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
	
		$tables = $wpdb->get_results("show tables like '{$wpdb->prefix}taxonomymeta'");
		if (!count($tables))
			$wpdb->query("CREATE TABLE {$wpdb->prefix}taxonomymeta (
				meta_id bigint(20) unsigned NOT NULL auto_increment,
				taxonomy_id bigint(20) unsigned NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY	(meta_id),
				KEY taxonomy_id (taxonomy_id),
				KEY meta_key (meta_key)
			) $charset_collate;");
	}

	function new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network(plugin_basename(__FILE__)) )
			$this->setup_blog($blog_id);
	}}
$taxonomy_metadata = new Taxonomy_Metadata;
register_activation_hook( __FILE__, array($taxonomy_metadata, 'activate') );

add_action('plan_add_form_fields', 'plan_metabox_add', 10, 1);
add_action('plan_edit_form_fields', 'plan_metabox_edit', 10, 1);    


function plan_metabox_add($tag) { 
global $wpdb; 
if($tag=='bikini_categories'){
$taxostep='bikni_steps';
}
if($tag=='figure_categories'){
$taxostep='figure_steps';
}
$custosteps = get_terms($taxostep, array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => 0 ) );
?>
    <div class="form-field">
    
    <div style="width:100%!important;">
       
  <label for="tag-pricetext">Price</label>
  
   <input type="text" name="pricetext" id="pricetext"  value="<?php echo get_term_meta($tag->term_id, 'priceText', true); ?>">
    </div>
    <div style="clear:both!important;"></div>    
    
    </div>
    
    
    
    
    
    
<?php }     

function plan_metabox_edit($tag) { 
?>
    <table class="form-table">
    
    
        <tr class="form-field">
        <th scope="row" valign="top">
            <label for="extrafield1"><?php _e('Price'); ?></label>
        </th>
        <td>
   <div style="width:100%!important;">
       
   <div style="width:100%!important;">
<!--   <textarea name="pricetext" id="seotext" rows="5" cols="50"><?php //echo get_term_meta($tag->term_id, 'priceText', true); ?></textarea>-->
  <input type="text" name="pricetext" id="pricetext"  value="<?php echo get_term_meta($tag->term_id, 'priceText', true); ?>">
   </div>
        
   
        
    </div>
    <div style="clear:both!important;"></div>    
        </td>
        </tr>
    </table>
<?php }

//add_action('created_plan', 'save_plan_metadata', 10, 1);
//add_action('edited_plan', 'save_plan_metadata', 10, 1);

add_action('created_plan', 'save_plan_metadata', 10, 1);
add_action('edited_plan', 'save_plan_metadata', 10, 1);


function save_plan_metadata($term_id){
{
if (isset($_POST['pricetext'])) update_term_meta( $term_id,'priceText', $_POST['pricetext']);
}
}

if (!function_exists('add_term_meta')) {
function add_term_meta($term_id, $meta_key, $meta_value, $unique = false) {
return add_metadata('taxonomy', $term_id, $meta_key, $meta_value, $unique);
}
}
if (!function_exists('delete_term_meta')) {
function delete_term_meta($term_id, $meta_key, $meta_value = '') {
	return delete_metadata('taxonomy', $term_id, $meta_key, $meta_value);
}
}
if (!function_exists('get_term_meta')) {
function get_term_meta($term_id, $key, $single = false) {
return get_metadata('taxonomy', $term_id, $key, $single);
}
}
if (!function_exists('update_term_meta')) {
function update_term_meta($term_id, $meta_key, $meta_value, $prev_value = '') { 
return update_metadata('taxonomy', $term_id, $meta_key, $meta_value, $prev_value);
}
}

?>