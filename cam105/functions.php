<?php
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