<?php
# gigx_editor_buttons.php
# custom buttons for shortcodes in TinyMCE editor

/**
Hook into WordPress
*/

add_action('init', 'gigx_buttons');

/**
Create Our Initialization Function
*/

function gigx_buttons() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
     return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
     add_filter( 'mce_external_plugins', 'gigx_add_plugin' );
     add_filter( 'mce_buttons', 'gigx_register_button' );
   }

}

/**
Register Button
*/

function gigx_register_button( $buttons ) {
 array_push( $buttons, "|", "box" );
 return $buttons;
}

/**
Register TinyMCE Plugin
*/

function gigx_add_plugin( $plugin_array ) {
   $plugin_array['box'] = get_bloginfo( 'template_url' ) . '/js/gigx_box_button.js';
   return $plugin_array;
}


?>