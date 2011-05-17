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
         ?>
    </div><!-- end of posts div -->  

  	<?php if ( is_active_sidebar( 'below_posts_widgets' ) ) : // Widgets Below Posts ?>
    	<div id="below-posts-widgets">
    		<?php dynamic_sidebar('below_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>