<?php
/**
 * BuddyPress Template: Member Blogs
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<div class="item-list-tabs" id="subnav">
	<ul>
		<?php bp_get_options_nav(); ?>

		<li id="blogs-order-select" class="last filter">
			<?php _e( 'Order By:', t() ); ?>
			<select id="blogs-all">
				<option value="active"><?php _e( 'Last Active', t() ); ?></option>
				<option value="newest"><?php _e( 'Newest', t() ); ?></option>
				<option value="alphabetical"><?php _e( 'Alphabetical', t() ); ?></option>

				<?php do_action( 'bp_member_blog_order_options' ); ?>
			</select><!-- #blogs-all -->
		</li><!-- #blogs-order-select .last .filter -->
	</ul>
</div><!-- #subnav .item-list-tabs .no-ajax -->

<?php do_action( 'bp_before_member_blogs_content' ); ?>

<div class="blogs myblogs">
	<?php get_template_part( 'blogs/blogs-loop' ); ?>
</div><!-- .blogs .myblogs -->

<?php do_action( 'bp_after_member_blogs_content' ); ?>