<?php
/**
 * Semantic Markup is made up of class-generating functions
 * that dynamically generate context sensitive classes for your HTMl elements
 * to give unprecedented control over your layout options via CSS.
 * 
 * @package WP Framework
 * @subpackage Semantic Markup
 */

add_filter( 'body_class', 'wpf_filter_body_class', 20 );
add_filter( 'post_class', 'wpf_filter_post_class', 20, 3 );
add_filter( 'comment_class', 'wpf_filter_comment_class', 20 );

/**
 * Enhances the body_class() with more semantic classes.
 *
 * @since 0.3.0
 */
function wpf_filter_body_class( $classes ) {
	global $wpf_theme, $wp_query, $wp_registered_sidebars;

	// Starts the semantic markup array
	$classes[] = 'wpf';

	// locale
	$classes[] = get_locale();

	// BuddyPress Support
	if ( is_bp_component_page() )
		$classes[] = 'bp';

	// @todo find a way to get active widget-areas on the current page. is that possible?

	// Device & Browser detection
	$classes = array_merge( $classes, wpf_semantic_device_detection() );

	// Applies the time- and date-based classes
	// @todo need to adjust for daylight savings
	$time = time() + ( get_option( 'gmt_offset' ) * 3600 );
	$classes[] = strtolower( gmdate( '\yY \mm \dd \hH l', $time ) );

	// Singular post (post_type) classes
	if ( is_singular() ) {

		$post_type = get_post_type();

		// Checks for custom template
		$template = str_replace( array( "{$post_type}-template-", "{$post_type}-", '.php' ), '', get_post_meta( get_the_ID(), "_wp_{$post_type}_template", true ) );
		if ( $template )
			$classes[] = "{$post_type}-template-{$template}";

		// Comment status class
		$classes[] = ( ( comments_open() ) ? 's-comments-open' : 's-comments-closed' );

		// Attachment mime types
		if ( is_attachment() ) {
			foreach ( explode( '/', get_post_mime_type() ) as $type )
				$classes[] = "attachment-{$type}";
		}
	}

	// Paged views
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
		$classes[] = 'paged paged-' . intval( $page );
	
	// note: - maybe add this: defined('WP_DEBUG') && WP_DEBUG
	if ( $wpf_theme->requested_template ) {
		$classes[] = 'theme-' . strtolower( get_stylesheet() );
		preg_match( '/('. get_template() .'|'. get_stylesheet() .')\/(.*)/is', $wpf_theme->requested_template, $matches );
		$template = str_replace( '/', '_', str_replace( '.', '-', $matches[0] ) );
		$classes[] = 'template-'. strtolower( $template );
	}
	
	/* Merge WP classes with WPF classes */
	// $classes = array_merge( (array) $bp_classes, (array) $wp_classes );

	$classes = array_unique( $classes );
	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'wpf_filter_body_class', $classes );
}

/**
 * Enhances the post_class() with more semantic classes.
 *
 * @since 0.3.0
 */
function wpf_filter_post_class( $classes, $class, $post_id ) {
	static $post_alt, $post_count;

	$post = get_post( $post_id );

	if ( !empty($post) ) {

		// post status
		$classes[] = "status-{$post->post_status}";

		// Password-protected posts
		if ( post_password_required() )
			$classes[] = 'protected';

		// Post comment
		$classes[] = 'postnum-' . ++$post_count;

		// Post alt class
		$classes[] = 'post-' . ++$post_alt;
		$classes[] = ( $post_alt % 2 ) ? 'odd' : 'even alt';

		// Author class
		$classes[] = 'author-' . sanitize_html_class( get_the_author_meta( 'user_nicename' ), get_the_author_meta( 'ID' ) );

		// Get the post taxonomies and terms if any
		$taxonomies = get_object_taxonomies( $post );
		if ( !empty( $taxonomies ) ) {
			$classes = array_merge( $classes, array_map( '__wpf_convert_post_tag_to_tag', $taxonomies) );

			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_the_terms( $post_id, $taxonomy );
				if ( !empty($terms) ) {
					foreach ( $terms as $term ) {
						if ( !empty($term->slug ) ) {
							$classes[] = __wpf_convert_post_tag_to_tag( $term->taxonomy ) . '-' . sanitize_html_class( $term->slug,  $term->term_id );
						}
					}
				}
			}
		}
	} else {
		$classes[] = 'post-0';
	}

	$classes = array_unique( $classes );
	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'wpf_filter_post_class', $classes );
}

