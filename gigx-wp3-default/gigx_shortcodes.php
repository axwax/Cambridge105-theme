<?php
/*
File Description: Shortcodes
Built By: GIGX
Theme Version: 0.5

This needs sorting out - I just copied and pasted it in case we want to have our own shortcodes
it isn't actually tested
*/

## progressbar ##
/* shortcode */
// [progressbar title="title" goal="100" current="50" previous="10" label="percent"]

/**
 * Processes [progressbar] shortcode
 * 
 * @param array $atts User defined attributes in shortcode tag.
 * @access public
 * @return string HTML markup of meter.
 */
function gigx_progressbar_func($atts) {
  $opts = apply_filters('gigx_progressbar_shortcode_atts',
                        array(),
                        $atts);
  return gigx_progressbar_generate_meter($opts['title'],
                                      $opts['goal'],
                                      $opts['current'],
                                      $opts['previous'],
                                      $opts['label'],
                                      $opts['separator'],
                                      $opts['class'],
                                      $opts['prefix'],
                                      $opts['error']
                                      );
  
}

/**
 * Hooks into gigx_progressbar_shortcode_atts.
 *
 * Parses out user attributes from shortcode.
 * 
 * @param array $opts Contains arguments to pass to 
 * {@link gigx_progressbar_generate_meter}, parsed out of {@link $atts}.
 * @param array $atts User defined attributes in shortcode tag.
 * @static
 * @access public
 * @return Combined and filtered attribute list.
 * @see shortcode_atts
 */
function gigx_progressbar_shortcode_atts_default($opts,$atts) {
  return shortcode_atts(array(
                              'title' => '',
                              'goal' => 0,
                              'current' => 0,
                              'previous' => 0,
                              'label' => '',
                              'separator' => '/',
                              'class' => '',
                              'prefix' => '',
                              'error' => '',
                              ), $atts);
}

    add_shortcode('progressbar', 'gigx_progressbar_func');
    add_filter('gigx_progressbar_shortcode_atts', 
               'gigx_progressbar_shortcode_atts_default',
               1, 2);

/**
 * Generate progressbar meter
 *
 * Generates meter based on input parameters.
 * 
 * @param string $title 
 * @param int $goal 
 * @param int $current 
 * @param int $previous (optional) defaults to 0
 * @param string $label (optional)
 * @param string $separator (optional) defaults to '/'
 * @param string $class (optional)
 * @param string $prefix (optional)
 * @param string $error (optional) if set, will display instead of meter
 * @access public
 * @return HTML markup of progress meter.
 */
function gigx_progressbar_generate_meter( $title, $goal, $current, $previous=0,
                                       $label="", $separator='/', $class='',
                                       $prefix='', $error='') {

  /** @todo Add filter(s) */
  if ($error) {
    return "<div><b>progressbar Error:</b><i>$error</i></div>";
  }
  if ($previous == '') {
    $previous = 0;
  }
  /* avoid divide by zero */
  if ($goal == 0) {
    return '';
  }
  $goal_label = "Goal: " . $goal;
  $prog_label = $current;
  $new_label = '';	
  $new_width = 0;
  if ($previous > 0 && $current < $goal) {
    $new = $current - $previous;
    $new_width = (int)(($new/$goal)*100);
    $current_width = (int)(($previous/$goal)*100);
    if ($new_width + $current_width != 100) {
      $new_width++;
    }
    $prog_label = $previous;
    $new_label = $new;
  } else {
    $current_width= (int)(($current/$goal)*100);
    if ($current_width > 100) {
      $current_width = 100;
    }
  }
  $isfeed = is_feed(); 
  $class = trim('gigx_pb ' . $class);
  
  $ret = '<div class="'.$class.'"'. 
    ($isfeed ? ' style="width: 80%; max-width:200px;margin:0 auto;padding:0;text-align:center;_width:200px;" ' :'') .'>'.
    '<div class="gigx_pb_title"'. ($isfeed ? ' style="font-weight: bold" ' : '') . '>'.$title.'</div>'.
    '<div class="gigx_pb_meter" '. gigx_progressbar_generate_title($goal_label,$label) . ($isfeed ? ' style="border: 1px solid #000; height: 20px; overflow: hidden; padding: 2px; width: 100%;" ' : '') . ' >'.
    '<div class="gigx_pb_prog" '. gigx_progressbar_generate_title($prog_label,$label) .' style="width:'.$current_width.'%;' . ($isfeed ? ' background-color: #000; float: left; height: 100%' : '') .'"><!--*--></div>'.
    '<div class="gigx_pb_new" ' . gigx_progressbar_generate_title($new_label,$label) .  ' style="width:'.$new_width.'%;'. ($isfeed ? ' background-color: #000; float: left; height: 100%' : '') .'"><!--*--></div>'.
    '</div>'.
    '<span class="gigx_pb_count">'.
    '<span class="gigx_pb_current">' . 
    ($prefix ? '<span class="gigx_pb_prefix">'.$prefix.'</span>' : '') .
    number_format($current) . '</span>' .
    '<span class="gigx_pb_separator">' . $separator .'</span>'.
    '<span class="gigx_pb_goal">' . 
    ($prefix ? '<span class="gigx_pb_prefix">'.$prefix.'</span>' : '') .
    number_format($goal) . '</span>';
  if (strcmp("",$label) != 0) {
    $ret .= ' <span class="gigx_pb_label">' . $label . '</span>';
  }
  $ret .= '</span></div>';
  return $ret;
} 

