<?php
/**
 * Custom functions and definitions
 *
 * Creating child themes is awesome. It's the easiest way to make changes
 * to a theme without having to physically modify said theme.
 *
 * Here, let me show you the ropes. A child theme can override any
 * parental template by simply using a file with the same name.
 * This also includes any non-php based files including stylesheets,
 * and javascript files, the whole nine. WP Framework's got you covered!
 *
 * For more information on hooks, actions, and filters, see:
 * http://codex.wordpress.org/Plugin_API.
 *
 * @package WP Framework
 * @subpackage Functions
 */

/**
 * STEP 1: Register your class as the theme class.
 *
 * Registers the "theme" API to the Child_Theme class.
 * This overrides the functionality provided by the Parent_Theme class.
 *
 * The first step to creating your own custom child theme is to register your
 * class to the WP Framework class API. This involves hooking into 'wpf_init'
 * and calling wpf_register_class(), passing the API you want to
 * override (in this case, 'theme') and the name of your class. Were going to
 * call it Child_Theme but it could be called anything really.
 */
add_action( 'wpf_init', 'register_child_theme_classes' );
function register_child_theme_classes() {
	wpf_register_class( 'theme', 'Child_Theme' );
}

/**
 * STEP 2: Define your theme class and extend the parent class.
 *
 * Now here's the good stuff. We've created a new class named Child_Theme
 * which extends the Parent_Theme which in-turn extends the core WPF class.
 * 
 * By doing this, we've inherited all of those classes' methods and functionality.
 * This is the PHP equivalently of being able to override your parent theme's
 * template files, but with functionality not files! Crazy? I know. But genius.
 *
 * Example:
 * Let's say the Parent_Theme class has some funky stuff going on in it's
 * after_theme_setup() method that your not too fond of. It's gotta go!
 * Well, simply create an after_theme_setup() method in your Child_Theme class
 * et volia!
 *
 * That's it! You're ready to start to building out your custom theme :D
 *
 * For more information about classes in WP Framework:
 * @link http://devpress.com/codex/wp-framework/classes/
 */
class Child_Theme extends Parent_Theme {
	/**
	 * The constructor method. This method calls the Parent_Theme
	 * construct method and fires off all the initial hooks needed
	 * for the theme to work.
	 *
	 * FYI: This function appears after the 'setup_theme' action.
	 *
	 * To can pass the following parameters to the WPF() method:
	 * - content_width  : Pass a integer value for the global $content_width
	 *                    used to set the width of images and content.
	 * - textdomain     : Pass a string value for the textdomain used for your theme.
	 * - excerpt_length : Pass an integer value for the lenth used in
	 *                    the_excerpt() function.
	 * - strings        : Pass an array of strings for use throughout your theme. @see wpf_default_strings().
	 */
	function Child_Theme() {
		parent::Parent_Theme( array(
			// Set the content width based on the theme's design and stylesheet.
			'content_width' => 640,
			// Sets the text domain for your theme. Use the t() in your template files.
			'textdomain' => get_stylesheet(),
		) );
	}
	
	/**
	 * This is a magic method that WPF calls on the 'after_setup_theme' action.
	 *
	 * This is where you would probably want to start doing theme stuff,
	 * adding actions/filters and the like.
	 *
	 * Theme Features is a set of features defined by theme authors that
	 * allows a theme to register support of a certain feature. By default
	 * WordPress comes bundled with the following features:
	 * - Post Thumbnails		: A featured image used to represent a post type.
	 * - Navigation Menus		: Enable support for the WordPress Menus system in your theme.
	 * - Widgets				: Drag and droppable sections for widgetized areas in your theme.
	 * - Post Formats			: Differentiate the presentation of your post types.
	 * - Custom Backgrounds		: Manage your theme's background from the WordPress admin.
	 * - Custom Headers			: Manage your theme's header display from the WordPress admin.
	 * - Editor Style			: Add custom css to the WordPress text editor to simulate
	 *				   			  how it looks like in your theme.
	 * - Automatic Feed Links	: Automatically inject post type and comment feed
	 *							  links onto the page.
	 *
	 * To learn more about theme features in WordPress:
	 * @see http://codex.wordpress.org/Theme_Features
	 */
	function after_setup_theme() {
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /library/languages/ directory.
		 */
		wpf_load_theme_translations();

		// Navigation Menu support.
		register_nav_menus( array(
			'header' => __( 'Site Navigation', t() ),
			'footer' => __( 'Footer Links', t() ),
		) );

		// Enable dynamically generated css classes to your markup
		add_theme_support( 'semantic-markup' );

		// Enable the Roll Your Own Grid System - CSS Framework
		add_theme_support( 'css-grid-framework' );

		// Post thumbnails support.
		add_theme_support( 'post-thumbnails' );
		
		// Post Format support.
		add_theme_support( 'post-formats', array( 'aside', 'chat', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );

		// Automatic Feed Links support.
		add_theme_support( 'automatic-feed-links' );
		
		// Uncomment the following line to enable the Theme Options page within the WordPress admin.
		// add_theme_support( 'theme-options' );

		// Editor Styles support.
		add_editor_style( THEME_CSS . '/editor-style.css' );

		// Custom Background support.
		add_custom_background();

		/**
		 * Custom Header business
		 */
		if ( ! defined( 'HEADER_TEXTCOLOR' ) )
			define( 'HEADER_TEXTCOLOR', '' );

		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		if ( ! defined( 'HEADER_IMAGE' ) )
			define( 'HEADER_IMAGE', DEFAULT_HEADER_IMAGE );

		// Don't support text inside the header image.
		if ( ! defined( 'NO_HEADER_TEXT' ) )
			define( 'NO_HEADER_TEXT', true );

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'header_image_width', 978 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'header_image_height', 198 ) );

