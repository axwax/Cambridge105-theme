<?php

/**
 * Create meta boxes for editing pages in WordPress
 * Compatible with custom post types in WordPress 3.0
 *
 * Support input types: text, textarea, checkbox, radio box, select, file, image
 *
 * @author: Rilwis
 * @url: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 * @version: 2.4.1
 *
 * Changelog:
 * - 2.4.1: fix bug of not receiving value for select box
 * - 2.4: (image upload features are credit to Kai http://twitter.com/ungestaltbar)
 *   + change image upload using meta fields to using default WP gallery
 *   + add delete button for images, using ajax
 *   + allow to upload multiple images
 *   + add validation for meta fields
 * - 2.3: add wysiwyg editor type, improve check for upload fields, change context and priority attributes to optional
 * - 2.2: add enctype to post form (fix upload bug), thanks to http://www.hashbangcode.com/blog/add-enctype-wordpress-post-and-page-forms-471.html
 * - 2.1: add file upload, image upload support
 * - 2.0: oop code, support multiple post types, multiple meta boxes
 * - 1.0: procedural code
 */

/*
Usage: for more information, please visit: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html

// Register meta boxes

$prefix = 'dbt_';

$meta_boxes = array();

// first meta box
$meta_boxes[] = array(
	'id' => 'my-meta-box-1',
	'title' => 'Custom meta box 1',
	'pages' => array('post', 'page', 'link'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'priority' => 'high', // high, low (optional)
	'fields' => array(
		array(
			'name' => 'Text box',
			'desc' => 'Enter something here',
			'id' => $prefix . 'text',
			'type' => 'text', // text box
			'std' => 'Default value 1',
			'validate_func' => 'check_text' // validate function, created below, inside RW_Meta_Box_Validate class
		),
		array(
			'name' => 'Textarea',
			'desc' => 'Enter big text here',
			'id' => $prefix . 'textarea',
			'type' => 'textarea', // text area
			'std' => 'Default value 2'
		),
		array(
			'name' => 'Select box',
			'id' => $prefix . 'select',
			'type' => 'select', // select box
			'options' => array( // array of name, value pairs for select box
				array('name' => 'Name 1', 'value' => 'Value 1'),
				array('name' => 'Name 2', 'value' => 'Value 2')
			)
		),
		array(
			'name' => 'Radio',
			'id' => $prefix . 'radio',
			'type' => 'radio', // radio box
			'options' => array( // array of name, value pairs for radio options
				array('name' => 'Name 1', 'value' => 'Value 1'),
				array('name' => 'Name 2', 'value' => 'Value 2')
			)
		),
		array(
			'name' => 'Checkbox', // check box
			'id' => $prefix . 'checkbox',
			'type' => 'checkbox'
		)
	)
);

// second meta box
$meta_boxes[] = array(
	'id' => 'my-meta-box-2',
	'title' => 'Custom meta box 2',
	'pages' => array('post', 'film', 'album'), // custom post types, since WordPress 3.0
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Rich editor',
			'id' => $prefix . 'wysiwyg',
			'type' => 'wysiwyg', // WYSIWYG editor
			'std' => 'Default value 2'
		),
		array(
			'name' => 'File upload',
			'desc' => 'For file upload',
			'id' => $prefix . 'file',
			'type' => 'file' // file upload
		),
		array(
			'name' => 'Image upload',
			'desc' => 'For image upload',
			'id' => $prefix . 'image',
			'type' => 'image' // image upload
		),

	)
);

foreach ($meta_boxes as $meta_box) {
	$my_box = new RW_Meta_Box($meta_box);
}

// Validate value of meta fields

// Define ALL validation methods inside this class
// and use the names of these methods in the definition of meta boxes (key 'validate_func' of each field)

class RW_Meta_Box_Validate {
	function check_text($text) {
		if ($text != 'hello') {
			return false;
		}
		return true;
	}
}
*/

