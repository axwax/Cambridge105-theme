<?php
/*
File Description: The Loop
Built By: GIGX
Theme Version: 0.2
*/
?>
  	<?php if ( is_active_sidebar( 'above_posts_widgets' ) ) : // Widgets Above Posts ?>
    	<div id="above-posts-widgets">
    		<?php dynamic_sidebar('above_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>
       
    <div class="posts">
<?php if ( function_exists('yoast_breadcrumb') ) {
	yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?>
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
                		<?php dynamic_sidebar('above_entry_widgets'); ?>
                	</div>  
                <?php endif; ?>



                
        				<div class="entry"> 
        					<?php the_content('Read the rest of this entry &raquo;'); ?>
        				</div>

              <?php if(defined ('CUSTOM_POST_TYPE') && is_singular(CUSTOM_POST_TYPE)) {
                //echo CUSTOM_POST_TYPE;
                  get_template_part(CUSTOM_POST_TYPE);
                }
                
                
  					// Let's find out if we have taxonomy information to display
            // Something to build our output in
            $taxo_text = "";
            
            // Variables to store each of our possible taxonomy lists
            // This one checks for an Operating System classification
            //$presenters_list = get_the_term_list( $post->ID, 'presenters', '<strong>Presenter(s):</strong> ', ', ', '' );
            // add check for missing taxonomies

            $shows_array = get_posts( array(
              'suppress_filters' => false,
              'post_type' => 'shows',
              'connected_to' => $post->ID,
            ) );
            $shows_list='';
            $oldpost=$post;
            $count=0;
            foreach($shows_array as $post) :
               setup_postdata($post);
               $shows_list.='<a href="'.get_permalink().'">'.get_the_title().'</a>';
               $shows_list.=', ';
               $count++;
            endforeach; 


            $shows_list=substr_replace($shows_list,'',-2); //strip last ', '
            $post=$oldpost;
            if ($count<1)$shows_list= '';
            else if ($count>1)$shows_list= '<strong>Shows:</strong> '.$shows_list;
            else $shows_list= '<strong>Show:</strong> '.$shows_list; 
            $frequency_list = get_the_term_list( $post->ID, 'frequency', '<strong>Show Frequency:</strong> ', ', ', '' );
            $genres_list = get_the_term_list( $post->ID, 'genres', '<strong>Genre(s):</strong> ', ', ', '' );
            // Add OS list if this post was so tagged
            if ( '' != $shows_list ) {
                $taxo_text .= "$shows_list<br />\n";
            }
            // Add RAM list if this post was so tagged
            if ( '' != $frequency_list ) {
                $taxo_text .= "$frequency_list<br />\n";
            }
            // Add HD list if this post was so tagged
            if ( '' != $genres_list ) {
                $taxo_text .= "$genres_list<br />\n";
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