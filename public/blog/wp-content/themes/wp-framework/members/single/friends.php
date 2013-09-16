<?php
/**
 * BuddyPress Template: Member Friends
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<div class="item-list-tabs no-ajax" id="subnav">
	<ul>
		<?php if ( bp_is_my_profile() ) : ?>
			<?php bp_get_options_nav(); ?>
		<?php endif; ?>

		<li id="members-order-select" class="last filter">

			<?php _e( 'Order By:', t() ); ?>
			<select id="members-all">
				<option value="active"><?php _e( 'Last Active', t() ); ?></option>
				<option value="newest"><?php _e( 'Newest Registered', t() ); ?></option>
				<option value="alphabetical"><?php _e( 'Alphabetical', t() ); ?></option>

				<?php do_action( 'bp_member_blog_order_options' ); ?>
			</select><!-- #members-all -->
		</li><!-- #members-order-select .last .filter -->
	</ul>
</div><!-- #subnav .item-list-tabs .no-ajax -->

<?php if ( 'requests' == bp_current_action() ) : ?>
	<?php get_template_part( 'members/single/friends/requests' ); ?>

<?php else : ?>

	<?php do_action( 'bp_before_member_friends_content' ); ?>

	<div class="members friends">
		<?php get_template_part( 'members/members-loop' ); ?>
	</div><!-- .members .friends -->

	<?php do_action( 'bp_after_member_friends_content' ); ?>

<?php endif; ?>