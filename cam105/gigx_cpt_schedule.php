<?php
/* ------------------- THEME FORCE ---------------------- */

/*
 * EVENTS FUNCTION (CUSTOM POST TYPE) - GPL & all that good stuff obviously...
 *
 * If you intend to use this, please:
 * -- Amend your paths (CSS, JS, Images, etc.)
 * -- Rename functions, unless you're down with the force ;)
 *
 * This is not a plug-in on purpose, it's meant to be it's on file within your theme.
 * http://www.noeltock.com/web-design/wordpress/custom-post-types-schedule-pt1/
 */


// 0. Base


function gigx_functions_css() {
	wp_enqueue_style('gigx-functions-css', get_bloginfo('stylesheet_directory') . '/css/gigx-functions.css');
}

// 1. Custom Post Type Registration (Schedule)

add_action( 'init', 'create_schedule_postype' );

function create_schedule_postype() {

$labels = array(
    'name' => _x('Schedule', 'post type general name'),
    'singular_name' => _x('Schedule Entry', 'post type singular name'),
    'add_new' => _x('Add New', 'schedule'),
    'add_new_item' => __('Add New Schedule Entry'),
    'edit_item' => __('Edit Schedule Entry'),
    'new_item' => __('New Schedule Entry'),
    'view_item' => __('View Schedule Entry'),
    'search_items' => __('Search Schedule'),
    'not_found' =>  __('No schedule found'),
    'not_found_in_trash' => __('No schedule found in Trash'),
    'parent_item_colon' => '',
);

$args = array(
    'label' => __('Schedule'),
    'labels' => $labels,
    'public' => true,
    'can_export' => true,
    'show_ui' => true,
    '_builtin' => false,
    '_edit_link' => 'post.php?post=%d', // ?
    'capability_type' => 'post',
    'menu_icon' => get_bloginfo('stylesheet_directory').'/images/calendar-month.png',
    'menu_position' => 6,
    'hierarchical' => false,
    'rewrite' => array( "slug" => "schedule" ),
    'supports'=> array( 'thumbnail','editor') ,
    'show_in_nav_menus' => true
);

register_post_type( 'gigx_schedule', $args);

}

/*
// 2. Custom Taxonomy Registration (Schedule Entry Types)

function create_schedule_category_taxonomy() {

    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'popular_items' => __( 'Popular Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('gigx_schedule_category','gigx_schedule', array(
        'label' => __('Schedule Entry Category'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'schedule-category' ),
    ));

}

add_action( 'init', 'create_schedule_category_taxonomy', 0 );
*/

// 3. Show Columns

add_filter ("manage_edit-gigx_schedule_columns", "gigx_schedule_edit_columns");
add_action ("manage_posts_custom_column", "gigx_schedule_custom_columns");

function gigx_schedule_edit_columns($columns) {

    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "gigx_col_ev_show" => "Show",
        "gigx_col_ev_date" => "Dates",
        "gigx_col_ev_times" => "Times",
        "gigx_col_ev_thumb" => "Thumbnail",
        "title" => "Schedule Entry",
        "gigx_col_ev_desc" => "Description",
        );

    return $columns;

}

function gigx_schedule_custom_columns($column) {

    global $post;
    $custom = get_post_custom();
    switch ($column)

        {
            case "gigx_col_ev_show":
                // - show taxonomy terms -
            $shows_array = get_posts( array(
              'suppress_filters' => false,
              'post_type' => 'shows',
              'connected' => $post->ID,
            ) );
            //print_r($schedule_array);
            foreach($shows_array as $show) :
              $title= $show->post_title;
              edit_post_link($title, '<p><strong>', '</strong></p>',$post->ID);
              #setup_postdata($show);
              #echo get_the_title();
            endforeach;              
              /*
                $schedule_cats = get_the_terms($post->ID, "gigx_schedule_category");
                $schedule_cats_html = array();
                if ($schedule_cats) {
                    foreach ($schedule_cats as $schedule_cat)
                    array_push($schedule_cats_html, $schedule_cat->name);
                    echo implode($schedule_cats_html, ", ");
                } else {
                _e('None', 'themeforce');;
                }
                */
            break;
            case "gigx_col_ev_cat":
                // - show taxonomy terms -
                $schedule_cats = get_the_terms($post->ID, "gigx_schedule_category");
                $schedule_cats_html = array();
                if ($schedule_cats) {
                    foreach ($schedule_cats as $schedule_cat)
                    array_push($schedule_cats_html, $schedule_cat->name);
                    echo implode($schedule_cats_html, ", ");
                } else {
                _e('None', 'themeforce');;
                }
            break;
            case "gigx_col_ev_date":
                // - show dates -
                $startd = $custom["gigx_schedule_startdate"][0];
                $endd = $custom["gigx_schedule_enddate"][0];
                $startdate = date("F j, Y", $startd);
                $enddate = date("F j, Y", $endd);
                echo $startdate . '<br /><em>' . $enddate . '</em>';
            break;
            case "gigx_col_ev_times":
                // - show times -
                $startt = $custom["gigx_schedule_startdate"][0];
                $endt = $custom["gigx_schedule_enddate"][0];
                $time_format = get_option('time_format');
                $starttime = date($time_format, $startt);
                $endtime = date($time_format, $endt);
                echo $starttime . ' - ' .$endtime;
            break;
            case "gigx_col_ev_thumb":
                // - show thumb -
                $post_image_id = get_post_thumbnail_id(get_the_ID());
                if ($post_image_id) {
                    $thumbnail = wp_get_attachment_image_src( $post_image_id, 'post-thumbnail', false);
                    if ($thumbnail) (string)$thumbnail = $thumbnail[0];
                    echo '<img src="';
                    echo bloginfo('stylesheet_directory');
                    echo '/timthumb/timthumb.php?src=';
                    echo $thumbnail;
                    echo '&h=60&w=60&zc=1" alt="" />';
                }
            break;
            case "gigx_col_ev_desc";
                the_excerpt();
            break;

        }
}


