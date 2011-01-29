<?php
#######################
### gigx_page_title ###
### v0.2            ###
#######################

# changelog: 0.2 added single_title_tag option

class gigx_page_title extends WP_Widget {
	/**
	 * gigx_page_title()
	 *
	 * @return void
	 **/

	function gigx_page_title() {
		$widget_name = 'GIGX Page Title';
		$widget_ops = array(
			'classname' => 'gigx_page_title',
			'description' => 'The page\'s title. Must not be placed in the loop.',
			);
		$control_ops = array(
		//	'width' => 330,
			);
		
		$this->WP_Widget('gigx_page_title', $widget_name, $widget_ops, $control_ops);
	} # gigx_page_title()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {
		//if ( $args['id'] != 'the_page'  && is_letter() )
		//	return;

		$instance = wp_parse_args($instance, gigx_page_title::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);
		
	 # construct title
		$title = wp_title('',false,'');	
    if(!$title) $title=get_the_title($post->post_parent);
    # Single Pages
    if(is_single()){
      if(get_post_type()=="post") {
        $title=get_the_title($post->post_category );
      }
      else {
        $post_type_object=get_post_type_object( get_post_type() );
        $title=$post_type_object->label;
        //$title=$posttypelabels['name'];
      }
    }
    # Taxonomy Title
    if(is_tax()) {
        $post_type_object=get_post_type_object( get_post_type() );
        $post_type_label=$post_type_object->singular_label;    
      $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
      $taxonomy_object=get_taxonomy($term->taxonomy);
      $title= $post_type_label.' '.$taxonomy_object->singular_label.': '.$term->name;      
    }
    if ( $single_title_tag && is_singular() ) {
      $title_tag=$single_title_tag;
		}
		if ( $title && $title_tag  ) {
  		//echo'<h2 class="page-title"><a href="' . the_permalink() . '" rel="bookmark" title="Permanent Link to ' . the_title_attribute() . '">' . the_title() .'</a></h2>';
      echo'<' . $title_tag . ' class="page-title">' . $title . '</' . $title_tag . '>';
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
		$instance['show_page_title'] = isset($new_instance['show_page_title']);
		$instance['title_tag'] = trim(strip_tags($new_instance['title_tag']));
		if(!preg_match('/^[a-zA-Z0-9]+$/i', $instance['title_tag']))$instance['title_tag']='h2';		
		$instance['single_title_tag'] = trim(strip_tags($new_instance['single_title_tag']));
		if(!preg_match('/^[a-zA-Z0-9]+$/i', $instance['single_title_tag']))$instance['single_title_tag']='h1';		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_page_title::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_page_title') . '"'
			. checked($show_page_title, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show page\'s title.'
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
		echo '<p>'
			. '<label>'
			. '<code>' . 'Tag surrounding title on single pages: &lt;' .$single_title_tag . '&gt;</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('single_title_tag') . '"'
			. ' value="' . esc_attr($single_title_tag) . '"'
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
			'show_page_title' => true,
			'title_tag' => 'h2',
			'single_title_tag' => 'h1',
			);
	} # defaults()
} # gigx_page_title




?>
