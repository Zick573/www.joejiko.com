<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * 
 * @package landscape
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 *
 * @package landscape
 */
$args = array(
	'width'         => 1440,
	'height'        => 500,
	'default-image' => get_template_directory_uri() . '/images/default-header.jpg',
	'header-text'   => false,
	'uploads'       => true,
);
add_theme_support( 'custom-header', $args );



if ( ! function_exists( 'landscape_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see landscape_custom_header_setup().
 *
 * @since landscape 1.0
 */
function landscape_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php }
endif; // landscape_admin_header_image


if ( ! function_exists( 'landscape_custom_header_image' ) ) :
/**
 * Header image styles for custom header and featured images
 *
 * @since landscape 1.0
 */
function landscape_custom_header_image() {

	$header_image = get_header_image();
	global $content_width;
?>

	<style type="text/css">

	<?php

	if ( ! empty( $header_image ) ) :

		$background = "rgba(0,0,0,.7)"; ?>

		#masthead {
			background: #111 url( <?php echo esc_url( $header_image ); ?> ) center 0 no-repeat;
			margin-top: 0;
			padding-bottom: 0;
			max-width: 100%;
			height: <?php echo get_custom_header()->height; ?>px;
		}

	<?php endif; // !empty header_image()

	if ( is_single() || is_page() ) :

		if ( '' != get_the_post_thumbnail() ) :

			$featured_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'featured-thumbnail' );
			$header_image = "background: #111 url(" . $featured_image_url[0] . ") center 0 no-repeat;";
			$menubackground = "rgba(0,0,0,.7)";
			$mastheadbackground = "#111";
			$mastheadheight = "500px";
			$paddingbottom = "0"; ?>


			#masthead {
				<?php echo $header_image; ?>
				margin-top: 0;
				padding-bottom: <?php echo $paddingbottom; ?>;
				max-width: 100%;theadheight; ?>;
				position: relative;
				background-color: <?php echo $mastheadbackground; ?>;
			}
			#masthead hgroup{
				display:none;
			}
		<?php endif; //'' != get_the_post_thumbnail() ?>
	<?php endif; //is_single() && ! is_attachment() ?>
	</style>
<?php
}
endif; // landscape_custom_header_image

add_action( 'wp_head', 'landscape_custom_header_image' );