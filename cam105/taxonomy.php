<?php
/*
File Description: The template used to display custom Taxonomies
Built By: GIGX
Theme Version: 0.5.9
*/
?>

<?php get_header();
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
 ?>

			<div id="content">

				<?php
				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'taxonomy' );
				?>

			</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
