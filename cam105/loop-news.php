<?php
/*
File Description: The Loop for the main News page 
Author: Axel Minet
Theme Version: 0.6.2
*/
?>
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
              <div class="twocol-content col_<?php echo $count; ?>" id="post-<?php the_ID(); ?>">
                
                <?php
                $img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'shows-image',false);
            		//print_r($img);
                $excerpt = $post->post_excerpt;
                $content = $post->post_content;
                $link=get_page_link($page->ID);
                if (function_exists('gigx_excerpt')){
                  $content=gigx_excerpt ($content,$excerpt,false,300,$link,'(more...)',True);
                }
                echo '<div class="wp-caption" style="width: 310px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$post->post_title.'"/></div>';
                echo '<h3 class="post-title">'.get_the_title().'</h3>';
                echo '<span class="postdate">Posted on '. get_the_time('jS F Y') .'</span>'; ?> 
                <div class="entry" style="padding-top:10px;">
                  <?php echo $content; ?>
              	</div>
              </div>
    
            <?php endforeach; ?>            
        </div>       

        <div class="twocol">
            <?php
            $args['category_name']='external-news';
            $args['numberposts']=8;
            
            $news=get_posts( $args );
            //print_r($news);
            $count=0;
            foreach($news as $post) : setup_postdata($post); 
            $count++;
            ?>
              <div class="twocol-content twocol-<?php echo $count; ?>" id="post-<?php the_ID(); ?>">
                
                <?php

                $excerpt = $post->post_excerpt;
                $content = $post->post_content;
                $link=get_page_link($page->ID);
                if (function_exists('gigx_excerpt')){
                  $content=gigx_excerpt ($content,$excerpt,false,300,$link,'(more...)',True);
                }
				//echo "excerpt:$excerpt-content:$content";
                //echo '<div class="wp-caption" style="width: 310px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$post->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';
                echo '<h5 class="post-title">'.get_the_title().'</h5>';
                echo '<span class="postdate">Posted on '. get_the_time('jS F Y') .'</span>'; ?> 
                <div class="entry" style="padding-top:10px;">
                  <?php echo $content; ?>
              	</div>
              </div>
    
            <?php endforeach; ?>            
        </div>  
</div><!-- end of posts div -->  