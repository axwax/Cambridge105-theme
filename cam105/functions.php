<?php
/*
File Description: Cambridge105 custom functions
Author: Axel Minet
Theme Version: 0.6.1
*/

# experimental and utility functions (eg unregister custom post types/taxonomies)
//include 'functions/experimental_functions.php';

# include custom post types
include 'functions/gigx_cpt_shows.php';

# posts to posts stuff #
# (linking podcasts to shows) #
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

# add shows image and thumbnail size
add_image_size( 'shows-image', 300, 225, true );
add_image_size( 'shows-thumb', 150, 112, true );

# add facebook thumbnail size
add_image_size( 'facebook-thumb', 130, 130, true );
	
# change header image size
add_filter('gigx_header_image_width', function($size) { return 510; });
add_filter('gigx_header_image_height', function($size) { return 120; });

# remove all default widgets except the ones specified
add_filter('gigx_add_default_widgets', function($widgetsToAdd) { return array('WP_Widget_Tag_Cloud','WP_Widget_Text');});
# remove specified custom widgets
add_filter('gigx_remove_custom_widgets', function($widgetsToRemove) { return array('gigx_custom_title','Shiba_Widget_Author');});

# remove all default dashboard widgets except the ones specified
add_filter('gigx_add_default_dashboard_widgets', function($widgetsToAdd) { return array('RightNow','IncomingLinks','RecentDrafts');});
# remove specified custom dashboard widgets
add_filter('gigx_remove_custom_dashboard_widgets', function($widgetsToRemove) { return array('PowerpressNews' => array('powerpress_dashboard_news', 'dashboard', 'normal'),
                                                                                             'PowerpressStats' => array('powerpress_dashboard_stats', 'dashboard', 'normal'),                                                                                             
                                                                                             'YoastDB' => array('yoast_db_widget', 'dashboard', 'side'),                                                                                             
                                                                                             'CForms' => array('cforms_dashboard', 'dashboard', 'normal')                                                                                            
                                                                                             );
                                                                              });

# add gigx dashboard widget      
function gigx_dashboard_widget_function(){
    ?>
    <p>Enjoy your new website. If you've got any questions or encounter any bugs, please contact me at <a href="mailto:axel@gigx.co.uk">axel@gigx.co.uk</a>.
    I will add a list of useful links and tutorials below, as well as any messages about updates.<br/><a href="http://axwax.de" target="_blank">Axel</a></p>    
    <?php wp_widget_rss_output('http://www.axwax.de/category/support/history/feed/', array('items' => 5, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 1));
}

################
# not fully tested stuff:
#

// remove wp seo menu (wp 3.3+)
function remove_yoast_seo_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wpseo-menu');
}
// and we hook our function via
add_action( 'wp_before_admin_bar_render', 'remove_yoast_seo_admin_bar' );
//wpseo-menu

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
		<input type="text" id="selectshow" name="select_show" value=""/>
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
		$author=get_userdatabylogin($show_slug);
		if (isset($author)) $authorID=$author->ID;
        if (!$authorID) $authorID=$user_ID;
		$post = array( // add a new post to the wordpress database
			'post_title' => $show_title.' '.date('d/m/Y',time()),
			'post_name' => $show_slug.'-'.date('d-m-Y',time()),
			'post_status' => 'draft', // set post status to draft - we don't want the new post to appear live yet.
			'post_date_gmt' => date('Y-m-d H:i:s',time()),
			'post_date' => get_date_from_gmt( date('Y-m-d H:i:s',time()) ),
			//'post_author' => $user_ID, // set post author to current logged on user.
			'post_author' => $authorID, // set post author to current logged on user.
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
	wp_enqueue_script( 'suggest' );
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



/* remove tags from posts */
function unregister_taxonomy(){
    register_taxonomy('post_tag', array());
}
add_action('init', 'unregister_taxonomy');

