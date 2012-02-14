<?php
/*
File Description: loop for ajax-driven read more pages
Author: Axel Minet
Theme Version: 0.6.1
*/
?>
<?php if (have_posts()) : ?>
	<?php /* Start the Loop */ ?>  	
	<?php while (have_posts()) : the_post();
		# Show Title (Shows CPT)
		$title_tag='h1';
		$single_title_tag='h2';
		$title = the_title('', '', false);		
		if ( $title && !is_singular() ) {
			$permalink='';
			$permalink = apply_filters('the_permalink', get_permalink());
			if(isset($permalink)) {
				$title = '<a href="' . esc_url($permalink) . '" title="' . esc_attr($title) . '">'.$title.'</a>';
			}
		}
		if ( $single_title_tag && is_singular() ) {
			$title_tag=$single_title_tag;
		}		
		if ( $title && $title_tag && !is_page()) {
			$title_html='<' . $title_tag . ' class="post-title">' . $title . '</' . $title_tag . '>'."\n\r";
		}
		?>
			<div <?php post_class("clearfix") ?> id="post-<?php the_ID(); ?>"> 		
				<?php echo $title_html ?>
				<?php echo $img_html ?>
				
				<div class="entry" style="padding-top:10px;">
					<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
				</div>
				<div class="entry-utility">
					<?php echo $website_html; ?>
					<?php echo $frequency_html; ?>
				</div>
				<?php 
					if(defined ('CUSTOM_POST_TYPE') && is_singular(CUSTOM_POST_TYPE)) {
						 get_template_part(CUSTOM_POST_TYPE);
					}
				?>
			</div>

			<?php comments_template( '', true ); ?> 			
	<?php endwhile; ?>