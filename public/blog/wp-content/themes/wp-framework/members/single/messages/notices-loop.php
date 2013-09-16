<?php
/**
 * BuddyPress Template: Member Notices Loop
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_notices_loop' ); ?>

<?php if ( bp_has_message_threads() ) : ?>

	<div class="pagination" id="user-pag">

		<div class="pag-count" id="messages-dir-count">
			<?php bp_messages_pagination_count(); ?>
		</div><!-- #messages-dir-count .pag-count -->

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div><!-- #messages-dir-pag .pagination-links -->

	</div><!-- #user-pag .pagination -->

	<?php do_action( 'bp_after_notices_pagination' ); ?>
	<?php do_action( 'bp_before_notices' ); ?>

	<table id="message-threads" class="zebra">
		<?php while ( bp_message_threads() ) : bp_message_thread(); ?>
			<tr>
				<td width="1%">
				</td>
				<td width="38%">
					<strong><?php bp_message_notice_subject(); ?></strong>
					<?php bp_message_notice_text(); ?>
				</td>
				<td width="21%">
					<strong><?php bp_message_is_active_notice(); ?></strong>
					<span class="activity"><?php _e( 'Sent:', t() ); ?> <?php bp_message_notice_post_date(); ?></span>
				</td>

				<?php do_action( 'bp_notices_list_item' ); ?>

				<td width="10%">
					<a class="button" href="<?php bp_message_activate_deactivate_link(); ?>" class="confirm"><?php bp_message_activate_deactivate_text(); ?></a>
					<a class="button" href="<?php bp_message_notice_delete_link(); ?>" class="confirm" title="<?php _e( 'Delete Message', t() ); ?>">x</a>
				</td>
			</tr>
		<?php endwhile; ?>
	</table><!-- #message-threads .zebra -->

	<?php do_action( 'bp_after_notices' ); ?>

<?php else: ?>

	<?php wpf_message( __( 'Sorry, no notices were found.', t() ), 'info' ); ?>

<?php endif;?>

<?php do_action( 'bp_after_notices_loop' ); ?>