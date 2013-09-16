<?php
/**
 * BuddyPress Template: Activate
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

					<div id="activate-page" class="hfeed">

						<?php do_action( 'template_notices' ); ?>

						<?php if ( bp_account_was_activated() ) : ?>

							<h1 class="page-title"><?php _e( 'Account Activated', t() ); ?></h1>

							<?php do_action( 'bp_before_activate_content' ); ?>

							<?php if ( isset( $_GET['e'] ) ) : ?>
								<p><?php _e( 'Your account was activated successfully! Your account details have been sent to you in a separate email.', t() ); ?></p>
							<?php else : ?>
								<p><?php _e( 'Your account was activated successfully! You can now log in with the username and password you provided when you signed up.', t() ); ?></p>
							<?php endif; ?>

						<?php else : ?>

							<h2><?php _e( 'Activate your Account', t() ); ?></h2>

							<?php do_action( 'bp_before_activate_content' ); ?>

							<p><?php _e( 'Please provide a valid activation key.', t() ); ?></p>

							<form action="" method="get" class="standard-form" id="activation-form">

								<label for="key"><?php _e( 'Activation Key:', t() ); ?></label>
								<input type="text" name="key" id="key" value="" />

								<?php submit_button( __( 'Activate', t() ), 'primary', 'submit' ); ?>

							</form><!-- #activation-form .standard-form -->

						<?php endif; ?>

						<?php do_action( 'bp_after_activate_content' ); ?>

					</div><!-- #activate-page -->

					<?php do_action( 'bp_after_activation_page' ); ?>
					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar', 'buddypress' ); ?>

<?php get_template_part( 'footer' ); ?>