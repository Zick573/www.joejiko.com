<?php
/**
 * WordPress Template: Search
 *
 * The search template is used when a search term is queried.
 *
 * Template Hierarchy
 * - search.php
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