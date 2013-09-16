<?php
/**
 * Extends the base admin class by providing a complete metabox/option API.
 *
 * To Learn more on how to use this class, click the following link:
 * @link http://devpress.com/codex/wp-framework/theme-options-api/
 *
 * @since 0.3.0
 */
class WPF_Admin_Metabox extends WPF_Admin {
	/**
	 * The name of callback method to use for registering metaboxes/options.
	 *
	 * @access public
	 * @since 0.3.0
	 * @var string
	 */
	var $api_callback;

	/**
	 * The default number of screen columns to display.
	 *
	 * @access public
	 * @since 0.3.0
	 * @var int
	 */
	var $default_screen_columns;
	
	/**
	 * The maximun number of screen columns to display.
	 *
	 * @access public
	 * @since 0.3.0
	 * @var int
	 */
	var $max_screen_columns;

	/**
	 * Constructor method that bootstraps the admin class.
	 *
	 * When registering your own admin class, you'll need to populate
	 * the following args:
	 * - option_group - The id name used to store all the options to the database. Defaults to null.
	 * - api_callback - A magic function for users to register metaboxes/options to this page.
	 * - default_screen_columns - The default number of screen columns to display.
	 * - max_screen_columns - The maximun number of screen columns to display.
	 *
	 * @since 0.3.0
	 */
	function WPF_Admin_Metabox( $args = array() ) {
		$default_args = array( 'default_screen_columns' => 3, 'max_screen_columns' => 4, 'api_callback' => null, 'option_group' => THEME_OPTIONS );
		$args = wp_parse_args( $args, $default_args );
		parent::__construct( $args );
	}
	
	function admin_init() {
		if ( ! $this->verify() )
			return false;

		add_action( 'load-' . $this->hook, array( $this, 'set_screen_layout_columns' ) );
		add_filter( 'screen_layout_columns', array( $this, 'set_max_screen_columns' ), 10, 2 );

		add_action( 'wpf_admin_page_close_' . $this->slug, array( $this, 'inject_metabox_nonce' ) );
		add_action( "wpf_before_form_{$this->slug}", array( $this, 'register_form_globals' ) );

		add_action( 'load-' . $this->hook, array( $this, 'metabox_scripts' ) );
		add_action( 'load-' . $this->hook, array( $this, 'api_callback' ) );
		add_action( 'load-' . $this->hook, array( $this, 'register_metaboxes' ) );

		add_action( 'admin_head-' . $this->hook, 'wpf_inject_css_in_head' );
		add_action( 'admin_head-' . $this->hook, 'wpf_inject_javascript_in_head' );

		add_action( "wpf_before_form_{$this->slug}", array( $this, 'no_options_notice' ) );
	}

	/**
	 * Update options.
	 *
	 * This method does basic validation however your encouraged to override
	 * this method with your own custom validator.
	 *
	 * Basically, it should check that $new_options is set correctly.
	 * The newly calculated value of $options should be returned.
	 * If "false" is returned, the options won't be saved/updated.
	 *
	 * @param array $new_options New options from $_POST.
	 * @param array $old_options Old options from database.
	 * @return array Options to save or bool false to cancel saving.
	 */
	function update( $new_options, $old_options ) {
		global $wpf_admin;

		foreach ( $wpf_admin->fields as $option_id => $args ) {
			// Set all defaults
			if ( $args['default'] && !isset($old_options[$option_id]) ) {
				$old_options[$option_id] = $args['default'];
			}

			// fix checkboxes
			if ( 'checkbox' == $args['type'] ) {
				if ( isset($new_options[$option_id]) ) {
					$new_options[$option_id] = $args['multiple'] ? $new_options[$option_id] : (bool) $new_options[$option_id];
				} else {
					$new_options[$option_id] = false;
				}
			}
		}

		$options = wp_parse_args( $new_options, $old_options );

		return $options;
	}

