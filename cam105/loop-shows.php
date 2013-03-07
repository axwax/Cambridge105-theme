<?php
/*
File Description: The Loop for "Shows" Custom Post Type (list as well as single shows)
Author: Axel Minet
Theme Version: 0.7.0
*/

require_once $_SERVER['DOCUMENT_ROOT']."/schedule_info/Schedule.class.php"; 

function related_order($input) {
   return 'COUNT(wp_term_relationships.object_id) DESC';   
}
?>       
<div id="shows-container" class="shows-container">
	<?php /* Do we have posts, then start the loop, otherwise display 404 */
	$postcount=0;
	if (have_posts()) :
		/* Start the Loop */  	
		while (have_posts()) : 
			
         the_post(); 	  
         $postcount++; 
         
         #Show Slug
         $show_slug=get_post($post->ID)->post_name;
         
         # Show Title (Shows CPT)
         $title_tag = (is_singular() ? 'h1' : 'h2');
         $show_title = the_title('', '', false);
         
         
         # make Show Title clickable when not single show
         $permalink='';
         if ( !is_singular() ) {
            $permalink = apply_filters('the_permalink', get_permalink());
            if(isset($permalink)) $show_title = '<a href="' . esc_url($permalink) . '" title="' . esc_attr($show_title) . '">'.$show_title.'</a>';
         }
         $title_html='<' . $title_tag . ' class="post-title">' . $show_title . '</' . $title_tag . '>'."\n\r";
         
         # Show Image
         if (is_singular()){
            $img_html = '<div class="shows-image alignleft">' . get_show_image('shows-image') . '</div>';
         }
         else{
            if(isset($permalink)){
               $img_html = '<div class="shows-thumb alignleft"><a href="' . esc_url($permalink) . '" title="' . esc_attr($show_title) . '">' . get_show_image('shows-thumb') . '</a></div>';  
            }     
            else $img_html = '<div class="shows-thumb alignleft">' . get_show_image('shows-thumb') . '</div>';      
         }
         
         if(is_singular()){
            # Show's Website (Custom Meta)
            $website_html='';
            $website_title=get_post_meta($post->ID, 'WebsiteTitle', True);
            $website_url=get_post_meta($post->ID, 'show_url', True);
            if  ('' != $website_url) {
               if (!$website_title) $website_title = $website_url;
               $website_html='<p><a href="'.$website_url.'" target="_blank"><img src="' . get_bloginfo('stylesheet_directory').'/images/website-icon-small.png" width="16" height="16" alt="External Show Website" title="External Show Website" /> External Show Website</a></p>'."\n\r";
            }
            
            # Show's Twitter (Custom Meta)
            $twitter_html='';
            $twitter_handle=get_post_meta($post->ID, 'show_twitter_handle', True);
            if  ('' != $twitter_handle) {
               $twitter_html='<p><a href="http://twitter.com/'.$twitter_handle.'" target="_blank"><img src="' . get_bloginfo('stylesheet_directory').'/images/twitter-icon-small.png" width="16" height="16" alt="Twitter Profile" title="Twitter Profile" /> Twitter Profile</a></p>'."\n\r";
            }
            
            # Show's Facebook (Custom Meta)
            $facebook_html='';
            $facebook_url=get_post_meta($post->ID, 'show_facebook_url', True);
            if  ('' != $facebook_url) {
               $facebook_html='<p><a href="'.$facebook_url.'" target="_blank"><img src="' . get_bloginfo('stylesheet_directory').'/images/facebook-icon-small.png" width="16" height="16" alt="Facebook Page" title="Facebook Page" /> Facebook Page</a></p>'."\n\r";
            }
            
            # Show's Mixcloud (Custom Meta)
            $mixcloud_html='';
            $mixcloud_handle=get_post_meta($post->ID, 'show_mixcloud_handle', True);
            if  ('' != $mixcloud_handle) {			   
               $mixcloud_html='<p><iframe width="300" height="90" src="//www.mixcloud.com/widget/iframe/?feed=http%3A%2F%2Fwww.mixcloud.com%2F'.$mixcloud_handle.'%2F%3Flimit%3D10&embed_uuid=cde7f48c-899e-476e-b263-52ad4a31b795&stylecolor=83AE2C&embed_type=widget_standard" frameborder="0"></iframe></p>'."\n\r";	
               $mixcloud_html.='<p><a href="http://www.mixcloud.com/'.$mixcloud_handle.'" target="_blank"><img src="' . get_bloginfo('stylesheet_directory').'/images/mixcloud16x16.png" width="16" height="16" alt="Mixcloud Page" title="Mixcloud Page" /> Mixcloud Page</a></p>'."\n\r";
            }			
              
            # Show Frequency list (Tax)
            $frequency_html='';
            $frequency_list = get_the_term_list( $post->ID, 'frequency', '<strong>Show Frequency</strong>: ', ', ', '' );
            if ( '' != $frequency_list ) {
               $frequency_html= "<p>$frequency_list</p>\n";
            }
            
            $schedule = new Schedule(Schedule::GetCachedScheduleURL(), Schedule::GetProgrammesURL());
            $is_current = false;
            $nextshowing = $schedule->GetNextShowing($show_slug, &$is_current);
            
            # Next Episode
            $nextonair_html='';
            if($nextshowing)
            {
               $nextonair_html = '<p><strong>Next On Air</strong>: '.date('D jS M: ga', $nextshowing['start']).'</p>';
            }
            
            # Is the show currently playing?
            $nowplaying_html='';
            $now = $schedule->GetCurrentShow();
            if($now['pid']== $show_slug){
               $nowplaying_html = '<h2 class="related-shows latest-podcast">now playing: '.$now['start_text'].' - '.$now['end_text'].'</h2>';
               $nowplaying_html.= '<div id="latest_podcast" class="widget widget_gigxrecentposts">';
               $nowplaying_html .= '<p>'.$now['desc'].'</p>';
               $nowplaying_html .= '</div>';
            }
   
            # Podcasts (posts-to-posts)
            // Find connected pages
            $podcasts_html = '';
            $podcast_rss = '';
            $podcast_more = '';
            if ( function_exists( 'p2p_type' ) && is_single() ){
               $connected = p2p_type( 'posts_to_shows' )->get_connected( $post->ID );
               
               // Display connected pages
               if ( $connected->have_posts() ) :
                  $first=false;
                  $podcastcount = 0;
                  $podcasts_html = "";
                  $podcasts_html.= '  <h2 class="related-shows latest-podcast">Listen Again:</h2>';
                  $podcasts_html.= '<div id="latest_podcast" class="widget widget_gigxrecentposts">';
                                 
                  while ( $connected->have_posts() && $podcastcount<5) :
                     $podcastcount++;
                     $connected->the_post();
                     $podcast_title = the_title('', '', false);
                     $permalink = apply_filters('the_permalink', get_permalink());
                     
                     if(!$first){
                        $podcasts_html.= '      <div class="twocol">';
                        $podcasts_html.= '      <div class="twocol-1 twocol-content">';
                        
                        $podcasts_html.= '      <p><a href="' . esc_url($permalink) . '" title="'.esc_attr($podcast_title).'">';   
                        $podcasts_html.=          get_show_image('shows-thumb');
                        $podcasts_html.=          $podcast_title;
                        $podcasts_html.= '      </a>';
                        global $more; $more = 0;
                        
                        $podcast_desc = gigx_excerpt (get_the_content(),get_the_excerpt(),false,500,$permalink,'more',false);
                        $podcasts_html.= '      <br/>' . $podcast_desc;
                        $podcasts_html.=  do_shortcode('[powerpress]');
                        $podcasts_html.= '      </p></div>';
                        if($connected->post_count>1) $podcasts_html.= '      <div class="twocol-2 twocol-content">Previous Shows:<ul >';
                        $first=true;
                        
                        $cat=get_category_by_slug($show_slug)->term_id;
                        $podcast_rss = '<a href="' . get_category_link($cat) .'feed" target="_blank"><img src="' . get_bloginfo('stylesheet_directory').'/images/rss16x16.png" width="16" height="16" alt="Subscribe to ' . $show_title . '\'s Podcasts via RSS" title="Subscribe to ' . $show_title . '\'s Podcasts via RSS" /> Subscribe to ' . $show_title . '\'s Podcasts via RSS</a>';
                        $podcast_more = '<a href="' . get_category_link($cat) .'">View all ' . $show_title . ' Podcasts</a>';
                     }
                     else{
                        $podcasts_html.= '      <li><a href="' . esc_url($permalink) . '" title="'.esc_attr($podcast_title).'">';   
                        $podcasts_html.=          $podcast_title;
                        $podcasts_html.= '      </a>';
                        $podcasts_html.=  do_shortcode('[powerpress]');
                        $podcasts_html.= '      </li>';
                     }
                  endwhile;
                  if($connected->post_count>1) $podcasts_html.= '</ul>' . $podcast_more . '</div>';
                  else $podcasts_html.= $podcast_more;
                  $podcasts_html.= '</div>';
                  $podcasts_html.=  $podcast_rss;
                  $podcasts_html.= '</div>';
                  // Prevent weirdness
                  wp_reset_postdata();
               endif;
            }
            $single_sidebar = $website_html . "\r\n";
            $single_sidebar .= $twitter_html . "\r\n";
            $single_sidebar .= $facebook_html . "\r\n";
            $single_sidebar .= $mixcloud_html . "\r\n";
            if ($website_html || $twitter_html || $facebook_html || $mixcloud_html) {
               $single_sidebar .= '<p style="margin: 0 0 15px 0;font-size: smaller;">Cambridge 105 is not responsible for the content of external websites.</p>' . "\r\n";
            }
            $single_sidebar = $frequency_html . "\r\n";
            $single_sidebar = $nextonair_html . "\r\n";         
         } // end of is_singular();
			 ?>			
			<div <?php post_class("clearfix") ?> id="post-<?php the_ID(); ?>">
				<?php if ( function_exists('yoast_breadcrumb') && $postcount ==1) yoast_breadcrumb('<div id="breadcrumbs">','</div>'); ?>				
				<?php if (is_single()) : ?>			
					<div class="alignleft" style="width: 312px;">
						<?php echo $img_html . $single_sidebar; ?>
					</div>
				<?php else : ?>
					<?php echo $img_html ?>
				<?php endif; ?>
			
				<?php echo $title_html ?>			
				<div class="entry clearfix">
					<?php 
					if (is_single()) the_content('<p>Read the rest of this entry &raquo;</p>');
					else echo gigx_excerpt (get_the_content(),get_the_excerpt(),false,500,$permalink,'more',True);
					?>               
				</div>
				<div class="entry-utility">
					<?php
					if (is_single()){
						if ($podcasts_html && $nowplaying_html){
						echo '<div class ="twocol">';
							echo '<div class ="twocol-content twocol-1">';
							echo $nowplaying_html;
							echo '</div>';
							echo '<div class ="twocol-content twocol-2">';
							echo $podcasts_html;
							echo '</div>';
							echo '</div>';
						}
						elseif ($nowplaying_html) echo $nowplaying_html;
						elseif ($podcasts_html) echo $podcasts_html;
					}
					
					// related shows
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
							'post_type'=>'shows',
							'orderby'=> 'rand'
						);

						add_filter('posts_orderby', 'related_order');
						$my_query = new WP_Query($args);
						remove_filter('posts_orderby', 'related_order');

						if( $my_query->have_posts()  && is_single()) {
							?><h2 class="related-shows">Related Shows:</h2>
							<div class="fourcol">
								<?php
								$col=0;
								while ($my_query->have_posts()) : 
									$my_query->the_post();
									$permalink = apply_filters('the_permalink', get_permalink());
									$related_title = the_title('', '', false);
									if(isset($permalink)){
										$img_html = '<div class="shows-thumb"><a href="' . esc_url($permalink) . '" title="' . esc_attr($related_title) . '">' . get_show_image('shows-thumb') . '</a></div>';  
									}     
									else {
										$img_html = '<div class="shows-thumb ">' . get_show_image('shows-thumb') . '</div>';           
									}
									$col++;
									if ($col>4) $col=1;
									?>
									<div class="fourcol-content fourcol-<?php echo $col ?>">      
										<?php echo $img_html; ?>
										<div class="thumb-caption"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
									</div>   
									<?php 
								endwhile; ?>
							</div><?php   
						} else {  }
					}
					$post = $backup;  // copy it back
					wp_reset_query(); // to use the original query again
					?>
					
				</div><!-- end of entry-utility -->
				<?php 

				# below entry widgets
				if ( is_active_sidebar( 'below_entry_widgets' ) ) : // Nothing here by default and design ?>
					<div class="below-entry-widgets">
						<?php dynamic_sidebar('below_entry_widgets'); ?>
					</div><?php 
				endif; ?>

				<?php /* Display navigation to next/previous pages when applicable */
				if ( (($wp_query->current_post + 1) >= ($wp_query->post_count)) && (get_next_posts_link() || get_previous_posts_link()) ) : ?>
					<div id="nav-below" class="navigation">
						<?php gigx_pagination("&laquo; previous page", "next page &raquo;"); ?>
					</div><!-- #nav-below --><?php 
				endif; ?>
				
				
			</div><!-- end of post div? -->
			<?php comments_template( '', true ); ?>
			<?php     
				$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
				<div><label class="screen-reader-text" for="s">' . __('Search Shows:') . '</label>
				<input type="text" value="' . $q . '" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
				</div>
				</form>';
				//if (is_single()) echo $form;
			?>
			<?php if (is_single()) edit_post_link('edit', '<p>', '</p>'); ?><?php 
		endwhile;
		
	else : ?>      	
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?><?php 
	endif; ?>
</div><!-- end of shows-container div -->
