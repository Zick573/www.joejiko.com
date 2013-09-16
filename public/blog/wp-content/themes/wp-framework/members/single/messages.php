<?php
/**
 * BuddyPress Template: Member Messages
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<div class="item-list-tabs no-ajax" id="subnav">
	<ul>
		<?php bp_get_options_nav(); ?>
	</ul>
</div><!-- #subnav .item-list-tabs .no-ajax -->

<?php if ( 'compose' == bp_current_action() ) : ?>
	<?php get_template_part( 'members/single/messages/compose' ); ?>

<?php elseif ( 'view' == bp_current_action() ) : ?>
	<?php get_template_part( 'members/single/messages/single' ); ?>

<?php else : ?>

	<?php do_action( 'bp_before_member_messages_content' ); ?>

	<div class="messages">
		<?php if ( 'notices' == bp_current_action() ) : ?>
			<?php get_template_part( 'members/single/messages/notices-loop' ); ?>

		<?php else : ?>
			<?php get_template_part( 'members/single/messages/messages-loop' ); ?>

		<?php endif; ?>
	</div><!-- .messages -->

	<?php do_action( 'bp_after_member_messages_content' ); ?>

<?php endif; ?>