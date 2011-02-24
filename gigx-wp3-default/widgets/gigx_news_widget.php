<?php
############################
### gigx_news_widget ###
### v0.1                 ###
############################

class gigx_news_widget extends WP_Widget {
	/**
	 * gigx_news_widget()
	 *
	 * @return void
	 **/

	function gigx_news_widget() {
		$widget_name = 'GIGX News Widget';
		$widget_ops = array(
			'classname' => 'gigx_news_widget',
			'description' => 'Displays posts for a specific category in the sidebar.',
			);
		$control_ops = array(
		//	'width' => 330,
			);		
		$this->WP_Widget('gigx_news_widget', $widget_name, $widget_ops, $control_ops);
	} # gigx_news_widget()
	
	
	/**
	 * widget()
	 *
	 * @param array $args widget args
	 * @param array $instance widget options
	 * @return void
	 **/

	function widget($args, $instance) {


		$instance = wp_parse_args($instance, gigx_news_widget::defaults());
		extract($args, EXTR_SKIP);
		extract($instance, EXTR_SKIP);



		//if ( !$filed_under_by ) return;
		
		$categories = get_the_category_list(', ');
    echo $args['before_widget'];
    echo $args['before_title'] . $data['widget_title'] . $args['after_title'];
    echo"<div class=\"gigx-news-widget\">
        <div class=\"gigx-news-box\" style=\"width: 400px;\">            
    <div class=\"gigx-news-header\"><h3></h3><h4></h4>               
    </div>            
    <div class=\"gigx-news-body\">              
      <div class=\"gigx-news-posts\" style=\"height: 225px; \">                
        ";
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts('showposts=10&paged='.$paged);
    while (have_posts()) : 
        the_post();
        echo "<div class=\"gigx-news-post\">
                              
              <div class=\"gigx-news-post-text\">";
        echo "<h5><a href=\"";
        the_permalink();
        echo "\">";
        the_title(); 
        echo "</a></h5>";
        echo "<p>";
        the_content(' [more...]');
        echo "<i><a class=\"gigx-news-timestamp\" href=\"";
        the_permalink();
        echo "\">";
        echo the_time('j F') . " at ";
        echo the_time('H:i') ."</a></i>";

        echo"</p>         
              </div>       
            
          </div>";
        endwhile;
        posts_nav_link();
        wp_reset_query();                         
        echo"<!-- tweets show here -->                
                      
      </div>            
    </div>            
    <div class=\"gigx-news-footer\">              
      <div>
      </div>            
    </div>          
  </div>
</div>";
    
echo $args['after_widget'];
			
	} # widget()
	
	
	/**
	 * update()
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 **/

	function update($new_instance, $old_instance) {
		$instance['show_news_widget'] = isset($new_instance['show_news_widget']);
		$instance['before_news_widget'] = trim(strip_tags($new_instance['before_news_widget']));		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance widget options
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, gigx_news_widget::defaults());
		extract($instance, EXTR_SKIP);
		
		echo '<h3>Config</h3>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="' . $this->get_field_name('show_news_widget') . '"'
			. checked($show_news_widget, true, false)
			. ' />'
			. '&nbsp;'
			. 'Show news widget.'
			. '</label>'
			. '</p>' . "\n";
		echo '<p>'
			. '<label>'
			. '<code>' . 'Before News Widget: ' .$before_news_widget . '</code>'
			. '<br />' . "\n"
			. '<input type="text" class="widefat"'
			. ' name="' . $this->get_field_name('before_news_widget') . '"'
			. ' value="' . esc_attr($before_news_widget) . '"'
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
			'show_news_widget' => true,
			'before_news_widget' => 'in',
			);
	} # defaults()
} # gigx_news_widget




?>
