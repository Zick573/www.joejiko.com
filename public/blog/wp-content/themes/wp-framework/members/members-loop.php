<?php
/**
 * BuddyPress Template: Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - wpf_bp_object_filter().
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">
			<?php bp_members_pagination_count(); ?>
		</div><!-- #member-dir-count-top .pag-count -->

		<div class="pagination-links" id="member-dir-pag-top">
			<?php bp_members_pagination_links(); ?>
		</div><!-- #member-dir-pag-top .pagination-links -->

	</div><!-- #pag-top .pagination -->

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list">
	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div><!-- .item-avatar -->

			<div class="item">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>

					<?php if ( bp_get_member_latest_update() ) : ?>

						<span class="update"> - <?php bp_member_latest_update( 'length=10' ); ?></span><!-- .update -->

					<?php endif; ?>

				</div><!-- .item-title -->

				<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div><!-- .item-meta -->

				<?php do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /**
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
			</div><!-- .item -->

			<div class="action">

				<?php do_action( 'bp_directory_members_actions' ); ?>

			</div><!-- .action -->

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>
	</ul><!-- #members-list .item-list -->

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">
			<?php bp_members_pagination_count(); ?>
		</div><!-- #member-dir-count-top .pag-count -->

		<div class="pagination-links" id="member-dir-pag-top">
			<?php bp_members_pagination_links(); ?>
		</div><!-- #member-dir-pag-top .pagination-links -->

	</div><!-- #pag-top .pagination -->

<?php else: ?>

	<?php wpf_message( __( 'Sorry, no members were found.', t() ), 'info' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
