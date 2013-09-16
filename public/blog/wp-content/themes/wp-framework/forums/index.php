<?php
/**
 * BuddyPress Template: Forums Index
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_forums_page' ); ?>

					<div id="forums-page" class="hfeed">

						<form action="" method="post" id="forums-search-form" class="dir-form">

							<h3 class="page-title"><?php _e( 'Group Forums Directory', t() ); ?><?php if ( is_user_logged_in() ) : ?> &nbsp;<a class="button" href="#new-topic" id="new-topic-button"><?php _e( 'New Topic', t() ); ?></a><?php endif; ?></h3>

							<?php do_action( 'bp_before_directory_forums_content' ); ?>

							<div id="forums-dir-search" class="dir-search" role="search">
								<?php bp_directory_forums_search_form(); ?>
							</div><!-- #forums-dir-search .dir-search -->
						</form><!-- #forums-search-form .dir-form -->

						<div id="new-topic-post">
							<?php if ( is_user_logged_in() ) : ?>

								<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100' ) ) : ?>

									<form action="" method="post" id="forum-topic-form" class="standard-form">

										<?php do_action( 'groups_forum_new_topic_before' ); ?>

										<a name="post-new"></a>
										<h5><?php _e( 'Post a New Topic:', t() ); ?></h5>

										<label><?php _e( 'Title:', t() ); ?></label>
										<input type="text" name="topic_title" id="topic_title" value="" />

										<label><?php _e( 'Content:', t() ); ?></label>
										<textarea name="topic_text" id="topic_text"></textarea>

										<label><?php _e( 'Tags (comma separated):', t() ); ?></label>
										<input type="text" name="topic_tags" id="topic_tags" value="" />

										<label><?php _e( 'Post In Group Forum:', t() ); ?></label>
										<select id="topic_group_id" name="topic_group_id">

											<option value="">----</option>

											<?php while ( bp_groups() ) : bp_the_group(); ?>

												<?php if ( bp_group_is_forum_enabled() && 'public' == bp_get_group_status() ) : ?>

													<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

												<?php endif; ?>

											<?php endwhile; ?>

										</select><!-- #topic_group_id -->

										<?php do_action( 'groups_forum_new_topic_after' ); ?>

										<div class="submit">
											<input type="submit" name="submit_topic" id="submit" value="<?php _e( 'Post Topic', t() ); ?>" />
											<input type="button" name="submit_topic_cancel" id="submit_topic_cancel" value="<?php _e( 'Cancel', t() ); ?>" />
										</div><!-- .submit -->

										<?php wp_nonce_field( 'bp_forums_new_topic' ); ?>

									</form><!-- #forum-topic-form .standard-form -->

								<?php else : ?>

									<?php wpf_message( sprintf(__( "You are not a member of any groups so you don't have any group forums you can post in. To start posting, first find a group that matches the topic subject you'd like to start. If this group does not exist, why not <a href='%s'>create a new group</a>? Once you have joined or created the group you can post your topic in that group's forum.", t() ), site_url( BP_GROUPS_SLUG . '/create/' ) ), 'info' ); ?>

								<?php endif; ?>

							<?php endif; ?>
						</div><!-- #new-topic-post -->

						<form action="" method="post" id="forums-directory-form" class="dir-form">

							<div class="item-list-tabs">
								<ul>
									<li class="selected" id="forums-all"><a href="<?php bp_root_domain(); ?>"><?php printf( __( 'All Topics (%s)', t() ), bp_get_forum_topic_count() ); ?></a></li>

									<?php if ( is_user_logged_in() && bp_get_forum_topic_count_for_user( bp_loggedin_user_id() ) ) : ?>
										<li id="forums-personal"><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG . '/' ?>"><?php printf( __( 'My Topics (%s)', t() ), bp_get_forum_topic_count_for_user( bp_loggedin_user_id() ) ); ?></a></li><!-- #forums-personal -->
									<?php endif; ?>

									<?php do_action( 'bp_forums_directory_group_types' ); ?>

									<li id="forums-order-select" class="last filter">

										<?php _e( 'Order By:', t() ); ?>
										<select>
											<option value="active"><?php _e( 'Last Active', t() ); ?></option>
											<option value="popular"><?php _e( 'Most Posts', t() ); ?></option>
											<option value="unreplied"><?php _e( 'Unreplied', t() ); ?></option>

											<?php do_action( 'bp_forums_directory_order_options' ); ?>
										</select>
									</li><!-- #forums-order-select .last .filter -->
								</ul>
							</div><!-- #item-list-tabs -->

							<div id="forums-dir-list" class="forums dir-list">
								<?php get_template_part( 'forums/forums-loop' ); ?>
							</div><!-- #forums-dir-list .forums .dir-list -->

							<?php do_action( 'bp_directory_forums_content' ); ?>

							<?php wp_nonce_field( 'directory_forums', '_wpnonce-forums-filter' ); ?>

							<?php do_action( 'bp_after_directory_forums_content' ); ?>

						</form><!-- #forums-directory-form .dir-form -->

					</div><!-- #forums-page -->

					<?php do_action( 'bp_after_forums_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>