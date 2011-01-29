<?php
/*
File Description: The Sidebar
Built By: GIGX
Theme Version: 0.5
*/
?><div id="sidebar">
	<div id="sidebar-right">   
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('main_sidebar') ) : else : ?>	    
	    <div id="archives" class="widget-container">
        <h2>Recent Posts</h2>
  			<ul>
  			<?php get_archives('postbypost', 10); ?>
  			</ul>
      </div>					    
	    <?php $tagcloud= wp_tag_cloud( array( 'taxonomy' => 'post_tag', 'format' => 'array', 'echo' => false) );
	          if($tagcloud) : ?> 
              <div id="tags" class="widget-container">
                <h2><?php _e('Tags'); ?></h2>
      				  <?php echo $tagcloud; ?>
      				</div>
			<?php endif; ?>
      <div id="meta" class="widget-container">		  
  	   	<h2><?php _e('Meta'); ?></h2>
  			<ul>
  				<?php wp_register(); ?>
  				<li><?php wp_loginout(); ?></li>
  				<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
  				<?php wp_meta(); ?>
  		  </ul> 
      </div> 	    	 
	  <?php endif; ?>	
	</div>
</div>