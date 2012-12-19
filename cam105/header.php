<?php
/*
File Description: Default Header
Built By: GIGX
Theme Version: 0.5.9
*/
define ('GIGX_DEBUG', true);
global $wp_query;
$post_id=$wp_query->post->ID;
$is_single= $wp_query->is_single;
if(!$wp_query->is_front_page() && $wp_query->is_page()) $bodyclass="non_homepage";
//else $bodyclass="ax";
#if ($is_single!=0) echo "single!";
#$post_parent=$wp_query->post->post_parent;
#if ($post_parent!=0) echo "child!";
#$children = get_pages('child_of='.$post_id);

# get image for facebook - look for featured image, then first attached image, then default
$fbimg='';
if($is_single!=0){
  $fbimg=wp_get_attachment_image_src (get_post_thumbnail_id($post_id),'facebook-thumb',false);
  if (!$fbimg) $fbimg=wp_get_attachment_image_src (gigx_find_image_attachment($post_id),'facebook-thumb',false);
}
if (!$fbimg) $fbimg= Array(get_bloginfo('stylesheet_directory').'/images/facebookdefault.gif');	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php diag("BEGIN HTML") ?>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

  <head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta property="fb:app_id" content="134459063259922" />
    <title><?php wp_title(''); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" media="screen" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="image_src" href="<?php echo $fbimg[0]; ?>" />
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
  </head>
  
  <?php diag("BEGIN BODY") ?>
  <body <?php body_class($bodyclass); ?>>
    <!--[if lte IE 6]><script src="<?php bloginfo('template_directory'); ?>/js/ie6/warning.js"></script><script>window.onload=function(){e("<?php bloginfo('template_directory'); ?>/js/ie6/")}</script><![endif]-->

	<?php diag("BEGIN PAGE") ?>
    <div id="page">

      	<?php if ( is_active_sidebar( 'above_header_widgets' ) ) : // Widgets Above Header ?>
        	<div id="above-header-widgets">
        		<?php dynamic_sidebar('above_header_widgets'); ?>
        	</div>  
        <?php endif; ?>      

        <?php wp_nav_menu( array( 'theme_location' => 'above-header', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
	
        <div id="header" class="clearfix">
	  <div id="header-container">  
		<?php diag("BEGIN HEADER") ?>

        	<div id="logo">
  				<a href="<?php echo home_url();?>"><?php
					if (get_header_image()) : ?>
  						<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="<?php bloginfo('show'); ?>" />
  					<?php endif; ?>
				</a>
        	</div> 

          <?php if ( is_active_sidebar( 'below_header_widgets' ) ) : // Widgets Below Header ?>
          	<div id="below-header-widgets-container">
              <div id="below-header-widgets">
            		<?php dynamic_sidebar('below_header_widgets'); ?>
            	</div>
            </div>  
          <?php endif; ?>  
	  </div><!-- end of header container div -->  
        </div><!-- end of header div -->
		<?php diag("END HEADER") ?>

          <?php wp_nav_menu( array( 'theme_location' => 'below-header', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>

		  <div id="main" class="clearfix">
     
     
		<?php diag("BEGIN CONTAINER") ?>
        <div id="container">
        