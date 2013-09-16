<?php
/**
 * BuddyPress Template: Member Profile Edit
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

<?php do_action( 'bp_before_profile_edit_content' ); ?>

<?php if ( bp_has_profile( 'profile_group_id=' . bp_get_current_profile_group_id() ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

<form action="<?php bp_the_profile_group_edit_form_action(); ?>" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug(); ?>">

	<?php do_action( 'bp_before_profile_field_content' ); ?>

		<h1 class="page-title"><?php printf( __( "Editing '%s' Profile Group", t() ), bp_get_the_profile_group_name() ); ?></h1>

		<ul class="button-nav">
			<?php bp_profile_group_tabs(); ?>
		</ul><!-- .button-nav -->

		<div class="clear"></div>

		<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

			<div<?php bp_field_css_class( 'editfield' ); ?>>

				<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

					<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
					<input type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" />

				<?php endif; ?>

				<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

					<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
					<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_edit_value(); ?></textarea>

				<?php endif; ?>

				<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

					<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
					<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>">
						<?php bp_the_profile_field_options(); ?>
					</select>

				<?php endif; ?>

				<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

					<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>
					<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple">
						<?php bp_the_profile_field_options(); ?>
					</select>

					<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
						<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', t() ); ?></a>
					<?php endif; ?>

				<?php endif; ?>

				<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

					<div class="radio">
						<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></span>

						<?php bp_the_profile_field_options(); ?>

						<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
							<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', t() ); ?></a>
						<?php endif; ?>
					</div><!-- .radio -->

				<?php endif; ?>

				<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

					<div class="checkbox">
						<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></span>

						<?php bp_the_profile_field_options(); ?>
					</div><!-- .checkbox -->

				<?php endif; ?>

				<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

					<div class="datebox">
						<label for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', t() ); ?><?php endif; ?></label>

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

				<p class="description"><?php bp_the_profile_field_description(); ?></p><!-- .description -->
			</div>

		<?php endwhile; ?>

	<?php do_action( 'bp_after_profile_field_content' ); ?>

	<?php submit_button( __( 'Save Changes', t() ), 'primary', 'profile-group-edit-submit' ); ?>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />
	<?php wp_nonce_field( 'bp_xprofile_edit' ); ?>

</form><!-- #profile-edit-form .standard-form -->

<?php endwhile; endif; ?>

<?php do_action( 'bp_after_profile_edit_content' ); ?>