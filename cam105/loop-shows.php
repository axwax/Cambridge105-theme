<?php
/*
File Description: The Loop for "Shows" Custom Post Type
Author: Axel Minet
Theme Version: 0.5.11
*/
?>

  	<?php wp_nav_menu( array( 'theme_location' => 'above-posts', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
  	<?php if ( is_active_sidebar( 'above_posts_widgets' ) ) : // Widgets Above Posts ?>
    	<div id="above-posts-widgets">
    		<?php dynamic_sidebar('above_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>
       
    <div id="shows_container" class="shows_container">
        <?php /* Do we have posts, then start the loop, otherwise display 404 */
        
        ?>
      	<?php if (have_posts()) : ?>
          <?php /* Start the Loop */ ?>  	
      		<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class("clearfix") ?> id="post-<?php the_ID(); ?>"> 		
<?php 
	  
	# Show Title (Shows CPT)
	$title_tag='h1';
	$single_title_tag='h2';
	$title = the_title('', '', false);		
	if ( $title && !is_singular() ) {
                $permalink='';
		$permalink = apply_filters('the_permalink', get_permalink());
		if(isset($permalink)) $title = '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">'.$title.'</a>';
	}
	if ( $single_title_tag && is_singular() ) {
		$title_tag=$single_title_tag;
	}		
	if ( $title && $title_tag && !is_page()) {
		$title_html='<' . $title_tag . ' class="post-title">' . $title . '</' . $title_tag . '>'."\n\r";
	}
	# Show Image (Post Thumbnail)
	if (is_singular()){
		if(has_post_thumbnail()) {
			$img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'shows-image',false);    
		} else {
			$img=get_bloginfo("template_url").'/images/shows-default.png';
		}
		//$img_html= '<div class="wp-caption alignleft"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';
		$img_html= '<div class="wp-caption alignleft"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$post->post_title.'" title="'.$post->post_title.'"/></div>';
	}
	else{
		if(has_post_thumbnail()) {
			$img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'shows-thumb',false);    
		} else {
			$img=get_bloginfo("template_url").'/images/shows-thumb-default.png';
		}
		//$img_html= '<div class="wp-caption alignleft"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';
		if(isset($permalink)) $img_html= '<div class="wp-caption alignleft"><a href="' . esc_url($permalink) . '" title="' . esc_attr(the_title('', '', false)) . '"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$post->post_title.'" title="'.$post->post_title.'"/></a></div>';
                else $img_html= '<div class="wp-caption alignleft"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$post->post_title.'" title="'.$post->post_title.'"/></div>';    
	}

	# Show genres list (Tax)
	/*
	$genres_html='';
	$genres_list = get_the_term_list( $post->ID, 'genres', '', ', ', '' );
	if ( '' != $genres_list ) {
		$genres_html='<h4> ('.$genres_list.')</h4>';               
	}
        */
	# Show's Website (Custom Meta)
	$website_html='';
	$website_title=get_post_meta($post->ID, 'WebsiteTitle', True);
	$website_url=get_post_meta($post->ID, 'WebsiteURL', True);
	if ( ('' != $website_title) and ('' != $website_url)  ) {
		$website_html='<p>Website: <a href="'.$website_url.'" target="_blank">'.$website_title.'</a></p>'."\n\r";
	}
		
	# Show Frequency list (Tax)
	$frequency_html='';
	$frequency_list = get_the_term_list( $post->ID, 'frequency', 'Show Frequency: ', ', ', '' );
	if ( '' != $frequency_list ) {
		$frequency_html= "<p>$frequency_list</p>\n";
	}
	# Podcasts (posts-to-posts)
	//$connected = p2p_type( 'posts_to_shows' )->get_connected( $post->ID );	
 ?>
				<?php echo $title_html ?>
				<?php echo $img_html ?>
				
                <div class="entry" style="padding-top:10px;">
                  <?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
        		</div>
				<div class="entry-utility">
					<?php echo $website_html; ?>
					<?php echo $frequency_html; ?>
<?php
// Find connected pages

if ( function_exists( 'p2p_type' ) && is_single() ){
	$connected = p2p_type( 'posts_to_shows' )->get_connected( $post->ID );

	// Display connected pages
	if ( $connected->have_posts() ) :
	$done=false;
	?>
	<h3>Latest Podcast:</h3>
	<?php while ( $connected->have_posts() && !$done) : $connected->the_post(); $done=true; ?>
		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<p><?php the_excerpt() ?></p>
	<?php endwhile; ?>

	<?php 
	// Prevent weirdness
	wp_reset_postdata();

	endif;
}
?>

<?php  //for use in the loop, list 5 post titles related to first tag on current post
  $backup = $post;  // backup the current object
  $tags = wp_get_post_tags($post->ID);
  $tagIDs = array();
  if ($tags) {
    $tagcount = count($tags);
    for ($i = 0; $i < $tagcount; $i++) {
      $tagIDs[$i] = $tags[$i]->term_id;
    }
    $args=array(
      'tag__in' => $tagIDs,
      'post__not_in' => array($post->ID),
      'showposts'=>5,
      'ignore_sticky_posts'=>1,
      'post_type'=>'shows'
    );
    $my_query = new WP_Query($args);
    if( $my_query->have_posts()  && is_single()) {
      ?><h2>Related Shows:</h2><?php
      while ($my_query->have_posts()) : $my_query->the_post(); ?>
        <p><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></p>
      <?php endwhile;
    } else {  }
  }
  $post = $backup;  // copy it back
  wp_reset_query(); // to use the original query again
?>
					
				</div>
				<?php 
				if(defined ('CUSTOM_POST_TYPE') && is_singular(CUSTOM_POST_TYPE)) {
				  get_template_part(CUSTOM_POST_TYPE);
				}

                # below entry widgets
				if ( is_active_sidebar( 'below_entry_widgets' ) ) : // Nothing here by default and design ?>
                    <div class="below-entry-widgets">
                		  <?php dynamic_sidebar('below_entry_widgets'); ?>
                  	</div>
                <?php endif; ?>  
        			</div>
    
               <?php comments_template( '', true ); ?> 			
      		<?php endwhile; ?>
              
      	<?php else : ?>
      	
      		<h2 class="center">Not Found</h2>
      		<p class="center">Sorry, but you are looking for something that isn't here.</p>
      		<?php get_search_form(); ?>
      
      	<?php endif; ?>
    </div><!-- end of posts div -->
    <div id="shows_pager"></div>

  

  	<?php if ( is_active_sidebar( 'below_posts_widgets' ) ) : // Widgets Below Posts ?>
    	<div id="below-posts-widgets">
    		<?php dynamic_sidebar('below_posts_widgets'); ?>
    	</div>  
    <?php endif; ?> 