// 4. Show Meta-Box

add_action( 'admin_init', 'gigx_schedule_create' );

function gigx_schedule_create() {
    add_meta_box('gigx_schedule_meta', 'Schedule', 'gigx_schedule_meta', 'gigx_schedule');
}

function gigx_schedule_meta () {

    // - grab data -

    global $post;
    $custom = get_post_custom($post->ID);
    $meta_sd = $custom["gigx_schedule_startdate"][0];
    $meta_ed = $custom["gigx_schedule_enddate"][0];
    $meta_st = $meta_sd;
    $meta_et = $meta_ed;

    // - grab wp time format -

    $date_format = get_option('date_format'); // Not required in my code
    $time_format = get_option('time_format');

    // - populate today if empty, 00:00 for time -

    if ($meta_sd == null) { $meta_sd = time(); $meta_ed = $meta_sd; $meta_st = 0; $meta_et = 0;}

    // - convert to pretty formats -

    $clean_sd = date("D, M d, Y", $meta_sd);
    $clean_ed = date("D, M d, Y", $meta_ed);
    $clean_st = date($time_format, $meta_st);
    $clean_et = date($time_format, $meta_et);

    // - security -

    echo '<input type="hidden" name="gigx-schedule-nonce" id="gigx-schedule-nonce" value="' .
    wp_create_nonce( 'gigx-schedule-nonce' ) . '" />';

    // - output -

    ?>
    <div class="gigx-meta">
        <ul>
            <li><label>Start Date</label><input name="gigx_schedule_startdate" class="scheduledate" value="<?php echo $clean_sd; ?>" /></li>
            <li><label>Start Time</label><input name="gigx_schedule_starttime" value="<?php echo $clean_st; ?>" /><em>Use 24h format (7pm = 19:00)</em></li>
            <li><label>End Date</label><input name="gigx_schedule_enddate" class="scheduledate" value="<?php echo $clean_ed; ?>" /></li>
            <li><label>End Time</label><input name="gigx_schedule_endtime" value="<?php echo $clean_et; ?>" /><em>Use 24h format (7pm = 19:00)</em></li>
        </ul>
    </div>
    <?php
}

// 5. Save Data

add_action ('save_post', 'save_gigx_schedule');

function save_gigx_schedule(){

    global $post;

    // - still require nonce

    if ( !wp_verify_nonce( $_POST['gigx-schedule-nonce'], 'gigx-schedule-nonce' )) {
        return $post->ID;
    }

    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

    // - convert back to unix & update post

    if(!isset($_POST["gigx_schedule_startdate"])):
        return $post;
        endif;
        $updatestartd = strtotime ( $_POST["gigx_schedule_startdate"] . $_POST["gigx_schedule_starttime"] );
        update_post_meta($post->ID, "gigx_schedule_startdate", $updatestartd );

    if(!isset($_POST["gigx_schedule_enddate"])):
        return $post;
        endif;
        $updateendd = strtotime ( $_POST["gigx_schedule_enddate"] . $_POST["gigx_schedule_endtime"]);
        update_post_meta($post->ID, "gigx_schedule_enddate", $updateendd );

}


// 6. Customize Update Messages

add_filter('post_updated_messages', 'schedule_updated_messages');

function schedule_updated_messages( $messages ) {

  global $post, $post_ID;

  $messages['gigx_schedule'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Schedule Entry updated. <a href="%s">View item</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Schedule Entry updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Schedule Entry restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Schedule Entry published. <a href="%s">View Schedule Entry</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Schedule Entry saved.'),
    8 => sprintf( __('Schedule Entry submitted. <a target="_blank" href="%s">Preview Schedule Entry</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Schedule Entry scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Schedule Entry</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Schedule Entry draft updated. <a target="_blank" href="%s">Preview Schedule Entry</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

// 7. JS Datepicker UI

function schedule_styles() {
    global $post_type;
    if( 'gigx_schedule' != $post_type )
        return;
    wp_enqueue_style('ui-datepicker', get_bloginfo('stylesheet_directory') . '/css/jquery-ui-1.8.9.custom.css');
}

function schedule_scripts() {
    global $post_type;
    if( 'gigx_schedule' != $post_type )
        return;
    #wp_deregister_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui', get_bloginfo('stylesheet_directory') . '/js/jquery-ui-1.8.9.custom.min.js', array('jquery'));
    wp_enqueue_script('ui-datepicker', get_bloginfo('stylesheet_directory') . '/js/jquery.ui.datepicker.min.js',array('jquery-ui'));
    wp_enqueue_script('custom_script', get_bloginfo('stylesheet_directory').'/js/gigx-admin.js', array('jquery'));
}

add_action( 'admin_print_styles-post.php', 'schedule_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'schedule_styles', 1000 );

add_action( 'admin_print_scripts-post.php', 'schedule_scripts', 1000 );
add_action( 'admin_print_scripts-post-new.php', 'schedule_scripts', 1000 );


?>