<?php
/**
 * General WP Framework functions for use everywhere.
 *
 * @package WP Framework
 */

/**
 * Searches for a file in the child theme then parent theme directory
 * and returns either it's path or URL location.
 *
 * @since 0.3.0
 *
 * @param string $file The file to locate. Can be relative path from the theme's directory or full path.
 * @param string $type file|url Whether to return the file path or url location of the file.
 * @return string Returns either the path or URL location of the file.
 */
function get_theme_part( $file, $type = 'url' ) {
	if ( 'file' == $type ) {
		
		$files = array();
		foreach ( (array) $file as $_file ) {
			// Removes the first slash if nessecary.
			$_file = ltrim( $_file, '/' );
			$files[] = $_file;
		}

		// Return the file path of the file.
		$locate = locate_template( $files, false, false );

		return !empty( $locate ) ? $locate : false;
	}

	if ( 'url' == $type ) {
		$files = (array) $file;

		foreach ( $files as $location ) {
			if ( false !== stripos( $location, 'wp-content/' ) ) {
				// Remove everything before the theme's folder name.
				$match = preg_replace( '/(.*?)\/('. get_template() .'|'. get_stylesheet() .')\//is', '', $location );

				// Get the file path of the file.
				$locate = get_theme_part( $match, 'file' );

				// The file must exists.
				if ( !file_exists($locate) )
					return false;

				// Switch out the file path with the URL path to the file.
				return preg_replace( '/(.*?)\/wp-content/is', WP_CONTENT_URL, $locate );

			} elseif ( $location = get_theme_part( $location, 'file' ) ) {
				if ( !file_exists( $location ) )
					return false;

				// Must be a relative path, recursion baby!
				return get_theme_part( $location );
			}
		}
	}
}

/**
 * Returns an array of contextual data based on the requested page.
 * It does this by running through all the WordPress conditional tags
 * and for every condition that is true, it the function adds contextual data
 * specific to that condition into the array and finally returns it.
 *
 * @link http://codex.wordpress.org/Conditional_Tags/
 *
 * @since 0.3.0
 * @global $wp_query The current page's query object.
 * @global $wpf_theme The global Theme object.
 * @return Array Returns an array of contexts based on the query.
 */
function wpf_get_request() {
	// The query isn't parsed until wp, so bail if the function is called before.
	if ( !did_action( 'wp' ) )
		return false;

	global $wp_query, $wpf_theme;

	if ( isset($wpf_theme->request) && !empty($wpf_theme->request) )
		return $wpf_theme->request;

	/* Front page of the site. */
	if ( is_front_page() )
		$request[] = 'front_page';

	/* Blog page. */
	if ( is_home() )
		$request[] = 'home';

	/* Singular views. */
	elseif ( is_singular() ) {
		$request[] = 'singular';

		if ( wpf_is_subpage() )
			$request[] = 'subpage';

		$request[] = 'post_type_' . $wp_query->post->post_type;		
		$request[] = 'post_type_' . $wp_query->post->post_type . '_' . str_replace( '-', '_', $wp_query->post->post_name );
	}

	/* Archive views. */
	elseif ( is_archive() ) {
		$request[] = 'archive';

		/* Taxonomy archives. */
		if ( is_tax() || is_category() || is_tag() ) {
			$term = $wp_query->get_queried_object();
			$request[] = 'taxonomy';
			$request[] = 'taxonomy_' . $term->taxonomy;
			$request[] = 'taxonomy_' . "{$term->taxonomy}_" . sanitize_html_class( $term->slug, $term->term_id );
		}

		/* User/author archives. */
		elseif ( is_author() ) {
			$request[] = 'user';
			$request[] = 'user_' . sanitize_html_class( get_the_author_meta( 'user_nicename', get_query_var( 'author' ) ), $wp_query->get_queried_object_id() );
		}

		/* Date archives. */
		else {
			if ( is_date() ) {
				$request[] = 'date';
				if ( is_year() )
					$request[] = 'year';
				if ( is_month() )
					$request[] = 'month';
				if ( get_query_var( 'w' ) )
					$request[] = 'week';
				if ( is_day() )
					$request[] = 'day';
			}
		}
	}

	/* Search results. */
	elseif ( is_search() )
		$request[] = 'search';
	
	elseif ( is_feed() )
		$request[] = 'feed';
	
	elseif ( is_multisite() )
		$request[] = 'multisite';

	/* Error 404 pages. */
	elseif ( is_404() )
		$request[] = '404';

	$wpf_theme->request = apply_filters( 'wpf_request', $request );

	return $wpf_theme->request;
}

