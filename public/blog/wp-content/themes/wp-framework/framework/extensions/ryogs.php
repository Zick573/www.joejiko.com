<?php
/**
 * Roll Your Own Grid System - Personalized CSS Framework.
 *
 * To enable the use of RYOGS, add the following code into the
 * after_setup_theme hook:
 *
 * @version 0.3.1
 * @author Ptah Dunbar {@link http://ptahdunbar.com/}
 * @license GPL v2 {@link http://www.gnu.org/licenses/old-licenses/gpl-2.0.html}
 */

$action = false;

// To generate the helper grid.png image, here's an example $_GET request: ryogs.php?c=40&g=20&b=22
if ( isset( $_GET['c'] ) and isset( $_GET['g'] ) )
 	$action = 'showgrid';

// To generate the css grid stylesheet, here's an example $_GET request: ryogs.php?ver=54-30-22
if ( isset( $_GET['ver'] ) )
	$action = 'showcss';

// Do action
switch ( $action ) {
	case 'showgrid':
		$column = intval( $_GET['c'] ); // column width
		$gutter = intval( $_GET['g'] ); // gutter width

		// $baseline
		isset( $_GET['b'] ) ? $baseline = intval( $_GET['b'] ) : $baseline = 1; // typo baseline

		if ( !wpf_roygs_sanitize( $baseline, 'int', 3 ) or !wpf_roygs_sanitize( $column, 'int', 3 ) or !wpf_roygs_sanitize( $gutter, 'int', 3 ) ) die();

		// Set the values for grig.png's demensions.
		$width = $column + $gutter;
		$height = $baseline;

		// Set the values for the baseline.
		$bl_width = $width - 1;
		$bl_height = $height - 1;

		// Create the image and define colors
		$png = imagecreatetruecolor( $width, $height );

		if ( !$png ) 
			return; // If the iamge wasn't create, die.

		$column_color	 = imagecolorallocatealpha( $png, 232, 239, 251, 0 ); // Defines the column color and alpha transparency
		$gutter_color 	 = imagecolorallocatealpha( $png, 255, 255, 255, 0 ); // Defines the gutter color and alpha transparency
		// $baseline_color	 = imagecolorallocatealpha( $png, 233, 233, 233, 0 ); // Defines the baseline color and alpha transparency
		$baseline_color	 = imagecolorallocatealpha( $png, 255, 172, 172, 0 ); // Defines the baseline color and alpha transparency (red)
		$baseline_color	 = imagecolorallocatealpha( $png, 194, 208, 201, 0 ); // Defines the baseline color and alpha transparency (gray)

		imagefilledrectangle( $png, 0, 0, $column, $height, $column_color ); // Column Color
		imagefilledrectangle( $png, $column, 0, $width, $height, $gutter_color ); // Gutter Color

		// Draw baseline color
		// x1 = 0, y1 = height -1, x2 = height -1, y2 = width -1
		if ( $height > 1 ) imageline ( $png, 0, $bl_height, $bl_width, $bl_height, $baseline_color );

		// Display the image
		header( 'Content-type: image/png' );
		imagepng( $png );
		imagedestroy( $png );
		break;

	case 'showcss':
		define( 'SHORTINT', true );

		// Find the path to wp-load.php. Since themes can exists anywhere,
		// it recursively checks down the directory tree.
		$count = 5;
		$path = '../';
		while ( $count <= 12 ) {			
			if ( file_exists( str_repeat( $path, $count ) . 'wp-load.php' ) )
				@include( str_repeat( $path, $count ) . 'wp-load.php' );

			$count++;
		}

		// bail if we failed :/
		if ( !defined('WPF_EXT_URI') )
			return;

		if ( !current_theme_supports( 'css-grid-framework' ) )
			return;

		$options = stripslashes( $_GET['ver'] );
		$options = explode( '-', $options );

		$columns = intval( $options[0] ); // number of columns
		$width = intval( $options[1] ); // column widths
		$gutter = intval( $options[2] ); // width of gutters
		$baseline = isset( $options[3] ) ? intval( $options[3] ) : 1; // baseline

		// Sanitize
		if ( !wpf_roygs_sanitize( $columns, 'int', 4 ) or !wpf_roygs_sanitize( $width, 'int', 4 ) or !wpf_roygs_sanitize( $gutter, 'int', 4 ) or !wpf_roygs_sanitize( $baseline, 'int', 4 ) )
			return; // Whoa now! Only numbers man.. and they can't be super loooong! like, more than 4 integers long. shesh. This is a solid CSS grid system generator script, not some lame, "I'm going to let you hack my script kinda thing." We don't roll like that 'round here, chump. Try again :)

		$wrap = $columns * $width + ($columns - 1) * $gutter; // wrap width;

		$css_div_widths = $css_column_widths = $css_append = $css_prepend = $css_push = $css_pull = '';

		// BEAST MODE. Creates all the classes for the grid: .column, .after, .before, .push, .pull
		if ( $columns == 1 ) {
			$css_column_widths = '.column-1 { width: ' . $width . 'px; }';
		} else {
			for ( $i = 1; $i <= $columns; $i++ ) {
				$i_width = $i * $width + ($i - 1) * $gutter;
				$ap_width = $i_width + $gutter;

				if ( $i == $columns )
					$div_widths[] = '.column-' . $i . '';
				else
					$div_widths[] = '.column-' . $i . ',';

				$widths[] = '.column-' . $i . ' { width: ' . $i_width . 'px; }';

				$append[] = '.after-' . $i . ' { padding-right: ' . $ap_width . 'px; }';
				$prepend[] = '.before-' . $i . ' { padding-left: ' . $ap_width . 'px; }';

				$push[] = '.push-' . $i . ' { left: ' . $ap_width . 'px; }';
				$pull[] = '.pull-' . $i . ' { left: -' . $ap_width . 'px; }';
			}

			$css_div_widths = join( " \n", $div_widths );
			$css_column_widths = join( " \n", $widths );
			$css_append = join( " \n", $append );
			$css_prepend = join( " \n", $prepend );
			$css_push = join( " \n", $push );
			$css_pull = join( " \n", $pull );
		}

		// Generates the helper grid image for .showgrid
		$site_url = WPF_EXT_URI . "/grid.png.php?c={$width}&g={$gutter}&b={$baseline}";

// Output the CSS Framework
header( 'Content-type: text/css' );
echo <<<CSS
/**
 * CSS Stylesheet: Grid
 *
 * Grid details:
 * - {$columns}px columns
 * - {$width}px column width
 * - {$gutter}px gutters
 * - {$baseline}px baseline typography
 *
 * @version 0.2
 * @package WP Framework
 * @subpackage RYOGS - Personalized CSS Framework
 */

/* FYI: This grid has {$columns} column(s), each spanning {$width}px wide, with {$gutter}px gutters. */

/* The wrap element should group all your columns */
.wrap { width: {$wrap}px; margin-left: auto; margin-right: auto; }

/* Use this class on any .column/.wrap to see the grid. */
.showgrid { background: url( '{$site_url}' ) !important; }

/* =Columns
-------------------------------------------------------------- */

/* Sets up basic grid floating and margin. */
$css_div_widths { position: relative; float: left; margin-right: {$gutter}px; }

/* The last column in a row needs this class. */
.last { margin-right: 0; }

/* Use these classes to set the width of a column. */
$css_column_widths

/* =Extra
-------------------------------------------------------------- */
/* Add these to a column to append empty cols. */
$css_append

/* Add these to a column to prepend empty cols. */
$css_prepend

/* Use these classes on an element to push it into the next column */
$css_push

/* Use these classes on an element to pull it into the previous column. */
$css_pull

/* Clear the .wrap */
.wrap:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
.wrap { display: block; }
CSS;
		break;

	default:
		die( 'Fail.' );
		break;
}

/**
 * Sanitize parameters.
 *
 * @param string $var 
 * @param string $type Type of data. ini|string
 * @param init $length 
 * @return void
 */
function wpf_roygs_sanitize( $var, $type, $length ){
	$is_type = 'is_'. $type; // assign the type
	if ( !$is_type( $var ) ) return false; // check to see whether the $var is of $type.
	elseif ( empty( $var ) ) return false; // now we see if there is anything in the string
	elseif ( strlen( $var ) > $length ) return false; // then we check how long the string is
	else return true; // if all is well, we return TRUE
}