/**
 * Generates title attribute for meter markup.
 * 
 * @param string $value 
 * @param string $label 
 * @access public
 * @return string 'title="text" ' or ''
 * @todo add filter
 */
function gigx_progressbar_generate_title($value,$label) {
  $ret = '';
  if (strcmp('',$value) != 0) {
    $ret = 'title="' . $value;
    if (strcmp('',$label) != 0) {
      $ret .= " " . $label;
    }
    $ret .= '"';
  }
  return $ret;
}



## end progressbar ##

# start box
function gigx_box( $atts, $content = null ) {
   return '<div class="gigx_box">' . $content . '</div>';
}

add_shortcode('box', 'gigx_box');	

# end box

function thmfooter_wp_link() {
    return '<a class="wp-link" href="http://WordPress.org/" title="WordPress" rel="generator">WordPress</a>';
}
add_shortcode('wp-link', 'thmfooter_wp_link');		  
		  
function thmfooter_theme_link() {
    $themelink = '<a class="theme-link" href="http://themeshaper.com/thematic/" title="Thematic Theme Framework" rel="designer">Thematic Theme Framework</a>';
    return apply_filters('thematic_theme_link',$themelink);
}
add_shortcode('theme-link', 'thmfooter_theme_link');	

function thmfooter_login_link() {
    if ( ! is_user_logged_in() )
        $link = '<a href="' . get_option('siteurl') . '/wp-login.php">' . __('Login','thematic') . '</a>';
    else
    $link = '<a href="' . wp_logout_url($redirect) . '">' . __('Logout','thematic') . '</a>';
    return apply_filters('loginout', $link);
}
add_shortcode('loginout-link', 'thmfooter_login_link');		  	  

function thmfooter_blog_title() {
	return '<span class="blog-title">' . get_bloginfo('name') . '</span>';
}
add_shortcode('blog-title', 'thmfooter_blog_title');

function thmfooter_blog_link() {
	return '<a href="' . get_option('siteurl') . '" title="' . get_option('blogname') . '" >' . get_option('blogname') . "</a>";
}
add_shortcode('blog-link', 'thmfooter_blog_link');

function thmfooter_year() {   
    return '<span class="the-year">' . date('Y') . '</span>';
}
add_shortcode('the-year', 'thmfooter_year');

// Providing information about Thematic

function theme_name() {
    return THEMENAME;
}
add_shortcode('theme-name', 'theme_name');

function theme_author() {
    return THEMEAUTHOR;
}
add_shortcode('theme-author', 'theme_author');

function theme_uri() {
    return THEMEURI;
}
add_shortcode('theme-uri', 'theme_uri');

function theme_version() {
    return THEMATICVERSION;
}
add_shortcode('theme-version', 'theme_version');

// Providing information about the child theme

function child_name() {
    return TEMPLATENAME;
}
add_shortcode('child-name', 'child_name');

function child_author() {
    return TEMPLATEAUTHOR;
}
add_shortcode('child-author', 'child_author');

function child_uri() {
    return TEMPLATEURI;
}
add_shortcode('child-uri', 'child_uri');

function child_version() {
    return TEMPLATEVERSION;
}
add_shortcode('child-version', 'child_version');

?>