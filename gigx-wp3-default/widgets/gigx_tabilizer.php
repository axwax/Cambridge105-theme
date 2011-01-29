<?php
/**
 * @package GIGX Tabilizer
 * @version 0.0.1
 */
 
/*
Plugin Name: GIGX Tabilizer
Plugin URI: http://www.gigx.co.uk/tabz
Description: GIGX Tabilizer
Author: Benjamin Rowe
Version: 0.0.1
Author URI: http://www.gigx.co.uk
*/

class gigx_tabilizer extends WP_Widget {
	/**
	 * gigx_tabilizer()
	 *
	 * @return void
	 **/

	function gigx_tabilizer() {
		$widget_name = 'GIGX Tabilizer';
		$widget_ops = array(
			'classname' => 'gigx_tabilizer',
			'description' => 'A funky tab widget.',
			);
		$control_ops = array(
		//	'width' => 330,
			);		
		$this->WP_Widget('gigx_tabilizer', $widget_name, $widget_ops, $control_ops);
	} # gigx_tabilizer()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {

		$instance = wp_parse_args($instance, gigx_tabilizer::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);
				
    

	
			echo '<span class="tabilizer">I am a funky tab.</span>';
			
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
		$instance['show_tabilizer'] = isset($new_instance['show_tabilizer']);		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_tabilizer::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_tabilizer') . '"'
			. checked($show_tabilizer, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show post author.'
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
			'show_tabilizer' => true,
			);
	} # defaults()
} # gigx_tabilizer

?>