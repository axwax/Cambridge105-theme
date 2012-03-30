<?php
/*
File Description: Widgets Collection
Built By: GIGX
Theme Version: 0.5.1
*/

####################
### GIGX widgets ###
### v0.2         ###
####################
//error_reporting(E_ALL);
# widgets to load / register

$widgetarray=	array(
                    'gigx_page_title', 
                    'gigx_post_title',
                    'gigx_post_date',
                    'gigx_post_author',
                    'gigx_post_author_image',
                    'gigx_custom_title',
                    'gigx_tabilizer',
                    'gigx_post_categories',
                   );


# widgets base directory
define('WIDGETS_BASE', 'widgets');

# initialise widget areas
add_action( 'init', 'gigx_widget_areas_init' );

# register widgets
add_action( 'widgets_init', 'gigx_widgets_register' );

# set up default widgets
function set_gigx_default_widgets( ) {
  //add_option( 'widget_categories', array( 'title' => 'My Categories' ));
  add_option("sidebars_widgets",
              array("above_entry_widgets" => array("categories","archives")
                    ));
}
add_action('populate_options', 'set_gigx_default_widgets'); 

#################
### functions ###
#################

# gigx_widget_areas_init()
# initialises widget bars 
function gigx_widget_areas_init() {
    $before_widget ='<div id="%1$s" class="widget %2$s">';
    $after_widget = '</div>';
    $before_title = '<h2 class="widget-title">';
    $after_title = '</h2>';
    if ( function_exists('register_sidebar') ) {
    	register_sidebar(array(
      	'id' => 'main_sidebar',
      	'name' => 'Main Sidebar',
    		'description' => 'The main widget area, most often used as a sidebar.',
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,		
    	));
    	
    	register_sidebar(array(
      	'id' => 'above_header_widgets',
    		'name' => 'Widgets Above Header',
    		'description' => 'Widget area above header.',		
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,
    	));
    	register_sidebar(array(
      	'id' => 'below_header_widgets',
    		'name' => 'Widgets Below Header',
    		'description' => 'Widget area below header.',		
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,
    	));

    	register_sidebar(array(
      	'id' => 'above_posts_widgets',
    		'name' => 'Widgets Above Posts',
    		'description' => 'These get displayed above the whole posts area.',				
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,
    	));
    	
    	register_sidebar(array(
      	'id' => 'above_entry_widgets',
    		'name' => 'Widgets Above Entries',
    		'description' => 'These get displayed above each entry.',				
    		'before_widget' => '',
    		'after_widget' => '',
    		'before_title' => '',
    		'after_title' => '',
    	));
    	register_sidebar(array(
      	'id' => 'below_entry_widgets',
    		'name' => 'Widgets Below Entries',
    		'description' => 'These get displayed below each entry.',				
    		'before_widget' => '',
    		'after_widget' => '',
    		'before_title' => '',
    		'after_title' => '',
    	));     	
    	
    	register_sidebar(array(
      	'id' => 'below_posts_widgets',
    		'name' => 'Widgets Below Posts',
    		'description' => 'These get displayed below the whole posts area.',				
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,
    	)); 
    	register_sidebar(array(
      	'id' => 'above_footer_widgets',
    		'name' => 'Widgets Above Footer',
    		'description' => 'Widget area above footer.',		
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,
    	));
    	register_sidebar(array(
      	'id' => 'below_footer_widgets',
    		'name' => 'Widgets Below Footer',
    		'description' => 'Widget area below footer.',		
    		'before_widget' => $before_widget,
    		'after_widget' => $after_widget,
    		'before_title' => $before_title,
    		'after_title' => $after_title,
    	));




  	

    }
}

####
# register widgets function
####
	function gigx_widgets_register() {
	global $widgetarray;
	$error='';
  foreach ($widgetarray as $value) {
      $widgetpath=dirname(__FILE__).'/'.WIDGETS_BASE.'/'.$value.'.php';
      if (file_exists  ($widgetpath)) {
      //$widgetpath= WIDGETS_BASE.'/'.$value.'.php';
      //if (is_file ($widgetpath)) {
          include ($widgetpath); 
          if (class_exists($value))register_widget($value);
          else $error .= 'Error: cannot register ' . $value . ' widget<br/>';
      }
      else $error .= 'Error: cannot load ' . $widgetpath . ' widget<br/>';
  }    
	if ($error) echo '<p class="error">'.$error.'</p>';   

	} 

?>
