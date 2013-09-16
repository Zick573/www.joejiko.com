<?php
/**
 * WP Framework core functions
 *
 * @package WP Framework
 */

/**
 * Default constants available throughout WP Framework.
 *
 * @since 0.3.0
 *
 * @return void
 */
function wpf_initial_constants() {	
	// Sets the File path to the current parent theme's directory.
	define( 'PARENT_THEME_DIR', TEMPLATEPATH );

	// Sets the URI path to the current parent theme's directory.
	define( 'PARENT_THEME_URI', get_template_directory_uri() );

	// Sets the File path to the current parent theme's directory.
	define( 'CHILD_THEME_DIR', STYLESHEETPATH );

	// Sets the URI path to the current child theme's directory.
	define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

	// Sets the file path to WP Framework
	define( 'WPF_DIR', PARENT_THEME_DIR . '/framework' );

	// Sets the URI path to WP Framework
	define( 'WPF_URI', PARENT_THEME_URI . '/framework' );

	// Sets the file path to extensions
	define( 'WPF_EXT_DIR', WPF_DIR . '/extensions' );

	// Sets the URI path to extensions
	define( 'WPF_EXT_URI', WPF_URI . '/extensions' );
}

/**
 * Templating constants that you can override before WP Framework is loaded.
 *
 * @since 0.3.0
 *
 * @return void
 */
function wpf_templating_constants() {
	// Sets a unique ID for the theme.
	if ( !defined( 'THEME_ID' ) )
		define( 'THEME_ID', 'wpf_' . get_template() );

	// Sets the default theme options db name
	if ( !defined( 'THEME_OPTIONS' ) )
		define( 'THEME_OPTIONS', 'theme_options' );

	// Sets relative paths for the default directories/paths
	if ( !defined( 'THEME_LIBRARY' ) )
		define( 'THEME_LIBRARY', '/library' );

	if ( !defined( 'THEME_I18N' ) )
		define( 'THEME_I18N', THEME_LIBRARY . '/languages' );

	if ( !defined( 'THEME_FUNC' ) )
		define( 'THEME_FUNC', THEME_LIBRARY . '/functions' );

	if ( !defined( 'THEME_IMG' ) )
		define( 'THEME_IMG', THEME_LIBRARY . '/images' );

	if ( !defined( 'THEME_CSS' ) )
		define( 'THEME_CSS', THEME_LIBRARY . '/css' );

	if ( !defined( 'THEME_JS' ) )
		define( 'THEME_JS', THEME_LIBRARY . '/js' );
	
	// Sets the default custom header image
	if ( !defined( 'DEFAULT_HEADER_IMAGE' ) )
		define( 'DEFAULT_HEADER_IMAGE', get_theme_part( THEME_IMG . '/custom-header.gif' ) );
}

/**
 * Retrieves the theme framework class and initalises it.
 *
 * @since 0.3.0
 * @uses wpf_get_class()
 *
 * @return object $wpf_theme class
 */
function WPF() {
	global $wpf_classes;

	$theme_class = wpf_get_class( 'theme' );

	return $wpf_classes['theme'] = new $theme_class;
}

/**
 * Loads BuddyPress functionality if the plugin is active.
 *
 * Extend or override the WPF_BP class by registering your own 'bp' class.
 *
 * @since 0.3.0
 */
function wpf_maybe_bp_init() {
	if ( !is_bp_active() )
		return false;

	global $wpf_classes, $wpf_theme;

	require_once( WPF_DIR . '/classes/wpf-bp.php' );

	$bp_class = wpf_get_class( 'bp' );

	return $wpf_classes['bp'] = new $bp_class();
}

/**
 * Registers a WP Framework class.
 *
 * @since 0.3.0
 *
 * @param string $handle Name of the api.
 * @param string $class The class name.
 * @return string The name of the class registered to the handle.
 */
function wpf_register_class( $handle, $class, $autoload = false ) {
	global $wpf_classes;

	$type = $autoload ? 'autoload' : 'static';

	$wpf_classes[$type][$handle] = $class;

	return $wpf_classes[$type][$handle];
}

/**
 * Registers a contextual WP Framework class.
 * Contextual classes will get loaded after the 'wp' action is fired.
 *
 * @since 0.3.0
 * @see wpf_load_contextual_classes()
 * @see wpf_get_request()
 *
 * @param string $handle Name of the api.
 * @param string $class The contextual class name.
 * @return string The name of the class registered to the handle.
 */
function wpf_register_contextual_class( $handle, $class ) {
	global $wpf_classes;

	$wpf_classes['contextual'][$handle] = $class;

	return $wpf_classes['contextual'][$handle];
}

/**
 * Registers an admin class in WP Framework.
 * An admin class allows you to create administrative pages in WordPress.
 *
 * @since 0.3.0
 * @see class WPF_Admin
 * @see class WPF_Admin_Metabox
 * @uses wpf_load_admin_pages()
 *
 * @param string $handle Identifier for the admin class.
 * @param string $class The admin class name.
 * @return string The name of the class registered to the handle.
 */
