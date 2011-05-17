<?php
/*
Plugin Name: GIGX Author Image
Plugin URI: http://gigx.co.uk
Description: Adds authors images to your site, which individual users can configure in their profile. Your wp-content folder needs to be temporarily writable by the server during plugin installation.
Version: 4.0.2
Author: Axel Minet, Denis de Bernardy (original plugin)
Author URI: http://gigx.co.uk
Text Domain: gigx-author-image
Domain Path: /lang
*/

load_plugin_textdomain('gigx-author-image', false, dirname(plugin_basename(__FILE__)) . '/lang');

if ( !defined('gigx_author_image_debug') )
	define('gigx_author_image_debug', false);


/**
 * author_image
 *
 * @package Author Image
 **/

class author_image extends WP_Widget {
	/**
	 * init()
	 *
	 * @return void
	 **/

	function init() {
		if ( get_option('widget_author_image') === false ) {
			foreach ( array(
				'author_image_widgets' => 'upgrade',
				) as $ops => $method ) {
				if ( get_option($ops) !== false ) {
					$this->alt_option_name = $ops;
					add_filter('option_' . $ops, array(get_class($this), $method));
					break;
				}
			}
		}
	} # init()
	
	
	/**
	 * widgets_init()
	 *
	 * @return void
	 **/

	function widgets_init() {
		register_widget('author_image');
	} # widgets_init()
	
	
	/**
	 * author_image()
	 *
	 * @return void
	 **/

	function author_image() {
		$widget_ops = array(
			'classname' => 'author_image',
			'description' => __('Displays the post author\'s image', 'gigx-author-image'),
			);
		
		$this->init();
		$this->WP_Widget('author_image', __('Author Image', 'gigx-author-image'), $widget_ops);
	} # author_image()
	
	
	/**
	 * widget()
	 *
	 * @param array $args
	 * @param array $instance
	 * @return void
	 **/

	function widget($args, $instance) {
		if ( is_admin() )
			return;
		
		extract($args, EXTR_SKIP);
		$instance = wp_parse_args($instance, author_image::defaults());
		extract($instance, EXTR_SKIP);
		
		if ( $always ) {
			$author_id = author_image::get_single_id();
		} elseif ( in_the_loop() ) {
			$author_id = get_the_author_ID();
		} elseif ( is_singular() ) {
			global $wp_the_query;
			$author_id = $wp_the_query->posts[0]->post_author;
		} elseif ( is_author() ) {
			global $wp_the_query;
			$author_id = $wp_the_query->get_queried_object_id();
		} else {
			return;
		}
		
		if ( !$author_id )
			return;
		
		$image = author_image::get($author_id, $instance);
		
		if ( !$image )
			return;
		
		$desc = $bio ? trim(get_usermeta($author_id, 'description')) : false;
		
		$title = apply_filters('widget_title', $title);
		
		echo $before_widget;
		
		if ( $title )
			echo $before_title . $title . $after_title;
		
		echo $image . "\n";
		
		if ( $desc )
			echo wpautop(apply_filters('widget_text', $desc));
		
		echo $after_widget;
	} # widget()
	
	
	/**
	 * get_single_id()
	 *
	 * @return int $author_id
	 **/
	
