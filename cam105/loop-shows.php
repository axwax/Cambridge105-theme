<?php
/*
File Description: The Loop for "Shows" Custom Post Type
Author: Axel Minet
Theme Version: 0.5.11
*/
?>

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
          if(has_post_thumbnail()) {
	           the_post_thumbnail('shows-image',array('class'	=> "alignleft"));
          } else {
	           echo '<img src="'.get_bloginfo("template_url").'/images/shows-default.png" />';
          } 
          ?>
              	<?php if ( is_active_sidebar( 'above_entry_widgets' ) ) : // Nothing here by default and design ?>
                	<div class="above-entry-widgets">
                		<?php 

		# construct title
		$title_tag='h1';
		$single_title_tag='h2';
		
		$title = the_title('', '', false);		
    if ( $title && !is_singular() ) {
			$permalink = apply_filters('the_permalink', get_permalink());
			$title = '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">'
				. $title
				. '</a>';
		}
		if ( $single_title_tag && is_singular() ) {
      $title_tag=$single_title_tag;
		}		
		if ( $title && $title_tag && !is_page()) {
      echo'<' . $title_tag . ' class="post-title">' . $title . '</' . $title_tag . '>';
		}
    echo "\n\r";  
     echo'<h4> ('.get_the_term_list( $post->ID, 'genres', '', ', ', '' ).')</h4>';               
                     ?>
                     
                	</div>  
                <?php endif; ?>

                
        				<div class="entry"> 
        					<?php the_content('Read the rest of this entry &raquo;'); ?>
        				</div>

              <?php if(defined ('CUSTOM_POST_TYPE') && is_singular(CUSTOM_POST_TYPE)) {

                  get_template_part(CUSTOM_POST_TYPE);
                }
                
			
  			# Something to build our output in
            $taxo_text = "";
            
			# Website Custom Meta
			$website_title=get_post_meta($post->ID, 'WebsiteTitle', True);
            $website_url=get_post_meta($post->ID, 'WebsiteURL', True);
			if ( ('' != $website_title) and ('' != $website_url)  ) {
                $taxo_text .= '<p>Website: <a href="'.$website_url.'" target="_blank">'.$website_title.'</p>\n";
            }
            
            # Let's find out if we have taxonomy information to display
            # Variables to store each of our possible taxonomy lists
            $frequency_list = get_the_term_list( $post->ID, 'frequency', '<strong>Show Frequency:</strong> ', ', ', '' );
            $genres_list = get_the_term_list( $post->ID, 'genres', '<strong>Genre(s):</strong> ', ', ', '' );
            
            # Presenters list
			/* remove presenters list
            $presenters_array = get_posts( array(
              'suppress_filters' => false,
              'post_type' => 'presenters',
              'connected' => $post->ID,
            ) );
            $presenters_list='';
            $oldpost=$post;
            $count=0;
            foreach($presenters_array as $post) :
               setup_postdata($post);
               $presenters_list.='<a href="'.get_permalink().'">'.get_the_title().'</a>';
               $presenters_list.=', ';
               $count++;
            endforeach; 
            */

            
            /* removed presenters list
            $presenters_list=substr_replace($presenters_list,'',-2); //strip last ', '
            $post=$oldpost;
            if ($count<1)$presenters_list= '';
            else if ($count>1)$presenters_list= '<strong>Presenters:</strong> '.$presenters_list;
            else $presenters_list= '<strong>Presenter:</strong> '.$presenters_list; 
            */

            
            /* removed presenters
            // Add presenters list if this post was so tagged
            if ( '' != $presenters_list ) {
                $taxo_text .= "<p>$presenters_list</p>\n";
            }
            */
			
            # Add frequency list if this post was so tagged
            if ( '' != $frequency_list ) {
                $taxo_text .= "<p>$frequency_list</p>\n";
            }
            # Add genres list if this post was so tagged
            if ( '' != $genres_list ) {
                //$taxo_text .= "$genres_list<br />\n";
            }
            // Output taxonomy information if there was any
            // NOTE: We won't even open a div if there's nothing to put inside it.
            if ( '' != $taxo_text ) {
            ?>
            <div class="entry-utility">
            <?php
            echo $taxo_text;
            ?>
            </div>
            <?
            } // endif
            

			#schedule
            #schedule test
            $schedule_array = get_posts( array(
              'suppress_filters' => false,
              'post_type' => 'gigx_schedule',
              'connected' => $post->ID,
            ) );
            $schedule_list='';
            $oldpost=$post;
            $count=0;
            foreach($schedule_array as $post) :
               setup_postdata($post);
               $start=get_post_custom_values("gigx_schedule_startdate");
               $end=get_post_custom_values("gigx_schedule_enddate");
               $schedtime= date('jS F Y g:ia',$start[0]).' - '.date('g:ia',$end[0]);
               $schedule_list.='<h3><a href="'.get_permalink().'"><strong>'.$schedtime.'</strong> - '.$title.'</a></h3>';
               $schedule_list.='<p>'.get_the_excerpt().'</p>';
               $schedule_list.=', ';
               $count++;
            endforeach; 
            $schedule_list=substr_replace($schedule_list,'',-2); //strip last ', '
            $post=$oldpost;
            if ($count<1)$schedule_list= '';
            else if ($count>1)$schedule_list= '<strong>Schedule:</strong> '.$schedule_list;
            else $schedule_list= '<strong>Next Show:</strong> '.$schedule_list; 
            if ( '' != $schedule_list ) {
                echo '<div class="schedule_entry">'.$schedule_list.'</div>';
            }
			#schedule
                
                
                
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