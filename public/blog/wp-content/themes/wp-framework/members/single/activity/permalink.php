<?php
/**
 * BuddyPress Template: Member Activity Permalink
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_member_home_page' ); ?>

					<div id="member-activity-permalink">

						<?php do_action( 'bp_before_member_activity_permalink' ); ?>

						<div class="activity no-ajax">
							<?php if ( bp_has_activities( 'display_comments=threaded&include=' . bp_current_action() ) ) : ?>

								<ul id="activity-stream" class="activity-list item-list">
								<?php while ( bp_activities() ) : bp_the_activity(); ?>

									<?php get_template_part( 'activity/entry' ); ?>

								<?php endwhile; ?>
								</ul><!-- #activity-stream .activity-list .item-list -->

							<?php endif; ?>
						</div><!-- .activity no-ajax -->

						<?php do_action( 'bp_after_member_activity_permalink' ); ?>

					</div><!-- #member-activity-permalink -->

					<?php do_action( 'bp_after_member_home_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>