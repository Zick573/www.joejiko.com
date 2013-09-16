<?php
/**
 * BuddyPress Template: Group Create
 *
 * This template is only used on multisite installations.
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_group_create_page' ); ?>

					<div id="groups-create-page">

						<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" class="standard-form" enctype="multipart/form-data">
							<h3><?php _e( 'Create a Group', t() ); ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . BP_GROUPS_SLUG . '/' ?>"><?php _e( 'Groups Directory', t() ); ?></a></h3>

							<?php do_action( 'bp_before_create_group' ); ?>

							<div class="item-list-tabs no-ajax" id="group-create-tabs">
								<ul>
									<?php bp_group_creation_tabs(); ?>
								</ul>
							</div><!-- #group-create-tabs .item-list-tabs .no-ajax -->

							<?php do_action( 'template_notices' ); ?>

							<div class="item-body" id="group-create-body">

								<?php /* Group creation step 1: Basic group details */ ?>
								<?php if ( bp_is_group_creation_step( 'group-details' ) ) : ?>

									<?php do_action( 'bp_before_group_details_creation_step' ); ?>

									<label for="group-name"><?php _e( '* Group Name', t() ); ?> <?php _e( '(required)', t() )?></label>
									<input type="text" name="group-name" id="group-name" value="<?php bp_new_group_name(); ?>" />

									<label for="group-desc"><?php _e( '* Group Description', t() ); ?> <?php _e( '(required)', t() )?></label>
									<textarea name="group-desc" id="group-desc"><?php bp_new_group_description(); ?></textarea>

									<?php do_action( 'bp_after_group_details_creation_step' ); /* Deprecated -> */ do_action( 'groups_custom_group_fields_editable' ); ?>

									<?php wp_nonce_field( 'groups_create_save_group-details' ); ?>

								<?php endif; ?>

								<?php /* Group creation step 2: Group settings */ ?>
								<?php if ( bp_is_group_creation_step( 'group-settings' ) ) : ?>

									<?php do_action( 'bp_before_group_settings_creation_step' ); ?>

									<?php if ( function_exists('bp_wire_install') ) : ?>
									<div class="checkbox">
										<label><input type="checkbox" name="group-show-wire" id="group-show-wire" value="1"<?php if ( bp_get_new_group_enable_wire() ) { ?> checked="checked"<?php } ?> /> <?php _e( 'Enable comment wire', t() ); ?></label>
									</div><!-- .checkbox -->
									<?php endif; ?>

									<?php if ( function_exists('bp_forums_is_installed_correctly') ) : ?>
										<?php if ( bp_forums_is_installed_correctly() ) : ?>
											<div class="checkbox">
												<label><input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php if ( bp_get_new_group_enable_forum() ) { ?> checked="checked"<?php } ?> /> <?php _e( 'Enable discussion forum', t() ); ?></label>
											</div>
										<?php else : ?>
											<?php if ( is_super_admin() ) : ?>
												<div class="checkbox">
													<label><input type="checkbox" disabled="disabled" name="disabled" id="disabled" value="0" /> <?php printf( __('<strong>Attention Site Admin:</strong> Group forums require the <a href="%s">correct setup and configuration</a> of a bbPress installation.', t() ), admin_url( 'admin.php?page=bb-forums-setup' ) ); ?></label>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>

									<hr />

									<h4><?php _e( 'Privacy Options', t() ); ?></h4>

									<div class="radio">
										<label><input type="radio" name="group-status" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
											<strong><?php _e( 'This is a public group', t() ); ?></strong>
											<ul>
												<li><?php _e( 'Any site member can join this group.', t() ); ?></li>
												<li><?php _e( 'This group will be listed in the groups directory and in search results.', t() ); ?></li>
												<li><?php _e( 'Group content and activity will be visible to any site member.', t() ); ?></li>
											</ul>
										</label>

										<label><input type="radio" name="group-status" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
											<strong><?php _e( 'This is a private group', t() ); ?></strong>
											<ul>
												<li><?php _e( 'Only users who request membership and are accepted can join the group.', t() ); ?></li>
												<li><?php _e( 'This group will be listed in the groups directory and in search results.', t() ); ?></li>
												<li><?php _e( 'Group content and activity will only be visible to members of the group.', t() ); ?></li>
											</ul>
										</label>

										<label><input type="radio" name="group-status" value="hidden"<?php if ( 'hidden' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
											<strong><?php _e( 'This is a hidden group', t() ); ?></strong>
											<ul>
												<li><?php _e( 'Only users who are invited can join the group.', t() ); ?></li>
												<li><?php _e( 'This group will not be listed in the groups directory or search results.', t() ); ?></li>
												<li><?php _e( 'Group content and activity will only be visible to members of the group.', t() ); ?></li>
											</ul>
										</label>
									</div><!-- .radio -->

									<?php do_action( 'bp_after_group_settings_creation_step' ); ?>

									<?php wp_nonce_field( 'groups_create_save_group-settings' ); ?>

								<?php endif; ?>

								<?php /* Group creation step 3: Avatar Uploads */ ?>
								<?php if ( bp_is_group_creation_step( 'group-avatar' ) ) : ?>

									<?php do_action( 'bp_before_group_avatar_creation_step' ); ?>

									<?php if ( !bp_get_avatar_admin_step() ) : ?>

										<div class="left-menu">
											<?php bp_new_group_avatar(); ?>
										</div><!-- .left-menu -->

										<div class="main-column">
											<p><?php _e( 'Upload an image to use as an avatar for this group. The image will be shown on the main group page, and in search results.', t() ); ?></p>

											<p>
												<input type="file" name="file" id="file" />
												<input type="submit" name="upload" id="upload" value="<?php _e( 'Upload Image', t() ); ?>" />
												<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
											</p>

											<p><?php _e( 'To skip the avatar upload process, hit the "Next Step" button.', t() ); ?></p>
										</div><!-- .main-column -->

									<?php endif; ?>

									<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

										<h3><?php _e( 'Crop Group Avatar', t() ); ?></h3>

										<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', t() ); ?>" />

										<div id="avatar-crop-pane">
											<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', t() ); ?>" />
										</div><!-- #avatar-crop-pane -->

										<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e( 'Crop Image', t() ); ?>" />

										<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
										<input type="hidden" name="upload" id="upload" />
										<input type="hidden" id="x" name="x" />
										<input type="hidden" id="y" name="y" />
										<input type="hidden" id="w" name="w" />
										<input type="hidden" id="h" name="h" />

									<?php endif; ?>

									<?php do_action( 'bp_after_group_avatar_creation_step' ); ?>

									<?php wp_nonce_field( 'groups_create_save_group-avatar' ); ?>

								<?php endif; ?>

								<?php /* Group creation step 4: Invite friends to group */ ?>
								<?php if ( bp_is_group_creation_step( 'group-invites' ) ) : ?>

									<?php do_action( 'bp_before_group_invites_creation_step' ); ?>

									<?php if ( function_exists( 'bp_get_total_friend_count' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
										<div class="left-menu">

											<div id="invite-list">
												<ul>
													<?php bp_new_group_invite_friend_list(); ?>
												</ul>

												<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>
											</div><!-- #invite-list -->

										</div><!-- .left-menu -->

										<div class="main-column">

											<?php wpf_message( __( 'Select people to invite from your friends list.' ), 'info' ); ?>

											<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
											<ul id="friend-list" class="item-list">
											<?php if ( bp_group_has_invites() ) : ?>

												<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

													<li id="<?php bp_group_invite_item_id(); ?>">
														<?php bp_group_invite_user_avatar(); ?>

														<h4><?php bp_group_invite_user_link(); ?></h4>
														<span class="activity"><?php bp_group_invite_user_last_active(); ?></span>

														<div class="action">
															<a class="remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Remove Invite', t() ); ?></a>
														</div><!-- .action -->
													</li><!-- #<?php bp_group_invite_item_id(); ?> -->

												<?php endwhile; ?>

												<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites' ); ?>
											<?php endif; ?>
											</ul>

										</div><!-- .main-column -->

									<?php else : ?>

										<?php wpf_message( __( 'Once you have built up friend connections you will be able to invite others to your group. You can send invites any time in the future by selecting the "Send Invites" option when viewing your new group.', t() ), 'info' ); ?>

									<?php endif; ?>

									<?php wp_nonce_field( 'groups_create_save_group-invites' ); ?>
									<?php do_action( 'bp_after_group_invites_creation_step' ); ?>

								<?php endif; ?>

								<?php do_action( 'groups_custom_create_steps' ) // Allow plugins to add custom group creation steps ?>

								<?php do_action( 'bp_before_group_creation_step_buttons' ); ?>

								<?php if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>
									<div class="submit" id="previous-next">
										<?php /* Previous Button */ ?>
										<?php if ( !bp_is_first_group_creation_step() ) : ?>
											<input type="button" value="&larr; <?php _e( 'Previous Step', t() ); ?>" id="group-creation-previous" name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'" />
										<?php endif; ?>

										<?php /* Next Button */ ?>
										<?php if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>
											<input type="submit" value="<?php _e( 'Next Step', t() ); ?>" id="group-creation-next" name="save" />
										<?php endif;?>

										<?php /* Create Button */ ?>
										<?php if ( bp_is_first_group_creation_step() ) : ?>
											<input type="submit" value="<?php _e( 'Create Group and Continue', t() ); ?>" id="group-creation-create" name="save" />
										<?php endif; ?>

										<?php /* Finish Button */ ?>
										<?php if ( bp_is_last_group_creation_step() ) : ?>
											<input type="submit" value="<?php _e( 'Finish', t() ); ?>" id="group-creation-finish" name="save" />
										<?php endif; ?>
									</div><!-- #previous-next .submit -->
								<?php endif;?>

								<?php do_action( 'bp_after_group_creation_step_buttons' ); ?>

								<?php /* Don't leave out this hidden field */ ?>
								<input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />

								<?php do_action( 'bp_directory_groups_content' ); ?>

							</div><!-- #group-create-body .item-body -->

							<?php do_action( 'bp_after_create_group' ); ?>

						</form><!-- #create-group-form .standard-form -->

					</div><!-- #groups-create-page -->

					<?php do_action( 'bp_after_group_create_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>