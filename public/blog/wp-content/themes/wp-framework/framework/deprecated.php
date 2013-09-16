<?php
/**
 * This file contains APIs from WP Framework that have become obsolete
 * and are no longer being used by the framework.
 *
 * You are encouraged not to use these functions as they have been replaced
 * by better functions in WP Framework or WordPress core. Deprecated functions
 * will be removed at some point in a future release cycle so please don't
 * rely on them. Thanks.
 *
 * @package WP Framework
 */

function wpf_register_metabox( $page_slug, $metabox_id, $metabox_title = '', $column = 1, $priority = 'default' ) {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_add_metabox()' );
	return wpf_add_metabox( $page_slug, $metabox_id, $metabox_title, $column, $priority );
}

function wpf_unregister_metabox( $page_slug, $metabox_id ) {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_delete_metabox()' );
	return wpf_delete_metabox( $page_slug, $metabox_id );
}

function wpf_register_option( $metabox_id, $option_id, $args = array(), $data = array() ) {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_add_setting()' );
	return wpf_add_setting( $metabox_id, $option_id, $args, $data );
}

function wpf_unregister_option( $metabox_id, $option_id ) {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_delete_setting()' );
	return wpf_delete_setting( $metabox_id, $option_id );
}

function wpf_add_option( $metabox_id, $option_id, $args = array(), $data = array() ) {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_add_setting()' );
	return wpf_add_setting( $metabox_id, $option_id, $args, $data );
}

function wpf_delete_option( $metabox_id, $option_id ) {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_delete_setting()' );
	return wpf_delete_setting( $metabox_id, $option_id );
}

function wpf_get_custom_header() {
	_deprecated_function( __FUNCTION__, '0.3-beta', 'wpf_custom_header()' );
	return wpf_custom_header();
}

function is_a_bp_page() {
	_deprecated_function( __FUNCTION__, '0.3.3', 'is_bp_component_page()' );
	return is_bp_component_page();
}