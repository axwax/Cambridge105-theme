<?php 

/* Customise columns shown in list of custom post type */

# define new custom columns
add_filter("manage_edit-shows_columns", "gigx_shows_columns");
 function gigx_shows_columns($columns)
{
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",  
        "title" => "Title",
        "pid" => "PID",
        "frequency" => "Frequency",
        "tags" => "Tags",
		"linkimage" => "Featured Image",       
        "date" => "Date"
    );
    return $columns;
}

# add sortable columns
add_filter( 'manage_edit-shows_sortable_columns', 'gigx_shows_sortable_columns' );
function gigx_shows_sortable_columns( $columns ) {
	#$columns['linktitle'] = 'linktitle'; 
	#$columns['url'] = 'url'; 
	$columns['ID'] = 'ID'; 
	$columns['menu_order'] = 'menu_order';
	return $columns;
}
add_filter( 'request', 'gigx_shows_column_orderby' );
function gigx_shows_column_orderby( $vars ) { 
	if ( isset( $vars['orderby'] )){
      /*			
        #sort by linktitle
       if( 'linktitle' == $vars['orderby'] ) {	
         $vars = array_merge( $vars, array(
            'meta_key' => 'gigx_slide_title',
            'orderby' => 'meta_value'
         ) );
       }
       #sort by url
      elseif ( 'url' == $vars['orderby'] ) {	
         $vars = array_merge( $vars, array(
            'meta_key' => 'gigx_slide_url',
            'orderby' => 'meta_value'
         ) );				
      }
      */
	} 
	return $vars;
}


# Display the columns' content
add_action("manage_posts_custom_column", "gigx_shows_custom_columns"); 
function gigx_shows_custom_columns($column)
{
    global $post;
    if ("ID" == $column) echo $post->ID;
    elseif ('menu_order' == $column) echo $post->menu_order;
    elseif ("url" == $column) {
        $url = get_post_meta($post->ID, "WebsiteUrl", $single=true);
        echo "<a href=\"$url\" target=\"_blank\">$url</a>";
    }
    elseif ("linkimage" == $column) {
        $title = get_post_meta($post->ID, "gigx_slide_title", $single=true);
        $img=wp_get_attachment_image_src (get_post_thumbnail_id($post->ID),array(64,64),false);
  			$image = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$title.'" title="'.$title.'"/>';
        //edit_post_link($image, '<p><strong>', '</strong></p>',$post->ID);
    }    
}
?>