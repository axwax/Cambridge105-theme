<?php
/*
File Description: The default Loop (used for posts, including podcasts)
Author: Axel Minet
Theme Version: 0.6.1
*/
?>

<div class="posts">
<?php if ( function_exists('yoast_breadcrumb') ) {
	yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?>       
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
 ?>
				<?php echo $title_html ?>
                <div class="entry" style="padding-top:10px;">
                  <?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
        		</div>
				<?php 
                # below entry widgets
				if ( is_active_sidebar( 'below_entry_widgets' ) ) : // Nothing here by default and design ?>
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