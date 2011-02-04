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
      

?>