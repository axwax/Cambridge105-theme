<?php
/*
File Description: Default Index Page
Author: Axel Minet
Theme Version: 0.6.2
*/

define('CUSTOM_POST_TYPE',get_post_type());
if(CUSTOM_POST_TYPE=='page'){
   $loop = $post->post_name;  // page name
}
elseif (is_tag()) {
	$loop = 'tag';
   if (!locate_template( 'loop-'.$loop.'.php', false, false )) $loop="taxonomy";
}
elseif (is_category()) {
	$loop = 'category';
   if (!locate_template( 'loop-'.$loop.'.php', false, false )) $loop="taxonomy";
}
elseif (is_tax()) {
	$loop = 'taxonomy';
}
else {
   $loop = CUSTOM_POST_TYPE;
}
get_header();
?>
	<div id="content" class="main_columns">
   
      <?php wp_nav_menu( array( 'theme_location' => 'above-posts', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>

      <?php if ( is_active_sidebar( 'above_posts_widgets' ) ) : // Widgets Above Posts ?>
         <div id="above-posts-widgets">
            <?php dynamic_sidebar('above_posts_widgets'); ?>
         </div>  
      <?php endif; ?>
      
		<?php get_template_part( 'loop', $loop ); ?>
      
      <?php if ( is_active_sidebar( 'below_posts_widgets' ) ) : // Widgets Below Posts ?>
         <div id="below-posts-widgets">
            <?php dynamic_sidebar('below_posts_widgets'); ?>
         </div>  
      <?php endif; ?>      

	</div>
   
<?php get_sidebar(); ?>
<?php get_footer(); ?>
<? if (GIGX_DEBUG === true) echo "<!-- ".$loop." -->\r\n";?>