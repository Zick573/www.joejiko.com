<?php
/**
 * The Theme Options admin class.
 *
 * This class provides a new "Theme Options" page under the Appearance section.
 *
 * See the following link on how to add your own metaboxes and options to this page:
 * @link http://devpress.com/codex/wp-framework/theme-options-api/
 *
 * @package WP Framework
 */
class WPF_Theme_Options extends WPF_Admin_Metabox {
	/* @see WPF_Admin_Metabox(); */
	function WPF_Theme_Options() {
		global $wpf_theme;

		parent::__construct( array(
			'default_screen_columns' => apply_filters( 'wpf_default_screen_columns', (int) 3 ),
			'max_screen_columns' => apply_filters( 'wpf_max_screen_columns', (int) 4 ),
			'api_callback' => array( $wpf_theme, 'theme_options' ),
			'option_group' => THEME_OPTIONS,
			'page_slug' => array( 'options' ),
		) );
	}

	/* Magic method that gets called on the admin_menu action hook. */
	function admin_menu() {
		if ( !current_theme_supports('theme-options') )
			return;

		// We need to get the name of the current theme.
		$theme_data = get_theme_data( CHILD_THEME_DIR . '/style.css' );

		// Some filters for changing the page/menu labels.
		$page_title = apply_filters( 'wpf_theme_options_page_title', sprintf( __( 'Theme Options for %s', t() ), $theme_data['Name'] ) );
		$menu_title = apply_filters( 'wpf_theme_options_menu_title', __( 'Theme Options', t() ) );

		add_theme_page( $page_title, $menu_title, 'edit_theme_options', 'options', array( $this, 'init' ) );
	}

	/**
	 * Displays the header section of the admin page.
	 *
	 * @since 0.3.0
	 */
	function header() {
		screen_icon( 'themes' );
		echo $this->wrap( 'h2', __( 'Theme Options', t() ) );
	}
}