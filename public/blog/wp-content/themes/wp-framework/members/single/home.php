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
					<?php do_action( 'bp_before_member_home_page' ); ?>

					<div id="member-home-page">

						<?php do_action( 'bp_before_member_home_content' ); ?>

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
						</div><!-- #item-nav -->

						<div id="item-body">
							<?php do_action( 'bp_before_member_body' ); ?>

							<?php if ( bp_is_user_activity() || !bp_current_component() ) : ?>
								<?php get_template_part( 'members/single/activity' ); ?>

							<?php elseif ( bp_is_user_blogs() ) : ?>
								<?php get_template_part( 'members/single/blogs' ); ?>

							<?php elseif ( bp_is_user_friends() ) : ?>
								<?php get_template_part( 'members/single/friends' ); ?>

							<?php elseif ( bp_is_user_groups() ) : ?>
								<?php get_template_part( 'members/single/groups' ); ?>

							<?php elseif ( bp_is_user_messages() ) : ?>
								<?php get_template_part( 'members/single/messages' ); ?>

							<?php elseif ( bp_is_user_profile() ) : ?>
								<?php get_template_part( 'members/single/profile' ); ?>

							<?php else : ?>
								<?php
									/* If nothing sticks, just load a member front template if one exists. */
									get_template_part( 'members/single/front' );
								?>
							<?php endif; ?>

							<?php do_action( 'bp_after_member_body' ); ?>

						</div><!-- #item-body -->

						<?php do_action( 'bp_after_member_home_content' ); ?>

					</div><!-- #member-home-page -->

					<?php do_action( 'bp_after_member_home_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>