		// We'll be using post thumbnails for custom header images on posts and pages.
		// We want them to be HEADER_IMAGE_WIDTH pixels wide by HEADER_IMAGE_HEIGHT pixels tall.
		// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

		// Custom Header support.
		add_custom_image_header( array( $this, 'custom_header_frontend_callback' ), array( $this, 'custom_header_admin_callback' ) );

		// ... and thus ends the changeable header business.
	}

	/**
	 * Register widgetized areas.
	 *
	 * This is a magic method which is automatically called
	 * on the 'widgets_init' action hook.
	 */
	function widgets_init() {
		register_sidebar( array(
			'name' => __( 'Asides', t() ),
			'id' => 'aside-widget-area',
			'description' => __( 'Asides widget area.', t() ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );
	}

	/**
	 * Enqueue Assets.
	 *
	 * Stylesheets:
	 * - reset.css		: resets default browser styling.
	 * - master.css		: blank css file, ready for you to edit.
	 * - default.css	: default styles for this theme.
	 * - grid.css		: custom css grid generator.
	 *
	 * Scripts:
	 * - html5shiv.js	: Adds support for HTML5 elements.
	 * - scripts.js		: Sample js script for rapid theme development.
	 * - hoverIntent/superfish/sf-options: Adds nice transitions to your menus.
	 *
	 * BuddyPress:
	 * - wpf-bp-admin-bar: Styles the BuddyPress admin bar.
	 * - wpf-bp			: Styles BuddyPress component pages.
	 * - wpf-bp-ajax-js	: Adds AJAX support to BuddyPress pages.
	 */
	function enqueue_assets() {
		wp_enqueue_style( 'reset', get_theme_part( THEME_CSS . '/reset.css' ), null, null );
		wp_enqueue_style( 'default', get_theme_part( THEME_CSS . '/default.css' ), array( 'reset' ), null );
		wp_enqueue_style( 'master', get_theme_part( THEME_CSS . '/master.css' ), array( 'reset' ), null );

		// Custom CSS Framework genereated from RYOGS, see comments below.
		wp_enqueue_style( 'grid', get_theme_part( THEME_CSS . '/grid.css' ), null, null );

		// FYI: For production sites, it's best to hardcode the generated into a static grid.css file for a performance boast.
		// Parameters: column - width - gutter - line-height: examples: 12-54-30-22 or 24-30-10-20
		// wp_enqueue_style( 'grid', get_theme_part( WPF_EXT_URI . '/ryogs.php' ), null, '12-54-30-22' );

		wp_enqueue_script( 'html5shiv', get_theme_part( THEME_JS . '/html5shiv.js' ), null, null );
		wp_enqueue_script( 'scripts', get_theme_part( THEME_JS . '/scripts.js' ), array( 'jquery' ), null, true );

		// Superfish Menus
		wp_enqueue_script( 'hoverIntent', includes_url( 'js/hoverIntent.js' ), array( 'jquery' ), null, true );
		wp_enqueue_script( 'superfish', get_theme_part( THEME_JS . '/superfish.js' ), null, null );

		// BuddyPress Styles
		if ( is_bp_active() )
			wp_enqueue_style( 'wpf-bp-admin-bar', get_theme_part( THEME_CSS .'/bp-admin-bar.css' ), null, null );

		if ( is_bp_component_page() ) {
			wp_enqueue_style( 'wpf-bp', get_theme_part( THEME_CSS . '/buddypress.css' ), null, null );
			wp_enqueue_script( 'wpf-bp-ajax-js', get_theme_part( THEME_JS . '/buddypress.js' ), array( 'jquery' ), null );
		}
	}

	/**
	 * This is the callback method for registering metaboxes and options
	 * in the Theme Options page.
	 *
	 * Note: You must enable add_theme_support( 'theme-options' );
	 * for this method to be called. @see after_setup_theme() above.
	 *
	 * Register a metabox:
	 * In order to register options, you'll need to create a metabox where the
	 * option(s) will be contained.
	 *
	 * wpf_add_setting( 'options', 'general', __( 'General Settings', t() ) );
	 *
	 * Register an option:
	 * Options get registered to a metabox. Once you've registered a metabox, you can now register an option under that metabox.
	 *
	 * wpf_add_option( 'general', 'option_id', array( 'type' => 'textbox', 'label' => __( 'This is a sample textbox', t() ) ) );
	 *
	 * Supported types of options you can register:
	 * textbox, textarea, checkbox, radio, select, upload, color, custom, callback
	 *
	 * Theme Options API:
	 * Now that you have options registered to your theme, you can now
	 * build functionality based on those options you've created by making
	 * use of the following functions throughout your template files:
	 *
	 * 	- get_theme_option( $option_id ) // Returns the value of a theme option.
	 *	- delete_theme_option( $option_id ) // Deletes a theme option.
	 * 	- add_theme_option( $option_id, $value ) // Adds a theme option.
	 * 	- update_theme_option( $option_id, $value ) // Updates a theme option.
	 *
	 * For more information on the Theme Options API, see the following article
	 * in the codex:
	 * @link http://devpress.com/codex/wp-framework/theme-options-api/
	 */
	function theme_options() {
		//
	}
}