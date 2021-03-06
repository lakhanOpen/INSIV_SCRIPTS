<?php/* * Plugin Name: whatus * Plugin URI: http://www.syoninfomedia.com * Description: A widget that allows the selection and configuration of a single 250X250 Banner * Version: 1.0 * Author: ashishwd * Author URI:  http://www.syoninfomedia.com *//* * Add function to widgets_init that'll load our widget. */add_action( 'widgets_init', 'socail_widgets' );/* * Register widget. */function socail_widgets() {	register_widget( 'socail_Widget' );}/* * Widget class. */class socail_Widget extends WP_Widget {	/* ---------------------------- */	/* -------- Widget setup -------- */	/* ---------------------------- */		function socail_Widget() {			/* Widget settings. */		$widget_ops = array( 'classname' => 'socail_widget', 'description' => __('A widget that allows the display and configuration of social network.', 'framework') );		/* Create the widget */		$this->WP_Widget( 'socail_widget', __('Social Network ', 'framework'), $widget_ops, $control_ops );	}	/* ---------------------------- */	/* ------- Display Widget -------- */	/* ---------------------------- */		function widget( $args, $instance ) {		extract( $args );		/* Our variables from the widget settings. */		//$title = apply_filters('widget_title', $instance['title'] );		$fbcode = $instance['fbcode'];		$twcode = $instance['twcode'];		$rcode = $instance['rcode'];		$tbcode = $instance['tbcode'];							//$desc = $instance['desc'];		/* Before widget (defined by themes). */		echo $before_widget;		/* Display the widget title if one was input (before and after defined by themes). */							/* Display Widget */		?>			  <map name="Map" id="Map">          <area shape="rect" coords="-1,6,39,43" href="<?php echo $fbcode ?>" />          <area shape="rect" coords="47,4,86,42" href="<?php echo $twcode ?>" />          <area shape="rect" coords="96,6,131,41" href="<?php echo $rcode ?>" />          <area shape="rect" coords="142,7,178,42" href="<?php echo $tbcode ?>" />        </map>										<?php		/* After widget (defined by themes). */		echo $after_widget;	}	/* ---------------------------- */	/* ------- Update Widget -------- */	/* ---------------------------- */		function update( $new_instance, $old_instance ) {		$instance = $old_instance;		/* Strip tags to remove HTML (important for text inputs). */		//$instance['title'] = htmlspecialchars_decode($new_instance['title'] );				/* Stripslashes for html inputs */		$instance['fbcode'] = stripslashes( $new_instance['fbcode']);		$instance['twcode'] = stripslashes( $new_instance['twcode']);		$instance['rcode']  = stripslashes( $new_instance['rcode']);		$instance['tbcode'] = stripslashes( $new_instance['tbcode']);							/* No need to strip tags for.. */		return $instance;	}		/* ---------------------------- */	/* ------- Widget Settings ------- */	/* ---------------------------- */		/**	 * Displays the widget settings controls on the widget panel.	 * Make use of the get_field_id() and get_field_name() function	 * when creating your form elements. This handles the confusing stuff.	 */	 	function form( $instance ) {		/* Set up some default widget settings. */		$defaults = array(		//'title' => 'What us',                'rcode' => stripslashes( 'Contect'),		'fbcode' => stripslashes( 'FaceBook'),		'twcode' => stripslashes( 'Twitter'),                'tbcode' => stripslashes( 'Instagram'),							//'desc' => 'This is my latest video, pretty cool huh!',		);		$instance = wp_parse_args( (array) $instance, $defaults ); ?>		<!-- Embed Code: Text Input -->                    <p>			<label for="<?php echo $this->get_field_id( 'rcode' ); ?>"><?php _e('Contect:', 'framework') ?></label>			<input class="widefat" id="<?php echo $this->get_field_id( 'rcode' ); ?>" name="<?php echo $this->get_field_name( 'rcode' ); ?>" value="<?php echo $instance['rcode']; ?>" />		</p>         <p>			<label for="<?php echo $this->get_field_id( 'fbcode' ); ?>"><?php _e('Facebook:', 'framework') ?></label>			<input class="widefat" id="<?php echo $this->get_field_id( 'fbcode' ); ?>" name="<?php echo $this->get_field_name( 'fbcode' ); ?>" value="<?php echo $instance['fbcode']; ?>" />		</p>		 <p>			<label for="<?php echo $this->get_field_id( 'twcode' ); ?>"><?php _e('Twitter:', 'framework') ?></label>			<input class="widefat" id="<?php echo $this->get_field_id( 'twcode' ); ?>" name="<?php echo $this->get_field_name( 'twcode' ); ?>" value="<?php echo $instance['twcode']; ?>" />		</p>              <p>			<label for="<?php echo $this->get_field_id( 'tbcode' ); ?>"><?php _e('Instagram:', 'framework') ?></label>			<input class="widefat" id="<?php echo $this->get_field_id( 'tbcode' ); ?>" name="<?php echo $this->get_field_name( 'tbcode' ); ?>" value="<?php echo $instance['tbcode']; ?>" />		</p>			<?php	}}?>