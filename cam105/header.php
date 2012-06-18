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
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

  <head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(''); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" media="screen" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="image_src" href="<?php echo $fbimg[0]; ?>" />
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
  </head>
  
  <body <?php body_class($bodyclass); ?>>
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
				<?php /* marathon hour counter */
				      $hour_number = ceil((time() - strtotime('2012-06-01 15:00')) / 60 / 60); 
					  if($hour_number <= 105){?>
				<div style="height: 120px; width: 230px; float: right; position: relative"><img style="position: absolute; height: 120px; left: 35px; opacity: 0.4; z-index: 100" src="http://cambridge105.fm/wp-content/uploads/2012/05/marathonslide.png" />
				<div style="position: absolute; text-align: center; font-family: Harabara,HarabaraRegular; font-size: 3em; line-height: 0.8em; padding-top: 0.4em; z-index: 121; color: white; opacity: 1; width: 100%;opacity: 1.9;text-shadow: 0px 0px 8px black;letter-spacing: 3px;text-decoration:none"><a style="color: white" href="/shows/marathon">hour<br /><?php echo $hour_number ?></a></div></div>							
				<?php }/* end */ ?>
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
 
        </div><!-- end of header div -->

          <?php wp_nav_menu( array( 'theme_location' => 'below-header', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>

		  <div id="main" class="clearfix">
     
  
        <div id="container">
        