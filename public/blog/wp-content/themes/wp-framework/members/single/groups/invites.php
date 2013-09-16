<?php
/**
 * BuddyPress Template: Member Groups Invite
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_group_invites_content' ); ?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>

	<ul id="group-list" class="invites item-list">

		<?php while ( bp_groups() ) : bp_the_group(); ?>

			<li>
				<?php bp_group_avatar_thumb(); ?>
				<h4><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a><span class="small"> - <?php printf( __( '%s members', t() ), bp_group_total_members( false ) ); ?></span></h4>

				<p class="desc">
					<?php bp_group_description_excerpt(); ?>
				</p><!-- .desc -->

				<?php do_action( 'bp_group_invites_item' ); ?>

				<div class="action">
					<a class="button accept" href="<?php bp_group_accept_invite_link(); ?>"><?php _e( 'Accept', t() ); ?></a> &nbsp;
					<a class="button reject confirm" href="<?php bp_group_reject_invite_link(); ?>"><?php _e( 'Reject', t() ); ?></a>

					<?php do_action( 'bp_group_invites_item_action' ); ?>

				</div><!-- .action -->
			</li>

		<?php endwhile; ?>
	</ul><!-- #group-list .invites .item-list -->

<?php else: ?>

	<?php wpf_message( __( 'You have no outstanding group invites.', t() ), 'info' ); ?>

<?php endif;?>

<?php do_action( 'bp_after_group_invites_content' ); ?>