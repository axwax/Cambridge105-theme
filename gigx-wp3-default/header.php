<?php
/*
File Description: Default Header
Built By: GIGX
Theme Version: 0.6.2
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

  <head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(''); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" media="screen" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="image_src" href="<?php bloginfo('stylesheet_directory'); ?>/images/facebookdefault.gif" />
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>
  </head>
  
  <body <?php body_class(); ?>>
    <!--[if lte IE 6]><script src="<?php bloginfo('template_directory'); ?>/js/ie6/warning.js"></script><script>window.onload=function(){e("<?php bloginfo('template_directory'); ?>/js/ie6/")}</script><![endif]-->

    <div id="page">

      	<?php if ( is_active_sidebar( 'above_header_widgets' ) ) : // Widgets Above Header ?>
        	<div id="above-header-widgets">
        		<?php dynamic_sidebar('above_header_widgets'); ?>
        	</div>  
        <?php endif; ?>      

        <?php wp_nav_menu( array( 'theme_location' => 'above-header', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>

        <div id="header">
        	<div id="logo">
  				<a href="<?php echo home_url();?>"><?php
				// Check if this is a post or page, if it has a thumbnail, and if it's a big one
  					if ( is_singular() &&
  							has_post_thumbnail( $post->ID ) &&
  							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail') ) &&
  							$image[1] >= HEADER_IMAGE_WIDTH ) :
  						// Houston, we have a new header image!
  						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
  					elseif (get_header_image()) : ?>
  						<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="<?php bloginfo('show'); ?>" />
  					<?php endif; ?>
				</a>
        	</div> 
        </div><!-- end of header div -->

        <div id="main">
          <?php wp_nav_menu( array( 'theme_location' => 'below-header', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
  
        	<?php if ( is_active_sidebar( 'below_header_widgets' ) ) : // Widgets Below Header ?>
          	<div id="below-header-widgets-container">
              <div id="below-header-widgets">
            		<?php dynamic_sidebar('below_header_widgets'); ?>
            	</div>
            </div>  
          <?php endif; ?>      
  
        <div id="container">
        