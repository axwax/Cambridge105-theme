<?php
/*
File Description: The template used to display Comments
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to gigx_comment which is
 * located in the functions.php file
Built By: GIGX
Theme Version: 0.5.12
*/
?>

			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<div class="nopassword">This post is password protected. Enter the password to view any comments.</div>
			</div><!-- .comments -->
<?php
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
			<h3 id="comments-title">
<?php
    printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'gigx' ),
        number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
?>
            </h3>

<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( '&larr; Older Comments' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Newer Comments &rarr;' ); ?></div>
			</div>
<?php endif; // check for comment navigation ?>

			<ol class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'gigx_comment' ) ); ?>
			</ol>

<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( '&larr; Older Comments' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Newer Comments &rarr;' ); ?></div>
			</div>
<?php endif; // check for comment navigation ?>

<?php else : // this is displayed if there are no comments so far ?>

<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

<?php else : // if comments are closed ?>

<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>

</div><!-- #comments -->
