<?php
/**
 * BuddyPress Template: Group Activity
 *
 * @package WP Framework
 * @subpackage Template
 */
?>
<div class="item-list-tabs no-ajax" id="subnav">
	<ul>
		<li class="feed"><a href="<?php bp_group_activity_feed_link(); ?>" title="<?php _e( 'RSS Feed', t() ); ?>"><?php _e( 'RSS', t() ); ?></a></li><!-- .feed -->

		<?php do_action( 'bp_group_activity_syndication_options' ); ?>

		<li id="activity-filter-select" class="last">
			<select>
				<option value="-1"><?php _e( 'No Filter', t() ); ?></option>
				<option value="activity_update"><?php _e( 'Show Updates', t() ); ?></option>

				<?php if ( bp_is_active( 'forums' ) ) : ?>
					<option value="new_forum_topic"><?php _e( 'Show New Forum Topics', t() ); ?></option>
					<option value="new_forum_post"><?php _e( 'Show Forum Replies', t() ); ?></option>
				<?php endif; ?>

				<option value="joined_group"><?php _e( 'Show New Group Memberships', t() ); ?></option>

				<?php do_action( 'bp_group_activity_filter_options' ); ?>
			</select>
		</li><!-- #activity-filter-select .last -->
	</ul>
</div><!-- #subnav .item-list-tabs .no-ajax -->

<?php do_action( 'bp_before_group_activity_post_form' ); ?>

<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>
	<?php get_template_part( 'activity/post-form' ); ?>
<?php endif; ?>

<?php do_action( 'bp_after_group_activity_post_form' ); ?>
<?php do_action( 'bp_before_group_activity_content' ); ?>

<div class="activity single-group">
	<?php get_template_part( 'activity/activity-loop' ); ?>
</div><!-- .activity.single-group -->

<?php do_action( 'bp_after_group_activity_content' ); ?>
