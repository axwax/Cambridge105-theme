<?php
/*
File Description: Theme Functions
Built By: GIGX
Theme Version: 0.6.1
*/

//error_reporting(E_ALL);

# default variables
if ( ! isset( $content_width ) ) $content_width = 640; 

# include components
include 'functions/gigx_widgets.php';
include 'functions/gigx_shortcodes.php';
include 'functions/gigx_editor_buttons.php';   
if (!function_exists('gigx_excerpt')) include 'functions/gigx_excerpt.php';

# set up action hooks
/** Tell WordPress to run gigx_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'gigx_setup' );

# admin style
function gigx_admin_style() {
    $url = get_bloginfo('stylesheet_directory') . '/css/admin.css';
    echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}
add_action('admin_head', 'gigx_admin_style');




### 
## no howdy
/**
 * replace WordPress Howdy in WordPress 3.3
 */
function replace_howdy( $wp_admin_bar ) {
	$my_account=$wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy,', 'Logged in as', $my_account->title );            
    $wp_admin_bar->add_node( array(
        'id' => 'my-account',
        'title' => $newtitle,
    ) );
}
add_filter( 'admin_bar_menu', 'replace_howdy',25 );
## eo no howdy

#####
#upload filetypes
function gigx_mime_types($mime_types){
	$mime_types['xml'] = 'application/xml'; //Adding avi extension
	return $mime_types;
}
add_filter('upload_mimes', 'gigx_mime_types', 1, 1);

 
# gigx_setup()
# Sets up theme defaults and registers support for various WordPress features.  

if ( ! function_exists('gigx_setup') ):
    /**
     * To override gigx_setup() in a child theme, add your own gigx_setup to your child theme's
     * functions.php file.
     *
     * @uses add_theme_support() To add support for post thumbnails, navigation menus, and automatic feed links.
     * @uses add_custom_background() To add support for a custom background.
     * @uses add_editor_style() To style the visual editor.
     * @uses add_custom_image_header() To add support for a custom header.
     * @uses register_default_headers() To register the default custom header images provided with the theme.
     * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
     */
    function gigx_setup() {
        # excerpt for pages
        add_post_type_support( 'page', 'excerpt' );
      
      	// This theme styles the visual editor with editor-style.css to match the theme style.
      	add_editor_style();
      
      	// This theme uses post thumbnails
      	add_theme_support( 'post-thumbnails' );
      	
      	// add specific post thumbnail size
      	add_image_size( 'gigx-nav-thumbnail', 100, 100, true );
      
        # wp3 menus
      	// This theme uses wp_nav_menu()
      	add_theme_support( 'nav-menus' );
        register_nav_menu('above-header', 'Navigation Menu above header');
        register_nav_menu('below-header', 'Navigation Menu below header');
        register_nav_menu('above-posts', 'Navigation Menu above posts');
        function header_menu() {
            # if no menu is selected in the backend, output nothing
          	//echo '<div class="header-menu"><ul><li><a href="'.get_bloginfo('url').'">Home</a></li>';
          	//wp_list_categories('title_li=&depth=1&number=5');
          	//echo '</ul></div>';
        }
        
      	// Add default posts and comments RSS feed links to head
      	add_theme_support( 'automatic-feed-links' );
      
      	// This theme allows users to set a custom background
      	add_custom_background();
      
      	// Your changeable header business starts here
      	define( 'HEADER_TEXTCOLOR', '' );
      	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
      	define( 'HEADER_IMAGE', apply_filters( 'gigx_header_image', '%s/images/headers/default.png' ) );
      
      	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
      	// Add a filter to gigx_header_image_width and gigx_header_image_height to change these values.
      	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'gigx_header_image_width', 1000 ) );
      	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'gigx_header_image_height', 120 ) );
      
      	// We'll be using post thumbnails for custom header images on posts and pages.
      	// We want them to be 940 pixels wide by 120 pixels tall (larger images will be auto-cropped to fit).
      	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );
      
      	// Don't support text inside the header image.
      	define( 'NO_HEADER_TEXT', true );
      
      	// Add a way for the custom header to be styled in the admin panel that controls
      	// custom headers. See gigx_admin_header_style(), below.
      	add_custom_image_header( '', 'gigx_admin_header_style' );
      
      	// ... and thus ends the changeable header business.
      
      	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
        /*
      	register_default_headers( array (
      		'sunset' => array (
      			'url' => '%s/images/headers/sunset.jpg',
      			'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
      			'description' => __( 'Sunset', 'gigx' )
      		)
      	) );
        */	
    }
