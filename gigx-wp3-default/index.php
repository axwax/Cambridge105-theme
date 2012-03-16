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
	<div id="content">
		<?php get_template_part( 'loop', $loop ); ?>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
<? if (GIGX_DEBUG === true) echo "<!-- ".$loop." -->\r\n";?>