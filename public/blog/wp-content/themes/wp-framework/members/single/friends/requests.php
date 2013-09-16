<?php
/**
 * BuddyPress Template: Member Friend Requests
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_member_friend_requests_content' ); ?>

<?php if ( bp_has_members( 'include=' . bp_get_friendship_requests() . '&per_page=0' ) ) : ?>

	<ul id="friend-list" class="item-list">
		<?php while ( bp_members() ) : bp_the_member(); ?>

			<li id="friendship-<?php bp_friend_friendship_id(); ?>">
				<div class="item-avatar">
					<a href="<?php bp_member_link(); ?>"><?php bp_member_avatar(); ?></a>
				</div><!-- .item-avatar -->

				<div class="item">
					<div class="item-title"><a href="<?php bp_member_link(); ?>"><?php bp_member_name(); ?></a></div>
					<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>
				</div><!-- .item -->

				<?php do_action( 'bp_friend_requests_item' ); ?>

				<div class="action">
					<a class="button accept" href="<?php bp_friend_accept_request_link(); ?>"><?php _e( 'Accept', t() ); ?></a> &nbsp;
					<a class="button reject" href="<?php bp_friend_reject_request_link(); ?>"><?php _e( 'Reject', t() ); ?></a>

					<?php do_action( 'bp_friend_requests_item_action' ); ?>
				</div><!-- .action -->
			</li><!-- #friendship-<?php bp_friend_friendship_id(); ?> -->

		<?php endwhile; ?>
	</ul><!-- #friend-list .item-list -->

	<?php do_action( 'bp_friend_requests_content' ); ?>

<?php else: ?>

	<?php wpf_message( __( 'You have no pending friendship requests.', t() ), 'info' ); ?>

<?php endif;?>

<?php do_action( 'bp_after_member_friend_requests_content' ); ?>