<?php
/*
File Description: The Loop for the main News page 
Author: Axel Minet
Theme Version: 0.6.2
*/


if ( function_exists('yoast_breadcrumb') ) $breadcrumb = yoast_breadcrumb('<div id="breadcrumbs">','</div>',false);	

// get headline news
$args = array('numberposts'=>3, 'offset' => 0,'orderby' => 'post_date', 'order'=>'DESC','post_status' => 'publish');    
$args['category_name'] = 'local-news';
$args['numberposts']   = $postcount = 1;
$news=get_posts( $args );
$out = gigx_newspost ($news, false, $breadcrumb);

// get other news
$args['category_name']='press-releases';
$args['numberposts']=1;
$news=get_posts( $args );
$out .= '<div class="twocol clearfix">';
$out .= gigx_newspost($news,1,false,false);


$args['category_name']='station-news';
$args['numberposts']=1;
$news=get_posts( $args );
$out .= gigx_newspost($news,2,false,false);
$out .= "</div><!--end of twocol -->";
?>            

<div class="posts">
    <?php echo $out ?>      
</div><!-- end of posts div -->
<?php
function gigx_newspost ($news, $twocol_row = false, $breadcrumb = '', $show_img=true){
    $out = '';
    foreach($news as $post) : setup_postdata($post);

	$postID = $post->ID;
	$img=wp_get_attachment_image_src (get_post_thumbnail_id($postID),'shows-image',false);
	$excerpt = $post->post_excerpt;
	$content = $post->post_content;
	$title = get_the_title($postID);
	$permalink = apply_filters('the_permalink', get_permalink($postID));
	
   $title_link = $title;
	if ($title) {
	    if(isset($permalink)) $title_link = '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">'.$title.'</a>';
	}    
	if($img && $show_img){
	    if(isset($permalink)){
		$img_html = '<div class="shows-thumb alignleft"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">' . get_show_image('shows-thumb',$postID) . '</a></div>';
		$img_html = '<div class="wp-caption alignleft" style="width: 310px"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$title.'" title="'.$title.'"/></a><p class="wp-caption-text">'.$title_link.'</p></div>';
	    }     
	    else $img_html = '<div class="wp-caption alignleft" style="width: 310px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$title.'" title="'.$title.'"/><p class="wp-caption-text">'.$title_link.'</p></div>';
	}    
	if (function_exists('gigx_excerpt')){
	  $content=gigx_excerpt ($content,$excerpt,false,500,$permalink,'more',True);
	}
	if($twocol_row) $out .= '<div class="' . implode(' ',get_post_class(array('clearfix', 'twocol-content col_' . $twocol_row))) . '" id="post-' . $postID . '">';
	else $out .= '<div class="' . implode(' ',get_post_class("clearfix")) . '" id="post-' . $postID . '">';
	if ($breadcrumb) $out .= $breadcrumb;	
	$out .= 	$img_html;
	$out .= '	<h1 class="post-title">'.$title_link.'</h1>';
	$out .= '	<span class="postdate">Posted by ' . get_the_author() . ' on '. get_the_time('jS F Y',$postID) .'</span>'; 
	$out .= '	<div class="entry" style="padding-top:10px;">' . $content . '</div>';
	$out .= '</div>'."\r\n";
    endforeach;
    return $out;
}
?>