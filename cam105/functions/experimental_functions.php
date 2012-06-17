<?php


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

################
# untested stuff:
#



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

/* shows slider test */
	function gigx_head() {
		if( !is_admin() ) {
			$queued = true;
			$url = plugin_dir_url( __FILE__ );
			//wp_enqueue_script( 'jquery' );
			//wp_enqueue_script( 'gigx-caroufredsel-js', get_bloginfo('stylesheet_directory'). '/js/jquery.carouFredSel-5.2.3-packed.js', array( 'jquery' ), '1.4', true );
			//wp_enqueue_script( 'gigx-shows-slides-js', get_bloginfo('stylesheet_directory').'/js/gigx-shows-slides.js', array ('gigx-caroufredsel-js'), '0.1', true );
			//wp_enqueue_script( 'gigx-syncheight-js', get_bloginfo('stylesheet_directory').'/js/jquery.syncheight.min.js', array('jquery'), false, false );
                        //wp_enqueue_script( 'snowstorm-js', get_bloginfo('stylesheet_directory').'/js/snowstorm.js', array (), '0.1', true );

		}
	}
	function gigx_footer() {
	global $queued;
		if( $queued ) {
			//wp_deregister_script( 'gigx-caroufredsel-js' );
			//wp_deregister_script( 'gigx-shows-slides-js' );
                        //wp_deregister_script( 'gigx-syncheight-js' );
		}
	}
		add_action( 'wp_head', 'gigx_head' , 1 );
		add_action( 'wp_footer', 'gigx_footer', 2 );

/* remove top-level menu entry for gigx-slides */
add_action('admin_menu', 'remove_niggly_bits');
function remove_niggly_bits() {
    global $menu;
    global $submenu;
    //unset($submenu['edit.php?post_type=portfolio'][11]);
    //print_r($menu); print_r($submenu); exit;
}
add_action( 'admin_menu', 'gigx_remove_menus', 999 );

function gigx_remove_menus() {

	//remove_submenu_page( 'edit.php', 'post_type=gigx_slide' );

}
                