<?php
/*
File Description: Default Index Page
Built By: GIGX
Theme Version: 0.5.10
*/
define('CUSTOM_POST_TYPE',get_post_type()); 
get_header();
?>

	<div id="content">
    <?php get_template_part( 'loop', CUSTOM_POST_TYPE ); ?>
	</div>

 
<?php get_sidebar(); ?>
<?php get_footer(); ?>