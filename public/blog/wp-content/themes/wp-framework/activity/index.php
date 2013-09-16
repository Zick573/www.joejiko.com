<?php
/**
 * BuddyPress Template: Index
 *
 * This template is used by activity-loop.php and AJAX functions to show each
 * activity.
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_activity_page' ); ?>

					<div id="activity-page" class="hfeed">
						<?php if ( !is_user_logged_in() ) : ?>
							<h3 class="page-title"><?php _e( 'Site Activity', t() ); ?></h3>
						<?php endif; ?>

						<?php do_action( 'bp_before_directory_activity_content' ); ?>

						<?php if ( is_user_logged_in() ) : ?>
							<?php get_template_part( 'activity/post-form' ); ?>
						<?php endif; ?>

						<?php do_action( 'template_notices' ); ?>

						<div class="item-list-tabs activity-type-tabs">
							<ul>
								<?php do_action( 'bp_before_activity_type_tab_all' ); ?>

								<li class="selected" id="activity-all"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/' ?>" title="<?php _e( 'The public activity for everyone on this site.', t() ); ?>"><?php printf( __( 'All Members (%s)', t() ), bp_get_total_site_member_count() ); ?></a></li>

								<?php if ( is_user_logged_in() ) : ?>

									<?php do_action( 'bp_before_activity_type_tab_friends' ); ?>

									<?php if ( bp_is_active( 'friends' ) ) : ?>
										<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
											<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/' . BP_FRIENDS_SLUG . '/' ?>" title="<?php _e( 'The activity of my friends only.', t() ); ?>"><?php printf( __( 'My Friends (%s)', t() ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>
										<?php endif; ?>
									<?php endif; ?>

									<?php do_action( 'bp_before_activity_type_tab_groups' ); ?>

									<?php if ( bp_is_active( 'groups' ) ) : ?>
										<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
											<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/' . BP_GROUPS_SLUG . '/' ?>" title="<?php _e( 'The activity of groups I am a member of.', t() ); ?>"><?php printf( __( 'My Groups (%s)', t() ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>
										<?php endif; ?>
									<?php endif; ?>

									<?php do_action( 'bp_before_activity_type_tab_favorites' ); ?>

									<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>
										<li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/favorites/' ?>" title="<?php _e( "The activity I've marked as a favorite.", t() ); ?>"><?php printf( __( 'My Favorites (<span>%s</span>)', t() ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>
									<?php endif; ?>

									<?php do_action( 'bp_before_activity_type_tab_mentions' ); ?>

									<li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/mentions/' ?>" title="<?php _e( 'Activity that I have been mentioned in.', t() ); ?>"><?php printf( __( '@%s Mentions', t() ), bp_get_loggedin_user_username() ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><?php printf( __( '(%s new)', t() ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></strong><?php endif; ?></a></li>

								<?php endif; ?>

								<?php do_action( 'bp_activity_type_tabs' ); ?>
							</ul>
						</div><!-- .item-list-tabs -->

						<div class="item-list-tabs no-ajax" id="subnav">
							<ul>
								<li class="feed"><a href="<?php bp_sitewide_activity_feed_link(); ?>" title="<?php _e( 'RSS Feed', t() ); ?>"><?php _e( 'RSS', t() ); ?></a></li>

								<?php do_action( 'bp_activity_syndication_options' ); ?>

								<li id="activity-filter-select" class="last">
									<select>
										<option value="-1"><?php _e( 'No Filter', t() ); ?></option>
										<option value="activity_update"><?php _e( 'Show Updates', t() ); ?></option>

										<?php if ( bp_is_active( 'blogs' ) ) : ?>
											<option value="new_blog_post"><?php _e( 'Show Blog Posts', t() ); ?></option>
											<option value="new_blog_comment"><?php _e( 'Show Blog Comments', t() ); ?></option>
										<?php endif; ?>

										<?php if ( bp_is_active( 'forums' ) ) : ?>
											<option value="new_forum_topic"><?php _e( 'Show New Forum Topics', t() ); ?></option>
											<option value="new_forum_post"><?php _e( 'Show Forum Replies', t() ); ?></option>
										<?php endif; ?>

										<?php if ( bp_is_active( 'groups' ) ) : ?>
											<option value="created_group"><?php _e( 'Show New Groups', t() ); ?></option>
											<option value="joined_group"><?php _e( 'Show New Group Memberships', t() ); ?></option>
										<?php endif; ?>

										<?php if ( bp_is_active( 'friends' ) ) : ?>
											<option value="friendship_accepted,friendship_created"><?php _e( 'Show Friendship Connections', t() ); ?></option>
										<?php endif; ?>

										<option value="new_member"><?php _e( 'Show New Members', t() ); ?></option>

										<?php do_action( 'bp_activity_filter_options' ); ?>
									</select>
								</li><!-- #activity-filter-select -->
							</ul>
						</div><!-- .item-list-tabs -->

						<div class="activity">
							<?php get_template_part( 'activity/activity-loop' ); ?>
						</div><!-- .activity -->

						<?php do_action( 'bp_after_directory_activity_content' ); ?>
					</div><!-- #activity-page -->

					<?php do_action( 'bp_after_activity_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>