<?php

/**
 * Template Name: Showslist
 *
 * Selectable from a dropdown menu on the edit page screen.
 */

$q = strtolower($_REQUEST["q"]);
if (!$q) return;


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
//print_r($programmes);
foreach($programmes as $post) : setup_postdata($post);
	if (strpos(strtolower($post->post_title), $q) !== false) {
		echo $post->post_title."\n";
        //print_r($post);
	}
    
endforeach;
?>