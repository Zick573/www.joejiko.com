<?php
/**
 * BuddyPress Template: Register
 *
 * This template is only used on multisite installations.
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-9">

					<?php do_action( 'content_open' ); ?>
					<?php do_action( 'bp_before_activation_page' ); ?>

					<div id="register-page" class="hfeed">

						<form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

						<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

							<h1 class="page-title"><?php _e( 'Create an Account', t() ); ?></h1>

							<?php do_action( 'template_notices' ); ?>

							<p><?php _e( 'Registering for this site is easy, just fill in the fields below and we\'ll get a new account set up for you in no time.', t() ); ?></p>

							<?php do_action( 'bp_before_account_details_fields' ); ?>

							<div class="register-section" id="basic-details-section">

								<?php /***** Basic Account Details ******/ ?>

								<h4><?php _e( 'Account Details', t() ); ?></h4>

								<label for="signup_username"><?php _e( 'Username', t() ); ?> <?php _e( '(required)', t() ); ?></label>
								<?php do_action( 'bp_signup_username_errors' ); ?>
								<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" />

								<label for="signup_email"><?php _e( 'Email Address', t() ); ?> <?php _e( '(required)', t() ); ?></label>
								<?php do_action( 'bp_signup_email_errors' ); ?>
								<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" />

								<label for="signup_password"><?php _e( 'Choose a Password', t() ); ?> <?php _e( '(required)', t() ); ?></label>
								<?php do_action( 'bp_signup_password_errors' ); ?>
								<input type="password" name="signup_password" id="signup_password" value="" />

								<label for="signup_password_confirm"><?php _e( 'Confirm Password', t() ); ?> <?php _e( '(required)', t() ); ?></label>
								<?php do_action( 'bp_signup_password_confirm_errors' ); ?>
								<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />

							</div><!-- #basic-details-section -->

							<?php do_action( 'bp_after_account_details_fields' ); ?>

							<?php /***** Extra Profile Details ******/ ?>

							<?php if ( bp_is_active( 'xprofile' ) ) : ?>

								<?php do_action( 'bp_before_signup_profile_fields' ); ?>

								<div class="register-section" id="profile-details-section">

									<h4><?php _e( 'Profile Details', t() ); ?></h4>

									<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
									<?php if ( function_exists( 'bp_has_profile' ) ) : if ( bp_has_profile( 'profile_group_id=1' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

									<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

										<div class="editfield">

											<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

												<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
												<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
												<input type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" />

											<?php endif; ?>

											<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

												<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
												<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
												<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_edit_value(); ?></textarea>

											<?php endif; ?>

											<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

												<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
												<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
												<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>">
													<?php bp_the_profile_field_options(); ?>
												</select>

											<?php endif; ?>

											<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

												<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
												<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
												<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple">
													<?php bp_the_profile_field_options(); ?>
												</select>

											<?php endif; ?>

											<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

												<div class="radio">
													<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></span>

													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
													<?php bp_the_profile_field_options(); ?>

													<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
														<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', t() ); ?></a>
													<?php endif; ?>
												</div><!-- .radio -->

											<?php endif; ?>

											<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

												<div class="checkbox">
													<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></span>

													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
													<?php bp_the_profile_field_options(); ?>
												</div><!-- .checkbox -->

											<?php endif; ?>

											<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

												<div class="datebox">
													<label for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
													<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>

													<select name="<?php bp_the_profile_field_input_name(); ?>_day" id="<?php bp_the_profile_field_input_name(); ?>_day">
														<?php bp_the_profile_field_options( 'type=day' ); ?>
													</select>

													<select name="<?php bp_the_profile_field_input_name(); ?>_month" id="<?php bp_the_profile_field_input_name(); ?>_month">
														<?php bp_the_profile_field_options( 'type=month' ); ?>
													</select>

													<select name="<?php bp_the_profile_field_input_name(); ?>_year" id="<?php bp_the_profile_field_input_name(); ?>_year">
														<?php bp_the_profile_field_options( 'type=year' ); ?>
													</select>
												</div><!-- .datebox -->

											<?php endif; ?>

											<?php do_action( 'bp_custom_profile_edit_fields' ); ?>

											<p class="description"><?php bp_the_profile_field_description(); ?></p>

										</div><!-- .editfield -->

									<?php endwhile; ?>

									<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

									<?php endwhile; endif; endif; ?>

								</div><!-- #profile-details-section .register-section -->

								<?php do_action( 'bp_after_signup_profile_fields' ); ?>

							<?php endif; ?>

							<?php if ( bp_get_blog_signup_allowed() ) : ?>

								<?php do_action( 'bp_before_blog_details_fields' ); ?>

								<?php /***** Blog Creation Details ******/ ?>

								<div class="register-section" id="blog-details-section">

									<h4><?php _e( 'Blog Details', t() ); ?></h4>

									<p><input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes, I\'d like to create a new blog', t() ); ?></p>

									<div id="blog-details"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>

										<label for="signup_blog_url"><?php _e( 'Blog URL', t() ); ?> <?php _e( '(required)', t() ); ?></label>
										<?php do_action( 'bp_signup_blog_url_errors' ); ?>

										<?php if ( is_subdomain_install() ) : ?>
											http:// <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" /> .<?php echo str_replace( 'http://', '', site_url() ); ?>
										<?php else : ?>
											<?php echo site_url(); ?>/ <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
										<?php endif; ?>

										<label for="signup_blog_title"><?php _e( 'Blog Title', t() ); ?> <?php _e( '(required)', t() ); ?></label>
										<?php do_action( 'bp_signup_blog_title_errors' ); ?>
										<input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>" />

										<span class="label"><?php _e( 'I would like my blog to appear in search engines, and in public listings around this site', t() ); ?>:</span>
										<?php do_action( 'bp_signup_blog_privacy_errors' ); ?>

										<label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes', t() ); ?></label>
										<label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'No', t() ); ?></label>

									</div><!-- #blog-details -->

								</div><!-- #blog-details-section .register-section -->

								<?php do_action( 'bp_after_blog_details_fields' ); ?>

							<?php endif; ?>

							<?php do_action( 'bp_before_registration_submit_buttons' ); ?>

							<?php submit_button( __( 'Complete Sign Up', t() ), 'primary', 'signup_submit' ); ?>

							<?php do_action( 'bp_after_registration_submit_buttons' ); ?>

							<?php wp_nonce_field( 'bp_new_signup' ); ?>

						<?php endif; // request-details signup step ?>

						<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

							<h2><?php _e( 'Sign Up Complete!', t() ); ?></h2>

							<?php do_action( 'template_notices' ); ?>

							<?php if ( bp_registration_needs_activation() ) : ?>
								<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', t() ); ?></p>
							<?php else : ?>
								<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', t() ); ?></p>
							<?php endif; ?>

						<?php endif; // completed-confirmation signup step ?>

						<?php do_action( 'bp_custom_signup_steps' ); ?>

						</form><!-- #signup_form .standard-form -->

					</div><!-- #register-page -->

					<?php do_action( 'bp_after_activation_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>