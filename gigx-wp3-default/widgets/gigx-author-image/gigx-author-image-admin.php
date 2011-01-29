<?php
/**
 * author_image_admin
 *
 * @package Author Image
 **/

class author_image_admin {
	/**
	 * edit_image()
	 *
	 * @return void
	 **/
	
	function edit_image() {
		if ( !is_dir(WP_CONTENT_DIR . '/authors') && !wp_mkdir_p(WP_CONTENT_DIR . '/authors') ) {
			echo '<div class="error">'
				. '<p>'
				. sprintf(__('Author Images requires that your %s folder be writable by the server', 'gigx-author-image'), 'wp-content')
				. '</p>'
				. '</div>' . "\n";
			return;
		} elseif ( !is_writable(WP_CONTENT_DIR . '/authors') ) {
			echo '<div class="error">'
				. '<p>'
				. sprintf(__('Author Images requires that your %s folder be writable by the server', 'gigx-author-image'), 'wp-content/authors')
				. '</p>'
				. '</div>' . "\n";
			return;
		}
		
		echo '<h3>'
			. __('Author Image', 'gigx-author-image')
			. '</h3>';
		
		global $profileuser;
		$author_id = $profileuser->ID;
		
		$author_image = author_image::get_meta($author_id);
		$author_image_url = content_url() . '/authors/' . str_replace(' ', rawurlencode(' '), $author_image);
		
		echo '<table class="form-table">';
		
		if ( $author_image ) {
			echo '<tr valign="top">'
				. '<td colspan="2">'
				. '<img src="' . esc_url($author_image_url) . '" alt="" />'
				. '<br />'. "\n";
			
			if ( is_writable(WP_CONTENT_DIR . '/authors/' . $author_image) ) {
				echo '<label for="delete_author_image">'
					. '<input type="checkbox"'
						. ' id="delete_author_image" name="delete_author_image"'
						. ' />'
					. '&nbsp;'
					. __('Delete author image', 'gigx-author-image')
					. '</label>';
			} else {
				echo __('This author image is not writable by the server.', 'gigx-author-image');
			}
			
			echo '</td></tr>' . "\n";
		}
		
		if ( !$author_image || is_writable(WP_CONTENT_DIR . '/authors/' . $author_image) ) {
			echo '<tr valign-"top">'
				. '<th scope="row">'
				. __('New Image', 'gigx-author-image')
				. '</th>'
				. '<td>';
			
			echo '<input type="file"'
				. ' id="author_image" name="author_image"'
				. ' />'
				. ' ';
			
			if ( defined('GLOB_BRACE') ) {
				echo __('(jpg, jpeg or png)', 'gigx-author-image') . "\n";
			} else {
				echo __('(jpg)', 'gigx-author-image') . "\n";
			}
			
			echo '</td>'
				. '</tr>' . "\n";
		}
		
		echo '</table>' . "\n";
	} # edit_image()
	
	
	/**
	 * save_image()
	 *
	 * @return void
	 **/
	
	function save_image($user_ID) {
		if ( !$_POST )
			return;
		
		if ( isset($_FILES['author_image']['name']) && $_FILES['author_image']['name'] ) {
			$user = get_userdata($user_ID);
			$author_login = $user->user_login;
			
			if ( defined('GLOB_BRACE') ) {
				if ( $image = glob(WP_CONTENT_DIR . '/authors/' . $author_login . '{,-*}.{jpg,jpeg,png}', GLOB_BRACE) ) {
					foreach ( $image as $img ) {
						if ( preg_match("#/$author_login-\d+\.(?:jpe?g|png)$#", $img) ) {
							@unlink($img);
						}
					}
				}
			} else {
				if ( $image = glob(WP_CONTENT_DIR . '/authors/' . $author_login . '-*.jpg') ) {
					foreach ( $image as $img ) {
						if ( preg_match("#/$author_login-\d+\.jpg$#", $img) ) {
							@unlink($img);
						}
					}
				}
			}
			
			$tmp_name =& $_FILES['author_image']['tmp_name'];
			
			preg_match("/\.([^.]+)$/", $_FILES['author_image']['name'], $ext);
			$ext = end($ext);
			$ext = strtolower($ext);

			if ( !in_array($ext, array('jpg', 'jpeg', 'png')) ) {
				echo '<div class="error">'
					. "<p>"
						. "<strong>"
						. __('Invalid File Type.', 'gigx-author-image')
						. "</strong>"
					. "</p>\n"
					. "</div>\n";
			} else {
				$entropy = intval(get_site_option('gigx_entropy')) + 1;
				update_site_option('gigx_entropy', $entropy);

				$new_name = WP_CONTENT_DIR . '/authors/' . $author_login . '-' . $entropy . '.' . $ext;

				// Set a maximum height and width
				$width = 360;
				$height = 360;

				// Get new dimensions
				list($width_orig, $height_orig) = getimagesize($tmp_name);

				if ( $width_orig > $width || $height_orig > $height ) {
					if ( $width_orig < $height_orig ) {
						$width = intval(($height / $height_orig) * $width_orig);
					} else {
						$height = intval(($width / $width_orig) * $height_orig);
					}

					// Resample
					$image_p = imagecreatetruecolor($width, $height);

					if ( $ext == 'png' ) {
						$image = imagecreatefrompng($tmp_name);
					} else {
						$image = imagecreatefromjpeg($tmp_name);
					}

					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
					
					imagejpeg($image_p, $new_name, 75);
				} else {
					move_uploaded_file($tmp_name, $new_name);
				}
				
				$stat = stat(dirname($new_name));
				$perms = $stat['mode'] & 0000666;
				@chmod($new_name, $perms);
			}
		} elseif ( isset($_POST['delete_author_image']) ) {
			$user = get_userdata($user_ID);
			$author_login = $user->user_login;

			if ( defined('GLOB_BRACE') ) {
				if ( $image = glob(WP_CONTENT_DIR . '/authors/' . $author_login . '{,-*}.{jpg,jpeg,png}', GLOB_BRACE) ) {
					foreach ( $image as $img ) {
						if ( preg_match("#/$author_login-\d+\.(?:jpe?g|png)$#", $img) ) {
							unlink($img);
						}
					}
				}
			} else {
				if ( $image = glob(WP_CONTENT_DIR . '/authors/' . $author_login . '-*.jpg') ) {
					foreach ( $image as $img ) {
						if ( preg_match("#/$author_login-\d+\.jpg$#", $img) ) {
							unlink($img);
						}
					}
				}
			}
		}

		delete_transient('author_image_cache');
		delete_usermeta($user_ID, 'author_image_cache');
		
		return $user_ID;
	} # save_image()
} # author_image_admin

add_action('edit_user_profile', array('author_image_admin', 'edit_image'));
add_action('show_user_profile', array('author_image_admin', 'edit_image'));
add_action('profile_update', array('author_image_admin', 'save_image'));
?>