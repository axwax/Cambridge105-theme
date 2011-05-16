<?php
/**
 * Plugin Name: GIGX Banner Widget
 * Plugin URI: http://gigx_banner.com/widget
 * Description: A widget that serves as an gigx_banner for developing more advanced widgets.
 * Version: 0.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'gigx_banner_load_widgets' );

/**
 * Register our widget.
 * 'GIGX_Banner_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function gigx_banner_load_widgets() {
	register_widget( 'GIGX_Banner_Widget' );
}

/**
 * GIGX Banner Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class GIGX_Banner_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function GIGX_Banner_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gigx_banner', 'description' => __('An gigx_banner widget that displays a person\'s name and sex.', 'gigx_banner') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'gigx-banner-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'gigx-banner-widget', __('GIGX Banner Widget', 'gigx_banner'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];
		$sex = $instance['sex'];
		$show_sex = isset( $instance['show_sex'] ) ? $instance['show_sex'] : false;

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display name from widget settings if one was input. */
#		if ( $name )
#			printf( '<p>' . __('Hello. My name is %1$s.', 'gigx_banner') . '</p>', $name );

		/* If show sex was selected, display the user's sex. */
#		if ( $show_sex )
#			printf( '<p>' . __('I am a %1$s.', 'gigx_banner.') . '</p>', $sex );

    echo '<div class="cam105-infobox">				                     
    <a href="http://cam105.net/digital-switchover" title="Digital Switchover Help Scheme" target="_blank">                              
      <img src="http://cambridge105.fm/wp-content/uploads/2011/02/duk.gif" width="468" height="60" alt="Digital Switchover Help Scheme" title="Digital Switchover Help Scheme"/></a>          		                                   
</div>';
     
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['name'] = strip_tags( $new_instance['name'] );

		/* No need to strip tags for sex and show_sex. */
		$instance['sex'] = $new_instance['sex'];
		$instance['show_sex'] = $new_instance['show_sex'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('GIGX Banner', 'gigx_banner'), 'name' => __('John Doe', 'gigx_banner'), 'sex' => 'male', 'show_sex' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- Your Name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('Your Name:', 'gigx_banner'); ?></label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:100%;" />
		</p>

		<!-- Sex: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'sex' ); ?>"><?php _e('Sex:', 'gigx_banner'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'sex' ); ?>" name="<?php echo $this->get_field_name( 'sex' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'male' == $instance['format'] ) echo 'selected="selected"'; ?>>male</option>
				<option <?php if ( 'female' == $instance['format'] ) echo 'selected="selected"'; ?>>female</option>
			</select>
		</p>

		<!-- Show Sex? Checkbox -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_sex'], true ); ?> id="<?php echo $this->get_field_id( 'show_sex' ); ?>" name="<?php echo $this->get_field_name( 'show_sex' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_sex' ); ?>"><?php _e('Display sex publicly?', 'gigx_banner'); ?></label>
		</p>

	<?php
	}
}

?>