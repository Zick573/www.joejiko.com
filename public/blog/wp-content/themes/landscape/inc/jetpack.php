<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package landscape
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function landscape_infinite_scroll_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'content',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'landscape_infinite_scroll_setup' );

if ( function_exists( 'jetpack_is_mobile' ) ) {
 
    function landscape_has_footer_widgets() {
        if ( jetpack_is_mobile( '', true ) && is_active_sidebar( 'sidebar-1' ) )
            return true;
 
        return false;
    }
    add_filter( 'infinite_scroll_has_footer_widgets', 'landscape_has_footer_widgets' );
}