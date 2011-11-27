<?php
/*
File Description: The Loop for "Shows" Custom Post Type
Author: Axel Minet
Theme Version: 0.5.11
*/
?>tax
  	<?php wp_nav_menu( array( 'theme_location' => 'above-posts', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
  	<?php if ( is_active_sidebar( 'above_posts_widgets' ) ) : // Widgets Above Posts ?>
    	<div id="above-posts-widgets">
    		<?php dynamic_sidebar('above_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>
	<?php 
            $categorydesc = category_description(); 
            if ( ! empty( $categorydesc ) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' );
     ?>
       
    <div class="taxonomy list">
        <?php /* Do we have posts, then start the loop, otherwise display 404 */ ?>
      	<?php if (have_posts()) : ?>
          <?php /* Start the Loop */ ?>  	
      		<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>"> 		
            
<?php
	$title = the_title('', '', false);
	$permalink = apply_filters('the_permalink', get_permalink());
	echo '<div class="wp-caption alignleft">';
	echo '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">';
	if(has_post_thumbnail()) {
	   the_post_thumbnail('shows-thumb',array('class'	=> '','alt'	=> esc_attr($title), 'title'	=> esc_attr($title)));
	} else {
	   echo '<img src="'.get_bloginfo("template_url").'/images/shows-default.png" />';
	}
	echo'</a>';
	echo '<p class="wp-caption-text">'.get_the_title().'</p></div>';
          	
 ?>

              	<?php if ( is_active_sidebar( 'above_entry_widgets' ) ) : // Nothing here by default and design ?>
                	<div class="above-entry-widgets">
                		<?php dynamic_sidebar('above_entry_widgets'); ?>
                	</div>  
                <?php endif; ?>
        				<div class="entry"> 
        					<?php the_content('more &raquo;'); ?>
        				</div>

              <?php if(defined ('CUSTOM_POST_TYPE') && is_singular(CUSTOM_POST_TYPE)) {
                //echo CUSTOM_POST_TYPE;
                  get_template_part(CUSTOM_POST_TYPE);
                }
              ?>
        				
        		<!--		<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted by <?php if (function_exists('author_exposed')) {author_exposed(get_the_author_meta('display_name'));}   ?> in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>  -->
              	<?php if ( is_active_sidebar( 'below_entry_widgets' ) ) : // Nothing here by default and design ?>
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

    <?php /* Display navigation to next/previous pages when applicable */ ?>
    	<div id="nav-below" class="navigation">
    		<div class="nav-previous"><?php previous_posts_link(); ?></div>
    		<div class="nav-next"><?php next_posts_link(); ?></div>
    	</div><!-- #nav-above -->
  

  	<?php if ( is_active_sidebar( 'below_posts_widgets' ) ) : // Widgets Below Posts ?>
    	<div id="below-posts-widgets">
    		<?php dynamic_sidebar('below_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>