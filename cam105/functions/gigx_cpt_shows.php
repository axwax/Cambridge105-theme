<?php

# Cambridge105 custom post type Shows & Taxonomies "Genres" and "Frequency"
# needs to be executed during init
# v0.2
# author: Axel Minet

# register shows custom post type
add_action( 'init', 'gigx_cpt_shows' );
function gigx_cpt_shows() 
{
  $labels = array(
    'name' => _x('Shows', 'post type general name'),
    'singular_name' => _x('Show', 'post type singular name'),
    'add_new' => _x('Add New', 'show'),
    'add_new_item' => __('Add New Show'),
    'edit_item' => __('Edit Show'),
    'new_item' => __('New Show'),
    'view_item' => __('View Show'),
    'search_items' => __('Search Shows'),
    'not_found' =>  __('No Shows found'),
    'not_found_in_trash' => __('No Shows found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => array('slug' => 'shows','with_front' => false),
    'can_export' => true,
    'capability_type' => 'post',
    'has_archive' => 'shows',
    'exclude_from_search' => false,
    'hierarchical' => true,          //maybe change back
    'menu_position' => 5,
    'menu_icon' => null,
    'show_in_nav_menus' => true,
    'supports' => array('title','editor','thumbnail','sticky','custom-fields'),
    'taxonomies' => array( 'post_tag', 'category ')
  ); 
  register_post_type('show',$args);
}

# add filter to insure the text Show, or show, is displayed when user updates a show 
add_filter('post_updated_messages', 'show_updated_messages');
function show_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['show'] = array(
    0 => '', # Unused. Messages start at index 1.
    1 => sprintf( __('Show updated. <a href="%s">View Show</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Show updated.'),
    # translators: %s: date and time of the revision
    5 => isset($_GET['revision']) ? sprintf( __('Show restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Show published. <a href="%s">View Show</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Show saved.'),
    8 => sprintf( __('Show submitted. <a target="_blank" href="%s">Preview Show</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Show scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Show</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'l, jS F Y' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Show draft updated. <a target="_blank" href="%s">Preview Show</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

# display contextual help for Shows
add_action( 'contextual_help', 'add_help_text', 10, 3 );

function add_help_text($contextual_help, $screen_id, $screen) { 
  # $contextual_help .= var_dump($screen); // use this to help determine $screen->id
  if ('show' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a show:') . '</p>';
  } elseif ( 'edit-show' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of shows blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}


# register genre and frequency taxonomies for shows cpt

# hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_shows_taxonomies', 0 );

# create two taxonomies, "genres" and "frequency" for the post type "shows"
function create_shows_taxonomies() 
{
    /*    
  # Add "Genres" taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Genres', 'taxonomy general name' ),
    'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Genres' ),
    'all_items' => __( 'All Genres' ),
    'parent_item' => __( 'Parent Genre' ),
    'parent_item_colon' => __( 'Parent Genre:' ),
    'edit_item' => __( 'Edit Genre' ), 
    'update_item' => __( 'Update Genre' ),
    'add_new_item' => __( 'Add New Genre' ),
    'new_item_name' => __( 'New Genre Name' ),
    'menu_name' => __( 'Genres' ),
  ); 	

  register_taxonomy('genre',array('show'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'genre' ),
  ));
    */

  # Add "Frequency" taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Frequency', 'taxonomy general name' ),
    'singular_name' => _x( 'Frequency', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search by Frequency' ),
    'popular_items' => __( 'Popular Frequencies' ),
    'all_items' => __( 'All Frequency' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Frequency' ), 
    'update_item' => __( 'Update Frequency' ),
    'add_new_item' => __( 'Add New Frequency' ),
    'new_item_name' => __( 'New Frequency Name' ),
    'separate_items_with_commas' => __( 'Separate Frequencies with commas' ),
    'add_or_remove_items' => __( 'Add or remove Frequency' ),
    'choose_from_most_used' => __( 'Choose from the most used Frequencies' ),
    'menu_name' => __( 'Frequency' ),
  ); 

  register_taxonomy('frequency','show',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'frequency' ),
  ));
}

?>
