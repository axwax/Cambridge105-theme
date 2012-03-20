<?php
/*
File Description: The Loop for "Shows" Custom Post Type (list as well as single shows)
Author: Axel Minet
Theme Version: 0.6.2
*/
?>       
<div id="shows-container" class="shows-container">
		
      <?php /* Do we have posts, then start the loop, otherwise display 404 */
         $postcount=0;
         if (have_posts()) :
            /* Start the Loop */  	
      		while (have_posts()) : the_post(); ?>
               <div <?php post_class("clearfix") ?> id="post-<?php the_ID(); ?>"> 		
   
                  <?php if ( function_exists('yoast_breadcrumb') && $postcount ==0) {
                     yoast_breadcrumb('<div id="breadcrumbs">','</div>');
                  } ?>       

               <?php 
                $postcount++; 
               # Show Title (Shows CPT)
               $title_tag='h2';
               $single_title_tag='h1';
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
               if (is_singular()){
                  $img_html = '<div class="shows-image alignleft">' . get_show_image('shows-image') . '</div>';
               }
               else{
                  if(isset($permalink)){
                     $img_html = '<div class="shows-thumb alignleft"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">' . get_show_image('shows-thumb') . '</a></div>';  
                  }     
                  else $img_html = '<div class="shows-thumb alignleft">' . get_show_image('shows-thumb') . '</div>';      
               }
            
               $tags= get_the_term_list( $post->ID, 'post_tag', '<div class="ctc show-tags">', ' ', '</div>' );
               
               # Show's Website (Custom Meta)
               $website_html='';
               $website_title=get_post_meta($post->ID, 'WebsiteTitle', True);
               $website_url=get_post_meta($post->ID, 'WebsiteURL', True);
               if  ('' != $website_url) {
                  if (!$website_title) $website_title = $website_url;
                  $website_html='<p>Website: <a href="'.$website_url.'" target="_blank">'.$website_title.'</a></p>'."\n\r";
               }
                  
               # Show Frequency list (Tax)
               $frequency_html='';
               $frequency_list = get_the_term_list( $post->ID, 'frequency', 'Show Frequency: ', ', ', '' );
               if ( '' != $frequency_list ) {
                  $frequency_html= "<p>$frequency_list</p>\n";
               }
               
               # Podcasts (posts-to-posts)
               
               // Find connected pages
               $podcasts_html = '';
               if ( function_exists( 'p2p_type' ) && is_single() ){
                  $connected = p2p_type( 'posts_to_shows' )->get_connected( $post->ID );
               
                  // Display connected pages
                  if ( $connected->have_posts() ) :
                     $done=false;
                     while ( $connected->have_posts() && !$done) :
                        $connected->the_post();
                        $done=true;
                        $title = the_title('', '', false);
                        $permalink = apply_filters('the_permalink', get_permalink());
                        $podcasts_html = '<h4><a href="' . esc_url($permalink) . '">' . $title . '</a></h4>';
                        $podcasts_html .= '<p>' . gigx_excerpt (get_the_content(),get_the_excerpt(),false,500,$permalink,'(more...)',True) . '</p>';
                     endwhile;
                     // Prevent weirdness
                     wp_reset_postdata();
                  endif;
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
               <?php if (is_single()) the_content('<p>Read the rest of this entry &raquo;</p>');
                     else echo gigx_excerpt (get_the_content(),get_the_excerpt(),false,500,$permalink,'(more...)',True);
               ?>
               <?php if (is_single() && $podcasts_html): ?>
                  <h3>Latest Podcast:</h3>
                  <? echo $podcasts_html; ?>
               <? endif; ?>               
            </div>
	    <div class="entry-utility">                           
               <?php // related shows
                  //for use in the loop, list 5 post titles related to first tag on current post
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
                     'showposts'=>4,
                     'ignore_sticky_posts'=>1,
                     'post_type'=>'shows'
                   );
                   $my_query = new WP_Query($args);
                   if( $my_query->have_posts()  && is_single()) {
                     ?><h2 class="related-shows">Related Shows:</h2>
                  <div class="fourcol">
                     <?php
                     $col=0;
                     while ($my_query->have_posts()) : $my_query->the_post();
                        $permalink = apply_filters('the_permalink', get_permalink());
                        $title = the_title('', '', false);
                     if(isset($permalink)){
                        $img_html = '<div class="shows-thumb"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">' . get_show_image('shows-thumb') . '</a></div>';  
                     }     
                     else $img_html = '<div class="shows-thumb ">' . get_show_image('shows-thumb') . '</div>';           
                     $col++;
                     if ($col>4) $col=1;
                     ?>
               
                        <div class="fourcol-content fourcol-<?php echo $col ?>">      
                           <?php echo $img_html; ?>
                           <div class="thumb-caption"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
                        </div>   
                     <?php endwhile; ?>
                  </div><?php   
                     
                   } else {  }
                 }
                 $post = $backup;  // copy it back
                 wp_reset_query(); // to use the original query again
               ?>
					
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
</div><!-- end of shows-container div -->