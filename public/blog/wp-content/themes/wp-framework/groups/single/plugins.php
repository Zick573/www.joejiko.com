<?php
/**
 * BuddyPress Template: Groups Plugins
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_groups_plugins_page' ); ?>

					<div id="group-plugin-page">

						<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

						<?php do_action( 'bp_before_group_plugin_template' ); ?>

						<div id="item-header">
							<?php get_template_part( 'groups/single/group-header' ); ?>
						</div><!-- #item-header -->

						<div id="item-nav">
							<div class="item-list-tabs no-ajax" id="object-nav">
								<ul>
									<?php bp_get_options_nav(); ?>

									<?php do_action( 'bp_group_plugin_options_nav' ); ?>
								</ul><!-- #object-nav .item-list-tabs no-ajax -->
							</div>
						</div><!-- #item-nav -->

						<div id="item-body">

							<?php do_action( 'bp_before_group_body' ); ?>

							<?php do_action( 'bp_template_content' ); ?>

							<?php do_action( 'bp_after_group_body' ); ?>

						</div><!-- #item-body -->

						<?php endwhile; endif; ?>

						<?php do_action( 'bp_after_group_plugin_template' ); ?>

					</div><!-- #group-plugin-page -->

					<?php do_action( 'bp_after_groups_plugins_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>