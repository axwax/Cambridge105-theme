<?php
//Original Framework http://theundersigned.net/2006/06/wordpress-how-to-theme-options/ 
//Updated and added additional options by Jeremy Clark
//http://clark-technet.com
//Updated and added additional options by Mike Pippin -9/11/2009
//http://split-visionz.net/2009/wordpress-theme-options-framework-updated/

$themename = "GIGX Default";			//This should be the full name of your theme
$shortname = "gigx_default";			//This should be a shortened version of your theme name
	include (TEMPLATEPATH . "/options/gigx_options_array.php");

function gigx_add_options() {
global $themename, $shortname, $options;
foreach ($options as $value) {
	$key = $value['id'];
	$val = $value['std'];
		if( $existing = get_option($key)){ 	//This is useful if you've used a previous version that added seperate values to wp_options
			$new_options[$key] = $existing; //This will add the value to the array
			delete_option($key); 		//This deletes the old entry and cleans up the wp_option table
		} else {
			$new_options[$key] = $val; 
			delete_option($key);
		}
}
add_option($shortname.'_options', $new_options );
}

function first_run_options() {				//This is for theme init
global $shortname;
$check = get_option($shortname.'_activation_check');
	if ( $check != "set" ) {
		gigx_add_options();			//This runs the theme init fuction specified eariler
   		add_option($shortname.'_activation_check', "set");	// Add marker so it doesn't run in future
  	}
  delete_option($shortname.'_activation_check');	
}
add_action('wp_head', 'first_run_options');
add_action('admin_head', 'first_run_options');

function gigx_add_admin() {
    global $themename, $shortname, $options;
	$settings = get_option($shortname.'_options');
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['action'] ) {
		foreach ($options as $value) {
			if(($value['type'] === "checkbox" or $value['type'] === "multiselect" ) and is_array($_REQUEST[ $value['id'] ]))
				{ $_REQUEST[ $value['id'] ]=implode(',',$_REQUEST[ $value['id'] ]); //This will take from the array and make one string
				}
			$key = $value['id']; 
			$val = $_REQUEST[$key];
			$settings[$key] = $val;
		}
update_option($shortname.'_options', $settings);                   
header("Location: themes.php?page=controlpanel.php&saved=true");
                die;
        } else if( 'reset' == $_REQUEST['action'] ) {
		foreach ($options as $value) {
			$key = $value['id'];
			$std = $value['std'];
			$new_options[$key] = $std;
		}
update_option($shortname.'_options', $new_options );
            header("Location: themes.php?page=controlpanel.php&reset=true");
            die;
        }
    }
    //add_theme_page($themename." Options", "Current Theme Options", 'edit_themes', basename(__FILE__), 'gigx_admin');
    add_menu_page($themename." Options", "GIGX Options", 'edit_themes', 'gigx_main_options','',get_bloginfo('template_directory').'/images/gigx_settings_icon.png',61);
	  add_submenu_page('gigx_main_options' ,$themename." General Options", "General Options", 'edit_themes', 'gigx_main_options', 'gigx_general_options');
}
function gigx_general_options() {
	include_once(TEMPLATEPATH . '/options/gigx_general_options.php');
}

add_action('admin_menu', 'gigx_add_admin'); 
?>