/**
 * Defines the theme's textdomain for translating your theme into multiple langauges.
 * It defaults to the value of get_template().
 *
 * @since 0.3.0
 * @global object $wp_theme The global WP Framework object.
 * @return string $wp_theme->textdomain The textdomain of the theme.
 */
function t() {
	global $wpf_theme;

	// If the global textdomain isn't set, define it.
	// Plugin/theme authors may also define a custom textdomain.
	if ( isset($wpf_theme->textdomain) )
		return $wpf_theme->textdomain;
	
	return $wpf_theme->textdomain = apply_filters( 'wpf_textdomain', get_template() );
}

/**
 * Returns the $content_width variable.
 *
 * @since 0.3.0
 * @return int $content_width
 */
function wpf_get_content_width() {
	global $wpf_theme, $content_width;
	
	if ( !empty($content_width) )
		return $content_width;

	$content_width = apply_filters( 'wpf_content_width', (int) $wpf_theme->content_width );

	return $content_width;
}

/**
 * Helper function that outputs whatever's in the first parameter.
 *
 * @since 0.3.0
 *
 * @return scalar $custom
 */
function __wpf_echo_value( $custom ) {
	echo wp_kses_post( $custom );
}

/**
 * Helper function that returns whatever's in the first parameter.
 *
 * @since 0.3.0
 *
 * @return scalar $custom
 */
function __wpf_return_custom( $custom ) {
	return esc_attr( $custom );
}

/**
 * Displays a notice on the page.
 *
 * @since 0.3.0
 *
 * @param string $message The message to output.
 * @param string $status The type of notice. Accepts 'updated' or 'error'.
 */
function wpf_display_notice( $message, $status = 'updated' ) {
	global $wpf_theme;

	$wpf_theme->display_notice( $message, $status );
}

/**
 * Arguments for the wp_list_comments() function used in comments.php. Users can set up a 
 * custom comments callback function by changing $callback to the custom function.  Note that 
 * $style should remain 'ol' since this is hardcoded into the theme and is the semantically correct
 * element to use for listing comments.
 *
 * @since 0.3.0
 *
 * @return array $args Arguments for listing comments.
 */
function wpf_list_comment_args( $args = array() ) {
	global $wpf_theme;

	$defaults = array( 'style' => 'ol', 'avatar_size' => 40, 'callback' => array( $wpf_theme, 'comments_callback' ), 'end-callback' => array( $wpf_theme, 'comments_end_callback' ) );

	$args = wp_parse_args( $args, $defaults );

	return apply_filters( 'wpf_list_comments_args', $args );
}

/**
 * Includes the comment pagination template if the comments
 * exceed over the page_comments option.
 *
 * @since 0.3.0
 *
 * @return void
 */
function wpf_get_comments_pagination() {
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) )
		wpf_comments_pagination();
}

/**
 * Returns true if a post is the subpage of a post.
 *
 * @since 0.3.0
 *
 * @param string $post Optional. Post id, or object.
 * @return bool true if the post is a subpage, false if not.
 */
function wpf_is_subpage( $post = null ) {
	$post = get_post( $post );

	if ( is_page() && $post->post_parent )
		return $post->post_parent;

	return false;
}

/**
 * Function used in the comments template that passes the comment reply
 * strings to comment_reply_link().
 *
 * @since 0.3.0
 *
 * @return void
 */
function wpf_comment_reply_strings() {
	$strings = array();

	$strings['reply_text'] = __( 'Reply', t() );
	$strings['login_text'] = __( 'Log in to leave a Comment', t() );

	return $strings;
}