/**
 * AJAX delete images on the fly. This script is a slightly altered version of a function used by the Plugin "Verve Meta Boxes"
 * http://wordpress.org/extend/plugins/verve-meta-boxes/
 *
 */
add_action('wp_ajax_unlink_file', 'unlink_file_callback');
function unlink_file_callback() {
	global $wpdb;
	if ($_POST['data']) {
		$data = explode('-', $_POST['data']);
		$att_id = $data[0];
		$post_id = $data[1];
		wp_delete_attachment($att_id);
	}
}

/**
 * Create meta boxes
 */
class RW_Meta_Box {

	protected $_meta_box;

	// create meta box based on given data
	function __construct($meta_box) {
		if (!is_admin()) return;

		$this->_meta_box = $meta_box;

		// fix upload bug: http://www.hashbangcode.com/blog/add-enctype-wordpress-post-and-page-forms-471.html
		$upload = false;
		foreach ($meta_box['fields'] as $field) {
			if ($field['type'] == 'file' || $field['type'] == 'image') {
				$upload = true;
				break;
			}
		}
		$current_page = substr(strrchr($_SERVER['PHP_SELF'], '/'), 1, -4);
		if ($upload && ($current_page == 'page' || $current_page == 'page-new' || $current_page == 'post' || $current_page == 'post-new')) {
			add_action('admin_head', array(&$this, 'add_post_enctype'));
			add_action('admin_head', array(&$this, 'add_unlink_script'));
			add_action('admin_head', array(&$this, 'add_clone_script'));
		}

		add_action('admin_menu', array(&$this, 'add'));

		add_action('save_post', array(&$this, 'save'));
	}

