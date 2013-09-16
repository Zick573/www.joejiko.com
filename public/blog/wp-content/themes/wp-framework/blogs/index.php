<?php
/**
 * BuddyPress Template: Blogs Index
 *
 * This template is only used on multisite installations.
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_blogs_page' ); ?>

					<div id="blogs-page" class="hfeed">

						<form action="" method="post" id="blogs-directory-form" class="dir-form">

							<h3 class="page-title"><?php _e( 'Blogs Directory', t() ); ?><?php if ( is_user_logged_in() && bp_blog_signup_enabled() ) : ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . BP_BLOGS_SLUG . '/create/' ?>"><?php _e( 'Create a Blog', t() ); ?></a><?php endif; ?></h3>

							<?php do_action( 'bp_before_directory_blogs_content' ); ?>

							<div id="blog-dir-search" class="dir-search">
								<?php bp_directory_blogs_search_form(); ?>
							</div><!-- #blog-dir-search .dir-search -->

							<div class="item-list-tabs">
								<ul>
									<li class="selected" id="blogs-all"><a href="<?php bp_root_domain(); ?>"><?php printf( __( 'All Blogs (%s)', t() ), bp_get_total_blog_count() ); ?></a></li>

									<?php if ( is_user_logged_in() && bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) ) : ?>
										<li id="blogs-personal"><a href="<?php echo bp_loggedin_user_domain() . BP_BLOGS_SLUG . '/my-blogs/' ?>"><?php printf( __( 'My Blogs (%s)', t() ), bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>
									<?php endif; ?>

									<?php do_action( 'bp_blogs_directory_blog_types' ); ?>

									<li id="blogs-order-select" class="last filter">

										<?php _e( 'Order By:', t() ); ?>
										<select>
											<option value="active"><?php _e( 'Last Active', t() ); ?></option>
											<option value="newest"><?php _e( 'Newest', t() ); ?></option>
											<option value="alphabetical"><?php _e( 'Alphabetical', t() ); ?></option>

											<?php do_action( 'bp_blogs_directory_order_options' ); ?>
										</select>
									</li><!-- #blogs-order-select .last .filter -->
								</ul>
							</div><!-- .item-list-tabs -->

							<div id="blogs-dir-list" class="blogs dir-list">
								<?php get_template_part( 'blogs/blogs-loop' ); ?>
							</div><!-- #blogs-dir-list .blogs .dir-list -->

							<?php do_action( 'bp_after_directory_blogs_content' ); ?>

							<?php wp_nonce_field( 'directory_blogs', '_wpnonce-blogs-filter' ); ?>

						</form><!-- #blogs-directory-form .dir-form -->

					</div><!-- #blogs-page -->

					<?php do_action( 'bp_after_blogs_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>