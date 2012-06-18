<?php
/*
File Description: custom buttons for shortcodes in TinyMCE editor
Built By: GIGX
Theme Version: 0.6.2
*/

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

###
# style select box for editor
/* Custom CSS styles on WYSIWYG Editor */
if (!function_exists ("gigx_editor_styles_function")){
  function gigx_editor_styles_function ($init) {
    $init['theme_advanced_buttons2_add_before'] = 'styleselect';

    $default_styles='Box=gigx_box';
    $styles=apply_filters( 'gigx_editor_styles', $default_styles ) ;
    $init['theme_advanced_styles'] = $styles;
    return $init;
  }
}
add_filter('tiny_mce_before_init', 'gigx_editor_styles_function');
?>