	function add_post_enctype() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#post").attr("enctype", "multipart/form-data");
			jQuery("#post").attr("encoding", "multipart/form-data");
		});
		</script>';
	}

	function add_unlink_script(){
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$("a.deletefile").click(function () {
				var parent = jQuery(this).parent(),
					data = jQuery(this).attr("rel"),
					_wpnonce = $("input[name=\'_wpnonce\']").val();

				$.post(
					ajaxurl,
					{action: \'unlink_file\', _wpnonce: _wpnonce, data: data},
					function(response){
						//$("#info").html(response).fadeOut(3000);
						//alert(data.post);
					},
					"json"
				);
				parent.fadeOut("slow");
				return false;
			});
		});
		</script>';
	}

	function add_clone_script() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".add").click(function() {
				jQuery("#newimages p:first-child").clone().insertAfter("#newimages p:last").show();
				return false;
			});
			jQuery(".remove").click(function() {
				jQuery(this).parent().remove();
			});
		});
		</script>';
	}


	/// Add meta box for multiple post types
	function add() {
		$this->_meta_box['context'] = empty($this->_meta_box['context']) ? 'normal' : $this->_meta_box['context'];
		$this->_meta_box['priority'] = empty($this->_meta_box['priority']) ? 'high' : $this->_meta_box['priority'];
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
		}
	}

	// Callback function to show fields in meta box
	function show() {
		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		echo '<table class="form-table">';

		foreach ($this->_meta_box['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);

			echo '<tr>',
					'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
					'<td>';
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
						'<br />', $field['desc'];
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="15" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
						'<br />', $field['desc'];
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
					}
					echo '</select>';
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
					}
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					break;
				case 'file':
					echo $meta ? "$meta<br />" : '', '<input type="file" name="', $field['id'], '" id="', $field['id'], '" />',
						'<br />', $field['desc'];
					break;
				case 'wysiwyg':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" class="theEditor" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
						'<br />', $field['desc'];
					break;
				case 'image':
					?>
					<h2>Images attached to this post</h2>
					<div id="uploaded">
						<?php
						$args = array(
							'post_type' => 'attachment',
							'post_parent' => $post->ID,
							'numberposts' => -1,
							'post_status' => NULL
						);
						$attachs = get_posts($args);
						if (!empty($attachs)) {
							foreach ($attachs as $att) {
							?>
								<div class="single-att" style="margin: 0 10px 10px 0; float: left;">
									<?php echo wp_get_attachment_image($att->ID, 'thumbnail'); ?>
									<br />
									<a class="deletefile" href="#" rel="<?php echo $att->ID ?>-<?php echo $post_id ?> "title="Delete this file">Delete Image</a>
								</div>
							<?php
							}
						} else {
							echo 'No Images uploaded yet';
						}
						?>
					</div>

					<h2>Upload new Images</h2>
                    <div id="newimages">
						<p><input type="file" name="<?php echo $field['id'] ?>[]" id="" /></p>
						<a class="add" href="#">Add More Images</a>
					</div>
					<?php
					break;
			}
			echo 	'<td>',
				'</tr>';
		}

		echo '</table>';
	}

	// Save data from meta box
	function save($post_id) {
		// verify nonce
		if (!wp_verify_nonce($_POST['wp_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		foreach ($this->_meta_box['fields'] as $field) {
			$name = $field['id'];

			$old = get_post_meta($post_id, $name, true);
			$new = $_POST[$field['id']];

			/*
			// changed to using WP gallery
			if ($field['type'] == 'file' || $field['type'] == 'image') {
				$file = wp_handle_upload($_FILES[$name], array('test_form' => false));
				$new = $file['url'];
			}
			*/

			if ($field['type'] == 'file' || $field['type'] == 'image') {
				if (!empty($_FILES[$name])) {
					$this->fix_file_array($_FILES[$name]);
					foreach ($_FILES[$name] as $position => $fileitem) {
						$file = wp_handle_upload($fileitem, array('test_form' => false));
						$filename = $file['url'];
						if (!empty($filename)) {
							$wp_filetype = wp_check_filetype(basename($filename), null);
							$attachment = array(
								'post_mime_type' => $wp_filetype['type'],
								'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
								'post_status' => 'inherit'
							);
							$attach_id = wp_insert_attachment($attachment, $filename, $post_id);
							// you must first include the image.php file
							// for the function wp_generate_attachment_metadata() to work
							require_once(ABSPATH . 'wp-admin/includes/image.php');
							$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
							wp_update_attachment_metadata($attach_id, $attach_data);
						}
					}
				}
			}

			if ($field['type'] == 'wysiwyg') {
				$new = wpautop($new);
			}

			if ($field['type'] == 'textarea') {
				$new = htmlspecialchars($new);
			}

			// validate meta value
			if (isset($field['validate_func'])) {
				$ok = call_user_func(array('RW_Meta_Box_Validate', $field['validate_func']), $new);
				if ($ok === false) { // pass away when meta value is invalid
					continue;
				}
			}

			if ($new && $new != $old) {
				update_post_meta($post_id, $name, $new);
			} elseif ('' == $new && $old && $field['type'] != 'file' && $field['type'] != 'image') {
				delete_post_meta($post_id, $name, $old);
			}
		}
	}

	/**
	 * Fixes the odd indexing of multiple file uploads from the format:
	 *
	 * $_FILES['field']['key']['index']
	 *
	 * To the more standard and appropriate:
	 *
	 * $_FILES['field']['index']['key']
	 *
	 * @param array $files
	 * @author Corey Ballou
	 * @link http://www.jqueryin.com
	 */
	function fix_file_array(&$files) {
		$names = array(
			'name' => 1,
			'type' => 1,
			'tmp_name' => 1,
			'error' => 1,
			'size' => 1
		);

		foreach ($files as $key => $part) {
			// only deal with valid keys and multiple files
			$key = (string) $key;
			if (isset($names[$key]) && is_array($part)) {
				foreach ($part as $position => $value) {
					$files[$position][$key] = $value;
				}
				// remove old key reference
				unset($files[$key]);
			}
		}
	}
}

?>
