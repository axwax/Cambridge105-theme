<?php
/*
File Description: Cambridge105 custom functions
Author: Axel Minet
Theme Version: 0.6.1
*/

# experimental and utility functions (eg unregister custom post types/taxonomies)
//include 'functions/experimental_functions.php';

function diag($str)
{
	echo "<!-- $str ".microtime(true)." -->\r\n";
}

## filters and actions

/**
 * filter to only show current user's posts in posts admin (only for any non-admins)
 **/
function gigx_parse_query_useronly( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
        if ( !current_user_can( 'administrator' ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }
}
add_filter('parse_query', 'gigx_parse_query_useronly' );

/**
 * filter to remove "more" string from end of the_excerpt
 **/
function gigx_excerpt_more($more) {
	return '';
}
add_filter('excerpt_more', 'gigx_excerpt_more');

/**
 * sort shows alphabetically (leaving other posts/post types untouched)
 **/
function shows_alphabetical( $orderby )
{
  if( !is_admin() && is_post_type_archive( "shows" ) ){
  // alphabetical order by post title
     return "post_title ASC";
  }
  else return $orderby;
}  
add_filter('posts_orderby', 'shows_alphabetical' );

/**
 * fix for custom post type tag archives
 **/               
function query_post_type($query) {
    $post_types = get_post_types();
    if ( is_category() || is_tag()) {

        $post_type = get_query_var('post_type');
        
        if ( $post_type )
            $post_type = $post_type;
        else
            $post_type = $post_types;    
        $query->set('post_type', $post_type);

    return $query;
    }
}
add_filter('pre_get_posts', 'query_post_type');

# include custom post types
include 'functions/gigx-cpt-shows.php';

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
            'reciprocal' => true
    ) );
}
add_action('init', 'my_connection_types', 100);

# add shows image and thumbnail size
add_image_size( 'shows-image', 300, 225, true );
add_image_size( 'shows-thumb', 150, 112, true );

# add facebook thumbnail size
add_image_size( 'facebook-thumb', 130, 130, true );
	
# change header image size
add_filter('gigx_header_image_width', function($size) { return 468; });
add_filter('gigx_header_image_height', function($size) { return 110; });

# remove all default widgets except the ones specified
add_filter('gigx_add_default_widgets', function($widgetsToAdd) { return array('WP_Widget_Tag_Cloud','WP_Widget_Text','WP_Widget_Search');});
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

## new functions

/**
 * gets size and crop behaviour of a custom or standard "image size"
 * @param string $imageSize image size to query
 * @return array $out['width'],$out['height'],$out['crop']
 **/
function gigx_get_image_size( $imageSize ) {
   $out = array();
   switch($imageSize) {
      case 'thumbnail':
         $out['width'] = get_option( 'thumbnail_size_w' );
         $out['height'] = get_option( 'thumbnail_size_h' );
         $out['crop'] = get_option( 'thumbnail_crop' );
      break;
      case 'medium':
         $out['width'] = get_option( 'medium_size_w' );
         $out['height'] = get_option( 'medium_size_h' );
         $out['crop'] = false;
      break;
      case 'large':
         $out['width'] = get_option( 'large_size_w' );
         $out['height'] = get_option( 'large_size_h' );
         $out['crop'] = false;
      break;
      default:
         global $_wp_additional_image_sizes;
         if (isset($_wp_additional_image_sizes[$imageSize])) $out = $_wp_additional_image_sizes[$imageSize];         
   }
	if (!empty($out)) return $out;
	else return false;
}

/**
 * works just like http://codex.wordpress.org/Function_Reference/get_the_term_list
 * but has additional parameter exclude, which is an array of taxonomy IDs to exclude 
 * @param array $exclude array of taxonomy IDs to exclude
 **/
function gigx_get_the_term_list( $id = 0, $taxonomy, $before = '', $sep = '', $after = '', $exclude = array() ) {
	$terms = get_the_terms( $id, $taxonomy );

	if ( is_wp_error( $terms ) )
		return $terms;

	if ( empty( $terms ) )
		return false;

	foreach ( $terms as $term ) {

		if(!in_array($term->term_id,$exclude)) {
			$link = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) )
				return $link;
			$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
		}
	}

	$term_links = apply_filters( "term_links-$taxonomy", $term_links );

	return $before . join( $sep, $term_links ) . $after;
}

/**
 * returns a show's featured image if available, or featured image from "Default" page if not
 * returned image has rounded corners unless $phpThumbOptions is set to false
 **/
function get_show_image($imageSize='shows-thumb', $showID=false, $returnUrlOnly = false, $showTitle = false, $phpThumbOptions='&f=jpg&q=95&zc=1&fltr[]=ric|5|5'){    
   if (!function_exists('gigx_get_image_size')) return;
   $imageSizeAttribs = gigx_get_image_size($imageSize);
   $width = $imageSizeAttribs['width'];
   $height = $imageSizeAttribs['height'];
   if (!$showID) $showID = get_the_ID();
   if (!$showTitle) $showTitle = get_the_title($showID);
   $img = array();
   if(has_post_thumbnail($showID)) {
      $img=wp_get_attachment_image_src(get_post_thumbnail_id($showID), $imageSize, false);    
   }
   if(empty($img)) {
      $img= wp_get_attachment_image_src (get_post_thumbnail_id(get_page_by_title('Default',false,'page')->ID), $imageSize, false);          
   }
   if (function_exists('getphpthumburl') && $phpThumbOptions) {
      $img[0]=getphpthumburl($img[0],'w='.$width.'&h='.$height.$phpThumbOptions);
   }
   if ($returnUrlOnly) return $img[0];
   $img_html= '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$showTitle.'" title="'.$showTitle.'"/>';
   return $img_html;
}

