<?php
/*
File Description: The Loop for "Shows" Custom Post Type (list as well as single shows)
Author: Axel Minet
Theme Version: 0.6.2
*/

require_once $_SERVER['DOCUMENT_ROOT']."/schedule_info/Schedule.class.php"; 

function related_order($input) {
   return 'COUNT(wp_term_relationships.object_id) DESC';   
   //return 'COUNT(wp_term_relationships.object_id) DESC, wp_posts.post_date DESC';
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
						
			# Tags
			if (function_exists('gigx_get_the_term_list')) $tags= gigx_get_the_term_list( $post->ID, 'post_tag', '<div class="ctc show-tags">', ' ', '</div>',$excludeTags );
			else $tags= gigx_get_the_term_list( $post->ID, 'post_tag', '<div class="ctc show-tags">', ' ', '</div>' );
			# exclude tags starting with _
			$posttags = get_the_tags($post->ID);
			$excludeTags = array();
			if ($posttags) {
			   foreach($posttags as $tag){
				  if (substr($tag->name,0,1)=="_"){
					 $excludeTags[]=$tag->term_id;
				  }
			   }
			}
			
			# Show's Website (Custom Meta)
			$website_html='';
			$website_title=get_post_meta($post->ID, 'WebsiteTitle', True);
			$website_url=get_post_meta($post->ID, 'show_url', True);
			if  ('' != $website_url) {
				if (!$website_title) $website_title = $website_url;
				//$website_html='<p>Website: <a href="'.$website_url.'" target="_blank">'.$website_title.'</a></p>'."\n\r";
				$website_html='<p><a href="'.$website_url.'" target="_blank">External Show Website</a></p>'."\n\r";
			}

			# Show's Twitter (Custom Meta)
			$twitter_html='';
			$twitter_handle=get_post_meta($post->ID, 'show_twitter_handle', True);
			if  ('' != $twitter_handle) {
				$twitter_html='<p><a href="http://twitter.com/'.$twitter_handle.'" target="_blank"><img src="http://cambridge105.fm/wp-content/uploads/2011/11/twitter-icon-small.png" width="16" height="16" alt="Twitter Profile" title="Twitter Profile" /> Twitter Profile</a></p>'."\n\r";
			}

			# Show's Facebook (Custom Meta)
			$facebook_html='';
			$facebook_url=get_post_meta($post->ID, 'show_facebook_url', True);
			if  ('' != $facebook_url) {
				$facebook_html='<p><a href="'.$facebook_url.'" target="_blank"><img src="http://cambridge105.fm/wp-content/uploads/2011/11/facebook-icon-small.png" width="16" height="16" alt="Facebook Page" title="Facebook Page" /> Facebook Page</a></p>'."\n\r";
			}
			  
			# Show Frequency list (Tax)
			$frequency_html='';
			$frequency_list = get_the_term_list( $post->ID, 'frequency', 'Show Frequency: ', ', ', '' );
			if ( '' != $frequency_list ) {
				$frequency_html= "<p>$frequency_list</p>\n";
			}
			
			$schedule = new Schedule(Schedule::GetCachedScheduleURL(), Schedule::GetProgrammesURL());
			$is_current = false;
			$nextshowing = $schedule->GetNextShowing($post->post_name, &$is_current);
			
			$nextonair_html='';
			if($nextshowing)
			{
				$nextonair_html = '<p>Next On Air: '.date('D jS M: ga', $nextshowing['start']).'</p>';
			}

			# Current / next episode
			$nowplaying_html='';
			
			$now = $schedule->GetCurrentShow();
			//if($now['pid']== basename(get_permalink())){
			if($now['pid']== $post->post_name){
				$nowplaying_html = '<h2 class="related-shows latest-podcast">now playing: '.$now['start_text'].' - '.$now['end_text'].'</h2>';
				$nowplaying_html.= '<div id="latest_podcast" class="widget widget_gigxrecentposts">';
				$nowplaying_html .= '<p>'.$now['desc'].'</p>';
				$nowplaying_html .= '</div>';
			}
			
			# Podcasts (posts-to-posts)
			// Find connected pages
			$podcasts_html = '';
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
					  $title = the_title('', '', false);
					  $permalink = apply_filters('the_permalink', get_permalink());
					  
					  if(!$first){
					     $podcasts_html.= '      <div class="twocol">';
					     $podcasts_html.= '      <div class="twocol-1 twocol-content">';
					     
					     $podcasts_html.= '      <p><a href="' . esc_url($permalink) . '" title="'.esc_attr($title).'">';   
					     $podcasts_html.=          get_show_image('shows-thumb');
					     $podcasts_html.=          $title;
					     $podcasts_html.= '      </a>';
					     global $more; $more = 0;
					     $podcasts_html.= '      <br/>' . gigx_excerpt (get_the_content(),get_the_excerpt(),false,500,$permalink,'more',False);
					     //$podcasts_html.= '      <br/>' . get_the_content('[...]');
					     //$podcasts_html.= '      <br/>' . get_the_excerpt();
					     $podcasts_html.=  do_shortcode('[powerpress]');
					     $podcasts_html.= '      </p></div>';
					     $podcasts_html.= '      <div class="twocol-2 twocol-content">Previous Shows:<ul >';
					     
					     
					     $first=true;
					  }
					  else{
					     $podcasts_html.= '      <li><a href="' . esc_url($permalink) . '" title="'.esc_attr($title).'">';   
					     $podcasts_html.=          $title;
					     $podcasts_html.= '      </a>';
					     $podcasts_html.=  do_shortcode('[powerpress]');
					     $podcasts_html.= '      </li>';
					     //global $more; $more = 0;
					     //$podcasts_html.= '      <br/>' . gigx_excerpt (get_the_content(),get_the_excerpt(),false,100,$permalink,'more',True);
					     
					  }
					endwhile;
					$podcasts_html.= '</ul></div></div></div>';
					// Prevent weirdness
					wp_reset_postdata();
				endif;
			}
			 ?>
			
			<div <?php post_class("clearfix") ?> id="post-<?php the_ID(); ?>">
				<?php if ( function_exists('yoast_breadcrumb') && $postcount ==1) yoast_breadcrumb('<div id="breadcrumbs">','</div>'); ?>				
				<?php if (is_single()) : ?>			
					<div class="alignleft" style="width: 312px;">
						<?php echo $img_html ?>
						<?php if (is_single()) echo $website_html; ?>
						<?php if (is_single()) echo $twitter_html; ?>
						<?php if (is_single()) echo $facebook_html; ?>
						<?php if (is_single()) echo $frequency_html; ?>
						<?php if (is_single()) echo $nextonair_html; ?>
						<?php //if (is_single()) echo $tags ?>
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
									$title = the_title('', '', false);
									if(isset($permalink)){
										$img_html = '<div class="shows-thumb"><a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">' . get_show_image('shows-thumb') . '</a></div>';  
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