endif;

# gigx_admin_header_style()
# Styles the header image displayed on the Appearance > Header admin panel.
# Referenced via add_custom_image_header() in gigx_setup().

if ( ! function_exists( 'gigx_admin_header_style' ) ) :
    function gigx_admin_header_style() {
        ?>
        <style type="text/css">
        #headimg {
        	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
        }
        #headimg h1, #headimg #desc {
        	display: none;
        }
        </style>
        <?php
    }
endif;

# current_jquery($version)
# loads required jquery version from google
function current_jquery($version) {
    global $wp_scripts;
    if ( ( version_compare($version, $wp_scripts -> registered[jquery] -> ver) == 1 ) && !is_admin() ) {
        wp_deregister_script('jquery');  
        wp_register_script('jquery',
            'http://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js',
            false, $version);
    }
}

# custom admin footer
add_filter( 'admin_footer_text', 'gigx_admin_footer_text' );

if ( ! function_exists( 'gigx_admin_footer_text' ) ) :
	function gigx_admin_footer_text( $default_text ) {
        $child_theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
        $parent_theme_data = get_theme_data( get_template_directory() . '/style.css' );
        
		return '<span id="footer-thankyou">Thank you for creating with <a href="http://wordpress.org/" target="_blank">WordPress</a>. '. $child_theme_data['Title'] . ' Theme v' . $child_theme_data['Version'] .' is based on '. $parent_theme_data['Title'] . ' Theme v' . $parent_theme_data['Version'] .' and created by:
<a href="http://gigx.co.uk/"><img width="36" height="11" title="GIGX.co.uk" alt="GIGX.co.uk" src="'. get_template_directory_uri() .'/images/gigx-logo36x11.png">
</a></span>';
	}
endif;