/**
 * Wraps content into an HTML element.
 *
 * @since 0.3.0
 *
 * @param string $tag The HTML tag to wrap the content in.
 * @param string $content The content to display.
 * @param string $attrs Optional. HTML attributes to add to the element.
 * @param bool $echo Whether to echo the content or return it.
 */
function wpf_wrap( $tag, $content = '', $attrs = array(), $echo = true ) {
	global $wpf_theme;
	
	if ( $echo )
		return $wpf_theme->wrap( $tag, $content, $attrs, $echo );
	else
		return $wpf_theme->wrap( $tag, $content, $attrs, $echo );
}

function wpf_the_taxonomies( $args = array() ) {
	global $wpf_theme;

	$args = wp_parse_args( $args, array( 'before' => '<span class="taxonomy-links">', 'after' => '</span>' ) );

	echo $wpf_theme->the_taxonomies( $args );
}

function wpf_load_theme_translations() {
	global $wpf_theme;
	
	return $wpf_theme->load_theme_translations();
}

function wpf_custom_header() {
	global $wpf_theme;
	
	return $wpf_theme->get_custom_header();
}

function wpf_archive_title() {
	global $wpf_theme;

	echo $wpf_theme->archive_title();
}

/**
 * Returns whether a post has a previous post or not.
 *
 * @param int|object $post Optional. Post ID or Post Object. Defaults to the global $post.
 * @return bool True if the post has a previous post, else false.
 */
function wpf_has_prev_post( $post_id = 0 ) {
	return wpf_has_adjacent_post( $post_id, true );
}

/**
 * Returns whether a post has a next post or not.
 *
 * @param int|object $post Optional. Post ID or Post Object. Defaults to the global $post.
 * @return bool True if the post has a previous post, else false.
 */
function wpf_has_next_post( $post_id = 0 ) {
	return wpf_has_adjacent_post( $post_id, false );
}

/**
 * Retrieve adjacent post link.
 *
 * @param int|object $post Optional. Post ID or Post Object. Defaults to the global $post.
 * @param bool $prev_post Whether to check for a previous post OR next post.
 * @return bool True if the post has a previous post, else false.
 */
function wpf_has_adjacent_post( $post_id, $prev_post ) {
	global $post;
	
	// Save a cache of the real post.
	$the_post = $post;

	// Get the post.
	$post = get_post( $post_id );

	$result = (bool) get_adjacent_post( false, '', $prev_post );

	// Reset the $post back to it's original one.
	$post = $the_post;

	return $result;
}

/**
 * Displays pagination links.
 *
 * Credits to wp_pagenavi();
 *
 * @since 0.3.0
 *
 * @param string $args see WPF::paginate()
 */
function wpf_paginate_posts( $args = array() ) {
	global $wpf_theme;

	if ( !isset($args['is_page']) && is_page() )
		return;

	return $wpf_theme->paginate( wp_parse_args( $args, array( 'type' => 'posts' ) ) );
}

/**
 * Displays pagination links.
 *
 * Credits to wp_pagenavi();
 *
 * @since 0.3.0
 *
 * @param string $args see WPF::paginate()
 */
function wpf_paginate_comments( $args = array() ) {
	global $wpf_theme;

	return $wpf_theme->paginate( wp_parse_args( $args, array( 'type' => 'comments' ) ) );
}

function wpf_footer_nav_menu() {
	global $wpf_theme;
	
	return $wpf_theme->footer_nav_menu();
}

function is_bp_active() {
	return (bool) class_exists( 'BP_Core_User' );
}

function is_bp_component_page() {
	if ( !is_bp_active() )
		return false;

	global $bp;

	return (bool) $bp->current_component;
}

/**
 * Displays a notice on the page.
 *
 * @since 0.3.0
 * @todo maybe add wpf_get_message()?
 *
 *
 * @param string $message The message to output.
 * @param string $status The type of notice. Accepts 'updated' or 'error'.
 */
function wpf_message( $message, $status = 'updated' ) {
	global $wpf_theme;	

	WPF_Admin::message( $message, $status );
}

/**
 * CSS needed for various admin styles.
 *
 * @since 0.3.0
 */
