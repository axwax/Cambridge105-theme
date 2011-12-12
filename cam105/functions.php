<?php
/*
File Description: Cambridge105 custom functions
Author: Axel Minet
Theme Version: 0.5.11
*/

# unregister custom post types
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
unregister_post_type('site');

*/

# remove custom taxonomies
function remove_taxonomy($taxonomy) {
	if (!$taxonomy->_builtin) {
		global $wp_taxonomies;
		$terms = get_terms($taxonomy); 
		foreach ($terms as $term) {
			wp_delete_term( $term->term_id, $taxonomy );
		}
		unset($wp_taxonomies[$taxonomy]);
	}
}
//remove_taxonomy('genres');

# include custom post types
include 'gigx_cpt_shows.php';
#include 'gigx_cpt_schedule.php';
#include 'gigx_cpt_links.php';

# posts to posts stuff #
function my_connection_types() {
	// Make sure the Posts 2 Posts plugin is active.
	if ( !function_exists( 'p2p_register_connection_type' ) )
		return;

	p2p_register_connection_type( array(
		'id' => 'posts_to_shows',
		'from' => 'post',
		'to' => 'shows',
		'cardinality' => 'many-to-one',
		'reciprocal' => false
	) );
}
add_action('init', 'my_connection_types', 100);


# presenters stuff
/* 
include 'gigx_cpt_presenters.php';
# posts to posts shows<->presenters #
function my_connection_types() {
if ( !function_exists('p2p_register_connection_type') )
return;
p2p_register_connection_type( 'shows', 'presenters' );
}
add_action('init', 'my_connection_types', 100);
*/

# add shows image and thumbnail size
	add_image_size( 'shows-image', 300, 225, true );
	add_image_size( 'shows-thumb', 100, 100, true );

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
# untested stuff:
#

# remove admin bar
    //add_filter('show_admin_bar', '__return_false');

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
//unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

# custom xml-rpc handler
/*
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
*/

### Default editor content
/*
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
*/

# style select box for editor
/* Custom CSS styles on WYSIWYG Editor */
add_filter('gigx_editor_styles','cam105_editor_styles');
function cam105_editor_styles($styles){
$styles.=',105Box=cam105_box';
return $styles;
}


# remove columns from all posts screen
/*
    add_filter('manage_posts_columns', 'scompt_custom_columns');
    function scompt_custom_columns($defaults) {
    //unset($defaults['comments']);
    //unset($defaults['author']);
            //print_r($defaults);
    return $defaults;
    }
*/

# remove annoying "Robots Meta" columns that WP SEO puts in
/*
    remove_filter( 'manage_page_posts_columns',array($wpseo_metabox,'page_title_column_heading'), 10, 1 );
    remove_filter( 'manage_post_posts_columns',array($wpseo_metabox,'page_title_column_heading'), 10, 1 );
    remove_action( 'manage_pages_custom_column',array($wpseo_metabox,'page_title_column_content'), 10, 2 );
    remove_action( 'manage_posts_custom_column',array($wpseo_metabox,'page_title_column_content'), 10, 2 );
*/

/* meta boxes */
/*
    include_once get_stylesheet_directory() . '/MetaBox.php';
    
    include_once get_stylesheet_directory() . '/MediaAccess.php';
    
    // global styles for the meta boxes
    if (is_admin()) wp_enqueue_style('wpalchemy-metabox', get_stylesheet_directory_uri() . '/meta.css');
    
    $wpalchemy_media_access= new WPAlchemy_MediaAccess();
    
    $podcast_mb = new WPAlchemy_MetaBox(array
    (
            'id' => '_podcast_mb',
            'title' => 'Podcast Episode2',
            'types' => array('shows'), // added only for pages and to custom post type "events"
            'context' => 'normal', // same as above, defaults to "normal"
            'priority' => 'high', // same as above, defaults to "high"	
            'template' => get_stylesheet_directory() . '/podcast-mb.php',
    ));
*/
/* podcast menu button test */
add_action('admin_menu', 'register_podcast_submenu_page');

function register_podcast_submenu_page() {
	add_submenu_page( 'edit.php?post_type=shows', 'New Podcast', 'New Podcast', 'edit_posts', 'add_podcast', 'add_podcast_callback' ); 
}

