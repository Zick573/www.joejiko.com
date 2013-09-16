<?php
/**
 * BuddyPress Template: Groups Home
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_group_home_page' ); ?>

					<div id="group-home-page">

						<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

						<?php do_action( 'bp_before_group_home_content' ); ?>

						<div id="item-header">
							<?php get_template_part( 'groups/single/group-header' ); ?>
						</div><!-- #item-header -->

						<div id="item-nav">
							<div class="item-list-tabs no-ajax" id="object-nav">
								<ul>
									<?php bp_get_options_nav(); ?>

									<?php do_action( 'bp_group_options_nav' ); ?>
								</ul>
							</div>
						</div><!-- #item-nav -->

						<div id="item-body">
							<?php do_action( 'bp_before_group_body' ); ?>

							<?php if ( bp_is_group_admin_page() && bp_group_is_visible() ) : ?>
								<?php get_template_part( 'groups/single/admin' ); ?>

							<?php elseif ( bp_is_group_members() && bp_group_is_visible() ) : ?>
								<?php get_template_part( 'groups/single/members' ); ?>

							<?php elseif ( bp_is_group_invites() && bp_group_is_visible() ) : ?>
								<?php get_template_part( 'groups/single/send-invites' ); ?>

							<?php elseif ( bp_is_group_forum() && bp_group_is_visible() ) : ?>
								<?php get_template_part( 'groups/single/forum' ); ?>

							<?php elseif ( bp_is_group_membership_request() ) : ?>
								<?php get_template_part( 'groups/single/request-membership' ); ?>

							<?php elseif ( bp_group_is_visible() && bp_is_active( 'activity' ) ) : ?>
								<?php get_template_part( 'groups/single/activity' ); ?>

							<?php elseif ( !bp_group_is_visible() ) : ?>
								<?php /* The group is not visible, show the status message */ ?>

								<?php do_action( 'bp_before_group_status_message' ); ?>

								<?php wpf_message( bp_group_status_message(), 'info' ); ?>

								<?php do_action( 'bp_after_group_status_message' ); ?>

							<?php else : ?>
								<?php
									/* If nothing sticks, just load a group front template if one exists. */
									get_template_part( 'groups/single/front' );
								?>
							<?php endif; ?>

							<?php do_action( 'bp_after_group_body' ); ?>
						</div><!-- #item-body -->

						<?php do_action( 'bp_after_group_home_content' ); ?>

						<?php endwhile; endif; ?>

					</div><!-- #group-home-page -->

					<?php do_action( 'bp_after_group_home_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>