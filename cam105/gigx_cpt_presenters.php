<?php

# gigx custom post type presenters
# needs to be executed during init
# v0.1
# author: axwax

add_action( 'init', 'gigx_cpt_presenters' );
function gigx_cpt_presenters() 
{
  $labels = array(
    'name' => _x('Presenters', 'post type general name'),
    'singular_name' => _x('Presenter', 'post type singular name'),
    'add_new' => _x('Add New', 'presenter'),
    'add_new_item' => __('Add New Presenter'),
    'edit_item' => __('Edit Presenter'),
    'new_item' => __('New Presenter'),
    'view_item' => __('View Presenter'),
    'search_items' => __('Search Presenters'),
    'not_found' =>  __('No Presenters found'),
    'not_found_in_trash' => __('No Presenters found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'presenter_ui' => true, 
    'query_var' => true,
    'rewrite' => array('slug' => 'presenters','with_front' => false),
    'can_export' => true,
    'capability_type' => 'post',
    'has_archive' => 'presenters',
    'exclude_from_search' => false,
    'hierarchical' => false,         
    'menu_position' => 6,
    'menu_icon' => null,
    'presenter_in_nav_menus' => true,
    'supports' => array('title','editor','revisions','thumbnail','excerpt','sticky')
  ); 
  register_post_type('presenters',$args);
}

//add filter to insure the text Presenter, or presenter, is displayed when user updates a presenter 
add_filter('post_updated_messages', 'presenter_updated_messages');
function presenter_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['presenter'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Presenter updated. <a href="%s">View Presenter</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Presenter updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Presenter restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Presenter published. <a href="%s">View Presenter</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Presenter saved.'),
    8 => sprintf( __('Presenter submitted. <a target="_blank" href="%s">Preview Presenter</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Presenter scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Presenter</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'l, jS F Y' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Presenter draft updated. <a target="_blank" href="%s">Preview Presenter</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//display contextual help for Presenters
add_action( 'contextual_help', 'add_presenters_help_text', 10, 3 );

function add_presenters_help_text($contextual_help, $screen_id, $screen) { 
  //$contextual_help .= var_dump($screen); // use this to help determine $screen->id
  if ('presenter' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a presenter:') . '</p>';
  } elseif ( 'edit-presenter' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of presenters blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}

?>
