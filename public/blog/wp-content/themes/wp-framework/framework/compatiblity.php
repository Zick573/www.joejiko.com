<?php
/**
 * This file exists for when WordPress introduces newer APIs into core.
 * This keeps WP Framework up-to-date with the latest APIs from WordPress.
 *
 * @package WP Framework
 */

/**
 * Since WP includes submit_button() late in the stack, I have to resolve to
 * checking $wp_version instead of the proper method using function_exists().
 */
global $wp_version;

if ( version_compare( '3.0', $wp_version, '>' ) ):
/**
 * Echos a submit button, with provided text and appropriate class
 *
 * @since 3.1.0
 *
 * @param string $text The text of the button (defaults to 'Save Changes')
 * @param string $type The type of button. One of: primary, secondary, delete
 * @param string $name The HTML name of the submit button. Defaults to "submit". If no id attribute
 *               is given in $other_attributes below, $name will be used as the button's id.
 * @param bool $wrap True if the output button should be wrapped in a paragraph tag,
 * 			   false otherwise. Defaults to true
 * @param array|string $other_attributes Other attributes that should be output with the button,
 *                     mapping attributes to their values, such as array( 'tabindex' => '1' ).
 *                     These attributes will be ouput as attribute="value", such as tabindex="1".
 *                     Defaults to no other attributes. Other attributes can also be provided as a
 *                     string such as 'tabindex="1"', though the array format is typically cleaner.
 */
function submit_button( $text = NULL, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = NULL ) {
	echo get_submit_button( $text, $type, $name, $wrap, $other_attributes );
}
endif;

if ( version_compare( '3.0', $wp_version, '>' ) ):
/**
 * Returns a submit button, with provided text and appropriate class
 *
 * @since 3.1.0
 *
 * @param string $text The text of the button (defaults to 'Save Changes')
 * @param string $type The type of button. One of: primary, secondary, delete
 * @param string $name The HTML name of the submit button. Defaults to "submit". If no id attribute
 *               is given in $other_attributes below, $name will be used as the button's id.
 * @param bool $wrap True if the output button should be wrapped in a paragraph tag,
 * 			   false otherwise. Defaults to true
 * @param array|string $other_attributes Other attributes that should be output with the button,
 *                     mapping attributes to their values, such as array( 'tabindex' => '1' ).
 *                     These attributes will be ouput as attribute="value", such as tabindex="1".
 *                     Defaults to no other attributes. Other attributes can also be provided as a
 *                     string such as 'tabindex="1"', though the array format is typically cleaner.
 */
function get_submit_button( $text = NULL, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = NULL ) {
	switch ( $type ) :
		case 'primary' :
		case 'secondary' :
			$class = 'button-' . $type;
			break;
		case 'delete' :
			$class = 'button-secondary delete';
			break;
		default :
			$class = $type; // Custom cases can just pass in the classes they want to be used
	endswitch;
	$text = ( NULL == $text ) ? __( 'Save Changes' ) : $text;

	// Default the id attribute to $name unless an id was specifically provided in $other_attributes
	$id = $name;
	if ( is_array( $other_attributes ) && isset( $other_attributes['id'] ) ) {
		$id = $other_attributes['id'];
		unset( $other_attributes['id'] );
	}

	$attributes = '';
	if ( is_array( $other_attributes ) ) {
		foreach ( $other_attributes as $attribute => $value ) {
			$attributes .= $attribute . '="' . esc_attr( $value ) . '" '; // Trailing space is important
		}
	} else if ( !empty( $other_attributes ) ) { // Attributes provided as a string
		$attributes = $other_attributes;
	}

	$button = '<input type="submit" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="' . esc_attr( $class );
	$button	.= '" value="' . esc_attr( $text ) . '" ' . $attributes . ' />';

	if ( $wrap ) {
		$button = '<p class="submit">' . $button . '</p>';
	}

	return $button;
}
endif;

// Functions required to pass wp.org theme review process.
// WP Framework uses WPF::paginate() instead. check it out.
function wpf_wporg_compliance() {
	paginate_comments_links();
	get_the_tag_list();
}