function wpf_register_admin_class( $menu_slug, $class ) {
	global $wpf_classes;

	$wpf_classes['admin'][$menu_slug] = $class;

	return $wpf_classes['admin'][$menu_slug];
}

/**
 * Retrieves a registered WP Framework class.
 *
 * @since 0.3.0
 *
 * @param string $class The class handler
 * @return string The name of the class registered to the handler.
 */
function wpf_get_class( $class ) {
	global $wpf_classes;
	
	if ( isset($wpf_classes[$class]) )
		return $wpf_classes[$class];
	
	if ( isset($wpf_classes['admin'][$class]) )
		return $wpf_classes['admin'][$class];
	
	if ( isset($wpf_classes['static'][$class]) )
		return $wpf_classes['static'][$class];
	
	if ( isset($wpf_classes['autoload'][$class]) )
		return $wpf_classes['autoload'][$class];
	
	if ( isset($wpf_classes['contextual'][$class]) )
		return $wpf_classes['contextual'][$class];
	
	return false;
}

/**
 * Loops through all the registered autoloaded classes and instantiates them.
 *
 * @since 0.3.0
 * 
 * @return void
 */
function wpf_autoload_classes() {
	global $wpf_classes;

	if ( isset( $wpf_classes['autoload'] ) ) {
		foreach ( (array) $wpf_classes['autoload'] as $handle => $class ) {
			if ( !isset($wpf_classes[$handle]) ) {
				$wpf_classes[$handle] = new $class;
			}
		}
	}
}

/**
 * Loops through all the registered contextual classes and attempts to call 
 * classs methods based on wpf_get_request().
 *
 * @since 0.3.0
 * 
 * @return void
 */
function wpf_load_contextual_classes() {
	global $wpf_classes, $wpf_theme;

	if ( isset($wpf_classes['contextual']) && !empty( $wpf_classes['contextual'] ) ) {
		$methods = array();

		// Get the context, but not in the admin.
		if ( !is_admin() ) {
			$context = array_reverse( (array) wpf_get_request() );

			if ( !empty($context) ) {
				foreach ( $context as $method ) {
					$methods[] = str_replace( '-', '_', $method );
				}
			}
		}

		foreach ( (array) $wpf_classes['contextual'] as $handle => $class ) {
			if ( isset($wpf_classes[$handle]) )
				continue;

			// Call the admin method if we're in the admin area.
			if ( is_admin() ) {
				$wpf_theme->callback( $wpf_classes['contextual'][$handle], 'admin' );

			} else {

				// Call the constructor method if we're not in the admin,
				// pass all the methods that are valid for this page request.
				$wpf_classes[$handle] = new $class( $methods );
			}

			// Call all the contextual methods.
			if ( !empty( $methods ) ) {
				foreach( $methods as $method ) {
					$wpf_theme->callback( $wpf_classes[$handle], $method );
				}
			}
		}
	}
}

/**
 * Loops through all the registered admin pages and attempts to call 
 * classs methods based on wpf_get_request().
 *
 * @since 0.3.0
 * 
 * @return void
 */
function wpf_load_admin_pages() {
	if ( !is_admin() )
		return;

	global $wpf_classes;

	if ( isset($wpf_classes['admin']) && !empty($wpf_classes['admin']) ) {
		foreach ( $wpf_classes['admin'] as $handle => $class ) {
			if ( !isset($wpf_classes[$handle]) ) {
				$wpf_classes[$handle] = new $class;
			}
		}
	}
}

/**
 * Checks to see if any metaboxes are registered to a post type.
 *
 * @since 0.3.0
 */
function wpf_load_post_type_metaboxes() {
	global $wpf_classes;

	$metabox_class = wpf_get_class( 'metaboxes' );

	return $wpf_classes['metaboxes'] = new $metabox_class();
}

/**
 * Function for profiling your theme.
 *
 * @since 0.3.0
 *
 * @param string $id If you're running multitple profiles throughout your script, pass it an ID, else leave it blank.
 */
function wpf_timer_start( $id = 0 ) {
	global $wpf_profiler;

	$mtime = explode( ' ', microtime() );
	$wpf_profiler[$id]['start'] = $mtime[1] + $mtime[0];

	return true;
}

/**
 * Get the results of your profile.
 *
 * @since 0.3.0
 *
 * @param string $id If you're running multitple profiles throughout your script, pass it the ID from @wpf_timer_start(), else leave it blank.
 * @param string $display 
 * @param string $precision 
 */
function wpf_timer_stop( $id = 0, $echo = 0, $precision = 3 ) { // if called like timer_stop(1), will echo $timetotal
	global $wpf_profiler;

	if ( !isset($wpf_profiler[$id]) )
		return false;

	$mtime = microtime();
	$mtime = explode( ' ', $mtime );
	$wpf_profiler[$id]['stop'] = $mtime[1] + $mtime[0];
	$total = $wpf_profiler[$id]['stop'] - $wpf_profiler[$id]['start'];

	$wpf_profiler[$id]['total'] = ( function_exists( 'number_format_i18n' ) ) ? number_format_i18n( $total, $precision ) : number_format( $total, $precision );

	return ( $echo ) ? print $wpf_profiler[$id]['total'] : $wpf_profiler[$id]['total'];
}