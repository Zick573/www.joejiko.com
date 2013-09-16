<?php
/**
 * Extends the base admin class by providing a basic class for dealing with the native WP Settings API.
 *
 * To Learn more on how to use this class, click the following link:
 * @link http://devpress.com/codex/wp-framework/theme-options-api/
 *
 * @since 0.3.0
 */
class WPF_Admin_Form extends WPF_Admin {
	function WPF_Admin_Form( $args = array() ) {
		$default_args = array( 'api_callback' => null, 'settings_fields' => null, 'page_slug' => null );
		$args = wp_parse_args( $args, $default_args );
		parent::__construct( $args );
	}

	function admin_init() {
		if ( ! $this->verify() )
			return false;

		add_action( 'load-' . $this->hook, array( $this, 'api_callback' ) );
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
	 * Returns the form attributes formatted as an array.
	 *
	 * @since 0.3.0
	 * @uses form_attrs()
	 */
	function get_form_attrs() {
		return array(
			'action' => 'options.php',
			'method' => 'post',
			'enctype' => 'multipart/form-data',
		);
	}

	function display() {
		?>
		<form<?php echo $this->form_attrs(); ?>>
			<?php settings_fields( $this->slug ); ?>
			<?php self::contextual_callback( 'form', $this->get_options() ); ?>
			<?php submit_button( __( 'Save Changes', t() ), 'primary', 'submit', true ); ?>
		</form><!--form-->
		<?php
	}
}