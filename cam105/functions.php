<?php
# include custom post types
include 'gigx_cpt_shows.php';
include 'gigx_cpt_presenters.php';

# posts to posts stuff #
function my_connection_types() {
    if ( !function_exists('p2p_register_connection_type') )
        return;

    p2p_register_connection_type( 'shows', 'presenters' );
}
add_action('init', 'my_connection_types', 100);

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
?>