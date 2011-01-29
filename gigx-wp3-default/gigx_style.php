<?php 
global $shortname;
header('Content-type: text/css');   
header("Cache-Control: must-revalidate"); 
$offset = 72000 ; 
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT"; 
header($ExpStr);
$settings = get_option($shortname.'_options');
// Variables should be added with {} brackets
echo <<<CSS
/*Style Sheet Start*/
body {
background-color: {$settings['theme_option_color']};
}
CSS;
//More php can go here
echo <<<CSS
/*Style Sheet End*/
CSS;
?>
