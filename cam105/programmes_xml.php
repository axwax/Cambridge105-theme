<?php

/**
 * Template Name: Programmes XML
 *
 * Selectable from a dropdown menu on the edit page screen.
 */
 
 header ("Content-Type:text/xml");

$args = array(
    'numberposts'     => -1,
    'offset'          => 0,
    'category'        => '',
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'include'         => '',
    'exclude'         => '',
    'meta_key'        => '',
    'meta_value'      => '',
    'post_type'       => 'shows',
    'post_mime_type'  => '',
    'post_parent'     => '',
    'post_status'     => 'publish' );
	
$programmes=get_posts( $args );	
echo '<programmes>'."\n\r";
foreach($programmes as $post) : setup_postdata($post);
	//$img=wp_get_attachment_image_src (get_post_thumbnail_id($post->ID),'shows-thumb',false);
   //$img=$img[0];   
	$img = get_show_image('shows-thumb', $showID=$post->ID, true);
   $email=get_post_meta($post->ID, 'show_email', True);	
	echo '	<programme id="'.$post->post_name.'" image="'.$img.'" email="'.$email.'" />'."\n\r";
endforeach;
echo '</programmes>'."\n\r";
//print_r($programmes);	

/*
<programmes>
	<programme id="NONSTOP" image="images/shows/105logo100.png" />
	<programme id="BREAKFAST" image="images/shows/breakfast100.png" email="breakfast@cambridge105.fm" />
</programmes>
*/
?>


