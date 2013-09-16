<?php
/**
 * BuddyPress Template: Activity Post Form
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form">

	<?php do_action( 'bp_before_activity_post_form' ); ?>

	<?php if ( isset( $_GET['r'] ) ) : ?>
		<?php wpf_message( sprintf( __( 'You are mentioning %s in a new update, this user will be sent a notification of your message.', t() ), bp_get_mentioned_user_display_name( $_GET['r'] ) ), 'info' ); ?>
	<?php endif; ?>

	<div id="whats-new-avatar">
		<a href="<?php echo bp_loggedin_user_domain(); ?>">
			<?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?>
		</a>
	</div><!-- #whats-new-avatar -->

	<h5 id="whats-new-title">
		<?php if ( bp_is_group() ) : ?>
			<?php printf( __( "What's new in %s, %s?", t() ), bp_get_group_name(), bp_get_user_firstname() ); ?>
		<?php else : ?>
			<?php printf( __( "What's new %s?", t() ), bp_get_user_firstname() ); ?>
		<?php endif; ?>
	</h5>

	<div id="whats-new-content">
		<div id="whats-new-textarea">
			<textarea name="whats-new" id="whats-new" cols="50" rows="10"><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_attr( $_GET['r'] ); ?> <?php endif; ?></textarea>
		</div><!-- #whats-new-textarea -->

		<div id="whats-new-options">
			<div id="whats-new-submit">
				<span class="ajax-loader"></span> &nbsp;
				<input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" value="<?php _e( 'Post Update', t() ); ?>" />
			</div><!-- #whats-new-submit -->

			<?php if ( bp_is_active( 'groups' ) && !bp_is_my_profile() && !bp_is_group() ) : ?>
				<div id="whats-new-post-in-box">
					<?php _e( 'Post in', t() ); ?>:

					<select id="whats-new-post-in" name="whats-new-post-in">
						<option selected="selected" value="0"><?php _e( 'My Profile', t() ); ?></option>

						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0' ) ) : while ( bp_groups() ) : bp_the_group(); ?>
							<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>
						<?php endwhile; endif; ?>
					</select>
				</div><!-- #whats-new-post-in-box -->
				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
			<?php elseif ( bp_is_group_home() ) : ?>
				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
				<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />
			<?php endif; ?>

			<?php do_action( 'bp_activity_post_form_options' ); ?>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->

	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
	<?php do_action( 'bp_after_activity_post_form' ); ?>

</form><!-- #whats-new-form -->