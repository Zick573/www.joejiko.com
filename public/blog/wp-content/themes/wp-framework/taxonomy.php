<?php
/**
 * WordPress Template: Taxonomy
 *
 * The taxonomy template is the general template used when a taxonomy is 
 * queried. The category.php and tag.php default to this taxonomy template
 * for better semantics.
 * 
 * To use a custom template for a specfic taxonomy or taxonomy term, create a
 * taxonomy-{taxonomy}.php or taxonomy-{taxonomy}-{term}.php file in the your 
 * theme's root directory.
 *
 * Template Hierarchy
 * - taxonomy-{taxonomy}-{term}.php (i.e. taxonomy-category-uncategorized.php)
 * - taxonomy-{taxonomy}.php (i.e. taxonomy-category.php)
 * - taxonomy.php
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