/**
 * Enhances the comment_class() with more semantic classes.
 *
 * @since 0.3.0
 */
function wpf_filter_comment_class( $classes ) {
	global $comment;
	static $comment_count;

	// Comment count
	$classes[] = 'commentnum-' . ++$comment_count;

	// Comment Parent
	if ( $comment->comment_parent )
		$classes[] = 'parent';

	// Comment Status
	$classes[] = wp_get_comment_status( $comment->comment_ID );

	// User classes to match user role and user.
	if ( 0 < $comment->user_id ) {

		// Get the commentor's details
		$commenter = new WP_User( $comment->user_id );

		// Set a class with the user's role
		if ( is_array( $commenter->roles ) ) {
			foreach ( $commenter->roles as $role )
				$classes[] = "role-{$role}";
		}

		// Set a class with the user's name.
		$classes[] = 'user-' . sanitize_html_class( $commenter->user_nicename, $commenter->ID );
	}

	// If not a registered user
	else {
		$classes[] = 'visitor';
	}

	$classes = array_unique( $classes );
	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'wpf_filter_comment_class', $classes );
}

/**
 * Returns an array of device and browser info based on the user agent.
 *
 * @since 0.3.0
 */
function wpf_semantic_device_detection() {
	global $wpf_theme, $is_IE, $is_opera, $is_safari, $is_chrome, $is_iphone;

	if ( !empty($wpf_theme->user_agent) )
		return $wpf_theme->user_agent;

	// A little browser detection shall we?
	$user_agent = strtolower( $_SERVER[ 'HTTP_USER_AGENT' ] );

	$classes = array();

	// Apple iOS Devices
	if ( $is_iphone ) {
		array_push( $classes, 'mobile', 'ios' );

		if ( false !== stripos( $user_agent, 'ipod' ) )
			$classes[] = 'ipod';

		elseif ( false !== stripos( $user_agent, 'iphone' ) )
			$classes[] = 'iphone';

		elseif ( false !== stripos( $user_agent, 'ipad' ) )
			$classes[] = 'ipad';
	}

	// Android
	elseif ( false !== stripos( $user_agent, 'android' ) )
		array_push( $classes, 'mobile', 'android' );

	// WebOS
	elseif ( false !== stripos( $user_agent, 'webos' ) )
		array_push( $classes, 'mobile', 'webos' );

	// Backberry
	elseif ( false !== stripos( $user_agent, 'blackberry' ) )
		array_push( $classes, 'mobile', 'blackberry' );

	// Windows Mobile
	elseif ( false !== stripos( $user_agent, 'windows ce' ) )
		array_push( $classes, 'mobile', 'win' );

	// OS: Mac, PC ...or Linux?
	if ( !in_array( 'mobile', $classes ) ) {
		if ( false !== stripos( $user_agent, 'mac' ) )
			$classes[] = 'mac';

		elseif ( false !== stripos( $user_agent, 'windows' ) )
			$classes[] = 'win';

		elseif ( false !== stripos( $user_agent, 'linux' ) )
			$classes[] = 'linux';
	}

	// Chrome
	if ( $is_chrome )
		$browser = 'chrome';

	// Safari
	elseif ( $is_safari )
		$browser = 'safari';

	// Opera
	elseif ( $is_opera )
		$browser = 'opera';

	// Internet Explorer
	elseif ( $is_IE ) {
		$browser = 'ie';

		if ( false !== stripos( $user_agent, 'msie 6.0' ) )
			$classes[] = 'ie6';

		elseif ( false !== stripos( $user_agent, 'msie 7.0' ) )
			$classes[] = 'ie7';

		elseif ( false !== stripos( $user_agent, 'msie 8.0' ) )
			$classes[] = 'ie8';

		elseif ( false !== stripos( $user_agent, 'msie 9.0' ) )
			$classes[] = 'ie9';
	}

	// Firefox
	elseif ( false !== stripos( $user_agent, 'firefox' ) )
		$browser = 'firefox';

	$classes[] = $browser;

	$wpf_theme->user_agent = apply_filters( 'wpf_semantic_device_detection', $classes );

	return $wpf_theme->user_agent;
}

/**
 * Helper function for wpf_filter_post_class();
 *
 * @since 0.3.0
 */
function __wpf_convert_post_tag_to_tag( $taxonomy ) {
	return ( 'post_tag' == $taxonomy ) ? 'tag' : $taxonomy;
}

/* Remember: Semantic Classes, like the Sandbox, is for play. (-_^) */