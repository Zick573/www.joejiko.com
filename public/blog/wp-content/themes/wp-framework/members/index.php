<?php
/**
 * BuddyPress Template: Members Index
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_members_page' ); ?>

					<div id="members-page" class="hfeed">

						<form action="" method="post" id="members-directory-form" class="dir-form">

							<h1 class="page-title"><?php _e( 'Members Directory', t() ); ?></h1>

							<?php do_action( 'bp_before_directory_members_content' ); ?>

							<div id="members-dir-search" class="dir-search" role="search">
								<?php bp_directory_members_search_form(); ?>
							</div><!-- #members-dir-search .dir-search -->

							<div class="item-list-tabs">
								<ul>
									<li class="selected" id="members-all"><a href="<?php bp_root_domain(); ?>"><?php printf( __( 'All Members (%s)', t() ), bp_get_total_member_count() ); ?></a></li><!-- #members-all .selected -->

									<?php if ( is_user_logged_in() && bp_is_active('friends') && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
										<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . BP_FRIENDS_SLUG . '/my-friends/' ?>"><?php printf( __( 'My Friends (%s)', t() ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li><!-- #members-personal -->
									<?php endif; ?>

									<?php do_action( 'bp_members_directory_member_types' ); ?>

									<li id="members-order-select" class="last filter">

										<?php _e( 'Order By:', t() ); ?>
										<select>
											<option value="active"><?php _e( 'Last Active', t() ); ?></option>
											<option value="newest"><?php _e( 'Newest Registered', t() ); ?></option>

											<?php if ( bp_is_active( 'xprofile' ) ) : ?>
												<option value="alphabetical"><?php _e( 'Alphabetical', t() ); ?></option>
											<?php endif; ?>

											<?php do_action( 'bp_members_directory_order_options' ); ?>
										</select>
									</li><!-- #members-order-select .last .filter -->
								</ul>
							</div><!-- .item-list-tabs -->

							<div id="members-dir-list" class="members dir-list">
								<?php get_template_part( 'members/members-loop' ); ?>
							</div><!-- #members-dir-list .members .dir-list -->

							<?php do_action( 'bp_directory_members_content' ); ?>

							<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

							<?php do_action( 'bp_after_directory_members_content' ); ?>

						</form><!-- #members-directory-form .dir-form -->

					</div><!-- #members-page -->

					<?php do_action( 'bp_after_members_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>