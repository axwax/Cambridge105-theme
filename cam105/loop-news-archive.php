<?php
/*
File Description: The Loop for the main News page 
Author: Axel Minet
Theme Version: 0.5.11
*/
?>

  	<?php wp_nav_menu( array( 'theme_location' => 'above-posts', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
  	<?php if ( is_active_sidebar( 'above_posts_widgets' ) ) : // Widgets Above Posts ?>
    	<div id="above-posts-widgets">
    		<?php dynamic_sidebar('above_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>
       
    <div class="posts">
        <?php 
		
        $args = array('numberposts'=>3, 'offset' => 0,'orderby' => 'post_date', 'order'=>'DESC','post_status' => 'publish');    
        $args['category_name']='headline-news';
        $args['numberposts']=1;
        
        $news=get_posts( $args );
        //print_r($news);
        foreach($news as $post) : setup_postdata($post); ?>
          <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
            
            <?php
            $img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'shows-image',false);
        		//print_r($img);
            $excerpt = $post->post_excerpt;
            $content = $post->post_content;
            $link=get_page_link($page->ID);
            if (function_exists('gigx_excerpt')){
              $content=gigx_excerpt ($content,$excerpt,false,500,$link,'(more...)',True);
            }
            echo '<div class="wp-caption alignleft" style="width: 310px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$post->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';
            echo '<h1 class="post-title">'.get_the_title().'</h1>';
            echo '<span class="postdate">Posted on '. get_the_time('jS F Y') .'</span>'; ?> 
            <div class="entry" style="padding-top:10px;">
              <?php echo $content; ?>
          	</div>
          </div>

        <?php endforeach; ?>            


        <div class="twocol">
            <?php
            $args['category_name']='local-news';
            $args['numberposts']=2;
            
            $news=get_posts( $args );
            //print_r($news);
            $count=0;
            foreach($news as $post) : setup_postdata($post); 
            $count++;
            ?>
              <div class="twocol_content col_<?php echo $count; ?>" id="post-<?php the_ID(); ?>">
                
                <?php
                $img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'shows-image',false);
            		//print_r($img);
                $excerpt = $post->post_excerpt;
                $content = $post->post_content;
                $link=get_page_link($page->ID);
                if (function_exists('gigx_excerpt')){
                  $content=gigx_excerpt ($content,$excerpt,false,300,$link,'(more...)');
                }
                echo '<div class="wp-caption" style="width: 310px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$post->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';
                echo '<h1 class="post-title">'.get_the_title().'</h1>';
                echo '<span class="postdate">Posted on '. get_the_time('jS F Y') .'</span>'; ?> 
                <div class="entry" style="padding-top:10px;">
                  <?php echo $content; ?>
              	</div>
              </div>
    
            <?php endforeach; ?>            
        </div>       





            
<?php        
/*        
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
*/
         ?>
    </div><!-- end of posts div -->  

  	<?php if ( is_active_sidebar( 'below_posts_widgets' ) ) : // Widgets Below Posts ?>
    	<div id="below-posts-widgets">
    		<?php dynamic_sidebar('below_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>