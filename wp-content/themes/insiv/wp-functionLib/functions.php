<?php



if ( function_exists('register_sidebar') )



    register_sidebar();



	



add_action( 'init', 'register_my_menus' );







function register_my_menus() {



	register_nav_menus(



		array(



			'primary-menu' => __( 'Primary Menu' ),



			'secondary-menu' => __( 'Secondary Menu' ),



			'third-menu' => __( 'Third Menu' ),



			'forth-menu' => __( 'Fourth Menu' ),



			'fifth-menu' => __( 'Fifth Menu' ),

			

			'six-menu' => __( 'Six Menu' )



		)



	);



}



if ( function_exists( 'add_theme_support' ) ) {



	add_theme_support('post-thumbnails');



	set_post_thumbnail_size( 160, 100 );



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











if ( function_exists('register_sidebar') ) {



	register_sidebar(array(



		'name' => 'What is Rocket Under?',



		'before_widget' => '<div id="%1$s" class="widget %2$s whatus">',



		'after_widget' => '</div>',



		'before_title' => '<h3>What is <br><span>Rocket Under</span>?',



		'after_title' => '</h3>',



	));



	register_sidebar(array(



		'name' => 'Follow RocketUnder',



		'before_widget' => '<div id="%1$s" class="widget %2$s">',



		'after_widget' => '</div>',



	



	));



	register_sidebar(array(



		'name' => 'Newsletter Signup',



		'before_widget' => '<div id="%1$s" class="widget %2$s newsSignup">',



		'after_widget' => '</div>',



	



	));



	register_sidebar(array(



		'name' => 'Top Ad unit',



		'before_widget' => '<div id="%1$s" class="widget %2$s">',



		'after_widget' => '</div>',







	));



	register_sidebar(array(



		'name' => 'Facebook Fanbox',



		'before_widget' => '<div id="%1$s" class="widget %2$s">',



		'after_widget' => '</div>',







	));



register_sidebar(array(



		'name' => 'What New',



		'before_widget' => '<div id="%1$s" class="widget %2$s boxContent">',



		'after_widget' => '</div>',







	));



	register_sidebar(array(



		'name' => 'Friends stream',



		'before_widget' => '<div id="%1$s" class="widget %2$s boxContent">',



		'after_widget' => '</div>',







	));



	register_sidebar(array(



		'name' => 'Stream Activity',



		'before_widget' => '<div id="%1$s" class="widget %2$s ">',



		'after_widget' => '</div>',







	));



	register_sidebar(array(



		'name' => 'View User Header',



		'before_widget' => '<div id="%1$s" class="widget %2$s ">',



		'after_widget' => '</div>',







	));



	register_sidebar(array(



		'name' => 'Reg. User Header',



		'before_widget' => '<div id="%1$s" class="widget %2$s ">',



		'after_widget' => '</div>',







	));



	register_sidebar(array(



		'name' => 'Member User Header',



		'before_widget' => '<div id="%1$s" class="widget %2$s ">',



		'after_widget' => '</div>',







	));

	register_sidebar(array(

		'name' => 'Q & A Section',

		'before_widget' => '<div id="%1$s" class="widget %2$s  mostCommented">',

		'after_widget' => '</div>',

		'before_title' => '<h3>',

		'after_title' => '</h3>',

	));

	

	register_sidebar(array(

		'name' => 'Most Shared Posts',

		'before_widget' => '<div id="%1$s" class="widget %2$s  mostShared">',

		'after_widget' => '</div>',

		'before_title' => '<h3>',

		'after_title' => '</h3>',

	));



}



include("functions/widget-whatus.php");



include("functions/widget-newsletter.php");



include("functions/widget-social.php");



include("functions/widget-fan.php");



include("functions/widget-whatnew.php");



include("functions/widget-friends.php");



include("functions/widget-streamact.php");



include("functions/widget-gestuser.php");



include("functions/widget-nuser.php");



include("functions/widget-muser.php");











add_theme_support( 'qa_style' );



//add_theme_support( 'qa_script' );







add_action( 'init', 'register_cpt_showcase' );



function register_cpt_showcase() {



    $labels = array( 



        'name' => _x( 'Showcases', 'showcase' ),



        'singular_name' => _x( 'Showcase', 'showcase' ),



        'add_new' => _x( 'Add New', 'showcase' ),



        'add_new_item' => _x( 'Add New Showcase', 'showcase' ),



        'edit_item' => _x( 'Edit Showcase', 'showcase' ),



        'new_item' => _x( 'New Showcase', 'showcase' ),



        'view_item' => _x( 'View Showcase', 'showcase' ),



        'search_items' => _x( 'Search Showcases', 'showcase' ),



        'not_found' => _x( 'No showcases found', 'showcase' ),



        'not_found_in_trash' => _x( 'No showcases found in Trash', 'showcase' ),



        'parent_item_colon' => _x( 'Parent Showcase:', 'showcase' ),



        'menu_name' => _x( 'Showcases', 'showcase' ),



    );







    $args = array( 



        'labels' => $labels,



        'hierarchical' => false,



        'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),



        'taxonomies' => array( 'brand', 'country', 'language','post_tag' ),



        'public' => true,



        'show_ui' => true,



        'show_in_menu' => true,



        'show_in_nav_menus' => true,



        'publicly_queryable' => true,



        'exclude_from_search' => false,



        'has_archive' => true,



        'query_var' => true,



        'can_export' => true,



        'rewrite' => true,



        'capability_type' => 'post'



    );







    register_post_type( 'showcase', $args );



}







add_action( 'init', 'register_taxonomy_brands' );



function register_taxonomy_brands() {



    $labels = array( 



        'name' => _x( 'Brands', 'brands' ),



        'singular_name' => _x( 'Brand', 'brands' ),



        'search_items' => _x( 'Search Brands', 'brands' ),



        'popular_items' => _x( 'Popular Brands', 'brands' ),



        'all_items' => _x( 'All Brands', 'brands' ),



        'parent_item' => _x( 'Parent Brand', 'brands' ),



        'parent_item_colon' => _x( 'Parent Brand:', 'brands' ),



        'edit_item' => _x( 'Edit Brand', 'brands' ),



        'update_item' => _x( 'Update Brand', 'brands' ),



        'add_new_item' => _x( 'Add New Brand', 'brands' ),



        'new_item_name' => _x( 'New Brand', 'brands' ),



        'separate_items_with_commas' => _x( 'Separate brands with commas', 'brands' ),



        'add_or_remove_items' => _x( 'Add or remove brands', 'brands' ),



        'choose_from_most_used' => _x( 'Choose from the most used brands', 'brands' ),



        'menu_name' => _x( 'Brands', 'brands' ),



    );







    $args = array( 



        'labels' => $labels,



        'public' => true,



        'show_in_nav_menus' => true,



        'show_ui' => true,



        'show_tagcloud' => true,



        'hierarchical' => true,



        'rewrite' => true,



        'query_var' => true



    );



   register_taxonomy( 'brands', array('showcase'), $args );



}







add_action( 'init', 'register_taxonomy_countries' );



function register_taxonomy_countries() {



    $labels = array( 



        'name' => _x( 'countries', 'countries' ),



        'singular_name' => _x( 'Country', 'countries' ),



        'search_items' => _x( 'Search countries', 'countries' ),



        'popular_items' => _x( 'Popular countries', 'countries' ),



        'all_items' => _x( 'All countries', 'countries' ),



        'parent_item' => _x( 'Parent Country', 'countries' ),



        'parent_item_colon' => _x( 'Parent Country:', 'countries' ),



        'edit_item' => _x( 'Edit Country', 'countries' ),



        'update_item' => _x( 'Update Country', 'countries' ),



        'add_new_item' => _x( 'Add New Country', 'countries' ),



        'new_item_name' => _x( 'New Country', 'countries' ),



        'separate_items_with_commas' => _x( 'Separate countries with commas', 'countries' ),



        'add_or_remove_items' => _x( 'Add or remove countries', 'countries' ),



        'choose_from_most_used' => _x( 'Choose from the most used countries', 'countries' ),



        'menu_name' => _x( 'countries', 'countries' ),



    );



    $args = array( 



        'labels' => $labels,



        'public' => true,



        'show_in_nav_menus' => true,



        'show_ui' => true,



        'show_tagcloud' => true,



        'hierarchical' => true,



        'rewrite' => true,



        'query_var' => true



    );



    register_taxonomy( 'countries', array('showcase'), $args );



}







add_action( 'init', 'register_taxonomy_languages' );



function register_taxonomy_languages() {



    $labels = array( 



        'name' => _x( 'Languages', 'languages' ),



        'singular_name' => _x( 'Language', 'languages' ),



        'search_items' => _x( 'Search Languages', 'languages' ),



        'popular_items' => _x( 'Popular Languages', 'languages' ),



        'all_items' => _x( 'All Languages', 'languages' ),



        'parent_item' => _x( 'Parent Language', 'languages' ),



        'parent_item_colon' => _x( 'Parent Language:', 'languages' ),



        'edit_item' => _x( 'Edit Language', 'languages' ),



        'update_item' => _x( 'Update Language', 'languages' ),



        'add_new_item' => _x( 'Add New Language', 'languages' ),



        'new_item_name' => _x( 'New Language', 'languages' ),



        'separate_items_with_commas' => _x( 'Separate languages with commas', 'languages' ),



        'add_or_remove_items' => _x( 'Add or remove languages', 'languages' ),



        'choose_from_most_used' => _x( 'Choose from the most used languages', 'languages' ),



        'menu_name' => _x( 'Languages', 'languages' ),



    );



    $args = array( 



        'labels' => $labels,



        'public' => true,



        'show_in_nav_menus' => true,



        'show_ui' => true,



        'show_tagcloud' => true,



        'hierarchical' => true,



        'rewrite' => true,



        'query_var' => true



    );



    register_taxonomy( 'languages', array('showcase'), $args );



}







add_action( 'init', 'register_taxonomy_services' );



function register_taxonomy_services() {



    $labels = array( 



        'name' => _x( 'Services', 'services' ),



        'singular_name' => _x( 'Service', 'services' ),



        'search_items' => _x( 'Search Services', 'services' ),



        'popular_items' => _x( 'Popular Services', 'services' ),



        'all_items' => _x( 'All Services', 'services' ),



        'parent_item' => _x( 'Parent Service', 'services' ),



        'parent_item_colon' => _x( 'Parent Service:', 'services' ),



        'edit_item' => _x( 'Edit Service', 'services' ),



        'update_item' => _x( 'Update Service', 'services' ),



        'add_new_item' => _x( 'Add New Service', 'services' ),



        'new_item_name' => _x( 'New Service', 'services' ),



        'separate_items_with_commas' => _x( 'Separate services with commas', 'services' ),



        'add_or_remove_items' => _x( 'Add or remove services', 'services' ),



        'choose_from_most_used' => _x( 'Choose from the most used services', 'services' ),



        'menu_name' => _x( 'Services', 'services' ),



    );



    $args = array( 



        'labels' => $labels,



        'public' => true,



        'show_in_nav_menus' => true,



        'show_ui' => true,



        'show_tagcloud' => true,



        'hierarchical' => true,



        'rewrite' => true,



        'query_var' => true



    );



    register_taxonomy( 'services', array('showcase'), $args );



}







add_action( 'init', 'register_cpt_app' );



function register_cpt_app() {



    $labels = array( 



        'name' => _x( 'Apps', 'app' ),



        'singular_name' => _x( 'App', 'app' ),



        'add_new' => _x( 'Add New', 'app' ),



        'add_new_item' => _x( 'Add New App', 'app' ),



        'edit_item' => _x( 'Edit App', 'app' ),



        'new_item' => _x( 'New App', 'app' ),



        'view_item' => _x( 'View App', 'app' ),



        'search_items' => _x( 'Search Apps', 'app' ),



        'not_found' => _x( 'No apps found', 'app' ),



        'not_found_in_trash' => _x( 'No apps found in Trash', 'app' ),



        'parent_item_colon' => _x( 'Parent App:', 'app' ),



        'menu_name' => _x( 'Apps', 'app' ),



    );







    $args = array( 



        'labels' => $labels,



        'hierarchical' => true,



        'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),



		'taxonomies' => array( 'category' ),



        'public' => true,



        'show_ui' => true,



        'show_in_menu' => true,



        'show_in_nav_menus' => true,



        'publicly_queryable' => true,



        'exclude_from_search' => false,



        'has_archive' => true,



        'query_var' => true,



        'can_export' => true,



        'rewrite' => true,



        'capability_type' => 'page'



    );







    register_post_type( 'app', $args );







   }







function be_sample_metaboxes( $meta_boxes ) {



	$prefix = '_cmb_'; // Prefix for all fields



	$meta_boxes[] = array(



		'id' => 'test_metabox',



		'title' => 'Additional Apps Info',



		'pages' => array('app'), // post type



		'context' => 'normal',



		'priority' => 'high',



		'show_names' => true, // Show field names on the left



		'fields' => array(



			array(



				'name' => 'App One liner',



			//	'desc' => 'field description (optional)',



				'id' => $prefix . 'appone_code',



				'type' => 'text',



				'maxlength' =>'35' 



			),



			array(



				'name' => 'App Icon',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'appIcon_code',



				'type' => 'file'



			),



			array(



				'name' => 'Youtube Share Url',



				'desc' => 'OR',



				'id' => $prefix . 'video_code',



				'type' => 'text'



			),

			array(



				'name' => 'Photo Share Url',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'photo_code',



				'type' => 'text'



			),



			array(



				'name' => 'Top Heading1',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'tophead1_code',



				'type' => 'text',



				'maxlength' =>'35' 



			),



			array(



				'name' => 'Topbox1',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'topbox1_code',



				'type' => 'textarea'



			),



			array(



				'name' => 'Top Heading2',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'tophead2_code',



				'type' => 'text',



				'maxlength' =>'35'



			),



			array(



				'name' => 'Topbox2',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'topbox2_code',



				'type' => 'textarea'



			),



			array(



				'name' => 'Top Heading3',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'tophead3_code',



				'type' => 'text',



				'maxlength' =>'35'



			),



			array(



				'name' => 'Topbox3',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'topbox3_code',



				'type' => 'textarea'



			),



			array(



				'name' => 'Top Heading4',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'tophead4_code',



				'type' => 'text',



				'maxlength' =>'35'



			),



			array(



				'name' => 'Topbox4',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'topbox4_code',



				'type' => 'textarea'



			),



			array(



				'name' => 'Top Heading5',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'tophead5_code',



				'type' => 'text',



				'maxlength' =>'35'



			),



			array(



				'name' => 'Topbox5',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'topbox5_code',



				'type' => 'textarea'



			),



			array(



				'name' => 'Top Heading6',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'tophead6_code',



				'type' => 'text',



				'maxlength' =>'35'



			),



			array(



				'name' => 'Topbox6',



				//'desc' => 'field description (optional)',



				'id' => $prefix . 'topbox6_code',



				'type' => 'textarea'



			),



			array(



				'name'    => 'User Tips',



				'desc'    => 'field description (optional)',



				'id'      => $prefix . 'usertips_code',



				'type'    => 'wysiwyg',



				'options' => array(	'textarea_rows' => 5, ),



			),

            array(



                'name'    => 'Module Name',



                'id'      => $prefix . 'machine_name',



                'type'    => 'text',



            ),





        ),



	);



	$meta_boxes[] = array(



		'id' => 't_post_metabox',



		'title' => 'Additional Apps Info',



		'pages' => array('post'), // post type



		'context' => 'normal',



		'priority' => 'high',



		'show_names' => true, // Show field names on the left



		'fields' => array(



			array(



				'name' => 'Sub Heading ',



			//	'desc' => 'field description (optional)',



				'id' => $prefix . 'sub_heading',



				'type' => 'text'



				



			),



			

		),



	);



	return $meta_boxes;



}
add_filter( 'cmb_meta_boxes', 'be_sample_metaboxes' );









// Initialize the metabox class



add_action( 'init', 'be_initialize_cmb_meta_boxes', 9999 );



function be_initialize_cmb_meta_boxes() {



	if ( !class_exists( 'cmb_Meta_Box' ) ) {



		require_once( 'metabox/init.php' );



	}



}







/* Disable the Wordpress Admin Bar for all but admins. 



if (!current_user_can('administrator')):



	show_admin_bar(false);



	endif;*/



show_admin_bar(false);







/*add_action('login_redirect', 'redirect_login', 10, 3);



function redirect_login($redirect_to, $url, $user) {



	wp_redirect($redirect_to);



    exit;



}



*/







function randomPrefix($length)



{



	$random= "";



	



	srand((double)microtime()*1000000);



	



	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";



	//$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";



	$data .= "0FGH45OP89";



	



	for($i = 0; $i < $length; $i++)



	{



	$random .= substr($data, (rand()%(strlen($data))), 1);



	}



	



	return $random;



}





function hide_instant_messaging( $contactmethods ) {

unset($contactmethods['aim']);

unset($contactmethods['yim']);

unset($contactmethods['jabber']);

return $contactmethods;

}

add_filter('user_contactmethods','hide_instant_messaging',10,1);



add_action( 'show_user_profile', 'my_show_extra_profile_fields' );

add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );



function my_show_extra_profile_fields( $user ) { ?>

<h3>Extra profile information</h3>
<table class="form-table">
  <tr>
    <th><label for="twitter">Twitter</label></th>
    <td><input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" />
      <br />
      <span class="description">Please enter your Twitter URL.</span></td>
  </tr>
  <tr>
    <th><label for="linkedin ">Linkedin </label></th>
    <td><input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( get_the_author_meta( 'linkedin', $user->ID ) ); ?>" class="regular-text" />
      <br />
      <span class="description">Please enter your Linkedin URL.</span></td>
  </tr>
  <tr>
    <th><label for="instagram ">Instagram </label></th>
    <td><input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text" />
      <br />
      <span class="description">Please enter your Instagram URL.</span></td>
  </tr>
</table>
<?php }?>
<?php 

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );

add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );



function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )

		return false;

	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );

	update_user_meta( $user_id, 'linkedin', $_POST['linkedin'] );

	update_user_meta( $user_id, 'instagram', $_POST['instagram'] );

}





?>
