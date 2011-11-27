<?php
/**
 * Template Name: Shows Archive
 *
 * Selectable from a dropdown menu on the edit page screen.
 */
?>

<?php get_header();
 ?>

			<div id="content">

				<?php
				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				 wp_sfw_render();
				get_template_part( 'loop', 'cpt-archive' ); 
				?>

			</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
