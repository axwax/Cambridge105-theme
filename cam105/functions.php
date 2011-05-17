<?php



/*
if ( ! function_exists( 'unregister_post_type' ) ) :
function unregister_post_type( $post_type ) {
	global $wp_post_types;
	if ( isset( $wp_post_types[ $post_type ] ) ) {
		unset( $wp_post_types[ $post_type ] );
		return true;
	}
	return false;
}
endif;

unregister_post_type('gigx_schedule');
unregister_post_type('schedule');
unregister_post_type('shows');
*/

# include custom post types
include 'gigx_cpt_shows.php';
include 'gigx_cpt_schedule.php';
# posts to posts stuff #

function my_connection_types() {
    if ( !function_exists('p2p_register_connection_type') )
        return;
    $args=array('from'=>'gigx_schedule',
                'to'=>'shows',
                'title'=>'Select Show',
                'reciprocal'=>false,
                'box'=>'P2P_Box_Multiple');    
    p2p_register_connection_type( $args );
}
add_action('init', 'my_connection_types', 100);


/* presenters stuff
include 'gigx_cpt_presenters.php';
# posts to posts stuff #
function my_connection_types() {
    if ( !function_exists('p2p_register_connection_type') )
        return;
    p2p_register_connection_type( 'shows', 'presenters' );
}
add_action('init', 'my_connection_types', 100);
*/

# add shows image and thumbnail size
      	add_image_size( 'shows-image', 200, 200, true );
      	add_image_size( 'shows-thumb', 75, 75, true );

# add facebook thumbnail size
      	add_image_size( 'facebook-thumb', 130, 130, true );
      	
      
# change header image size
add_filter('gigx_header_image_width','cam105_header_image_width');
add_filter('gigx_header_image_height','cam105_header_image_height');
function cam105_header_image_width($size){
   return 510;
}
function cam105_header_image_height($size){
   return 120;
}

# gigx functions:

# gigx_find_image_attachment($post_id=0)
# parameters: $post_id
# returns: first image attachment id
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

################
# untesteds stuff:
#

# remove admin bar
add_filter('show_admin_bar', '__return_false');

# remove default widgets
// unregister all default WP Widgets
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

# custom xml-rpc handler
add_filter('xmlrpc_methods', 'gigx_xmlrpc_methods');
function gigx_xmlrpc_methods($methods)
{
	$methods['cam105Shows'] = 'cam105_shows';
	return $methods;
}

function cam105_shows($args)
{
	// Parse the arguments, assuming they're in the correct order
	$username	= $args[0];
	$password	= $args[1];
	$data = $args[2];

	global $wp_xmlrpc_server;

	// Let's run a check to see if credentials are okay
	if ( !$user = $wp_xmlrpc_server->login($username, $password) ) {
		return $wp_xmlrpc_server->error;
	}

	// Let's gather the title and custom fields
	// At a later stage we'll send these via XML-RPC
	$title = $data["title"];
	$custom_fields = $data["custom_fields"];

	// Format the new post
	$new_post = array(
		'post_status' => 'draft',
		'post_title' => $title,
		'post_type' => 'shows',
	);

	// Run the insert and add all meta values
	$new_post_id = wp_insert_post($new_post);
	foreach($custom_fields as $meta_key => $values)
		foreach ($values as $meta_value)
			add_post_meta($new_post_id, $meta_key, $meta_value);

	// Just output something ;)
	return "Done!";
}


### uncheck comments tickbox by default:

function page_comments_off_please() {
//print_r($_REQUEST);
global $pagenow;
//echo"page:$pagenow";

	// This function checks if you are creating a new page and turns off
	// the allow comments / allow pingbacks check boxes.
	// it's a simple plugin, and my first.  Enjoy!
	// //
# axmod: we are adding a new page,post or custom post
if ($pagenow=="post-new.php"){
		// I couldn't fig up a hook that would give me $post object,
		// so a crude circumstantial work-around was to use the fact 
		// that wordpress sets the request var post_type = page on
		// new page creation.  
		// 
		// This may be triggered in other places, but the javascript 
		// will simply fail in that case.  Hopefully with some grace...
		// //
		# axmod: only disable for posts (no post_type set) and pages
		if (($_REQUEST['post_type'] == "page") || (!isset($_REQUEST['post_type']))) {  
			// A simple javascript unchecks the boxes for you.
			// I would have prefered something more php-oriented,
			// but I just coudln't find the right hooks to make it
			// happen reliably.  
			// //
			$fixit = <<<ENDIT
				<script>
					if (document.post) {
						var the_comment = document.post.comment_status;
						var the_ping = document.post.ping_status;
						if (the_comment && the_ping) {
							the_comment.checked = false;
							the_ping.checked = false;
						}
					}									
				</script>
ENDIT;
				echo $fixit;
		}
	}
}

add_action ( 'admin_footer', 'page_comments_off_please' );


### Default editor content

add_filter( 'default_content', 'gigx_editor_content' );

function gigx_editor_content( $content ) {
  global $pagenow;
	if ($pagenow=="post-new.php"){
	  if ((!isset($_REQUEST['post_type']))){  //only for pages
      $content = "<h2>This is the headline</h2>\n\r
                  <strong>This is the blurb</strong>\n\r
                  [[insert image here]]\n\r
                  text to go by the side of the image.\n\r
                  <!--more-->\n\r
                  This text only gets displayed in single view.";
    }
  }
	return $content;
}

# style select box for editor
/* Custom CSS styles on WYSIWYG Editor */
/* replaced by filter below
  function gigx_editor_styles_function ($init) {
    $init['theme_advanced_buttons2_add_before'] = 'styleselect';
    $init['theme_advanced_styles'] = 'Box=cam105_box';
    return $init;
  }
*/
add_filter('gigx_editor_styles','cam105_editor_styles');
function cam105_editor_styles($styles){
    $styles.=',105Box=cam105_box';
    return $styles;
}
  
?>