# gigx_comment ( $comment, $args, $depth )
# comments function
if ( ! function_exists( 'gigx_comment' ) ) :
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own gigx_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since 3.0.0
     */
    function gigx_comment( $comment, $args, $depth ) {
    	$GLOBALS ['comment'] = $comment; ?>
    	<?php if ( '' == $comment->comment_type ) : ?>
    	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    		<div id="comment-<?php comment_ID(); ?>">
    		<div class="comment-author vcard">
    			<?php echo get_avatar( $comment, 40 ); ?>
    			<?php printf( '<cite class="fn">%s</cite> <span class="says">says:</span>', get_comment_author_link() ); ?>
    		</div>
    		<?php if ( $comment->comment_approved == '0' ) : ?>
    			<em>Your comment is awaiting moderation.</em>
    			<br />
    		<?php endif; ?>
    
    		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( '%1$s at %2$s', get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( '(Edit)', ' ' ); ?></div>
    
    		<div class="comment-body"><?php comment_text(); ?></div>
    
    		<div class="reply">
    			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
    		</div>
    	</div>
    
    	<?php else : ?>
    	<li class="post pingback">
    		<p>Pingback: <?php comment_author_link(); ?><?php edit_comment_link ( '(Edit)', ' ' ); ?></p>
    	<?php endif;
    }
endif;

if ( ! function_exists( 'gigx_find_image_attachment' ) ) :
/**
 * Finds the first attached image for a given post.
 * @author   Axel Minet <axel@gigx.co.uk>
 * @version  2012-02-16
 * @param int $post_id The Post's ID
 * @return int The ID of the first attached image. 
 **/
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
endif;

if ( ! function_exists( 'gigx_unregister_widgets' ) ) :
/**
 * Allows to unregister default and custom widgets.
 *
 * Removes all default widgets by default, but allows to define exceptions as well as custom widgets to remove.
 * Usage Example:
 * add_filter('gigx_add_default_widgets', function($widgetsToAdd) { return array('WP_Widget_Tag_Cloud','WP_Widget_Text');});
 * add_filter('gigx_remove_custom_widgets', function($widgetsToRemove) { return array('gigx_custom_title','Shiba_Widget_Author');});
 * @author   Axel Minet <axel@gigx.co.uk>
 * @version  2012-02-16
 **/
    function gigx_unregister_widgets() {
        $widgetsNotToRemove = apply_filters( 'gigx_add_default_widgets', array() );
        $otherWidgetsToRemove = apply_filters( 'gigx_remove_custom_widgets', array() );
        $defaultWidgets = array(
            'WP_Widget_Pages',
            'WP_Widget_Calendar',
            'WP_Widget_Archives',
            'WP_Widget_Links',
            'WP_Widget_Meta',
            'WP_Widget_Search',
            'WP_Widget_Categories',
            'WP_Widget_Recent_Posts',
            'WP_Widget_Recent_Comments',
            'WP_Widget_RSS',
            'WP_Widget_Text',
            'WP_Widget_Tag_Cloud',
        );
        $widgetsToRemove = array_merge($defaultWidgets, $otherWidgetsToRemove);
        $widgetsToRemove = array_diff($widgetsToRemove,$widgetsNotToRemove);
        foreach ($widgetsToRemove as $widgetToRemove) {
            unregister_widget($widgetToRemove);
        }
    }
    add_action('widgets_init', 'gigx_unregister_widgets', 11);
endif;

if ( ! function_exists( 'gigx_unregister_dashboard_widgets' ) ) :
/**
 * Allows to unregister default and custom dashboard widgets.
 *
 * Removes all default widgets by default, but allows to define exceptions as well as custom widgets to remove.
 * Usage Example:
 * add_filter('gigx_add_default_dashboard_widgets', function($widgetsToAdd) { return array('WP_Widget_Tag_Cloud','WP_Widget_Text');});
 * add_filter('gigx_remove_custom_dashboard_widgets', function($widgetsToRemove) { return array('gigx_custom_title','Shiba_Widget_Author');});
 * @author   Axel Minet <axel@gigx.co.uk>
 * @version  2012-02-16
 **/
    function gigx_unregister_dashboard_widgets() {
        $widgetsNotToRemove = apply_filters( 'gigx_add_default_dashboard_widgets', array() );
        $otherWidgetsToRemove = apply_filters( 'gigx_remove_custom_dashboard_widgets', array() );
        $defaultWidgets = array(
            'BrowserNag' => array('dashboard_browser_nag', 'dashboard', 'normal'),             
            'RightNow' => array('dashboard_right_now', 'dashboard', 'normal'),             
            'RecentComments' => array('dashboard_recent_comments', 'dashboard', 'normal'),             
            'IncomingLinks' => array('dashboard_incoming_links', 'dashboard', 'normal'),             
            'Plugins' => array('dashboard_plugins', 'dashboard', 'normal'),             
            'QuickPress' => array('dashboard_quick_press', 'dashboard', 'side'),
            'RecentDrafts' => array('dashboard_recent_drafts', 'dashboard', 'side'),
            'WordPressBlog' => array('dashboard_primary', 'dashboard', 'side'),
            'OtherWordPressNews' => array('dashboard_secondary', 'dashboard', 'side'),
        );
        foreach ($widgetsNotToRemove as $value){
            $widgetsNotToRemoveMulti[$value]=$defaultWidgets[$value];
        }
      
        $widgetsToRemove = array_merge($defaultWidgets, $otherWidgetsToRemove);
        //print_r($widgetsToRemove);
        //print_r($widgetsNotToRemoveMulti);
        $widgetsToRemove = array_diff_assoc($widgetsToRemove,$widgetsNotToRemoveMulti);
        //print_r($widgetsToRemove);
        //exit;        
        foreach ($widgetsToRemove as $widgetToRemove => $widgetParams) {
            list($widgetName, $page, $context) = $widgetParams;
            remove_meta_box($widgetName, $page, $context);
        }
    }
    add_action('wp_dashboard_setup', 'gigx_unregister_dashboard_widgets', 11);
endif;

####
# Custom Dashboard Widget

function add_gigx_dashboard_widget() {
   
   // add the widget
	wp_add_dashboard_widget('gigx_dashboard_widget', 'GIGX Help and Support', 'gigx_dashboard_widget_function');
	
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;                                                                	
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashboard widget from the end of the array
	$gigx_dashboard_widget_backup = array('gigx_dashboard_widget' => $normal_dashboard['gigx_dashboard_widget']);
	unset($normal_dashboard['gigx_dashboard_widget']);

	// Merge the two arrays together so our widget is at the beginning
   $sorted_dashboard = array_merge($gigx_dashboard_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 
   $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
} 
add_action('wp_dashboard_setup', 'add_gigx_dashboard_widget',12 );

# Display a default dashboard widget if there isn't one defined in child theme
if (!function_exists ('gigx_dashboard_widget_function')){
  function gigx_dashboard_widget_function(){
    echo "yay";
  }   
}


?>