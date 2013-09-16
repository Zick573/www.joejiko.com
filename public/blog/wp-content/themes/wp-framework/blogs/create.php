<?php
/**
 * BuddyPress Template: Blogs Create
 *
 * This template is only used on multisite installations.
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_blogs_create_page' ); ?>

					<div id="blogs-create-page">

						<?php do_action( 'template_notices' ); ?>

						<h3><?php _e( 'Create a Blog', t() ); ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . BP_BLOGS_SLUG . '/' ?>"><?php _e( 'Blogs Directory', t() ); ?></a></h3>

						<?php do_action( 'bp_before_create_blog_content' ); ?>

						<?php if ( bp_blog_signup_enabled() ) : ?>

							<?php bp_show_blog_signup_form(); ?>

						<?php else: ?>

							<?php wpf_message( __( 'Blog registration is currently disabled', t() ), 'info' ); ?>

						<?php endif; ?>

						<?php do_action( 'bp_after_create_blog_content' ); ?>

					</div><!-- #blogs-create-page -->

					<?php do_action( 'bp_after_blogs_create_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>