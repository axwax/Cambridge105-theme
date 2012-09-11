<?php
function gigx_help( $contextual_help, $screen_id) {
    
   # Uncomment this to see actual screen
   //echo 'Screen ID = '.$screen_id.'<br />';
     
    switch( $screen_id ) {
        case 'tools' :
     // To add a whole tab group
            get_current_screen()->add_help_tab( array(
            'id'        => 'my-help-tab',
            'title'     => __( 'How to get started?' ),
            'content'   => __( 'Put any text here bla bla bla ....' )
            ) );
            
            break;
        case 'mi_plugin_page' :
            //Just to modify text of first tab
            $contextual_help .= '<p>';
            $contextual_help = __( 'Your text here.' );
            $contextual_help .= '</p>';
            break;
    }
    return $contextual_help;
}
add_filter('contextual_help', 'gigx_help', 10, 2);

?>