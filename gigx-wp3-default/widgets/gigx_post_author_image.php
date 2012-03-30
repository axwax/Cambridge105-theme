<?php
##############################
### gigx_post_author_image ###
### v0.1                   ###
##############################

if (!function_exists('get_the_author_image')){
      $pluginpath=dirname(__FILE__).'/gigx-author-image/gigx-author-image.php';
      if (file_exists  ($pluginpath)) {
          include $pluginpath;
      } else echo '<p class="error">Author Image plugin cannot be found!</p>';  
}
class gigx_post_author_image extends WP_Widget {
	/**
	 * gigx_post_author_image()
	 *
	 * @return void
	 **/

	function gigx_post_author_image() {
		$widget_name = 'GIGX Post Author Image';
		$widget_ops = array(
			'classname' => 'gigx_post_author_image',
			'description' => 'The post author\'s image. Must be placed in the loop (each entry) and depends on gigx_author_image plugin.',
			);
		$control_ops = array(
		//	'width' => 330,
			);		
		$this->WP_Widget('gigx_post_author_image', $widget_name, $widget_ops, $control_ops);
	} # gigx_post_author_image()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {
		//if ( $args['id'] != 'the_entry'  && is_letter() )
		//	return;

		$instance = wp_parse_args($instance, gigx_post_author_image::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);
				
		# do not show author and image on special pages
		if ( !is_sticky() && ( is_single() || !is_singular() && !is_day() ) ) {
        
        # author image
        $author_image=false;
        if($show_post_author_image && function_exists(get_the_author_image)) { 
            $author_image= get_the_author_image();
            if(get_the_author_meta( 'user_url' )) 
                $author_image = '<a href="' . get_the_author_meta( 'user_url' ). '" target="_blank">' . $author_image . '</a>';
            $author_image = '<div class="entry_author_image">' . $author_image . '</div>'; 
        }        				
    }

	
    	if ($author_image) {
    		echo $author_image;
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
		$instance['show_post_author_image'] = isset($new_instance['show_post_author_image']);
		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_post_author_image::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
	
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_post_author_image') . '"'
			. checked($show_post_author_image, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show author image.'
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
			'show_post_author_image' => true,
			);
	} # defaults()
} # gigx_post_author_image




?>
