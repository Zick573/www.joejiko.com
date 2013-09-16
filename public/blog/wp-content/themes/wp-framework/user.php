<?php
/**
 * WordPress Template: User
 *
 * The user template is the general template used when a user's archive is
 * queried. The user template replaces author-type templates for better
 * semantics.
 * 
 * To use a custom template for a specfic user, create a user-{nicename}.php
 * file, user-{id}.php file, or user-role-{role}.php file in the your theme's 
 * root directory and replace the keywords in brackets with that user's nice 
 * name, id, or role.
 *
 * Template Hierarchy
 * - user-{nicename}.php (i.e. user-ptahdunbar.php)
 * - user-{id}.php (i.e. user-1.php)
 * - user-role-{role}.php (i.e. user-role-subscriber.php)
 * - user.php
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