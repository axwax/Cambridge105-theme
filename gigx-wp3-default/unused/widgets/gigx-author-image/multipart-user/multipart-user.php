<?php
/*
 * Multipart User Form
 * Author: Denis de Bernardy <http://www.mesoconcepts.com>
 * Version: 1.0
 */

/**
 * ob_multipart_user()
 *
 * @param string $buffer Output buffer
 * @return string $buffer Modified buffer
 **/

function ob_multipart_user($buffer) {
	return str_replace(
		'<form id="your-profile"',
		'<form enctype="multipart/form-data" id="your-profile"',
		$buffer
		);
} # ob_multipart_user()


if ( !function_exists('add_max_file_size') ) :
/**
 * add_max_file_size()
 *
 * @return void
 **/

function add_max_file_size() {
	$bytes = apply_filters('import_upload_size_limit', wp_max_upload_size());
	
	echo  "\n" . '<input type="hidden" name="MAX_FILE_SIZE" value="' . esc_attr($bytes) .'" />' . "\n";
} # add_max_file_size()
endif;

ob_start('ob_multipart_user');

add_action('show_user_profile', 'add_max_file_size');
add_action('edit_user_profile', 'add_max_file_size');
?>