	function get_single_id() {
		$author_id = get_transient('author_image_cache');
		
		if ( $author_id && !gigx_author_image_debug ) {
			return $author_id;
		} elseif ( $author_id === '' && !gigx_author_image_debug ) {
			return 0;
		} elseif ( !is_dir(WP_CONTENT_DIR . '/authors') ) {
			set_transient('author_image_cache', '');
			return 0;
		}
		
		# try the site admin first
		$user = get_user_by_email(get_option('admin_email'));
		if ( $user && $user->ID && author_image::get($user->ID) ) {
			set_transient('author_image_cache', $user->ID);
			return $user->ID;
		}
		
		global $wpdb;
		$author_id = 0;
		$i = 0;
		
		do {
			$offset = $i * 10;
			$limit = ( $i + 1 ) * 10;
			
			$authors = $wpdb->get_results("
				SELECT	$wpdb->users.ID,
						$wpdb->users.user_login
				FROM	$wpdb->users
				JOIN	$wpdb->usermeta
				ON		$wpdb->usermeta.user_id = $wpdb->users.ID
				AND		$wpdb->usermeta.meta_key = '" . $wpdb->prefix . "capabilities'
				JOIN	$wpdb->posts
				ON		$wpdb->posts.post_author = $wpdb->users.ID
				GROUP BY $wpdb->users.ID
				ORDER BY $wpdb->users.ID
				LIMIT $offset, $limit
				");
			
			if ( !$authors ) {
				set_transient('author_image_cache', '');
				return 0;
			}
			
			foreach ( $authors as $author ) {
				if ( defined('GLOB_BRACE') ) {
					$author_image = glob(WP_CONTENT_DIR . '/authors/' . $author->user_login . '{,-*}.{jpg,jpeg,png}', GLOB_BRACE);
				} else {
					$author_image = glob(WP_CONTENT_DIR . '/authors/' . $author->user_login . '-*.jpg');
				}

				if ( $author_image ) {
					$user = new WP_User($author->ID);
					if ( !$user->has_cap('publish_posts') && !$user->has_cap('publish_pages') )
						continue;
					$author_id = $author->ID;
					set_transient('author_image_cache', $author_id);
					return $author_id;
				}
			}
			
			$i++;
		} while ( !$author_id );
		
		set_transient('author_image_cache', '');
		return 0;
	} # get_single_id()
	
	
	/**
	 * get()
	 *
	 * @param bool $author_id
	 * @param array $instance
	 * @return string $image
	 **/

	function get($author_id = null, $instance = null,$before='<div class="entry_author_image">',$after='</div>') {
		if ( !$author_id ) {
			if ( in_the_loop() ) {
				$author_id = get_the_author_ID();
			} elseif ( is_singular() ) {
				global $wp_the_query;
				$author_id = $wp_the_query->posts[0]->post_author;
			} elseif ( is_author() ) {
				global $wp_the_query;
				$author_id = $wp_the_query->get_queried_object_id();
			} else {
				return;
			}
		}
		
		$author_image = get_usermeta($author_id, 'author_image');
		
		if ( $author_image === '' )
			$author_image = author_image::get_meta($author_id);
		
		if ( !$author_image )
			return;
		
		$instance = wp_parse_args($instance, author_image::defaults());
		extract($instance, EXTR_SKIP);
		
		$author_image = content_url() . '/authors/' . str_replace(' ', rawurlencode(' '), $author_image);
		$author_image = '<img src="' . esc_url($author_image) . '" alt="" />';
		
		if ( $link ) {
			if ( !$always )
				$author_link = get_author_posts_url($author_id);
			elseif ( get_option('show_on_front') != 'page' || !get_option('page_on_front') )
				$author_link = user_trailingslashit(get_option('home'));
			elseif ( $post_id = get_option('page_for_posts') )
				$author_link = apply_filters('the_permalink', get_permalink($post_id));
			else
				$author_link = user_trailingslashit(get_option('home'));
			
      #axmod
      if(get_the_author_meta( 'user_url' )) $author_link=get_the_author_meta( 'user_url' );	
			
			$author_image = '<a href="' . esc_url($author_link) . '">'
				. $author_image
				. '</a>';
		}
		
		return $before
			. $author_image
			. $after;
	} # get()
	
	
	/**
	 * update()
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 **/

	function update($new_instance, $old_instance) {
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['bio'] = isset($new_instance['bio']);
		$instance['link'] = isset($new_instance['link']);
		$instance['always'] = isset($new_instance['always']);
		
		delete_transient('author_image_cache');
		
		return $instance;
	} # update()
	
	
	/**
	 * form()
	 *
	 * @param array $instance
	 * @return void
	 **/

	function form($instance) {
		$instance = wp_parse_args($instance, author_image::defaults());
		extract($instance, EXTR_SKIP);
	
		echo '<p>'
			. '<label>'
			. __('Title:', 'gigx-author-image')
			. '<input type="text" id="' . $this->get_field_name('title') . '" class="widefat"'
			. ' name="'. $this->get_field_name('title') . '"'
			. ' value="' . esc_attr($title) . '"'
			. ' />'
			. '</label>'
			. '</p>' . "\n";
	
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="'. $this->get_field_name('bio') . '"'
			. checked($bio, true, false)
			. ' />'
			. '&nbsp;' . __('Display the author\'s bio', 'gigx-author-image')
			. '</label>'
			. '</p>' . "\n";
	
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="'. $this->get_field_name('link') . '"'
			. checked($link, true, false)
			. ' />'
			. '&nbsp;' . __('Link to the author\'s posts', 'gigx-author-image')
			. '</label>'
			. '</p>' . "\n";
		
		echo '<p>'
			. '<label>'
			. '<input type="checkbox"'
			. ' name="'. $this->get_field_name('always') . '"'
			. checked($always, true, false)
			. ' />'
			. '&nbsp;' . __('This site has a single author', 'gigx-author-image')
			. '</label>'
			. '</p>' . "\n"
			. '<p>'
			. __('Normally, this widget will only output something when in the loop or on singular posts or pages. Check the above checkbox if a single author has an image.', 'gigx-author-image')
			. '</p>' . "\n";
	} # form()
	
	
	/**
	 * defaults()
	 *
	 * @return array $instance
	 **/
	
	function defaults() {
		return array(
			'title' => '',
			'bio' => false,
			'link' => false,
			'always' => false,
			'widget_contexts' => array(
				'search' => false,
				'404_error' => false,
				),
			);
	} # defaults()
	
	
	/**
	 * get_meta()
	 *
	 * @param int $author_id
	 * @return string $image
	 **/

	function get_meta($author_id) {
		$user = get_userdata($author_id);
		$author_login = $user->user_login;
		
		if ( defined('GLOB_BRACE') ) {
			if ( $author_image = glob(WP_CONTENT_DIR . '/authors/' . $author_login . '{,-*}.{jpg,jpeg,png}', GLOB_BRACE) )
				$author_image = current($author_image);
			else
				$author_image = false;
		} else {
			if ( $author_image = glob(WP_CONTENT_DIR . '/authors/' . $author_login . '-*.jpg') )
				$author_image = current($author_image);
			else
				$author_image = false;
		}
		
		if ( $author_image ) {
			$author_image = basename($author_image);
			
			if ( !get_transient('author_image_cache') ) {
				$user = new WP_User($author_id);
				if ( $user->has_cap('publish_posts') || $user->has_cap('publish_pages') )
					set_transient('author_image_cache', $author_id);
			}
		} else {
			$author_image = 0;
		}
		
		update_usermeta($author_id, 'author_image', $author_image);
		
		return $author_image;
	} # get_meta()
	
	
	/**
	 * upgrade()
	 *
	 * @param array $ops
	 * @return array $ops
	 **/

	function upgrade($ops) {
		$widget_contexts = class_exists('widget_contexts')
			? get_option('widget_contexts')
			: false;
		
		foreach ( $ops as $k => $o ) {
			if ( isset($widget_contexts['author_image-' . $k]) ) {
				$ops[$k]['widget_contexts'] = $widget_contexts['author_image-' . $k];
			}
		}
		
		return $ops;
	} # upgrade()
} # author_image


/**
 * the_author_image()
 *
 * @param int $author_id
 * @param array $instance
 * @return void
 **/

function the_author_image($author_id = null, $instance = null) {
	echo author_image::get($author_id, $instance);
} # the_author_image()

/**
 * Axmod
 * get_the_author_image()
 *
 * @param int $author_id
 * @param array $instance
 * @return void
 **/

function get_the_author_image($author_id = null, $instance = null) {
	return author_image::get($author_id, $instance,'','');
} # the_author_image()


/**
 * author_image_admin()
 *
 * @return void
 **/

function author_image_admin() {
	include dirname(__FILE__) . '/gigx-author-image-admin.php';
} # author_image_admin()


if ( !function_exists('load_multipart_user') ) :
function load_multipart_user() {
	include dirname(__FILE__) . '/multipart-user/multipart-user.php';
} # load_multipart_user()
endif;

foreach ( array('profile', 'user-edit') as $hook ) {
	add_action("load-$hook.php", 'author_image_admin');
	add_action("load-$hook.php", 'load_multipart_user');
}


add_action('widgets_init', array('author_image', 'widgets_init'));
?>