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
      
# change header image size
add_filter('gigx_header_image_width','cam105_header_image_width');
add_filter('gigx_header_image_height','cam105_header_image_height');
function cam105_header_image_width($size){
   return 510;
}
function cam105_header_image_height($size){
   return 120;
}

?>