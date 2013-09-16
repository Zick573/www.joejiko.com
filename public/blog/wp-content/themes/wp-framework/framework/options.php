<?php
/**
 * Theme Options API
 *
 * @link http://devpress.com/codex/wp-framework/theme-options-api/
 *
 * @package WP Framework
 */

/**
 * Returns the value of an option from the db if it exists.
 *
 * @since 0.3.0
 *
 * @param string $key Option Identifier.
 * @return mixed Returns the option's value if it exists, false if it doesn't.
 */
function get_theme_option( $key ) {
	return WPF_Admin::get_option( $key, THEME_OPTIONS );
}

/**
 * Adds an option to the options db.
 * The option will not get added if the option already exists.
 *
 * @since 0.3.0
 *
 * @param string $key Option Identifier. Must be unique.
 * @param mixed $value Option Value.
 * @return bool true|false
 */
function add_theme_option( $key, $value ) {	
	return WPF_Admin::add_option( $key, THEME_OPTIONS );
}

/**
 * Updates an option to the options db.
 *
 * @since 0.3.0
 *
 * @param string $key Option Identifier.
 * @param mixed $value Option Value.
 * @return bool true|false
 */
function update_theme_option( $key, $value ) {
	return WPF_Admin::update_option( $key, THEME_OPTIONS );
}

/**
 * Deletes an option from the options db.
 *
 * @since 0.3.0
 *
 * @param string $key Option Identifier.
 * @return bool true|false
 */
function delete_theme_option( $key ) {
	return WPF_Admin::delete_option( $key, THEME_OPTIONS );
}

/**
 * Adds a metabox to a registered WP Framework admin page.
 *
 * @since 0.3.0
 *
 * @param string $page_slug Page where the metabox is registered to.
 * @param string $metabox_id Identifier of the metabox.
 * @param string $metabox_title Title of the meta box.
 * @param int $column Registers the metabox to a specific column (1, 2, 3, or 4).
 * @param string $priority Optional. The priority within the column the metabox should be shown ( 'high', 'core', 'default', 'low' ).
 * @return array the metabox parameters.
 */
function wpf_add_metabox( $page_slug, $metabox_id, $metabox_title = '', $column = 1, $priority = 'default' ) {
	return WPF_Admin_Metabox::add_metabox( $page_slug, $metabox_id, $metabox_title, $column, $priority );
}

/**
 * Remove a metabox from a registered WP Framework admin page.
 *
 * @since 0.3.0
 *
 * @param string $page_slug Page where the metabox is registered to.
 * @param string $metabox_id Id of the metabox.
 * @return bool True if the metabox was removed, else false.
 */
function wpf_delete_metabox( $page_slug, $metabox_id ) {
	return WPF_Admin_Metabox::delete_metabox( $page_slug, $metabox_id );
}

/**
 * Adds a setting to a metabox.
 *
 * Accepted $args:
 * - type		 : textbox, textarea, checkbox, radio, select, upload, color
 * - label		 : A Label to display after the radio.
 * - attrs		 : Key => Value pairs of attributes to add to the form field.
 * - default	 : A default value to use if the option doesn't have a db value.
 * - numeric_keys: Whether to use numeric keys as the value for all form elements
 *				   or the keys you've specified in $data.
 * - multiple	 : Whether a select box can accept multiple values or not.
 *
 * @since 0.3.0
 *
 * @param string $metabox_id The identifier of the metabox.
 * @param string $option_id The identifier of the option.
 * @param string $args Parameters to pass.
 * @param string $data Optional.
 */
function wpf_add_setting( $metabox_id, $option_id, $args = array(), $data = array() ) {
	return WPF_Admin_Metabox::add_setting( $metabox_id, $option_id, $args, $data );
}

/**
 * Removes a setting from a metabox.
 *
 * @since 0.3.0
 *
 * @param string $metabox_id The identifier of the metabox.
 * @param string $option_id The identifier of the option.
 * @return bool Returns true if the option was removed, else false.
 */
function wpf_delete_setting( $metabox_id, $option_id ) {
	return WPF_Admin_Metabox::delete_setting( $metabox_id, $option_id );
}