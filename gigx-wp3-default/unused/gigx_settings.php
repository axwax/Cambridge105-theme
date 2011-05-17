<?php
/*
File Description: Widgets Collection
Built By: GIGX
Theme Version: 0.5.11


This needs sorting out - I just copied and pasted it in case we want to have our own theme settings
it isn't actually tested/doing anything
(but there is a GIGX options entry in the admin area under Appearance)
This may be a good place for having a button to (re-)set up default widget settings
*/

// Theme options adapted from "A Theme Tip For WordPress Theme Authors"
// http://literalbarrage.org/blog/archives/2007/05/03/a-theme-tip-for-wordpress-theme-authors/

$themename = "GIGX Default Theme";
$shortname = "gigx_default_theme";

// Create theme options

$options = array (

				array(	"name" => 'Index Insert Position',
						"desc" => 'The widgetized Index Insert will follow after this post number.',
						"id" => $shortname."_insert_position",
						"std" => "2",
						"type" => "text"),

      	array(
      		"id" => $shortname."_body_font_family",
      		"name" => "Base Font Family",
      		"std" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
      		"type" => "radio",
      		"options" => array(
      			"'Segoe UI', 'Arial Narrow', 'Helvetica Neue', sans-serif",
      			"Georgia, Times, serif",
      			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
      			"'Trebuchet MS', Helvetica, sans-serif",
      			"Candara, Verdana, Geneva, sans serif"
      		)	
      	),

				array(	"name" => 'Text in Footer',
						"desc" => "You can use the following shortcodes in your footer text: [wp-link] [theme-link] [loginout-link] [blog-title] [blog-link] [the-year]",
						"id" => $shortname."_footertext",
						"std" => "Powered by [wp-link]. Built on the [theme-link].",
						"type" => "textarea",
						"options" => array(	"rows" => "5",
											"cols" => "94") ),

		);


function mytheme_add_admin() {

    global $themename, $shortname, $options, $blog_id;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=theme-options.php&saved=true");
                die;
          } else if( 'reset' == $_REQUEST['action'] ) {
          
          // delete options
          foreach ($options as $value) {
          delete_option( $value['id'] ); }
          
          // update standards
          foreach ($options as $value) {
          update_option( $value['id'], $value['std'] ); }
          
          header("Location: themes.php?page=theme-options.php&reset=true");
          die;
/* doesn't save defaults:
        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=theme-options.php&reset=true");
            die;
*/            

        } else if ( 'resetwidgets' == $_REQUEST['action'] ) {
            update_option('sidebars_widgets',NULL);
            header("Location: themes.php?page=gigx_settings.php&resetwidgets=true");
            die;
        }  else if ( 'defaultwidgets' == $_REQUEST['action'] ) {
              update_option("sidebars_widgets",
              array("main_sidebar" => array("categories","archives","meta"),
                    "above_header_widgets" => Null,
                    "below_header_widgets" => Null,                    
                    "above_posts_widgets" => array("GIGX Page Title"),
                    "above_entry_widgets" => array("GIGX Post Title","GIGX Post Date","GIGX Post Author"),
                    "below_entry_widgets" => Null,
                    "below_posts_widgets" => Null,
                    "above_footer_widgets" => Null,
                    "below_footer_widgets" => Null,
                    ));
            header("Location: themes.php?page=gigx_settings.php&defaultwidgets=true");
            die;
        } 
    }                    

    add_theme_page($themename." Options", "GIGX Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' '.'settings saved.'.'</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' '.'settings reset.'.'</strong></p></div>';
    if ( $_REQUEST['resetwidgets'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' '.'widgets reset.'.'</strong></p></div>';
    if ( $_REQUEST['defaultwidgets'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' '.'default widgets set.'.'</strong></p></div>';
    
?>
<div class="wrap">
<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
<h2><?php echo $themename; ?> Options</h2>

<form method="post" action="">

	<table class="form-table">

<?php foreach ($options as $value) { 
	
	switch ( $value['type'] ) {
		case 'text':
		?>
		<tr valign="top"> 
			<th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
			<td>
				<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
				<?php echo $value['desc']; ?>

			</td>
		</tr>
		<?php
		break;
		
		case 'select':
		?>
		<tr valign="top">
			<th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
				<td>
					<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
					<?php foreach ($value['options'] as $option) { ?>
					<option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
		break;
		
		case 'textarea':
		$ta_options = $value['options'];
		?>
		<tr valign="top"> 
			<th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
			<td><textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php 
				if( get_option($value['id']) != "") {
						echo stripslashes(get_option($value['id']));
					}else{
						echo $value['std'];
				}?></textarea><br /><?php echo $value['desc']; ?></td>
		</tr>
		<?php
		break;

		case 'radio':
		?>
		<tr valign="top"> 
			<th scope="row"><?php echo $value['name']; ?></th>
			<td>
				<?php foreach ($value['options'] as $key=>$option) { 
				$radio_setting = get_option($value['id']);
				if($radio_setting != ''){
					if ($key == get_option($value['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
				<input type="radio" name="<?php echo $value['id']; ?>" id="<?php echo $value['id'] . $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><label for="<?php echo $value['id'] . $key; ?>"><?php echo $option; ?></label><br />
				<?php } ?>
			</td>
		</tr>
		<?php
		break;
		
		case 'checkbox':
		?>
		<tr valign="top"> 
			<th scope="row"><?php echo $value['name']; ?></th>
			<td>
				<?php
					if(get_option($value['id'])){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				?>
				<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
				<label for="<?php echo $value['id']; ?>"><?php echo $value['desc']; ?></label>
			</td>
		</tr>
		<?php
		break;

		default:

		break;
	}
}
?>

	</table>

	<p class="submit">
		<input class="button-primary" name="save" type="submit" value="<?php echo 'Save changes'; ?>" />    
		<input type="hidden" name="action" value="save" />
	</p>
</form>
<form method="post" action="">
	<p class="submit">
		<input class="button-secondary" name="reset" type="submit" value="<?php echo 'Reset'; ?>" />
		<input type="hidden" name="action" value="reset" />
	</p>
</form>
<form method="post" action="">
	<p class="submit">
		<input class="button-secondary" name="default_widgets" type="submit" value="<?php echo 'Default Widgets'; ?>" />
		<input type="hidden" name="action" value="defaultwidgets" />
	</p>
</form>
<form method="post" action="">
	<p class="submit">
		<input class="button-secondary" name="reset_widgets" type="submit" value="<?php echo 'Reset Widgets'; ?>" />
		<input type="hidden" name="action" value="resetwidgets" />
	</p>
</form>

<p><?php echo 'For more information about this theme, <a href="http://themeshaper.com">visit ThemeShaper</a>. Please visit the <a href="http://themeshaper.com/forums/">ThemeShaper Forums</a> if you have any questions about Thematic.'; ?></p>
</div>
<?php
}

add_action('admin_menu' , 'mytheme_add_admin'); 


?>