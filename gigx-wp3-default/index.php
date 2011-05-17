<?php
/*
File Description: Default Index Page
Built By: GIGX
Theme Version: 0.5.11
*/
 
get_header();
?>

	<div id="content">
    <?php get_template_part( 'loop', 'index' ); ?>
	</div>

 
<?php get_sidebar(); ?>
<?php get_footer(); ?>