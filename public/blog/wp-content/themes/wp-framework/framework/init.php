<?php
/**
 * Loads WP Framework and initialises the framework.
 *
 * @package WP Framework
 */

// Defines the current version of WP Framework.
define( 'WPF_VERSION', '0.3.6' );

// Load early files.
require_once( TEMPLATEPATH . '/framework/deprecated.php' );
require_once( TEMPLATEPATH . '/framework/compatiblity.php' );
require_once( TEMPLATEPATH . '/framework/core.php' );

// Define a few default constants.
wpf_initial_constants();

// Load all APIs.
require_once( PARENT_THEME_DIR . '/framework/functions.php' );
require_once( PARENT_THEME_DIR . '/framework/options.php' );
require_once( PARENT_THEME_DIR . '/framework/media.php' );

// Load all classes.
require_once( PARENT_THEME_DIR . '/framework/classes/wpf-api.php' );
require_once( PARENT_THEME_DIR . '/framework/classes/wpf.php' );
require_once( PARENT_THEME_DIR . '/framework/classes/wpf-admin.php' );
require_once( PARENT_THEME_DIR . '/framework/classes/wpf-admin-metabox.php' );
require_once( PARENT_THEME_DIR . '/framework/classes/wpf-custom-metabox.php' );
require_once( PARENT_THEME_DIR . '/framework/classes/wpf-admin-form.php' );
require_once( PARENT_THEME_DIR . '/framework/classes/wpf-theme-options.php' );

// Define the templating constants.
wpf_templating_constants();

// Place your custom code (actions/filters/classes) in custom-functions.php and it will be loaded before anything else.
if ( file_exists( PARENT_THEME_DIR . '/custom-functions.php' ) )
	include_once( PARENT_THEME_DIR . '/custom-functions.php' );

if ( is_child_theme() && file_exists( CHILD_THEME_DIR . '/custom-functions.php' ) )
	include_once( CHILD_THEME_DIR . '/custom-functions.php' );

// Load theme extensions.

// Registers the theme backbone class.
wpf_register_class( 'theme', 'WPF' );

// Registers the BuddyPress class.
wpf_register_class( 'bp', 'WPF_BP' );

// Registers the Metaboxes class.
wpf_register_class( 'metaboxes', 'WPF_Custom_Metabox' );

// Registers the Theme Options admin class.
wpf_register_admin_class( 'options', 'WPF_Theme_Options' );

/**
 * WP Framework is fully loaded and is ready to start initalizing various APIs.
 * This is a good time to hook into WPF to override registered classes.
 */
do_action( 'wpf_init' );

// Load all autoload classes.
add_action( 'after_setup_theme', 'wpf_autoload_classes' );

// Load all contextual classes.
add_action( 'wp', 'wpf_load_contextual_classes', 1 );

// Load admin pages.
add_action( 'init', 'wpf_load_admin_pages', 100 );

// Add support for adding metaboxes/options on post type write/edit screens.
add_action( 'admin_init', 'wpf_load_post_type_metaboxes' );

/**
 * WP Framework theme object
 * @global object $wpf_theme
 * @since 0.3.0
 */
$wpf_theme = WPF();

/**
 * Maybe load BuddyPress support... maybe not.
 */
wpf_maybe_bp_init();

/**
 * This hook is fired once WP Framework is fully loaded and instantiated.
 */
do_action( 'wpf_loaded' );