function wpf_inject_css_in_head() {
	?>
	<style type="text/css">
		.wpf-wrap form { margin-bottom: 20px; }
		.wpf-wrap hr { background: #ccc; height: 1px; border: none; margin: 10px 0; }
		.wpf-wrap .metabox-holder { clear: both; }
		.wpf-wrap .meta-box-sortables { padding-right: 10px; }
		.wpf-wrap .inside { padding: 0 10px; overflow: hidden; }
		.wpf-wrap .inside select[multiple="multiple"] { height: auto !important; }
		.wpf-wrap .wpf-file { margin-bottom: 5px; }
		.wpf-wrap .upload .description { margin-top: 5px; display: block; }
		.wpf-wrap .small-text { width: 50px; }
		.wpf-wrap .color-picker-div { z-index: 100; background: #eee; border: 1px solid #ccc; display: none; width: 195px; margin-bottom: 10px; }		
		#poststuff .wpf-form select[multiple="multiple"] { height: auto !important; }
		#poststuff .wpf-form tr.checkbox p,
		#poststuff .wpf-form tr.radio p { margin-left: 0 !important; }
	</style>
	<?php
}

/**
 * JavaScript needed for various admin functionality.
 *
 * @since 0.3.0
 */
function wpf_inject_javascript_in_head() {
	?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles( $('#wpf-hook').val() );

			// Media Libary link
			$('.medialink').click(function(e){
				var input = $(e.target).parent().find('input');
				
				window.send_to_editor = function( media ) {						
					$(e.target).parent().find('.previewlink').attr( 'title', $(media).find('img').attr('title') );
					$( input ).val( $(media).attr( 'href' ) );
					tb_remove();
				}
			});

			// Preview Link
			$('.previewlink').click(function(e){
				var src = $(e.target).parent().find('input').val();
				if ( src ) {
					var id = $(e.target).parent().parent().attr('id');
					var preview = $('<img />').attr( 'src', src ).attr('title', src).hide();
					
					$('#thicky-' + id ).attr( 'title', src );
					$('#thicky-' + id ).append(preview);
					$('#thicky-' + id + ' img' ).show();

					$(e.target).addClass( 'thickbox' );
				};
				e.preventDefault();
			});

			// Upload Link
			$('.uploadlink').click(function(e){
				var input = $(e.target).parent().find('input');
				
				window.send_to_editor = function( media ) {						
					$(e.target).parent().find('.previewlink').attr( 'title', $(media).find('img').attr('title') );
					$( input ).val( $(media).attr( 'href' ) );
					tb_remove();
				}
			});

			// Toggle the color picker click on the link
			$('.pickcolor').toggle(function(e){
				var id = $(e.target).attr('rel');
				e.preventDefault();
				$( '#pickcolor-' + id ).show();
			}, function(e){
				var id = $(e.target).attr('rel');
				$( '#pickcolor-' + id ).hide();
				e.preventDefault();
			});
			
			// Add farbtastic colorwheel to all color pickers
			$('.wpf-color').each(function(){
				var id = $(this).siblings('a').attr('rel');
				var orginalColor = $(this).val();
				
				// set the original colors
				wpf_pickColor( $(this), orginalColor );
				
				// update the value of the color field
				$('#pickcolor-' + id).farbtastic(function(color){
					wpf_pickColor( $( '#wpf-form-' + id ), color );
				});
			
			// Update the value of the color field when the user manually types in a hex value
			}).keyup(function(e){
				var _hex = $(this).val(), hex = _hex;
				if ( hex[0] != '#' )
					hex = '#' + hex;
				hex = hex.replace(/[^#a-fA-F0-9]+/, '');
				if ( hex != _hex )
					$(this).val(hex);
				if ( hex.length == 4 || hex.length == 7 )
					wpf_pickColor( $(this), hex );
			});

			// Hide the color wheel when a user clicks away
			$(document).mousedown(function(){
				$('.color-picker-div').each(function(){
					var display = $(this).css('display');
					if ( display == 'block' )
						$(this).fadeOut(2);
				});
			});

			// Updates an elements value and background color :)
			function wpf_pickColor( element, color ) {
				$(element).val(color);
				$(element).css( 'background-color', color );
			}
		});
		//]]>
	</script>
	<?php
}