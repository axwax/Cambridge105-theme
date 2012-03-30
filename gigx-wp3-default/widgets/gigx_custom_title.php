<?php
######################
### gigx_custom_title ###
### v0.1           ###
######################

class gigx_custom_title extends WP_Widget {
	/**
	 * gigx_custom_title()
	 *
	 * @return void
	 **/

	function gigx_custom_title() {
		$widget_name = 'GIGX Custom Title';
		$widget_ops = array(
			'classname' => 'gigx_custom_title',
			'description' => 'A custom title or other html element.',
			);
		$control_ops = array(
		//	'width' => 330,
			);      		
		$this->WP_Widget('gigx_custom_title', $widget_name, $widget_ops, $control_ops);
	} # gigx_custom_title()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {

		$instance = wp_parse_args($instance, gigx_custom_title::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);
		
		if ( $custom_title && $title_tag) {
  		//echo'<h2 class="post-title"><a href="' . the_permalink() . '" rel="bookmark" title="Permanent Link to ' . the_title_attribute() . '">' . the_title() .'</a></h2>';
      echo $before_widget;
      echo'<' . $title_tag . ' class="widget-title">' . $custom_title . '</' . $title_tag . '>';
      echo $after_widget;
		};
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
		$instance['show_custom_title'] = isset($new_instance['show_custom_title']);
		$instance['custom_title'] = trim(strip_tags($new_instance['custom_title']));
		$instance['title_tag'] = trim(strip_tags($new_instance['title_tag']));
		if(!preg_match('/^[a-zA-Z0-9]+$/i', $instance['title_tag']))$instance['title_tag']='h2';		
		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_custom_title::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_custom_title') . '"'
			. checked($show_custom_title, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show custom title.'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Custom title: ' .$custom_title . '</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('custom_title') . '"'
			. ' value="' . esc_attr($custom_title) . '"'
			. ' />'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Tag surrounding title: &lt;' .$title_tag . '&gt;</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('title_tag') . '"'
			. ' value="' . esc_attr($title_tag) . '"'
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
			'show_custom_title' => true,
			'custom_title' => '',
			'title_tag' => 'h2',
			);
	} # defaults()
} # gigx_custom_title
?>
