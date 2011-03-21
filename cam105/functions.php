<?php



/*
if ( ! function_exists( 'unregister_post_type' ) ) :
function unregister_post_type( $post_type ) {
	global $wp_post_types;
	if ( isset( $wp_post_types[ $post_type ] ) ) {
		unset( $wp_post_types[ $post_type ] );
		return true;
	}
	return false;
}
endif;

unregister_post_type('gigx_schedule');
unregister_post_type('schedule');
unregister_post_type('shows');
*/

# include custom post types
include 'gigx_cpt_shows.php';
include 'gigx_cpt_schedule.php';
# posts to posts stuff #

function my_connection_types() {
    if ( !function_exists('p2p_register_connection_type') )
        return;
    $args=array('from'=>'gigx_schedule',
                'to'=>'shows',
                'title'=>'Select Show',
                'reciprocal'=>false,
                'box'=>'P2P_Box_Multiple');    
    p2p_register_connection_type( $args );
}
add_action('init', 'my_connection_types', 100);


/* presenters stuff
include 'gigx_cpt_presenters.php';
# posts to posts stuff #
function my_connection_types() {
    if ( !function_exists('p2p_register_connection_type') )
        return;
    p2p_register_connection_type( 'shows', 'presenters' );
}
add_action('init', 'my_connection_types', 100);
*/

# add shows image and thumbnail size
      	add_image_size( 'shows-image', 200, 200, true );
      	add_image_size( 'shows-thumb', 75, 75, true );

# add facebook thumbnail size
      	add_image_size( 'facebook-thumb', 130, 130, true );
      	
      
# change header image size
add_filter('gigx_header_image_width','cam105_header_image_width');
add_filter('gigx_header_image_height','cam105_header_image_height');
function cam105_header_image_width($size){
   return 510;
}
function cam105_header_image_height($size){
   return 120;
}

# gigx functions:

# gigx_find_image_attachment($post_id=0)
# parameters: $post_id
# returns: first image attachment id
function gigx_find_image_attachment($post_id=0){
  $args = array(
  	'post_type' => 'attachment',
  	'numberposts' => null,
  	'post_status' => null,
  	'post_parent' => $post_id
  );
  $attachments = get_posts($args);
  foreach( $attachments as $item ) {
  		$mime_types = explode( "/", get_post_mime_type( $item->ID ) );
  		if ( in_array( 'image', $mime_types ) ) {
        return $item->ID; 
  		}
  	}
  	return 0;
}

################
# untesteds stuff:
#
# remove default widgets
// unregister all default WP Widgets
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);
?>