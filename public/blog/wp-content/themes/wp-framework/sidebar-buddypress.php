<?php
/**
 * WordPress Template: Sidebar BuddyPress
 *
 * By default this sidebar template is displayed on BuddyPress pages.
 *
 * @package WP Framework
 * @subpackage Template
 */

do_action( 'bp_sidebar_before' ); ?>

				<div id="sidebar" class="column-3 last">

					<?php do_action( 'bp_sidebar_open' ); ?>

					<aside role="complementary">

						<?php do_action( 'bp_aside_open' ); ?>
						
						<?php /* If a user is login, then display their mini profile section */ ?>
						<?php if ( is_user_logged_in() ) : ?>

							<?php do_action( 'bp_before_sidebar_me' ); ?>

							<section id="sidebar-me" class="widget">
								<a href="<?php echo bp_loggedin_user_domain(); ?>">
									<?php bp_loggedin_user_avatar( 'type=thumb&width=40&height=40' ); ?>
								</a>

								<h4><?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?></h4>
								<a class="button logout" href="<?php echo wp_logout_url( bp_get_root_domain() ); ?>"><?php _e( 'Log Out', t() ); ?></a>

								<?php do_action( 'bp_sidebar_me' ); ?>
							</section>

							<?php do_action( 'bp_after_sidebar_me' ); ?>

							<?php if ( function_exists( 'bp_message_get_notices' ) ) : ?>
								<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
							<?php endif; ?>

						<?php /* If a user is *NOT* login, then display the login form */ ?>
						<?php else : ?>

							<?php do_action( 'bp_before_sidebar_login_form' ); ?>

							<section id="bp-login-form" class="widget">
								<p id="login-text">
									<?php _e( 'To start connecting please log in first.', t() ); ?>
									<?php if ( bp_get_signup_allowed() ) : ?>
										<?php printf( __( ' You can also <a href="%s" title="Create an account">create an account</a>.', t() ), site_url( BP_REGISTER_SLUG . '/' ) ); ?>
									<?php endif; ?>
								</p>

								<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
									<label><?php _e( 'Username', t() ); ?><br />
									<input type="text" name="log" id="sidebar-user-login" class="input" value="<?php echo esc_attr(stripslashes($user_login)); ?>" /></label>

									<label><?php _e( 'Password', t() ); ?><br />
									<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" /></label>

									<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <?php _e( 'Remember Me', t() ); ?></label></p>

									<?php do_action( 'bp_sidebar_login_form' ); ?>
									<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In', t() ); ?>" tabindex="100" />
									<input type="hidden" name="testcookie" value="1" />
								</form>
							</section>

							<?php do_action( 'bp_after_sidebar_login_form' ); ?>

						<?php endif; ?>

						<?php /* Show forum tags on the forums directory */
						if ( BP_FORUMS_SLUG == bp_current_component() && bp_is_directory() && bp_forum_topics() ) : ?>
							<div id="forum-directory-tags" class="widget tags">
								<h3 class="widgettitle"><?php _e( 'Forum Topic Tags', 'buddypress' ) ?></h3>
								<?php if ( function_exists('bp_forums_tag_heat_map') ) : ?>
									<div id="tag-text"><?php bp_forums_tag_heat_map(); ?></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( is_active_sidebar( 'buddypress-widget-area' ) ) : ?>
							<?php dynamic_sidebar( 'buddypress-widget-area' ); ?>
						<?php endif; ?>
						
						<?php do_action( 'bp_inside_after_sidebar' ); ?>

						<?php do_action( 'bp_aside_close' ); ?>

					</aside><!-- .aside -->

					<?php do_action( 'bp_sidebar_close' ); ?>

				</div><!--#sidebar-->

<?php do_action( 'bp_sidebar_after' ); ?>