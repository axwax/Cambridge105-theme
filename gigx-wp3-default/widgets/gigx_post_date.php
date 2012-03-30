<?php
######################
### gigx_post_date ###
### v0.1           ###
######################

class gigx_post_date extends WP_Widget {
	/**
	 * gigx_post_date()
	 *
	 * @return void
	 **/

	function gigx_post_date() {
		$widget_name = 'GIGX Post Date';
		$widget_ops = array(
			'classname' => 'gigx_post_date',
			'description' => 'The post\'s date. Must be placed in the loop (each entry).',
			);
		$control_ops = array(
		//	'width' => 330,
			);      		
		$this->WP_Widget('gigx_post_date', $widget_name, $widget_ops, $control_ops);
	} # gigx_post_date()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {

		$instance = wp_parse_args($instance, gigx_post_date::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);
		
    # construct date
		$date = false;
		if ( $show_post_date && !is_sticky() && ( is_single() || !is_singular() && !is_day() ) ) {
        $date= get_the_time($date_format);
        if ($before_date) $date = $before_date . ' ' . $date;
    }		
    if ( $date ) {
  		echo '<span class="postdate">' . $date.'</span> ' ;
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
		$instance['show_post_date'] = isset($new_instance['show_post_date']);
		$instance['before_date'] = trim(strip_tags($new_instance['before_date']));
		$instance['date_format'] = trim(strip_tags($new_instance['date_format']));
		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_post_date::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_post_date') . '"'
			. checked($show_post_date, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show post dates.'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Before Date: ' .$before_date . '</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('before_date') . '"'
			. ' value="' . esc_attr($before_date) . '"'
			. ' />'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Date Format: ' .date  (esc_attr($date_format)) . '</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('date_format') . '"'
			. ' value="' . esc_attr($date_format) . '"'
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
			'show_post_date' => true,
			'before_date' => 'Posted on',
			'date_format' => 'jS F Y',
			);
	} # defaults()
} # gigx_post_date
?>
