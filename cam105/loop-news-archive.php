<?php

?>

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
      
    <div class="posts">

        <?php /* Do we have posts, then start the loop, otherwise display 404 */ ?>
      	<?php query_posts( array('post_type'=> 'post','posts_per_page'=>6));
      	// set $more to 0 in order to only get the first part of the post
        global $more;
        $more = 0;
        $atts = array(
            'title' => 'Cambridge News', // post title
            'link' => '' . get_bloginfo( 'url' ).'news', //post title url
            'separator' => '<br/>', //Separator
            'hideposttitle' => 0, // hide post title
            'afterexcerpt' => ' [more...]',
					  'afterexcerptlink' => 1, // link to post?        
            'show_type' => 'post', // post type
            'postoffset' => 0, // Number of posts to skip 
            'shownum' => 10, // Number of posts to show
            'reverseorder' => 0, // Show posts in reverse order?  
            'excerpt' => 300,  // excerpt length (letters)
            'excerptlengthwords' => 30, // excerpt length (words) 
            'actcat' => false, // get posts current category
            'cats' => '4',  // Categories to get posts (post IDs, comma-separated)
            'cusfield' => '', // custom field name of thumbnail image
            'width' => 64,  // image width
            'height' => 64, // image height
            'firstimage' => true,  // get first image of post content
            'showauthor' => 0, // Show post author?
            'showtime' => 1,   // show post date/time?
            'format' => 'jS F Y h:ia',  //date/time format
            'atimage' => false, //get first attached image of post
            'defimage' => '', //default thumbnail image
            'spot' => 'spot2' //Put Time: 'spot1' => 'Before Title', 'spot2' => 'After Title', 'spot3' => 'After Separator'
        );

        echo '<div class="cambridgenews widget_gigxrecentposts"><h2 class="widget-title">Cambridge News</h2>';     
        $atts['shownum']=1;
        $atts['width']=200;
        $atts['height']=200;
        $atts['excerpt']=600;
        $atts['excerptlengthwords']=60;   
        gigx_recentposts($atts);
        
        $atts['shownum']=2;
        $atts['width']=64;
        $atts['height']=64;
        $atts['excerpt']=300;
        $atts['excerptlengthwords']=30;        
        $atts['postoffset']=1;
        gigx_recentposts($atts);
        echo '</div>';
        
        echo '<div class="stationnews widget_gigxrecentposts"><h2 class="widget-title">Station News</h2>';
        $atts['cats']=30;
        $atts['shownum']=1;
        $atts['width']=200;
        $atts['height']=200;
        $atts['excerpt']=600;
        $atts['excerptlengthwords']=60;        
        $atts['postoffset']=0;
        gigx_recentposts($atts);
        echo '</div>';
        
        if (have_posts()) : ?>
          <?php /* Start the Loop */ ?>  	
      		<?php while (have_posts()) : the_post(); ?>
 		
        			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
            
          <?php
          $title = the_title('', '', false);
    			$permalink = apply_filters('the_permalink', get_permalink());
    			echo '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">';
          if(has_post_thumbnail()) {
	           the_post_thumbnail('shows-thumb',array('class'	=> "alignleft",'alt'	=> esc_attr($title), 'title'	=> esc_attr($title)));
          } else {
	           echo '<img src="'.get_bloginfo("template_url").'/images/shows-default.png" />';
          }
          echo'</a>'; 
          ?>

              	<?php if ( is_active_sidebar( 'above_entry_widgets' ) ) : // Nothing here by default and design ?>
                	<div class="above-entry-widgets">
                		<?php dynamic_sidebar('above_entry_widgets'); ?>
                	</div>  
                <?php endif; ?>
        				<div class="entry"> 
        					<?php 
                  echo get_the_term_list( $post->ID, 'genres', '', ', ', '' );
                  the_content('more &raquo;');
                  echo get_the_term_list( $post->ID, 'frequency', '', ', ', '' );
                   ?>    
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