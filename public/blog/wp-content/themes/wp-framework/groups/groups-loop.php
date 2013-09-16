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

<?php do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="group-dir-count-top">
			<?php bp_groups_pagination_count() ?>
		</div><!-- #group-dir-count-top .pag-count -->

		<div class="pagination-links" id="group-dir-pag-top">
			<?php bp_groups_pagination_links() ?>
		</div><!-- #group-dir-pag-top .pagination-links -->

	</div><!-- #pag-top .pagination -->

	<?php do_action( 'bp_before_directory_groups_list' ); ?>

	<ul id="groups-list" class="item-list">
	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li>
			<div class="item-avatar">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
			</div><!-- .item-avatar -->

			<div class="item">
				<div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div><!-- .item-title -->
				<div class="item-meta"><span class="activity"><?php printf( __( 'active %s ago', t() ), bp_get_group_last_active() ); ?></span></div><!-- .item-meta -->

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div><!-- .item-desc -->

				<?php do_action( 'bp_directory_groups_item' ); ?>

			</div><!-- .item -->

			<div class="action">

				<?php do_action( 'bp_directory_groups_actions' ); ?>

				<div class="meta">

					<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>

				</div><!-- .meta -->

			</div><!-- .action -->

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>
	</ul><!-- #groups-list .item-list -->

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">
			<?php bp_groups_pagination_count() ?>
		</div><!-- #group-dir-count-bottom .pag-count -->

		<div class="pagination-links" id="group-dir-pag-bottom">
			<?php bp_groups_pagination_links() ?>
		</div><!-- #group-dir-pag-bottom .pagination-links -->

	</div><!-- #pag-bottom .pagination -->

<?php else: ?>

	<?php wpf_message( __( 'There were no groups found.', t() ), 'info' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_groups_loop' ); ?>