function add_podcast_callback() {
	global $user_ID;
	//$category = $_get['cat']; // get the category from the url
	//echo "add podcast".$user_ID.':';
	$category=13;
	if (!$_GET['submit']) {
	?>
	<div class="wrap">
        <div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2>Add New Podcast</h2>
	<div id="poststuff" class="metabox-holder" style="width:50%">
	<div class="postbox">
	    <div class="handlediv" title="Click to toggle"><br/></div>
	    <h3 class="hndle"><span>New Podcast</span></h3>
	    <div class="inside">
		<form action="<?php echo get_admin_url('','edit.php'); ?>" method="get">
   		<input type="hidden" name="post_type" value="shows" />
   		<input type="hidden" name="page" value="add_podcast" />
   		<?php wp_dropdown_pages(array('post_type' => 'shows')); ?>
		<input type="text" class="scheduledate" name="podcast_date" value="<?php echo date("D, M d, Y"); ?>"/>
   		<input type="submit" name="submit" value="Add Podcast" />
   		</form>
	    </div>	
	</div>
	</div>
	</div>
	<? }
	else {
		$showID=$_GET['page_id'];
		$show_title=get_post($showID)->post_title;
		$show_slug=get_post($showID)->post_name;
		$show_thumb=get_post_thumbnail_id( $showID );
		$post = array( // add a new post to the wordpress database
			'post_title' => $show_title.' '.date('d/m/Y',time()),
			'post_name' => $show_slug.'-'.date('d-m-Y',time()),
			'post_status' => 'draft', // set post status to draft - we don't want the new post to appear live yet.
			'post_date_gmt' => date('Y-m-d H:i:s',time()),
			'post_date' => get_date_from_gmt( date('Y-m-d H:i:s',time()) ),
			'post_author' => $user_ID, // set post author to current logged on user.
			'post_type' => 'post', // set post type to post.
			'post_category' => array(get_cat_ID( 'Podcasts' )) // set category to the category/categories parsed in your previous array
		);
	
		$insert_post = wp_insert_post($post,true); // insert the post into the wp db
	//print_r($insert_post);
		$post_details = get_post($insert_post); // get all the post details from new post
	//print_r($post_details);
		$post_id = $post_details->ID; // extract the post id from the post details
		update_post_meta($post_id, '_thumbnail_id', $show_thumb);
		$connected = p2p_type( 'posts_to_shows' )->get_connected( get_queried_object_id() );
		//echo "post:$post_id show:$showID connected:$connected";
		//echo "type:".p2p_type('posts_to_shows')->connect($post_id, $showID);
		$post_redirect = 'http://'.$_SERVER['SERVER_NAME'].'/wp-admin/post.php?action=edit&post='.$post_id; // construct url for editing of post
	//echo 'ax'.$post_id.$post_redirect;
		echo '<div class="podcast_form">Podcast post created. <a href="'.$post_redirect.'">Click here to edit</a></div>';
		wp_redirect($post_redirect);// redirect to edit page for new post.
		exit;
	}

}

# enqueue jquery UI for podcasts
function schedule_styles() {
    //global $post_type;
    //if( 'gigx_schedule' != $post_type ) return;
    //wp_enqueue_style('ui-datepicker', get_bloginfo('stylesheet_directory') . '/css/jquery-ui-1.8.9.custom.css');
    wp_enqueue_style('jquery.ui.theme', get_bloginfo('stylesheet_directory') . '/css/smoothness/jquery-ui-1.8.16.custom.css');
}

function schedule_scripts() {
    //global $post_type;
    //if( 'gigx_schedule' != $post_type ) return;
    #wp_deregister_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui', get_bloginfo('stylesheet_directory') . '/js/jquery-ui-1.8.9.custom.min.js', array('jquery'));
    wp_enqueue_script('ui-datepicker', get_bloginfo('stylesheet_directory') . '/js/jquery.ui.datepicker.min.js',array('jquery-ui'));
    wp_enqueue_script('ui-autocomplete', get_bloginfo('stylesheet_directory') . '/js/jquery.ui.autocomplete.min.js',array('jquery-ui'));
    wp_enqueue_script('gigx_podcast', get_bloginfo('stylesheet_directory').'/js/gigx-admin.js', array('jquery'));
}

add_action( 'admin_init', 'schedule_styles', 1000 );
//add_action( 'admin_print_styles-post-new.php', 'schedule_styles', 1000 );

add_action( 'admin_init', 'schedule_scripts', 1000 );
//add_action( 'admin_print_scripts-post-new.php', 'schedule_scripts', 1000 );
# end podcasts

// sort shows alphabetically 
add_filter('posts_orderby', 'shows_alphabetical' );

function shows_alphabetical( $orderby )
{
  if( !is_admin() && is_post_type_archive( "shows" ) ){
  // alphabetical order by post title
     return "post_title ASC";
  }
}  


/* shows slider test */
	function gigx_head() {
		if( !is_admin() ) {
			$queued = true;
			$url = plugin_dir_url( __FILE__ );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'gigx-caroufredsel-js', get_bloginfo('stylesheet_directory'). '/js/jquery.carouFredSel-5.2.3-packed.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-shows-slides-js', get_bloginfo('stylesheet_directory').'/js/gigx-shows-slides.js', array ('gigx-caroufredsel-js'), '0.1', true );
			wp_enqueue_script( 'gigx-syncheight-js', get_bloginfo('stylesheet_directory').'/js/jquery.syncheight.min.js', array('jquery'), false, false );
                        //wp_enqueue_script( 'snowstorm-js', get_bloginfo('stylesheet_directory').'/js/snowstorm.js', array (), '0.1', true );

		}
	}
	function gigx_footer() {
	global $queued;
		if( $queued ) {
			wp_deregister_script( 'gigx-caroufredsel-js' );
			wp_deregister_script( 'gigx-shows-slides-js' );
                        wp_deregister_script( 'gigx-syncheight-js' );
		}
	}
		add_action( 'wp_head', 'gigx_head' , 1 );
		add_action( 'wp_footer', 'gigx_footer', 2 );
  
/* fix for custom post type tag archives */               
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
    $post_types = get_post_types();
    if ( is_category() || is_tag()) {

        $post_type = get_query_var('post_type');
        //print_r($post_type);
    
        if ( $post_type )
            $post_type = $post_type;
        else
            $post_type = $post_types;

        $query->set('post_type', $post_type);

    return $query;
    }
}

/* remove top-level menu entry for gigx-slides */
add_action('admin_menu', 'remove_niggly_bits');
function remove_niggly_bits() {
    global $submenu;
    //unset($submenu['edit.php?post_type=portfolio'][11]);
    //print_r($submenu); exit;
}
add_action( 'admin_menu', 'gigx_remove_menus', 999 );

function gigx_remove_menus() {

	//remove_submenu_page( 'edit.php', 'post_type=gigx_slide' );

}