################
# not fully tested stuff:
#

/* remove tags from posts */
function unregister_taxonomy(){
    //register_taxonomy('post_tag', array());
}
add_action('init', 'unregister_taxonomy');

// remove wp seo menu (wp 3.3+)
function remove_yoast_seo_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wpseo-menu');
}
// and we hook our function via
add_action( 'wp_before_admin_bar_render', 'remove_yoast_seo_admin_bar' );
//wpseo-menu

/***********************/
/* Add Podcast Section */
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
		<!--<input type="text" id="selectshow" name="select_show" value=""/>-->
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
      $cat=get_category_by_slug($show_slug)->term_id;
      if(!$cat) {
            $new_cat = array('cat_name' => $show_title, 'category_nicename' => $show_slug, 'category_parent' => get_cat_ID( 'Podcasts' ));
            wp_insert_category($new_cat);
            $cat = get_cat_ID($show_title);
	    $newcat = true;
      }
      $show_thumb=get_post_thumbnail_id( $showID );
		$author=get_userdatabylogin($show_slug);
		$podcast_date = strtotime($_GET['podcast_date']);

		if (isset($author)) $authorID=$author->ID;
        if (!$authorID) $authorID=$user_ID;
		$post = array( // add a new post to the wordpress database
			'post_title' => $show_title.' '.date('d/m/Y',$podcast_date),
			'post_name' => $show_slug.'-'.date('d-m-Y',$podcast_date),
			'post_status' => 'draft', // set post status to draft - we don't want the new post to appear live yet.
			'post_author' => $authorID, // set post author to current logged on user.
			'post_type' => 'post', // set post type to post.
			'post_category' => array(get_cat_ID( 'Podcasts' ), $cat) // set category to the category/categories parsed in your previous array
		);

      echo '<div class="wrap">';
      echo '   <div id="icon-edit" class="icon32 icon32-posts-post">';
      echo '      <br/>';
      echo '   </div>';
      echo '   <h2>Add New Podcast</h2>';
      
		if($cat)
      {
         $insert_post = wp_insert_post($post,true); // insert the post into the wp db
         $post_details = get_post($insert_post); // get all the post details from new post
         $post_id = $post_details->ID; // extract the post id from the post details
         update_post_meta($post_id, '_thumbnail_id', $show_thumb);
         if (function_exists('p2p_type'))p2p_type('posts_to_shows')->connect($post_id, $showID);
         $post_redirect = 'http://'.$_SERVER['SERVER_NAME'].'/wp-admin/post.php?action=edit&post='.$post_id; // construct url for editing of post
         echo '<div class="updated">';
	 
	 if ($newcat) echo '<p>New category created. <a href="http://'.$_SERVER['SERVER_NAME'].'/wp-admin/admin.php?page=powerpress/powerpressadmin_categoryfeeds.php" target="_blank">Click here to set up Category Podcast</a> (Opens in a new Window)</p>';
	 echo '<p>Podcast post created. <a href="'.$post_redirect.'">Click here to edit</a></p>';
	 echo '</div>';
         //wp_redirect($post_redirect);// redirect to edit page for new post.
      }
      else{
         echo '<div class="error">Error creating category for ' . $show_title .'!';
         echo '<form action="' . get_admin_url('','edit.php') . '" method="get">';
   		echo '<input type="hidden" name="page_id" value="' . $showID . '" />';
         echo '<input type="hidden" name="podcast_date" value="' . date('d-m-Y',$podcast_date) . '" />';
         echo '<input type="submit" name="submit" value="Click here to retry" />';
   		echo '</form>';
         echo '</div>';         
      }
      echo '</div>'; // end wrap     
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



function gigx_pagination($prev = 'Ç', $next = 'È') {
    global $wp_query, $wp_rewrite;
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $pagination = array(
        'base' => @add_query_arg('paged','%#%'),
        'format' => '',
        'total' => $wp_query->max_num_pages,
        'current' => $current,
        'prev_text' => __($prev),
        'next_text' => __($next),
        'type' => 'plain'
);
    if( $wp_rewrite->using_permalinks() )
        $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

    if( !empty($wp_query->query_vars['s']) )
        $pagination['add_args'] = array( 's' => get_query_var( 's' ) );

    echo paginate_links( $pagination );
};
  

# include help files
include 'functions/gigx-help.php';

function hwl_home_pagesize( $query ) {
    if ( is_home() ) {
        //Display only 1 post for the original blog archive
        //$query->query_vars['posts_per_page'] = 1;
        return;
    }
    if ( is_post_type_archive('shows') ){
        //Display 50 posts for a custom post type called 'movie'
        $query->query_vars['posts_per_page'] = 10;
        return;
    }
}
//add_action('pre_get_posts', 'hwl_home_pagesize', 1);


# enqueue frontpage js
function gigx_frontpage_scripts() {
    wp_deregister_script( 'gigx-tooltip-js' );
    wp_register_script( 'gigx-tooltip-js', get_bloginfo('stylesheet_directory') . '/js/jquery.tipTip.minified.js', array( 'jquery' ), '1.4', true );
    wp_enqueue_script( 'gigx-tooltip-js' );
    
}
add_action('wp_enqueue_scripts', 'gigx_frontpage_scripts');
