<?php
/**
 * BuddyPress Template: Request Membership
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_group_request_membership_content' ); ?>

<?php if ( !bp_group_has_requested_membership() ) : ?>
	<p><?php printf( __( "You are requesting to become a member of the group '%s'.", t() ), bp_get_group_name( false ) ); ?></p>

	<form action="<?php bp_group_form_action('request-membership'); ?>" method="post" name="request-membership-form" id="request-membership-form" class="standard-form">
		<label for="group-request-membership-comments"><?php _e( 'Comments (optional)', t() ); ?></label>
		<textarea name="group-request-membership-comments" id="group-request-membership-comments"></textarea><!-- #group-request-membership-comments -->

		<?php do_action( 'bp_group_request_membership_content' ); ?>

		<?php submit_button( __( 'Send Request', t() ), 'primary', 'group-request-send' ); ?>

		<?php wp_nonce_field( 'groups_request_membership' ); ?>
	</form><!-- #request-membership-form .standard-form -->
<?php endif; ?>

<?php do_action( 'bp_after_group_request_membership_content' ); ?>
