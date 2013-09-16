<?php
/**
 * WordPress Template: Archive
 *
 * The archive template is the general template used when an archive-based 
 * template is queried.
 * 
 * To use a custom template for a specfic archive, create a taxonomy.php file 
 * for taxonomies, user.php file for users/authors, or date.php file for 
 * date-based archives and store them in your parent/child theme's root 
 * directory.
 * 
 * Template Hierarchy
 * - archive.php
 * - index.php
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-7">

					<?php do_action( 'content_open' ); ?>

					<?php get_template_part( 'loop', 'archive' ); ?>

					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar' ); ?>

<?php get_template_part( 'footer' ); ?>