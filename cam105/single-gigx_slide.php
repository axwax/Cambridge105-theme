<?php
/*
File Description: Default Index Page
Built By: GIGX
Theme Version: 0.5
*/
 
get_header();
?>

	<div id="content">
    <?php get_template_part( 'loop', 'gigx_slide' ); ?>
	</div>

 
<?php get_sidebar(); ?>
<?php get_footer(); ?>