	/**
	 * Displays the admin form.
	 *
	 * @since 0.3.0
	 */
	function form() {
		global $screen_layout_columns, $width, $hide2, $hide3, $hide4, $columns; ?>

		<?php do_action( "wpf_before_form_{$this->slug}" ); ?>
		<div class="metabox-holder">
			<?php			
			echo "\t<div class='postbox-container' style='$width'>\n";
			do_meta_boxes( $this->hook, $columns[1], '' );

			echo "\t</div><div class='postbox-container' style='{$hide2}$width'>\n";
			do_meta_boxes( $this->hook, $columns[2], '' );

			echo "\t</div><div class='postbox-container' style='{$hide3}$width'>\n";
			do_meta_boxes( $this->hook, $columns[3], '' );

			echo "\t</div><div class='postbox-container' style='{$hide4}$width'>\n";
			do_meta_boxes( $this->hook, $columns[4], '' );
			echo '</div>';
			?>
		</div><!--.metabox-holder-->
		<br class="clear" />
		<?php
		if ( $this->has_fields() )
			echo '<input type="submit" class="button-primary" value="'. __( 'Save Changes', t() ) .'" />';
		?>
		<input type="hidden" name="page" value="<?php echo esc_attr( $_GET['page'] ); ?>" id="page" />
		<input type="hidden" id="wpf-hook" value="<?php echo esc_attr($this->hook); ?>" />
		<?php do_action( "wpf_after_metabox_form_{$this->slug}" ); ?>
		<?php
	}

	/**
	 * AJAX needed to save metabox order and close/open state.
	 *
	 * @since 0.3.0
	 */
	function inject_metabox_nonce() {
		if ( !$this->has_metaboxes() )
			return false;
		?>
		<form id="metabox-nonce" method="get" action="" style="display: none;">
			<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce' ); ?>
			<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce' ); ?>
		</form>
		<?php
	}
	
	/**
	 * Displays and error notice when their are no options registered.
	 *
	 * @since 0.3.0
	 */
	function no_options_notice() {
		global $wp_meta_boxes;

		if ( !isset($wp_meta_boxes[$this->hook]) ) {
			$message = __( 'There are no registered options on this page.', t() );
			$this->message( apply_filters( "admin_no_options_notice_{$this->slug}", $message ), 'error' );
		}
	}
	
	/**
	 * Presets the user's screen layout columns if it isn't already set.
	 *
	 * @since 0.3.0
	 */
	function set_screen_layout_columns() {
		$user = wp_get_current_user();

		if ( !get_user_meta( $user->ID, "screen_layout_{$this->hook}" ) )
			update_user_meta( $user->ID, "screen_layout_{$this->hook}", apply_filters( "{$this->hook}_screen_layout_columns", (int) $this->default_screen_columns ) );
	}

	/**
	 * Registers a maximum number of screen columns for the admin page.
	 *
	 * @since 0.3.0
	 */
	function set_max_screen_columns( $columns, $screen ) {
		if ( $screen == $this->hook )
			$columns[$this->hook] = apply_filters( "{$this->hook}_max_screen_columns", (int) $this->max_screen_columns );

		return $columns;
	}

	/**
	 * Deletes all the metabox settings for the current user.
	 *
	 * @since 0.3.0
	 * @uses wp_get_current_user();
	 */
	function reset_metaboxes() {
		$user = wp_get_current_user();
		delete_user_meta( $user->ID, "screen_layout_{$this->hook}" ); // default screeen columns
		delete_user_meta( $user->ID, "closedpostboxes_{$this->hook}" ); //closed-postboxes
		delete_user_meta( $user->ID, "metaboxhidden_{$this->hook}" ); // hidden-columns
		delete_user_meta( $user->ID, "meta-box-order_{$this->hook}" ); // metabox-order
	}

	/**
	 * Loads JavaScript for various functionality tidbits.
	 *
	 * @todo is it worth conditionally loading the scripts based on registered fields?
	 *
	 * @since 0.3.0
	 */
	function metabox_scripts() {
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );

		// Thickbox
		add_thickbox();

		// Media Upload
		wp_enqueue_script( 'media-upload' );

