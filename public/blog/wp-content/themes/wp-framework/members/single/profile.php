<?php
/**
 * BuddyPress Template: Member Profile
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php if ( bp_is_my_profile() ) : ?>
	<div class="item-list-tabs no-ajax" id="subnav">
		<ul>
			<?php bp_get_options_nav(); ?>
		</ul>
	</div><!-- #subnav .item-list-tabs .no-ajax -->
<?php endif; ?>

<?php do_action( 'bp_before_profile_content' ); ?>

<div class="profile">
	<?php if ( 'edit' == bp_current_action() ) : ?>
		<?php get_template_part( 'members/single/profile/edit' ); ?>

	<?php elseif ( 'change-avatar' == bp_current_action() ) : ?>
		<?php get_template_part( 'members/single/profile/change-avatar' ); ?>

	<?php else : ?>
		<?php get_template_part( 'members/single/profile/profile-loop' ); ?>

	<?php endif; ?>
</div><!-- .profile -->

<?php do_action( 'bp_after_profile_content' ); ?>