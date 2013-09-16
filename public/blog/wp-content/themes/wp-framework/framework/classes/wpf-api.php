<?php
/**
 * The Backbone class of WP Framework.
 *
 * This class provides core functionality to all types of classes used
 * throughout WP Framework.
 *
 * To take advantage of the methods provided within this class,
 * simply extend the WPF class (which already extends this class).
 *
 * @package WP Framework
 */
class WPF_API {
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
	function wrap( $tag, $content = '', $attrs = array(), $echo = true ) {
		$attrs = self::parse_attrs( $attrs );
		$tag = esc_attr( $tag );

		$the_content = '';
		if ( is_array($content) ) {
			foreach ( $content as $line ) {
				$the_content .= $line;
			}
		} else {
			$the_content = $content;
		}

		$output = "<{$tag}{$attrs}>{$the_content}</{$tag}>";

		if ( !$echo )
			return $output;

		echo $output;
	}

	/**
	 * Parses a key => value array into a valid format as HTML attributes.
	 *
	 * @since 0.3.0
	 *
	 * @param array $args Key value pairs of attributes.
	 * @return string Valid HTML attribute format.
	 */
	function parse_attrs( $args = array() ) {
		if ( empty($args) )
			return '';
		
		$attrs = '';
		foreach ( (array) $args as $key => $value ) {
			if ( $value ) {
				$attrs .= ' '. sanitize_key($key) .'="'. esc_attr($value) .'"';
			}
		}

		return $attrs;
	}

	/**
	 * Checks to see if a method exists within the specified object.
	 *
	 * @since 0.3.0
	 *
	 * @param object $object Object to check.
	 * @param string $method Method to check to see if it exists.
	 * @return bool True if the method exists, else false.
	 */
	function is_method( $object, $method ) {
		if ( method_exists( $object, $method ) )
			return true;

		return false;
	}

	/**
	 * Calls a method from an object if it exists.
	 *
	 * @since 0.3.0
	 *
	 * @param object $object Objcet to check.
	 * @param string $method Method to check to see if it exists.
	 * @param string $args Optional. Parameteres to pass to the method.
	 * @return void
	 */
	function callback( $object, $method, $args = array() ) {
		if ( self::is_method( $object, $method ) ) {
			return call_user_func_array( array( $object, $method ), $args );
		}
	}

	/**
	 * Returns a formated method, replacing dashes with underscores.
	 *
	 * @since 0.3.0
	 * 
	 * @param string $prefix String to prepend to the $context
	 * @param string $context String to sanitize.
	 * @return string Formatted contextual method.
	 */
	function contextual_method( $prefix, $context ) {
		return "{$prefix}_" . str_replace( '-', '_', sanitize_title_with_dashes($context) );
	}

	/**
	 * Trys to call a contextual method if it exists.
	 * If it doesn't, call the default method.
	 *
	 * @since 0.3.0
	 * 
	 * @param string $method Base method name.
	 * @param mixed $args Optional. Parameters to pass to the method.
	 * @return void
	 */
	function contextual_callback( $method, $args = array() ) {
		$callback = self::contextual_method( $method, $this->slug );

		if ( self::is_method( $this, $callback ) ) {
			return self::callback( $this, $callback, $args );
		} elseif ( self::is_method( $this, $method ) ) {
			return self::callback( $this, $method, $args );
		}
	}

	/**
	 * PHP4 Constructor - Does nothing.
	 *
	 * @return WPF_API
	 */
	function WPF_API() {
		// Empty.
	}
}