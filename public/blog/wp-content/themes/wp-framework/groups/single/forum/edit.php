<?php
/**
 * BuddyPress Template: Group Forum Edit
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_group_forum_edit_form' ); ?>

<?php if ( bp_has_forum_topic_posts() ) : ?>

	<form action="<?php bp_forum_topic_action(); ?>" method="post" id="forum-topic-form" class="standard-form">

		<div id="topic-meta">
			<h3><?php bp_the_topic_title(); ?> (<?php bp_the_topic_total_post_count(); ?>)</h3>
			<a class="button" href="<?php bp_forum_permalink(); ?>/">&larr; <?php _e( 'Group Forum', t() ); ?></a> &nbsp; <a class="button" href="<?php bp_forum_directory_permalink(); ?>/"><?php _e( 'Group Forum Directory', t() ); ?></a>

			<?php if ( bp_group_is_admin() || bp_group_is_mod() || bp_get_the_topic_is_mine() ) : ?>
				<div class="admin-links"><?php bp_the_topic_admin_links(); ?></div><!-- #admin-links -->
			<?php endif; ?>
		</div><!-- #topic-meta -->

		<?php if ( bp_group_is_member() ) : ?>

			<?php if ( bp_is_edit_topic() ) : ?>

				<div id="edit-topic">

					<?php do_action( 'bp_group_before_edit_forum_topic' ); ?>

					<p><strong><?php _e( 'Edit Topic:', t() ); ?></strong></p>

					<label for="topic_title"><?php _e( 'Title:', t() ); ?></label>
					<input type="text" name="topic_title" id="topic_title" value="<?php bp_the_topic_title(); ?>" />

					<label for="topic_text"><?php _e( 'Content:', t() ); ?></label>
					<textarea name="topic_text" id="topic_text"><?php bp_the_topic_text(); ?></textarea>

					<?php do_action( 'bp_group_after_edit_forum_topic' ); ?>

					<?php submit_button( __( 'Save Changes', t() ), 'primary', 'save_changes' ); ?>

					<?php wp_nonce_field( 'bp_forums_edit_topic' ); ?>

				</div><!-- #edit-topic -->

			<?php else : ?>

				<div id="edit-post">

					<?php do_action( 'bp_group_before_edit_forum_post' ); ?>

					<p><strong><?php _e( 'Edit Post:', t() ); ?></strong></p>

					<textarea name="post_text" id="post_text"><?php bp_the_topic_post_edit_text(); ?></textarea>

					<?php do_action( 'bp_group_after_edit_forum_post' ); ?>

					<?php submit_button( __( 'Save Changes', t() ), 'primary', 'save_changes' ); ?>

					<?php wp_nonce_field( 'bp_forums_edit_post' ); ?>

				</div><!-- #edit-post -->

			<?php endif; ?>

		<?php endif; ?>

	</form><!-- #forum-topic-form .standard-form -->

<?php else: ?>

	<?php wpf_message( __( 'This topic does not exist.', t() ), 'info' ); ?>

<?php endif;?>

<?php do_action( 'bp_after_group_forum_edit_form' ); ?>