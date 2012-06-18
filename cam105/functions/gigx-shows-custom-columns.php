<?php 

/* Customise columns shown in list of custom post type */

# define new custom columns for shows custom post type
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


add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );

function custom_columns( $column, $post_id ) {
   echo"ysy";
	switch ( $column ) {
	case 'book_author':
		$terms = get_the_term_list( $post_id , 'book_author' , '' , ',' , '' );
		if ( is_string( $terms ) ) {
			echo $terms;
		} else {
			echo 'Unable to get author(s)';
		}
		break;

	case 'publisher':
		echo get_post_meta( $post_id , 'publisher' , true ); 
		break;
	}
}
?>