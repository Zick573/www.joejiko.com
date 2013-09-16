<?php
/**
 * BuddyPress Template: Activity Loop
 *
 * This template displays the BuddyPress activity loop.
 *
 * The querystring is set via AJAX in _inc/ajax.php - wpf_bp_activity_loop().
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_activity_loop' ); ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php /* Show pagination if JS is not enabled, since the "Load More" link will do nothing. Progressive Enhancement ftw! */ ?>
	<noscript>
		<div class="pagination">
			<div class="pag-count"><?php bp_activity_pagination_count(); ?></div>
			<div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
		</div>
	</noscript>

	<?php if ( empty( $_POST['page'] ) ) : ?>
		<ul id="activity-stream" class="activity-list item-list">
	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php get_template_part( 'activity/entry' ); ?>

	<?php endwhile; ?>

	<?php if ( bp_get_activity_count() == bp_get_activity_per_page() ) : ?>
		<li class="load-more">
			<a href="#more"><?php _e( 'Load More', t() ); ?></a>
		</li>
	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>
		</ul>
	<?php endif; ?>

<?php else : ?>
	<?php wpf_message( __( 'Sorry, there was no activity found. Please try a different filter.', t() ), 'info' ); ?>
<?php endif; ?>

<?php do_action( 'bp_after_activity_loop' ); ?>

<form action="" name="activity-loop-form" id="activity-loop-form" method="post">
	<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>
</form><!-- #activity-loop-form -->