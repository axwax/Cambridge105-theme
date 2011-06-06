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
       
    <div class="posts">
        <?php /* Do we have posts, then start the loop, otherwise display 404 */ ?>
      	<?php if (have_posts()) : ?>
          <?php /* Start the Loop */ ?>  	
      		<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>"> 		
<?php 
	  
	# Show Image (Post Thumbnail)
	if(has_post_thumbnail()) {
		$img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'shows-image',false);    
	} else {
		$img=get_bloginfo("template_url").'/images/shows-default.png';
	}
	$img_html= '<div class="wp-caption alignleft" style="width: 210px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';

	# Show Title (Shows CPT)
	$title_tag='h1';
	$single_title_tag='h2';
	$title = the_title('', '', false);		
	if ( $title && !is_singular() ) {
		$permalink = apply_filters('the_permalink', get_permalink());
		$title = '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">'.$title.'</a>';
	}
	if ( $single_title_tag && is_singular() ) {
		$title_tag=$single_title_tag;
	}		
	if ( $title && $title_tag && !is_page()) {
		$title_html='<' . $title_tag . ' class="post-title">' . $title . '</' . $title_tag . '>'."\n\r";
	}

	# Show genres list (Tax)
	$genres_list = get_the_term_list( $post->ID, 'genres', '', ', ', '' );
	if ( '' != $genres_list ) {
		$genres_html='<h4> ('.$genres_list.')</h4>';               
	}

	# Show's Website (Custom Meta)
	$website_title=get_post_meta($post->ID, 'WebsiteTitle', True);
	$website_url=get_post_meta($post->ID, 'WebsiteURL', True);
	if ( ('' != $website_title) and ('' != $website_url)  ) {
		$website_html='<p>Website: <a href="'.$website_url.'" target="_blank">'.$website_title.'</a></p>'."\n\r";
	}
		
	# Show Frequency list (Tax)
	$frequency_list = get_the_term_list( $post->ID, 'frequency', 'Show Frequency: ', ', ', '' );
	if ( '' != $frequency_list ) {
		$frequency_html= "<p>$frequency_list</p>\n";
	}
 ?>
				<?php echo $img_html ?>
				<?php echo $title_html ?>
				<?php echo $genres_html ?>
                <div class="entry" style="padding-top:10px;">
                  <?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
        		</div>
				<div class="entry-utility">
					<?php echo $website_html; ?>
					<?php echo $frequency_html; ?>
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

  

  	<?php if ( is_active_sidebar( 'below_posts_widgets' ) ) : // Widgets Below Posts ?>
    	<div id="below-posts-widgets">
    		<?php dynamic_sidebar('below_posts_widgets'); ?>
    	</div>  
    <?php endif; ?> 