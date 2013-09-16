<?php
/**
 * BuddyPress Template: Member Messages Loop
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads() ) : ?>

	<div class="pagination no-ajax" id="user-pag">

		<div class="pag-count" id="messages-dir-count">
			<?php bp_messages_pagination_count(); ?>
		</div><!-- #messages-dir-count .pag-count -->

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div><!-- #messages-dir-pag .pagination-links -->

	</div><!-- #user-pag .pagination .no-ajax -->

	<?php do_action( 'bp_after_member_messages_pagination' ); ?>
	<?php do_action( 'bp_before_member_messages_threads' ); ?>

	<table id="message-threads" class="zebra">
		<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

			<tr id="m-<?php bp_message_thread_id(); ?>"<?php if ( bp_message_thread_has_unread() ) : ?> class="unread"<?php else: ?> class="read"<?php endif; ?>>
				<td width="1%" class="thread-count">
					<span class="unread-count"><?php bp_message_thread_unread_count(); ?></span><!-- .unread-count -->
				</td><!-- .thread-count -->
				<td width="1%" class="thread-avatar"><?php bp_message_thread_avatar(); ?></td><!-- .thread-avatar -->

				<?php if ( 'sentbox' != bp_current_action() ) : ?>
					<td width="30%" class="thread-from">
						<?php _e( 'From:', t() ); ?> <?php bp_message_thread_from(); ?><br />
						<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
					</td><!-- .thread-from -->
				<?php else: ?>
					<td width="30%" class="thread-from">
						<?php _e( 'To:', t() ); ?> <?php bp_message_thread_to(); ?><br />
						<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
					</td><!-- .thread-from -->
				<?php endif; ?>

				<td width="50%" class="thread-info">
					<p><a href="<?php bp_message_thread_view_link(); ?>" title="<?php _e( 'View Message', t() ); ?>"><?php bp_message_thread_subject(); ?></a></p>
					<p class="thread-excerpt"><?php bp_message_thread_excerpt(); ?></p>
				</td><!-- .thread-info -->

				<?php do_action( 'bp_messages_inbox_list_item' ); ?>

				<td width="15%" class="thread-options">
					<input type="checkbox" name="message_ids[]" value="<?php bp_message_thread_id(); ?>" />
					<a class="button confirm" href="<?php bp_message_thread_delete_link(); ?>" title="<?php _e( 'Delete Message', t() ); ?>">x</a>
				</td><!-- .thread-options -->
			</tr>

		<?php endwhile; ?>
	</table><!-- #message-threads .zebra -->

	<div class="messages-options-nav">
		<?php bp_messages_options(); ?>
	</div><!-- .messages-options-nav -->

	<?php do_action( 'bp_after_member_messages_threads' ); ?>

	<?php do_action( 'bp_after_member_messages_options' ); ?>

<?php else: ?>

	<?php wpf_message( __( 'Sorry, no messages were found.', t() ), 'info' ); ?>

<?php endif;?>

<?php do_action( 'bp_after_member_messages_loop' ); ?>