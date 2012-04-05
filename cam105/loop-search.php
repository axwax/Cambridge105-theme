<?php
/*
File Description: The Loop for search results
Author: Axel Minet
Theme Version: 0.6.1
*/
?>

<div class="posts">
	<?php
   $postcount=0;
   /* Do we have posts, then start the loop, otherwise display 404 */ ?>
   <?php if (have_posts()) : ?>
   <?php /* Start the Loop */ ?>  	
      <?php while (have_posts()) : the_post(); ?>
         <div <?php post_class() ?> id="post-<?php the_ID(); ?>"> 		
            <?php if ( function_exists('yoast_breadcrumb') && $postcount ==0) {
               yoast_breadcrumb('<div id="breadcrumbs">','</div>');
            } ?>               
<?php
   $postcount ++;
	$title = the_title('', '', false);
	$permalink = apply_filters('the_permalink', get_permalink());
         $img_html = '<div class="shows-thumb alignleft"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">' . get_show_image('shows-thumb') . '</a></div>';  
         echo $img_html;       	
 ?>

              	<?php if ( is_active_sidebar( 'above_entry_widgets' ) ) : // Nothing here by default and design ?>
                	<div class="above-entry-widgets">
                		<?php dynamic_sidebar('above_entry_widgets'); ?>
                	</div>  
                <?php endif; ?>
        				<div class="entry"> 
        					<?php echo gigx_excerpt (get_the_content(),get_the_excerpt(),false,500,$permalink,'(more...)',True); ?>
        				</div>        				
        		<!--		<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted by <?php if (function_exists('author_exposed')) {author_exposed(get_the_author_meta('display_name'));}   ?> in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>  -->
              	<?php if ( is_active_sidebar( 'below_entry_widgets' ) ) : // Nothing here by default and design ?>
                    <div class="below-entry-widgets">
                		  <?php dynamic_sidebar('below_entry_widgets'); ?>
                  	</div>
                <?php endif; ?>  
                  <?php /* Display navigation to next/previous pages when applicable */
                  if ( (($wp_query->current_post + 1) >= ($wp_query->post_count)) && (get_next_posts_link() || get_previous_posts_link()) ) :
                  ?>
                    <div id="nav-below" class="navigation">
                       <div class="nav-previous"><?php previous_posts_link("&laquo; previous page"); ?></div>
                       <div class="nav-next"><?php next_posts_link("next page &raquo;"); ?></div>
                    </div><!-- #nav-below -->
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