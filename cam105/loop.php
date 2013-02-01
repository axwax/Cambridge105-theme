<?php
/*
File Description: The default Loop (used for pages & posts, including homepage and podcasts)
Author: Axel Minet
Theme Version: 0.7
*/
?>

<?php diag("BEGIN POSTS") ?>
<div class="posts">
<?php if ( function_exists('yoast_breadcrumb') ) {
	yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?>
<? if (is_front_page()) :
$feature_1_show = get_field('feature_1_show');
$feature_2_show = get_field('feature_2_show');
$feature_3_show = get_field('feature_3_show');
								?>
	<div class="entry clearfix">
		<div class="threecol threecol-frontpage">
				<div class="threecol-content threecol-1"><a href="<?=get_permalink($feature_1_show->ID)?>"><img class="alignleft" title="<?=get_field('feature_1_title')?>" alt="<?=get_field('feature_1_title')?>" src="<?= (get_field('feature_1_image') ? get_field('feature_1_image') : get_show_image('frontpage-thumb', $feature_1_show->ID , true, false, ''))?>" width="225" height="170" /></a></p>
						<div>
								<h2><a style="color: white;" href="<?=get_permalink($feature_1_show->ID)?>"><?=get_field('feature_1_title')?></a></h2>
								<p><?=get_field('feature_1_description')?></p>
						</div>
				</div>
				<div class="threecol-content threecol-2"><a href="<?=get_permalink($feature_2_show->ID)?>"><img class="alignleft" title="<?=get_field('feature_2_title')?>" alt="<?=get_field('feature_2_title')?>" src="<?= (get_field('feature_2_image') ? get_field('feature_2_image') : get_show_image('frontpage-thumb', $feature_2_show->ID , true, false, ''))?>" width="225" height="170" /></a></p>
						<div>
								<h2><a style="color: white;" href="<?=get_permalink($feature_2_show->ID)?>"><?=get_field('feature_2_title')?></a></h2>
								<p><?=get_field('feature_2_description')?></p>
						</div>
				</div>
				<div class="threecol-content threecol-3"><a href="<?=get_permalink($feature_3_show->ID)?>"><img class="alignleft" title="<?=get_field('feature_3_title')?>" alt="<?=get_field('feature_3_title')?>" src="<?= (get_field('feature_3_image') ? get_field('feature_3_image') : get_show_image('frontpage-thumb', $feature_3_show->ID , true, false, ''))?>" width="225" height="170" /></a></p>
						<div>
								<h2><a style="color: white;" href="<?=get_permalink($feature_3_show->ID)?>"><?=get_field('feature_3_title')?></a></h2>
								<p><?=get_field('feature_3_description')?></p>
						</div>
				</div>
		</div>
	</div>
<?php endif ?>

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
	
	# podcast-specific: deal with image and show link
	if (in_category( "podcasts")) {
		$podcast_image = get_show_image('shows-image');
		$img_html = '<div class="shows-image alignleft">' . $podcast_image . '</div>';			
		
		# Link image to associated show (posts-to-posts)
		if ( function_exists( 'p2p_type' ) && is_single() ){
			$connected = p2p_type( 'posts_to_shows' )->get_connected( $post->ID );

			// Display connected pages
			if ( $connected->have_posts() ) :
				$show_html = "";
				$connected->the_post();
				$title = the_title('', '', false);
				$permalink = apply_filters('the_permalink', get_permalink());
				$show_html.= '<p style="margin: 10px 0 0 0;"><a href="' . esc_url($permalink) . '" title="'.esc_attr($title).'">';   
				$show_html.= '<img src="' . get_bloginfo('stylesheet_directory').'/images/website-icon-small.png" width="16" height="16" alt="' . esc_attr($title) . '" title="' . esc_attr($title) . '" />';
				$show_html.= '         ' . $title;
				$show_html.= '      </a></p>';
				// Prevent weirdness
				wp_reset_postdata();
				$podcast_image = get_show_image('shows-image', false, false, $title);
				$img_html = '<div class="shows-image alignleft"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">' . $podcast_image . '</a>' . $show_html . '</div>';
			endif;					
		}			
	}
 ?>
            <?php if (is_single()) : ?>			
               <div class="alignleft" style="width: 312px;">
                     <?php echo $img_html ?>
                     <?php if (is_single()) echo $website_html; ?>
                     <?php if (is_single()) echo $frequency_html; ?>
                     <?php if (is_single()) echo $tags ?>

               </div>
            <?php else : ?>
               <?php echo $img_html ?>
            <?php endif; ?>
            
            <?php echo $title_html ?>			
            <div class="entry clearfix">
                  <?php 
                  ?>
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
               <?php if (is_single()) edit_post_link('edit', '<p>', '</p>'); ?>
      		<?php endwhile; ?>
              
      	<?php else : ?>
      	
      		<h2 class="center">Not Found</h2>
      		<p class="center">Sorry, but you are looking for something that isn't here.</p>
      		<?php get_search_form(); ?>
      
      	<?php endif; ?>
</div><!-- end of posts div -->
<?php diag("END POSTS") ?>