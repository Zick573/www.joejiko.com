<?php
/**
 * Functions for handling attachments and media uploads in WP Framework.
 *
 * @todo Rework all of these functions.
 *
 * @package WP Framework
 */

/**
 * Loads the correct function for handling attachments. Checks the attachment mime 
 * type to call correct function. Image attachments are not loaded with this function.
 * The functionality for them resides in image.php.
 *
 * Ideally, all attachments would be appropriately handled within their templates. However, 
 * this could lead to messy template files. For now, we'll use separate functions for handling 
 * attachment content. The biggest issue here is with handling different video types.
 *
 * @since 0.3.0
 * @uses get_post_mime_type() Gets the mime type of the attachment.
 * @uses wp_get_attachment_url() Gets the URL of the attachment file.
 */
function wpf_display_attachment() {
	$file = wp_get_attachment_url();
	$mime = get_post_mime_type();
	$mime_type = explode( '/', $mime );

	/* Loop through each mime type. If a function exists for it, call it. Allow users to filter the display. */
	foreach ( $mime_type as $type ) {
		if ( function_exists( "wpf_{$type}_attachment" ) )
			$attachment = call_user_func( "wpf_{$type}_attachment", $mime, $file );

		$attachment = apply_filters( "{$type}_attachment", $attachment );
	}

	echo apply_filters( 'attachment', $attachment );
}

/**
 * Handles application attachments on their attachment pages.
 * Uses the <object> tag to embed media on those pages.
 *
 * @todo Run a battery of tests on many different applications.
 * @todo Figure out what to do with FLV files outside of the current functionality.
 *
 * @since 0.3.0
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 * @return string
 */
function wpf_application_attachment( $mime = '', $file = '' ) {
	$application = '<object class="text" type="' . $mime . '" data="' . $file . '" width="400">';
	$application .= '<param name="src" value="' . $file . '" />';
	$application .= '</object>';

	return $application;
}

/**
 * Handles text attachments on their attachment pages.
 * Uses the <object> element to embed media in the pages.
 *
 * @since 0.3.0
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 * @return string
 */
function wpf_text_attachment( $mime = '', $file = '' ) {
	$text = '<object class="text" type="' . $mime . '" data="' . $file . '" width="400">';
	$text .= '<param name="src" value="' . $file . '" />';
	$text .= '</object>';

	return $text;
}

/**
 * Handles audio attachments on their attachment pages.
 * Puts audio/mpeg and audio/wma files into an <object> element.
 *
 * @todo Test out and support more audio types.
 *
 * @since 0.3.0
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 * @return string
 */
function wpf_audio_attachment( $mime = '', $file = '' ) {
	$audio = '<object type="' . $mime . '" class="player audio" data="' . $file . '" width="400" height="50">';
		$audio .= '<param name="src" value="' . $file . '" />';
		$audio .= '<param name="autostart" value="false" />';
		$audio .= '<param name="controller" value="true" />';
	$audio .= '</object>';

	return $audio;
}

/**
 * Handles video attachments on attachment pages.
 * Add other video types to the <object> element.
 *
 * @todo Test out and support more video types.
 *
 * @since 0.3.0
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 * @return string
 */
function wpf_video_attachment( $mime = false, $file = false ) {
	if ( $mime == 'video/asf' )
		$mime = 'video/x-ms-wmv';

	$video = '<object type="' . $mime . '" class="player video" data="' . $file . '" width="400" height="320">';
		$video .= '<param name="src" value="' . $file . '" />';
		$video .= '<param name="autoplay" value="false" />';
		$video .= '<param name="allowfullscreen" value="true" />';
		$video .= '<param name="controller" value="true" />';
	$video .= '</object>';

	return $video;
}

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme's style.css.
 *
 * @since 0.3.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function wpf_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'wpf_remove_gallery_css' );
?>