		// Color Picker
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
	}

	/**
	 * API Hook for adding metaboxes and options onto the admin page.
	 *
	 * @since 0.3.0
	 */
	function api_callback() {
		if ( is_object( $this->api_callback[0] ) )
			$this->callback( $this->api_callback[0], $this->api_callback[1] );
		elseif ( is_array( $this->api_callback ) )
			$this->callback( $this->api_callback, $this->api_callback[1] );
		elseif ( $this->api_callback )
			$this->callback( $this->api_callback );
		
		do_action( "wpf_admin_page_{$this->slug}" );
	}

	/**
	 * Registers all the metaboxes assigned to this admin page.
	 *
	 * @since 0.3.0
	 */
	function register_metaboxes() {
		if ( !$this->has_metaboxes() )
			return false;

		global $columns;

		$columns = array( 1 => 'normal', 2 => 'side', 3 => 'column3', 4 => 'column4' );

		$metaboxes = $this->get_metaboxes();

		foreach ( $metaboxes as $metabox ) {			
			add_meta_box( $metabox['id'], $metabox['title'], array( $this, 'do_metabox_options' ), $this->hook, $columns[ $metabox['column'] ], $metabox['priority'] );
		}
	}
	
	/**
	 * Internal function called by each metabox in the admin page to loop
	 * through and display any options registered to that metabox.
	 *
	 * @param string $column Data passed as the third parameter in do_meta_boxes().
	 * @param array $args Data passed as the forth parameter in wpf_register_option()
	 * @return void
	 */
	function do_metabox_options( $column, $metabox ) {
		if ( !$this->has_fields( $metabox['id'] ) )
			return $this->no_options( $metabox['id'] );

		$options = $this->get_fields( $metabox['id'] );
		
		do_action( "metabox_before_{$metabox['id']}", $column );
		foreach ( $options as $option_id => $option ) {
			if ( $metabox['id'] == $option['metabox'] ) {
				switch ( $option['type'] ) {
					case 'custom':
						call_user_func( '__wpf_echo_value', wpf_wrap( 'p', $option['data'], null, false ) );
						break;

					case 'callback':
						if ( is_callable($option['data']) )
							call_user_func( $option['data'], $metabox['id'] );
						break;

					default:
						if ( $this->is_method( $this, 'form_'. $option['type'] ) ) {
							$this->callback( $this, 'form_'. $option['type'], array( $option_id, $option['args'], $option['data'] ) );
						} else {
							do_action( "wpf_form_callback_{$option['type']}", $metabox['id'], $option, $this->slug );
						}
						break;
				}
			}
		}
		do_action( "metabox_after_{$metabox['id']}", $column );
	}

	/**
	 * Returns a boolean value on whether the admin page has registered
	 * metaboxes or not.
	 *
	 * @since 0.3.0
	 *
	 * @return bool True if the page has metaboxes, else false.
	 */
	function has_metaboxes() {
		global $wpf_admin;

		if ( isset( $wpf_admin->metaboxes ) AND isset( $wpf_admin->metaboxes[$this->slug] ) )
			return true;
		
		return false;
	}

	/**
	 * Returns all registered metaboxes for this admin page.
	 *
	 * @since 0.3.0
	 */
	function get_metaboxes() {
		global $wpf_admin;

		if ( !isset( $wpf_admin->metaboxes ) OR !isset( $wpf_admin->metaboxes[$this->slug] ) )
			return false;

		return $wpf_admin->metaboxes[$this->slug];
	}

	/**
	 * Returns a boolean value on whether a metabox has fields or not.
	 *
	 * @since 0.3.0
	 *
	 * @param string $metabox_id The identifier of the metabox.
	 * @return bool True of the metabox has options, else false.
	 */
	function has_fields( $metabox_id = '' ) {
		$options = $this->get_registered_fields();
		
		if ( $metabox_id )
			return (bool) isset( $options[$metabox_id] );
		else
			return (bool) $options;
		
		return false;
	}

	/**
	 * Returns all registered fields for a metabox.
	 *
	 * @since 0.3.0
	 *
	 * @param string $metabox_id The identifier of the metabox.
	 * @return array Fields for a metabox.
	 */
	function get_fields( $metabox_id = null ) {
		$options = $this->get_registered_fields();

		if ( $metabox_id AND isset($options[$metabox_id]) )
			return $options[$metabox_id];
		elseif ( $options )
			return $options;
	}

	/**
	 * Returns all registered fields.
	 *
	 * @since 0.3.0
	 */
	function get_registered_fields() {
		global $wpf_admin;

		if ( !isset($wpf_admin->settings) OR empty($wpf_admin->settings) OR !$this->has_metaboxes() )
			return false;

		$metaboxes = $this->get_metaboxes();
		$metaboxes = array_keys( $metaboxes );
		$registered_options = array();

		foreach ( $metaboxes as $metabox ) {
			if ( isset($wpf_admin->settings[$metabox]) ) {
				foreach ( $wpf_admin->settings[$metabox] as $option_id => $option ) {
					$registered_options[$metabox][$option_id] = array_merge( $option, array( 'metabox' => $metabox) );
				}
			}
		}	
		return $registered_options;
	}

	/**
	 * Displays a notice in the metabox when there are no options assign to it.
	 *
	 * @since 0.3.0
	 */
	function no_options( $metabox_id ) {
		$message = apply_filters( 'wpf_metabox_no_options', __( 'There are no options for this metabox.', t() ), $metabox_id );
		$this->wrap( 'p', $message, array( 'class' => 'no-metabox-options' ) );
	}

	/**
	 * Sets up all the globals needed for the screen columns.
	 *
	 * @since 0.3.0
	 */
	function register_form_globals() {
		global $screen_layout_columns, $width, $hide2, $hide3, $hide4;

		$hide2 = $hide3 = $hide4 = '';
		switch ( $screen_layout_columns ) {
			case 4:
				$width = 'width: 24.5%;';
				break;
			case 3:
				$width = 'width: 32.67%;';
				$hide4 = 'display: none;';
				break;
			case 2:
				$width = 'width: 49%;';
				$hide3 = $hide4 = 'display: none;';
				break;
			default:
				$width = 'width: 98%;';
				$hide2 = $hide3 = $hide4 = 'display: none;';
		}
	}

	/**
	 * Adds a metabox to a registered WP Framework admin page.
	 *
	 * @since 0.3.0
	 *
	 * @param string $page_slug Page where the metabox is registered to.
	 * @param string $metabox_id Identifier of the metabox.
	 * @param string $metabox_title Title of the meta box.
	 * @param int $column Registers the metabox to a specific column (1, 2, 3, or 4).
	 * @param string $priority Optional. The priority within the column the metabox should be shown ( 'high', 'core', 'default', 'low' ).
	 * @return array the metabox parameters.
	 */
	function add_metabox( $page_slug, $metabox_id, $metabox_title = '', $column = 1, $priority = 'default' ) {
		global $wpf_admin;

		$metabox_title = ( !isset($metabox_title) ) ? $metabox_id : $metabox_title;

		$wpf_admin->metaboxes[$page_slug][$metabox_id] = array( 'id' => $metabox_id, 'title' => $metabox_title, 'column' => $column, 'priority' => $priority );

		return $wpf_admin->metaboxes[$page_slug][$metabox_id];
	}

	/**
	 * Remove a metabox from a registered WP Framework admin page.
	 *
	 * @since 0.3.0
	 *
	 * @param string $page_slug Page where the metabox is registered to.
	 * @param string $metabox_id Id of the metabox.
	 * @return bool True if the metabox was removed, else false.
	 */
	function delete_metabox( $page_slug, $metabox_id ) {
		global $wpf_admin;

		if ( isset($wpf_admin->metaboxes[$page_slug][$metabox_id]) ) {
			unset( $wpf_admin->metaboxes[$page_slug][$metabox_id] );
			return true;
		}

		return false;
	}

	/**
	 * Adds an option to a metabox.
	 *
	 * Accepted $args:
	 * - type		 : textbox, textarea, checkbox, radio, select, upload, color
	 * - label		 : A Label to display after the radio.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 * - numeric_keys: Whether to use numeric keys as the value for all form elements
	 *				   or the keys you've specified in $data.
	 * - multiple	 : Whether a select box can accept multiple values or not.
	 *
	 * @since 0.3.0
	 *
	 * @param string $metabox_id The identifier of the metabox.
	 * @param string $option_id The identifier of the option.
	 * @param string $args Parameters to pass.
	 * @param string $data Optional.
	 */
	function add_setting( $metabox_id, $option_id, $args = array(), $data = array() ) {
		global $wpf_admin;

		if ( !isset($args['type']) )
			return false;

		$wpf_admin->settings[$metabox_id][$option_id] = array(
			'id' => $option_id,
			'type' => $args['type'],
			'args' => $args,
			'data' => $data,
		);

		$default = ( is_array($args) && isset( $args['default'] ) ) ? $args['default'] : false;	
		$multiple = ( !empty($data) ) ? true : false;

		$wpf_admin->fields[$option_id] = array( 'default' => $default, 'type' => $args['type'], 'multiple' => $multiple, 'data' => $data );

		return $wpf_admin->settings[$metabox_id][$option_id];
	}

	/**
	 * Removes an option from a metabox.
	 *
	 * @since 0.3.0
	 *
	 * @param string $metabox_id The identifier of the metabox.
	 * @param string $option_id The identifier of the option.
	 * @return bool Returns true if the option was removed, else false.
	 */
	function delete_setting( $metabox_id, $option_id ) {
		global $wpf_admin;

		if ( $wpf_admin->settings[$metabox_id][$option_id] ) {
			unset( $wpf_admin->settings[$metabox_id][$option_id] );

			// Remove the option from the required array if it's there.
			if ( isset( $wpf_admin->fields[$option_id] ) )
				unset( $wpf_admin->fields[$option_id] );

			return true;
		}

		return false;
	}

	/**
	 * Displays a text form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display before the input field.
	 * - description : A description of the field.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 */
	function form_textbox( $id, $args ) {
		$value = $this->get_option( $id );
		$value = isset( $args['default'] ) ? $args['default'] : $value;
		$name = $this->option_group .'['. esc_attr($id) .']';
		$attrs = !empty( $args['attrs'] ) ? wp_parse_args( $args['attrs'], array( 'class' => 'widefat' ) ) : array( 'class' => 'widefat' );
		$attrs = $this->parse_attrs( $attrs );
		$label = !empty($args['label']) ? $args['label'] . '<br />' : '';
		$description = !empty($args['description']) ? '<br />' . wpf_wrap( 'span', $args['description'], array('class' => 'description'), false ) : '';

		self::do_metabox_textbox( compact( 'id', 'name', 'value', 'attrs', 'label', 'description' ) );
	}
	
	function do_metabox_textbox( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<p id="wpf-p-<?php echo esc_attr( $id ); ?>">
			<?php echo wp_kses_post( $label ); ?>
			<input type="text" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"<?php echo $attrs; ?> />
			<?php echo wp_kses_post( $description ); ?>
		</p>
		<?php
	}

	/**
	 * Displays a textarea form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display before the textarea.
	 * - description : A description of the field.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 */
	function form_textarea( $id, $args ) {
		$value = $this->get_option( $id );
		$value = isset( $args['default'] ) ? $args['default'] : $value;
		$name = $this->option_group .'['. esc_attr($id) .']';
		$attrs = !empty( $args['attrs'] ) ? wp_parse_args( $args['attrs'], array( 'class' => 'widefat' ) ) : array( 'class' => 'widefat' );
		$attrs = $this->parse_attrs( $attrs );
		$label = isset($args['label']) ? $args['label'] . '<br />' : '';
		$description = !empty($args['description']) ? '<br />' . wpf_wrap( 'span', $args['description'], array('class' => 'description'), false ) : '';
		
		self::do_metabox_textarea( compact( 'id', 'name', 'value', 'attrs', 'label', 'description' ) );
	}
	
	function do_metabox_textarea( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<p id="wpf-p-<?php echo esc_attr( $id ); ?>">
			<?php echo wp_kses_post( $label ); ?>
			<textarea id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>"<?php echo $attrs; ?> /><?php echo esc_attr($value); ?></textarea>
			<?php echo wp_kses_post( $description ); ?>
		</p>
		<?php
	}

	/**
	 * Displays a checbox form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display after the checkbox.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - numeric_keys: Whether to use numeric keys as the value or the key you've
	 *				   you've specified in $data.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 * @param array $data Key => value pairs of data.
	 */
	function form_checkbox( $id, $args, $data ) {
		$value = $this->get_option( $id );
		$value = isset( $args['default'] ) ? $args['default'] : $value;
		$name = $this->option_group .'['. esc_attr($id) .']';
		$label = isset($args['label']) ? $args['label'] : '';
		
		self::do_metabox_checkbox( compact( 'id', 'name', 'value', 'label', 'args', 'data' ) );
	}
	
	function do_metabox_checkbox( $args = array() ) {
		extract( $args, EXTR_SKIP );
		if ( !empty( $data ) ) {
			$name .= '[]';
			
			echo wpf_wrap( 'p', wp_kses_post( $label ) );
			foreach ( $data as $option_key => $option_label ) {
				if ( !isset($args['numeric_keys']) )
					$option_key = is_numeric( $option_key ) ? $option_label : $option_key;
				
				$checked = $value ? checked( in_array( $option_key, $value ), true, false ) : null;
				$attrs = isset( $args['attrs'] ) ? $this->parse_attrs( $args['attrs'] ) : '';
				?>
				<p>
					<label for="wpf-form-<?php echo esc_attr($option_key); ?>">
						<input type="checkbox" id="wpf-form-<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option_key); ?>"<?php echo $attrs . $checked; ?> />
						<?php echo wp_kses_post( $option_label ); ?>
					</label>
				</p>
				<?php
			}
		} else {
			$value = (bool) $value;
			$checked = checked( $value, true, false );
			$attrs = isset( $args['attr'] ) ? $this->parse_attrs( $args['attr'] ) : '';
			?>
			<p id="wpf-p-<?php echo esc_attr( $id ); ?>">
				<label for="wpf-form-<?php echo esc_attr($id); ?>">
					<input type="checkbox" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="1"<?php echo $attrs . $checked; ?> />
					<?php echo wp_kses_post( $label ); ?>
				</label>
			</p>
			<?php
		}
	}

	/**
	 * Displays a radio form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display after the radio.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - numeric_keys: Whether to use numeric keys as the value or the key you've
	 *				   you've specified in $data.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 * @param array $data Key => value pairs of data.
	 */
	function form_radio( $id, $args, $data ) {
		$value = $this->get_option( $id );
		$value = isset( $args['default'] ) ? $args['default'] : $value;
		$name = $this->option_group .'['. esc_attr($id) .']';
		$label = isset($args['label']) ? $args['label'] : '';
		
		self::do_metabox_radio( compact( 'id', 'name', 'value', 'label', 'args', 'data' ) );
	}
	
	function do_metabox_radio( $args = array() ) {
		extract( $args, EXTR_SKIP );
		echo wpf_wrap( 'p', esc_html( $label ) );
		foreach ( $data as $option_key => $option_label ) {
			if ( !isset($args['numeric_keys']) )
				$option_key = is_numeric( $option_key ) ? $option_label : $option_key;
			
			$checked = $value ? checked( $value, $option_key, false ) : null;
			$attrs = isset( $args['attrs'] ) ? $this->parse_attrs( $args['attrs'] ) : '';
			?>
			<p id="wpf-p-<?php echo esc_attr( $id ); ?>">
				<label for="wpf-form-<?php echo esc_attr($option_key); ?>">
					<input type="radio" id="wpf-form-<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option_key); ?>"<?php echo $attrs . $checked; ?> />
					<?php echo wp_kses_post( $option_label ); ?>
				</label>
			</p>
			<?php
		}
	}

	/**
	 * Displays a select form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display after the select.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - numeric_keys: Whether to use numeric keys as the value or the key you've
	 *				   you've specified in $data.
	 * - multiple	 : Whether the select box can accept multiple values or not.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 * @param array $data Key => value pairs of data.
	 */
	function form_select( $id, $args, $data ) {
		if ( empty($data) )
			return false;
		
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = $this->get_option( $id );
		$name = $this->option_group .'['. esc_attr($id) .']';

		if ( isset($args['multiple']) && $args['multiple'] ) {
			$args['attrs']['multiple'] = 'multiple';
			$name .= '[]';
		}

		$attrs = !empty( $args['attrs'] ) ? $this->parse_attrs( $args['attrs'] ) : '';
		$label = !empty( $args['label'] ) ? $args['label'] : '';

		self::do_metabox_select( compact( 'id', 'name', 'value', 'label', 'args', 'data' ) );
	}
	
	function do_metabox_select( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<p id="wpf-p-<?php echo esc_attr( $id ); ?>">
			<label for="wpf-form-<?php echo esc_attr($id); ?>">
				<?php
				echo wp_kses_post( $label );

				if ( isset($args['multiple']) && $args['multiple'] )
					echo '<br />';
				?>
				<select id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>"<?php echo $attrs; ?>>
				<?php
					foreach ( $data as $option_key => $option_label ) {
						// If we're using numeric keys, ignore the keys passed into the $data array
						if ( !isset($args['numeric_keys']) )
							$option_key = is_numeric( $option_key ) ? $option_label : $option_key;
						
						if ( isset($args['multiple']) && $args['multiple'] ) {
							$selected = $value ? selected( in_array( $option_key, $value ), true, false ) : null;
						} else {
							$selected = selected( $value, $option_key, false );
							$selected = $selected ? $selected : '';
							
							if ( $default ) {
								$selected = selected( $default, $option_key, false );
								$selected = $selected ? $selected : '';
							}
						}
						?>
						<option value="<?php echo esc_attr( $option_key ); ?>"<?php echo esc_attr($selected); ?>><?php echo esc_html($option_label); ?></option>
						<?php
					}
				?>
				</select>
			</label>
		</p>
		<?php
	}
	
	/**
	 * Displays an upload form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display before the input field.
	 * - description : A description of the field.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 * - media		 : Allowed file types.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 */
	function form_upload( $id, $args ) {
		$value = $this->get_option( $id );
		$value = isset( $args['default'] ) ? $args['default'] : $value;
		$name = $this->option_group .'['. esc_attr($id) .']';
		$attrs = !empty( $args['attrs'] ) ? wp_parse_args( $args['attrs'], array( 'class' => 'widefat code wpf-file' ) ) : array( 'class' => 'widefat code wpf-file' );
		$attrs = $this->parse_attrs( $attrs );
		$label = !empty($args['label']) ? $args['label'] . '<br />' : '';
		$description = !empty($args['description']) ? '<br />' . wpf_wrap( 'span', $args['description'], array('class' => 'description'), false ) : '';

		$type = ( !empty( $args['media'] ) && in_array( $args['media'], array( 'image', 'video', 'audio' ) ) ) ? '&amp;type=' . $args['media'] : '';
		$media_args = '?tab=library&amp;width=640&amp;height=400'. $type . '&amp;TB_iframe=1';
		$upload_args = '?tab=type&amp;width=640&amp;height=400'. $type . '&amp;TB_iframe=1';
		
		self::do_metabox_upload( compact( 'id', 'name', 'value', 'label', 'description', 'attrs', 'media_args', 'upload_args' ) );
	}

	function do_metabox_upload( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<p id="wpf-p-<?php echo esc_attr( $id ); ?>" class="upload">
			<?php echo wp_kses_post( $label ); ?>
			<input type="text" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"<?php echo $attrs; ?> />
			<a class="previewlink button" href="#TB_inline?height=500&amp;width=640&amp;inlineId=<?php echo esc_attr( 'thicky-wpf-' . $id ); ?>"><?php _e( 'Preview', t() ); ?></a>
			<a class="medialink thickbox button" title="Choose a file from your media library" href="<?php echo admin_url( 'media-upload.php' . $media_args ); ?>"><?php _e( 'Media Library', t() ); ?></a>
			<a class="uploadlink thickbox button" title="Upload Media" href="<?php echo admin_url( 'media-upload.php' . $upload_args ); ?>"><?php _e( 'Upload', t() ); ?></a>
			<?php echo esc_html( $description ); ?>
			<div id="thicky-wpf-<?php echo esc_attr($id); ?>" style="display: none;"></div>
		</p>
		<?php
	}
	
	/**
	 * Displays an upload form field.
	 *
	 * Accepted $args:
	 * - label		 : A Label to display before the input field.
	 * - attrs		 : Key => Value pairs of attributes to add to the form field.
	 * - default	 : A default value to use if the option doesn't have a db value.
	 * - btn_label	 : A label for the color picker button. Defaults to 'Select a Color'.
	 *
	 * @since 0.3.0
	 *
	 * @param string $id The identifier of the input field.
	 * @param string $args Parameters to pass.
	 */
	function form_color( $id, $args ) {
		$value = $this->get_option( $id );
		$value = isset( $args['default'] ) ? $args['default'] : $value;
		$name = $this->option_group .'['. esc_attr($id) .']';
		$attrs = !empty( $args['attrs'] ) ? wp_parse_args( $args['attrs'], array( 'class' => 'small-text code wpf-color' ) ) : array( 'class' => 'small-text code wpf-color' );
		$attrs = $this->parse_attrs( $attrs );
		$label = !empty($args['label']) ? $args['label'] . '<br />' : '';
		$btn_label = !empty($args['btn_label']) ? $args['btn_label'] : __( 'Select a Color', t() );

		self::do_metabox_color( compact( 'id', 'name', 'value', 'label', 'btn_label', 'attrs' ) );
	}

	function do_metabox_color( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<p id="wpf-p-<?php echo esc_attr( $id ); ?>">
			<?php echo wp_kses_post( $label ); ?>
			<a class="pickcolor hide-if-no-js button" rel="<?php echo esc_attr( $id ); ?>" href="#"><?php echo esc_html( $btn_label ); ?></a>
			<input type="text" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"<?php echo $attrs; ?> />
		</p>
		<div id="pickcolor-<?php echo esc_attr( $id ); ?>" class="color-picker-div"></div>
		<?php
	}
}