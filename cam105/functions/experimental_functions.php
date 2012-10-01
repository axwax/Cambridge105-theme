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
  
#### custom search form ####
function gigx_search_form( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'gigx_search_form' );

///////////
// new functions 2012-10-01

/* remove tags from posts */
function unregister_taxonomy(){
    //register_taxonomy('post_tag', array());
}
add_action('init', 'unregister_taxonomy');

# custom mail sender:
/**
* How to change the user notification email address for WordPress the proper way
*/
add_filter('wp_mail_from', 'my_custom_mail_from');
add_filter('wp_mail_from_name', 'my_custom_mail_from_name');

function my_custom_mail_from($email) {
return "me@mysite.com";
}

function my_custom_mail_from_name($name) {
return "John Doe";
}

######### media uploader

## add new tab
## http://axcoto.com/blog/article/tag/media_upload_tabs
    function axcoto_genify_media_menu($tabs) {
    $newtab = array('genify' => __('Axcoto Genify', 'axcotogenify'));
    return array_merge($tabs, $newtab);
    }
    //add_filter('media_upload_tabs', 'axcoto_genify_media_menu');
    
    # Must start with media, otherwise css won't get loaded
    function media_axcoto_genify_process() {
    media_upload_header();
    echo 'hello';
    }
    function axcoto_genify_menu_handle() {
	    return wp_iframe( 'media_axcoto_genify_process');
    }
    //add_action('media_upload_genify', 'axcoto_genify_menu_handle');

## remove existing tabs
## http://shibashake.com/wordpress-theme/how-to-hook-into-the-media-upload-popup-interface
    //add_filter('media_upload_tabs', 'my_plugin_image_tabs', 10, 1);
     
    function my_plugin_image_tabs($_default_tabs) {
	    unset($_default_tabs['type']);
	    unset($_default_tabs['type_url']);
	    unset($_default_tabs['gallery']);
     
	    return($_default_tabs);	
    }


## new action button
    //add_filter('attachment_fields_to_edit', 'my_plugin_action_button', 20, 2);
    function my_plugin_action_button($form_fields, $post) {
     
	    $send = "<input type='submit' class='button' name='send[$post->ID]' value='" . esc_attr__( 'Upload to Rackspace Cloud' ) . "' />";
     
	    $form_fields['buttons'] = array('tr' => "\t\t<tr class='submit'><td></td><td class='savesend'>$send</td></tr>\n");
	    $form_fields['context'] = array( 'input' => 'hidden', 'value' => 'shiba-gallery-default-image' );
	    return $form_fields;
    }
    
## action to execute
    //add_filter('media_send_to_editor', 'my_plugin_image_selected', 10, 3); 
    function my_plugin_image_selected($html, $send_id, $attachment) {
	    ?>
	    <script type="text/javascript">
	    /* <![CDATA[ */
	    var win = window.dialogArguments || opener || parent || top;
     
	    win.jQuery( '#title' ).val('<?php echo $send_id;?>');
	    // submit the form
	    //win.jQuery( '#shiba-gallery_options' ).submit();
	    /* ]]> */
	    </script>
	    <?php
	    echo "yay";
	    exit();
    }  
