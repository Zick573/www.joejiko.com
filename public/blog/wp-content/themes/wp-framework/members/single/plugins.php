<?php
/**
 * BuddyPress Template: Member Plugins
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_member_plugins_page' ); ?>

					<div id="member-plugin-page" class="hfeed">

						<?php do_action( 'bp_before_member_plugin_template' ); ?>

						<div id="item-header">
							<?php get_template_part( 'members/single/member-header' ); ?>
						</div><!-- #item-header -->

						<div id="item-nav">
							<div class="item-list-tabs no-ajax" id="object-nav">
								<ul>
									<?php bp_get_displayed_user_nav(); ?>

									<?php do_action( 'bp_member_options_nav' ); ?>
								</ul>
							</div><!-- #object-nav .item-list-tabs no-ajax -->
							
							<div class="item-list-tabs no-ajax" id="subnav">
								<ul>
									<?php bp_get_options_nav(); ?>

									<?php do_action( 'bp_member_plugin_options_nav' ); ?>
								</ul>
							</div><!-- #subnav .item-list-tabs .no-ajax -->
						</div><!-- #item-nav -->

						<div id="item-body">

							<?php do_action( 'bp_before_member_body' ); ?>

							<h3><?php do_action( 'bp_template_title' ); ?></h3>

							<?php do_action( 'bp_template_content' ); ?>

							<?php do_action( 'bp_after_member_body' ); ?>

						</div><!-- #item-body -->

						<?php do_action( 'bp_after_member_plugin_template' ); ?>

					</div><!-- #member-plugin-page -->

					<?php do_action( 'bp_after_member_plugins_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>