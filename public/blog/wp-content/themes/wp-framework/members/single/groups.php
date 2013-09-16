<?php
/**
 * BuddyPress Template: Member Groups
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

		<?php if ( 'invites' != bp_current_action() ) : ?>
		<li id="groups-order-select" class="last filter">

			<?php _e( 'Order By:', t() ); ?>
			<select id="groups-sort-by">
				<option value="active"><?php _e( 'Last Active', t() ); ?></option>
				<option value="popular"><?php _e( 'Most Members', t() ); ?></option>
				<option value="newest"><?php _e( 'Newly Created', t() ); ?></option>
				<option value="alphabetical"><?php _e( 'Alphabetical', t() ); ?></option>

				<?php do_action( 'bp_member_group_order_options' ); ?>
			</select><!-- #groups-sort-by -->
		</li><!-- #groups-order-select .last .filter -->
		<?php endif; ?>
	</ul>
</div><!-- #subnav .item-list-tabs .no-ajax -->

<?php if ( 'invites' == bp_current_action() ) : ?>
	<?php get_template_part( 'members/single/groups/invites' ); ?>

<?php else : ?>
	
	<?php do_action( 'bp_before_member_groups_content' ); ?>

	<div class="groups mygroups">
		<?php get_template_part( 'groups/groups-loop' ); ?>
	</div><!-- .groups .mygroups -->

	<?php do_action( 'bp_after_member_groups_content' ); ?>

<?php endif; ?>