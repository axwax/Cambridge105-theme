<?php
/*
File Description: Default Index Page
Author: Axel Minet
Theme Version: 0.5.11
*/
define('CUSTOM_POST_TYPE',get_post_type());
if(CUSTOM_POST_TYPE=='page')$loop=$post->post_name;
else $loop= CUSTOM_POST_TYPE;
get_header();
?>

	<div id="content">
		<?php get_template_part( 'loop', $loop ); ?>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>