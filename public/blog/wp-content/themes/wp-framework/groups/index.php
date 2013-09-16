<?php
/**
 * BuddyPress Template: Groups Index
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_groups_page' ); ?>

					<div id="groups-page" class="hfeed">

						<form action="" method="post" id="groups-directory-form" class="dir-form">
							<h3 class="page-title"><?php _e( 'Groups Directory', t() ); ?><?php if ( is_user_logged_in() ) : ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . BP_GROUPS_SLUG . '/create/' ?>"><?php _e( 'Create a Group', t() ); ?></a><?php endif; ?></h3>

							<?php do_action( 'bp_before_directory_groups_content' ); ?>

							<div id="group-dir-search" class="dir-search" role="search">
								<?php bp_directory_groups_search_form(); ?>
							</div><!-- #group-dir-search .dir-search -->

							<div class="item-list-tabs">
								<ul>
									<li class="selected" id="groups-all"><a href="<?php echo bp_get_root_domain() . '/' . BP_GROUPS_SLUG ?>"><?php printf( __( 'All Groups (%s)', t() ), bp_get_total_group_count() ); ?></a></li>

									<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
										<li id="groups-personal"><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG . '/my-groups/' ?>"><?php printf( __( 'My Groups (%s)', t() ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li><!-- #groups-personal -->
									<?php endif; ?>

									<?php do_action( 'bp_groups_directory_group_types' ); ?>

									<li id="groups-order-select" class="last filter">

										<?php _e( 'Order By:', t() ); ?>
										<select>
											<option value="active"><?php _e( 'Last Active', t() ); ?></option>
											<option value="popular"><?php _e( 'Most Members', t() ); ?></option>
											<option value="newest"><?php _e( 'Newly Created', t() ); ?></option>
											<option value="alphabetical"><?php _e( 'Alphabetical', t() ); ?></option>

											<?php do_action( 'bp_groups_directory_order_options' ); ?>
										</select>
									</li><!-- #groups-order-select .last .filter -->
								</ul>
							</div><!-- .item-list-tabs -->

							<div id="groups-dir-list" class="groups dir-list">
								<?php get_template_part( 'groups/groups-loop' ); ?>
							</div><!-- #groups-dir-list -->

							<?php do_action( 'bp_directory_groups_content' ); ?>

							<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

						</form><!-- #groups-directory-form -->

						<?php do_action( 'bp_after_directory_groups_content' ); ?>

					</div><!-- #groups-page -->

					<?php do_action( 'bp_after_groups_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>