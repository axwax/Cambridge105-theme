<?php
########################
### gigx_post_author ###
### v0.1             ###
########################

class gigx_post_author extends WP_Widget {
	/**
	 * gigx_post_author()
	 *
	 * @return void
	 **/

	function gigx_post_author() {
		$widget_name = 'GIGX Post Author';
		$widget_ops = array(
			'classname' => 'gigx_post_author',
			'description' => 'The post\'s author. Must be placed in the loop (each entry).',
			);
		$control_ops = array(
		//	'width' => 330,
			);		
		$this->WP_Widget('gigx_post_author', $widget_name, $widget_ops, $control_ops);
	} # gigx_post_author()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {

		$instance = wp_parse_args($instance, gigx_post_author::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);
				
		# do not show author and image on special pages
		if ( $show_post_author && !is_sticky() && ( is_single() || !is_singular() && !is_day() ) ) {
        # construct author
        $author = false;
        $author = get_the_author_meta( 'display_name' );
        // make it a link if URL is set in profile
        if(get_the_author_meta( 'user_url' )) 
            $author = '<a href="' . get_the_author_meta( 'user_url' ). '" target="_blank">' . $author . '</a>';             
        if ($before_author) $author = $before_author . ' ' . $author;        				
    }

	
			if ( $author ) {
    		echo '<span class="postauthor">' . $author . '</span>';
			}
      echo "\n\r";			
	} # widget()
	
	
	/**
	 * update()
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 **/

	function update($new_instance, $old_instance) {
		$instance['show_post_author'] = isset($new_instance['show_post_author']);
		$instance['before_author'] = trim(strip_tags($new_instance['before_author']));		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_post_author::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_post_author') . '"'
			. checked($show_post_author, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show post author.'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Before Author: ' .$before_author . '</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('before_author') . '"'
			. ' value="' . esc_attr($before_author) . '"'
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
			'show_post_author' => true,
			'before_author' => 'by',
			);
	} # defaults()
} # gigx_post_author




?>
