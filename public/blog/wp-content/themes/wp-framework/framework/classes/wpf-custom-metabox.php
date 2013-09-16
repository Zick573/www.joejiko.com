<?php

// class that handles the display and saving of custom metaboxes.
// currently only supported on post type pages, but functionality will be
// extended to support plugin pages.
class WPF_Custom_Metabox extends WPF_Admin_Metabox {
	// called in add_meta_boxes hook
	function WPF_Custom_Metabox( $args = array() ) {
		global $wpf_admin, $typenow, $pagenow;

		if ( !in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) )
			return;

		$post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
		$post_id = isset($_POST['post_ID']) ? (int) $_POST['post_ID'] : $post_id;

		if ( $post_id ) {
			$post = get_post( $post_id );
			$typenow = $post->post_type;
		}

		if ( empty($typenow) )
			$typenow = 'post';

		if ( empty($wpf_admin->metaboxes) || !isset($wpf_admin->metaboxes['post_type-'. $typenow]) )
			return;
	
		parent::__construct( array(
			'option_group' => THEME_ID,
			'post_type' => $typenow,
			'metaboxes' => $wpf_admin->metaboxes['post_type-'. $typenow],
			'options' => $wpf_admin->settings,
			'prefix' => '_wpf_',
		) );
	}

	function admin_init() {
		add_action( 'admin_head', array( $this, 'metabox_scripts' ) );
		add_action( 'admin_head', 'wpf_inject_css_in_head' );
		add_action( 'admin_head', 'wpf_inject_javascript_in_head' );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post_meta' ), 10, 2 );

		add_action( 'dbx_post_sidebar', array($this, 'inject_form_nonce') );

		add_action( 'load-' . $this->hook, array( $this, 'metabox_scripts' ) );
		add_action( 'load-' . $this->hook, array( $this, 'api_callback' ) );
		add_action( 'load-' . $this->hook, array( $this, 'register_metaboxes' ) );

		add_action( 'admin_head-' . $this->hook, 'wpf_inject_css_in_head' );
		add_action( 'admin_head-' . $this->hook, 'wpf_inject_javascript_in_head' );
	}

	function inject_form_nonce() {
		global $current_screen;
		echo '<input type="hidden" name="'. esc_attr( $this->option_group ) .'-nonce" value="'. wp_create_nonce( 'wpf-postmeta-' . $current_screen->post_type ) . '" />';
	}

	function save_post_meta( $post_id, $post ) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
	        return $post_id;

		if ( ! wp_verify_nonce( $_POST[$this->option_group .'-nonce'], 'wpf-postmeta-' . $post->post_type ) )
			return $post_id;

		$post_type = get_post_type_object( $post->post_type );

		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		$new_options = isset( $_POST[$this->option_group] ) ? $_POST[$this->option_group] : array();
		if ( !empty($new_options) ) {
			$options = $this->contextual_callback( 'update', array( $new_options, $this->get_options( $post->post_type ) ) );

			if ( $options ) {
				foreach ( $options as $key => $value ) {
					update_post_meta( $post_id, $this->prefix . $key, $value );
				}
			}
			// delete all options from postmeta
			// else {
			// 	foreach ( $options as $key => $value ) {
			// 		delete_post_meta( $post_id, $this->prefix . $key );
			// 	}
			// }
		}
	}
	
	function get_options( $post_type ) {
		if ( isset($this->options[$post_type]) && !empty($this->options[$post_type]) ) {
			return $this->options[$post_type];
		} else {
			return array();
		}
	}

	function add_meta_boxes() {
		if ( empty($this->metaboxes) )
			return;

		$columns = array( 1 => 'normal', 2 => 'side', 3 => 'column3', 4 => 'column4' );

		foreach ( $this->metaboxes as $metabox ) {
			add_meta_box( $metabox['id'], $metabox['title'], array( $this, 'do_metabox_options' ), $this->post_type, $columns[ $metabox['column'] ], $metabox['priority'] );
		}
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
	 * Loads JavaScript for various functionality tidbits.
	 *
	 * @since 0.3.0
	 */
	function metabox_scripts() {
		// Thickbox
		wp_print_scripts( 'thickbox' );
		wp_print_styles( 'thickbox' );

		// Media Upload
		wp_print_scripts( 'media-upload' );

		// Color Picker
		wp_print_scripts( 'farbtastic' );
		wp_print_styles( 'farbtastic' );
	}

	function do_metabox_options( $column, $metabox ) {
		if ( !isset($this->options[ $metabox['id'] ]) || empty($this->options[ $metabox['id'] ]) )
			return $this->no_options( $metabox['id'] );

		$options = $this->options[ $metabox['id'] ];

		echo '<table class="wpf-form form-table">' . PHP_EOL;
		echo '<tbody>' . PHP_EOL;
		foreach ( $options as $option_id => $option ) {
			switch ( $option['type'] ) {
				case 'custom':
					call_user_func( '__wpf_echo_value', wpf_wrap( 'p', $option['data'], null, false ) );
					break;

				case 'callback':
					if ( function_exists($option['data']) )
						call_user_func( $option['data'], $metabox['id'] );
					break;

				default:
					if ( $this->is_method( $this, 'form_'. $option['type'] ) ) {
						$this->callback( $this, 'form_'. $option['type'], array( $option_id, $option['args'], $option['data'] ) );
					} else {
						do_action( "wpf_form_callback_{$option['type']}", $metabox['id'], $option, $this->post_type );
					}
					break;
			}
		}
		echo '</tbody>' . PHP_EOL;
		echo '</table>' . PHP_EOL;
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
	
	function do_table_textbox( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<tr id="wpf-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $args['type'] ); ?>">
			<th>
				<label for="wpf-form-<?php echo esc_attr($id); ?>"><?php echo wp_kses_post( $label ); ?></label>
			</th>
			<td>
				<input type="text" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"<?php echo $attrs; ?> />
				<?php echo wp_kses_post( $description ); ?>
			</td>
		</tr>
		<?php
	}

	function do_table_textarea( $args = array() ) {
		extract( $args, EXTR_SKIP );
		?>
		<tr id="wpf-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $args['type'] ); ?>">
			<th>
				<label for="wpf-form-<?php echo esc_attr($id); ?>"><?php echo wp_kses_post( $label ); ?></label>
			</th>
			<td>
				<textarea id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>"<?php echo $attrs; ?> /><?php echo esc_attr($value); ?></textarea>
				<?php echo wp_kses_post( $description ); ?>
			</td>
		</tr>
		<?php
	}
	
	function do_table_checkbox( $args = array() ) {
		extract( $args, EXTR_SKIP );
		echo '<tr id="wpf-'. esc_attr( $id ).'" class="'. esc_attr( $args['type'] ).'">';
		if ( !empty( $data ) ) {
			$name .= '[]';
			?>
			<th>
				<label for="wpf-form-<?php echo esc_attr($option_key); ?>">
					<?php echo wp_kses_post( $label ); ?>
				</label>
			</th>
			<td>
				<?php
				foreach ( $data as $option_key => $option_label ) {
					if ( !isset($args['numeric_keys']) )
						$option_key = is_numeric( $option_key ) ? $option_label : $option_key;
					
					$checked = in_array( $option_key, $value[0] ) ? checked( 1, 1, false ) : null;
					$attrs = isset( $args['attrs'] ) ? $this->parse_attrs( $args['attrs'] ) : '';
					?>
					<p id="wpf-p-<?php echo esc_attr($option_key); ?>">
						<label for="wpf-form-<?php echo esc_attr($option_key); ?>">
							<input type="checkbox" id="wpf-form-<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option_key); ?>"<?php echo $attrs . $checked; ?> />
							<?php echo wp_kses_post( $option_label ); ?>
						</label>
					</p>
					<?php
				}
				?>
			</td>
			<?php
		} else {
			$checked = checked( $value[0], true, false );
			$attrs = isset( $args['attr'] ) ? $this->parse_attrs( $args['attr'] ) : '';
			?>
			<th></th>
			<td>
				<label for="wpf-form-<?php echo esc_attr($id); ?>">
					<input type="checkbox" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="1"<?php echo $attrs . $checked; ?> />
					<?php echo wp_kses_post( $label ); ?>
				</label>
			</td>
			<?php
		}
		echo '</tr>';
	}
	
	function do_table_radio() {
		echo '<tr id="wpf-'. esc_attr( $id ).'" class="'. esc_attr( $args['type'] ).'">';
		?>
			<th>
				<?php echo wp_kses_post( $label ); ?>
			</th>
			<td>
				<?php
				foreach ( $data as $option_key => $option_label ) {
					if ( !isset($args['numeric_keys']) )
						$option_key = is_numeric( $option_key ) ? $option_label : $option_key;
					
					$checked = in_array( $option_key, $value ) ? checked( 1, 1, false ) : null;
					$attrs = isset( $args['attrs'] ) ? $this->parse_attrs( $args['attrs'] ) : '';
					?>
					<p id="wpf-p-<?php echo esc_attr($id); ?>">
						<label for="wpf-form-<?php echo esc_attr($option_key); ?>">
							<input type="radio" id="wpf-form-<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option_key); ?>"<?php echo $attrs . $checked; ?> />
							<?php echo wp_kses_post( $option_label ); ?>
						</label>
					</p>
					<?php
				}
				?>
			</td>
		<?php
		echo '</tr>';
	}

	function do_table_select() {
		?>
		<tr id="wpf-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $args['type'] ); ?>">
			<th>
				<label for="wpf-form-<?php echo esc_attr($id); ?>">
					<?php
					echo wp_kses_post( $label );

					if ( isset($args['multiple']) && $args['multiple'] )
						echo '<br />';
					?>
				</label>
			</th>
			<td>
				<select id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>"<?php echo $attrs; ?>>
				<?php
					foreach ( $data as $option_key => $option_label ) {
						// If we're using numeric keys, ignore the keys passed into the $data array
						if ( !isset($args['numeric_keys']) )
							$option_key = is_numeric( $option_key ) ? $option_label : $option_key;

						if ( isset($args['multiple']) && $args['multiple'] ) {
							$selected = $value ? selected( in_array( $option_key, $value[0] ), true, false ) : null;
						} else {
							$selected = selected( $value[0], $option_key, false );
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
			</td>
		</tr>
		<?php
	}
	
	function do_table_upload() {
		?>
		<tr id="wpf-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $args['type'] ); ?>">
			<th>
				<?php echo wp_kses_post( $label ); ?>
			</th>
			<td>
				<input type="text" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"<?php echo $attrs; ?> />
				<a class="previewlink button" href="#TB_inline?height=500&amp;width=640&amp;inlineId=<?php echo esc_attr( 'thicky-wpf-' . $id ); ?>"><?php _e( 'Preview', t() ); ?></a>
				<a class="medialink thickbox button" title="Choose a file from your media library" href="<?php echo admin_url( 'media-upload.php' . $media_args ); ?>"><?php _e( 'Media Library', t() ); ?></a>
				<a class="uploadlink thickbox button" title="Upload Media" href="<?php echo admin_url( 'media-upload.php' . $upload_args ); ?>"><?php _e( 'Upload', t() ); ?></a>
				<?php echo wp_kses_post( $description ); ?>
				<div id="thicky-wpf-<?php echo esc_attr($id); ?>" style="display: none;"></div>
			</td>
		</tr>
		<?php
	}
	
	function do_table_color() {
		?>
		<tr id="wpf-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $args['type'] ); ?>">
			<th>
				<?php echo wp_kses_post( $label ); ?>
			</th>
			<td>
				<a class="pickcolor hide-if-no-js button" rel="<?php echo esc_attr($id); ?>" href="#"><?php echo esc_html( $btn_label ); ?></a>
				<input type="text" id="wpf-form-<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"<?php echo $attrs; ?> />
				<div id="pickcolor-<?php echo esc_attr($id); ?>" class="color-picker-div"></div>
			</td>
		</tr>
		<?php
	}
}