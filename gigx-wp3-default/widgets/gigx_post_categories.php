<?php
########################
### gigx_post_author ###
### v0.1             ###
########################

class gigx_post_categories extends WP_Widget {
	/**
	 * gigx_post_categories()
	 *
	 * @return void
	 **/

	function gigx_post_categories() {
		$widget_name = 'GIGX Post Categories';
		$widget_ops = array(
			'classname' => 'gigx_post_categories',
			'description' => 'The post\'s categories. Must be placed in the loop (each entry).',
			);
		$control_ops = array(
		//	'width' => 330,
			);		
		$this->WP_Widget('gigx_post_categories', $widget_name, $widget_ops, $control_ops);
	} # gigx_post_categories()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {


		$instance = wp_parse_args($instance, gigx_post_categories::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);



		//if ( !$filed_under_by ) return;
		
		$categories = get_the_category_list(', ');

		//$categories = false;		
		# do not show categories on special pages or if empty
		if ( $categories && $show_post_categories && !is_sticky() && ( is_single() || !is_singular() && !is_day() ) ) {
        # construct categories      
        if ($before_categories) $categories = $before_categories . ' ' . $categories; 

    		echo '<span class="postcategories">' . $categories . '</span>';

      echo "\n\r";               				
    }

	
			
	} # widget()
	
	
	/**
	 * update()
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 **/

	function update($new_instance, $old_instance) {
		$instance['show_post_categories'] = isset($new_instance['show_post_categories']);
		$instance['before_categories'] = trim(strip_tags($new_instance['before_categories']));		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_post_categories::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_post_categories') . '"'
			. checked($show_post_categories, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show post categories.'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Before Categories: ' .$before_categories . '</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('before_categories') . '"'
			. ' value="' . esc_attr($before_categories) . '"'
			. ' />'
			. '</label>'
			. '</p>' . "\n";
	} # form()
	
	
	/**
	 * defaults()
	 *
	 * @return array $defaults
	 **/

	function defaults() {
		return array(
			'show_post_categories' => true,
			'before_categories' => 'in',
			);
	} # defaults()
} # gigx_post_categories




?>
