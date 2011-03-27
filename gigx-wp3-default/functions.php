<?php
/*
File Description: Theme Functions
Built By: GIGX
Theme Version: 0.5.9.9
*/

//error_reporting(E_ALL);
# default variables
if ( ! isset( $content_width ) ) $content_width = 640; //not used yet
if ( ! isset( $jquery_version ) ) $jquery_version='1.4.2'; // change number to latest version

# include components
include 'gigx_widgets.php';
//include 'gigx_settings.php';
include 'gigx_shortcodes.php';
include 'gigx_editor_buttons.php';   

# set up action hooks
/** Tell WordPress to run gigx_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'gigx_setup' );

# load jquery version defined above
//add_action( 'wp_head', current_jquery( $jquery_version ) );

# admin style
function gigx_admin_style() {
	//wp_enqueue_style( TEMPLATEPATH . '/admin.css' );
	$url = get_bloginfo('stylesheet_directory') . '/admin.css';
	echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}
add_action('admin_head', 'gigx_admin_style');

function your_dashboard_widget() {

 # Widget content goes here #
echo 'yay! dashboard widget!';
}

function add_your_dashboard_widget() {
  wp_add_dashboard_widget( 'your_dashboard_widget', 'my first dashboard widget', 'your_dashboard_widget' );
}
add_action('wp_dashboard_setup', 'add_your_dashboard_widget' );

### 
## no howdy

// Customize:
$nohowdy = "Logged in as ";

// Hook in
if (is_admin()) {
	add_action('init', 'ozh_nohowdy_h');
	add_action('admin_footer', 'ozh_nohowdy_f');
}

// Load jQuery
function ozh_nohowdy_h() {
	wp_enqueue_script('jquery');
}

// Modify
function ozh_nohowdy_f() {
global $nohowdy;
echo <<<JS
<script type="text/javascript">
//<![CDATA[
var nohowdy = "$nohowdy";
jQuery('#user_info p')
	.html(
	jQuery('#user_info p')
		.html()
		.replace(/Howdy,/,nohowdy)
	);
//]]>
JS;
}
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
      	define( 'HEADER_IMAGE', '%s/images/headers/default.png' );
      
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

?>