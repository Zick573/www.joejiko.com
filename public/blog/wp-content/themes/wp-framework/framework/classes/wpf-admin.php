<?php
/**
 * Base class for registering an administration page in WordPress.
 *
 * To Learn more on how to use this class, click the following link:
 * @link http://devpress.com/codex/wp-framework/theme-options-api/
 *
 * @since 0.3.0
 */
class WPF_Admin extends WPF_API {
	/**
	 * Sets the option group used to retrieve all the options from the db.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see __construct();
	 * @var string
	 */
	var $option_group;

	/**
	 * Sets the page slugs used to store all the registered pages.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see __construct();
	 * @var string
	 */
	var $page_slug = array();

	/**
	 * Sets the slug for a registered page.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see _setup_globals();
	 * @var string
	 */
	var $slug;

	/**
	 * Sets the hook id for a registered page.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see _setup_globals();
	 * @var string
	 */
	var $hook;

	/**
	 * Sets the parent id for a registered page.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see _setup_globals();
	 * @var string
	 */
	var $parent;

	/**
	 * Alias for the $pagenow global.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see _setup_globals();
	 * @var string
	 */
	var $pagenow;

	/**
	 * Container for all the messages used on the page.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see add_message();
	 * @var array
	 */
	var $messages = array();

	/**
	 * Constructor method that bootstraps the admin class.
	 *
	 * When registering your own admin class, you'll need to populate
	 * the following args:
	 * - option_group - The id name used to store all the options to the database. Defaults to null.
	 * - page_slug - For all the registered menu pages, add their page slug to this array. This is required for the pages to display.
	 *
	 * @since 0.3.0
	 */
	function WPF_Admin( $args = array() ) {
		$default_args = array( 'option_group' => null, 'page_slug' => array() );
		$args = wp_parse_args( $args, $default_args );

		foreach ( $args as $key => $value ) {
			$this->$key = $value;
		}

		add_action( 'admin_init', array( $this, '_setup_globals' ), 1 );

		// Magic hooks
		add_action( 'admin_menu', array( $this, '_admin_menu' ) );
		add_action( 'admin_init', array( $this, '_admin_init' ) );
		add_action( 'admin_head', array( $this, '_admin_head' ) );
	}

	/**
	 * Magic hook: Define your own admin_menu OR admin_menu_{$slug} method
	 *
	 * @since 0.3.0
	 */
	function _admin_menu() {
		self::contextual_callback( 'admin_menu' );
	}

	/**
	 * Magic hook: Define your own admin_init OR admin_init_{$slug} method
	 *
	 * @since 0.3.0
	 */
	function _admin_init() {
		self::contextual_callback( 'admin_init' );
	}

	/**
	 * Magic hook: Define your own admin_head OR admin_head_{$slug} method
	 *
	 * @since 0.3.0
	 */
	function _admin_head() {
		self::contextual_callback( 'admin_head' );
	}

	/**
	 * Only run the setup if the user is on that page.
	 *
	 * @since 0.3.0
	 */
	function verify() {		
		return ! in_array( $this->pagenow, (array) $this->page_slug ) ? false : true;
	}

	/**
	 * Sets up various globals needed to identity our admin page(s).
	 *
	 * @todo need to make slug, hook, parent unique based on the registered menu pages.
	 *
	 * @since 0.3.0
	 */
	function _setup_globals() {
		global $pagenow;

		$registered_page = $this->pagenow = ( isset($_GET['page']) ) ? stripslashes( $_GET['page'] ) : null;

		$the_parent = '';
		if ( isset($registered_page) ) {
			$the_parent = $pagenow;

			$registered_page = plugin_basename( $registered_page );

			if ( ! $page_hook = get_plugin_page_hookname( $registered_page, $the_parent ) )
				$page_hook = get_plugin_page_hookname( $registered_page, $registered_page );
		} else {
			$registered_page = $page_hook = null;
		}

		$this->slug = $registered_page;
		$this->hook = $page_hook;
		$this->parent = $the_parent;
		
		if ( ! $this->verify() )
			return false;

		$this->add_default_options();

		self::contextual_callback( 'setup' );

		add_action( 'load-' . $this->hook, array( $this, 'set_contextual_help' ) );
		add_action( 'load-' . $this->hook, array( $this, '_page_load' ) );
		add_action( "wpf__display_messages_{$this->slug}", array( $this, '_display_messages' ) );
		add_action( "admin_head-{$this->hook}", array( $this, 'inject_css' ) );
	}

	/**
	 * Callback for magic load method
	 *
	 * @since 0.3.0
	 */
	function _page_load() {
		self::contextual_callback( 'load' );
	}

	/**
	 * Handles the form processing and display of the admin page.
	 * When creating a new admin page, use this method as the callback.
	 *
	 * Example:
	 * add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'options', array( $this, 'init' ) );
	 *
	 * @since 0.3.0
	 */
	function init() {
		if ( ! $this->verify() )
			return false;

		self::process_form_data();
		self::contextual_callback( 'page' );
	}
	
	/**
	 * Processes the form data and updates the database with the new values.
	 *
	 * It calls the update method with you can override to provide stronger validation.
	 *
	 * Pro tip: You can prevent the form from saving if you return false in your update method.
	 *
	 * @since 0.3.0
	 * @uses check_admin_referer()
	 */
	function process_form_data() {
		$new_options = isset( $_POST[$this->option_group] ) ? $_POST[$this->option_group] : array();		
		if ( !empty($new_options) ) {
			check_admin_referer( $this->hook );

			$options = self::contextual_callback( 'update', array( $new_options, $this->get_options() ) );

			if ( $options ) {
				$this->add_message( wpf_wrap( 'strong', __( 'Settings saved.', t() ), array(), false ) );
				return $this->_update_options( $options );
			} else {
				$this->add_message( wpf_wrap( 'strong', __( 'Settings not saved.', t() ), array(), false ), 'error' );
			}
		}
	}

	/**
	 * Update options.
	 *
	 * This is a barebones method that your encouraged to override in order to provide your own validation
	 * specific to your options.
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
		$options = wp_parse_args( $new_options, $old_options );

		return $options;
	}

	/**
	 * Adds information to the help panel on the theme options page.
	 *
	 * This method calls add_contextual_help which you should provide within
	 * your admin class.
	 * 
	 * @since 0.3.0
	 * @uses add_contextual_help();
	 */
	function set_contextual_help() {
		self::contextual_callback( 'add_contextual_help' );
	}
	
	/**
	 * Registers a message that gets displayed on the page.
	 *
	 * @since 0.3.0
	 *
	 * @param string $message The message to display.
	 * @param string $status Use 'updated' for successful messages, or 'error' for errors.
	 */
	function add_message( $message, $status = 'updated' ) {
		$this->messages[] = array( $message, $status );
	}
	
	/**
	 * Loops through all the registered notices and displays them.
	 *
	 * @since 0.3.0
	 */
	function _display_messages() {
		if ( !empty($this->messages) ) {
			foreach ( $this->messages as $message ) {
				self::message( $message[0], $message[1] );
			}
		}
	}

	/**
	 * Displays a notice.
	 *
	 * @since 0.3.0
	 *
	 * @param string $message The message to display.
	 * @param string $status Use 'updated' for successful messages, or 'error' for errors.
	 */
	function message( $message, $status = 'updated' ) {
		if ( !empty($message) ) :
		?>
		<div id="message" class="<?php echo esc_attr($status); ?>">
			<p><?php echo wp_kses_post( $message ); ?></p>
		</div><!--#message-->
		<?php
		endif;
	}
	
	/**
	 * Displays the admin page.
	 *
	 * @since 0.3.0
	 */
	function page() {
		?>
		<div id="wpf-form-<?php echo esc_attr( $this->slug ); ?>" class="wpf-wrap wrap">
			<?php

			// Available action hook before the page title and form is displayed.
			do_action( "wpf_admin_page_open_{$this->slug}" );

			// Insert screen_icon and page title
			self::contextual_callback( 'header' );

			// Available action hook WPF uses to output any notices to the user.
			do_action( "wpf__display_messages_{$this->slug}" );
			
			self::contextual_callback( 'display' );
			
			self::contextual_callback( 'footer' );

			// Available action hook after the page  and form is displayed.
			do_action( "wpf_admin_page_close_{$this->slug}" );
			?>
		</div><!--#<?php echo esc_attr( $this->slug ); ?>-->
		<?php
	}
	
	/**
	 * Displays the header section of the admin page.
	 *
	 * @since 0.3.0
	 */
	function header() {
		screen_icon();
		echo $this->wrap( 'h2', __CLASS__ );
	}
	
	function display() {
		?>
		<form<?php echo $this->form_attrs(); ?>>
			<?php self::contextual_callback( 'form', $this->get_options() ); ?>
			<?php wp_nonce_field( $this->hook ); ?>
		</form><!--form-->
		<?php
	}

	/**
	 * Returns the options from the database.
	 * If none exists return an empty array.
	 *
	 * @since 0.3.0
	 */
	function get_options() {
		$options = get_option( $this->option_group );
		$options = !empty( $options ) ? $options : self::contextual_callback( 'get_default_options' );
		
		return $options;
	}

	/**
	 * Returns the form attributes formatted as an array.
	 *
	 * @since 0.3.0
	 * @uses form_attrs()
	 */
	function get_form_attrs() {
		return apply_filters( "wpf_admin_form_attrs_{$this->slug}", array(
			'action' => '',
			'method' => 'post',
			'enctype' => 'multipart/form-data',
		) );
	}

	/**
	 * Returns the form attributes formated for HTML display.
	 *
	 * @since 0.3.0
	 */
	function form_attrs() {
		$form_attrs = $this->get_form_attrs();
		$the_form_attrs = '';
		if ( empty($form_attrs) )
			return;

		foreach ( $form_attrs as $key => $value ) {
			$the_form_attrs .= ' ' . sanitize_key($key) . '="'. esc_attr( $value ) .'"';
		}

		return $the_form_attrs;
	}

	/**
	 * CSS needed for message styling.
	 *
	 * @since 0.3.0
	 */
	function inject_css() {
		?>
		<style type="text/css">
			#message ul { list-style: disc; }
			#message ul li { margin-left: 25px; }
		</style>
		<?php
	}

	/**
	 * Returns the value of an option from the db if it exists.
	 *
	 * @since 0.3.0
	 *
	 * @param string $key Option Identifier.
	 * @return mixed Returns the option's value if it exists, false if it doesn't.
	 */
	function get_option( $key, $option_group = null ) {
		$option_group = !empty($option_group) ? $option_group : $this->option_group;

		$options = get_option( $option_group );

		if ( isset($options[$key]) )
			return $options[$key];

		return false;
	}

	/**
	 * Adds an option to the options db.
	 * The option will not get added if the option already exists.
	 *
	 * @since 0.3.0
	 *
	 * @param string $key Option Identifier. Must be unique.
	 * @param mixed $value Option Value.
	 * @return bool true|false
	 */
	function add_option( $key, $value, $option_group = null ) {
		$option_group = !empty($option_group) ? $option_group : $this->option_group;

		$options = get_option( $option_group );

		if ( !isset($options[$name]) ) {
			$options[$name] = $value;
			return update_option( $option_group, $options );
		}

		return false;
	}

	/**
	 * Updates an option to the options db.
	 *
	 * @since 0.3.0
	 *
	 * @param string $key Option Identifier.
	 * @param mixed $value Option Value.
	 * @return bool true|false
	 */
	function update_option( $key, $value, $option_group = null ) {
		$option_group = !empty($option_group) ? $option_group : $this->option_group;

		$options = get_option( $option_group );

		$options[$key] = $value;

		return update_option( $option_group, $options );
	}

	/**
	 * Deletes an option from the options db.
	 *
	 * @since 0.3.0
	 *
	 * @param string $key Option Identifier.
	 * @return bool true|false
	 */
	function delete_option( $key, $option_group = null ) {
		$option_group = !empty($option_group) ? $option_group : $this->option_group;

		$options = get_option( $option_group );

		if ( !isset( $options[$key] ) )
			return false;

		unset( $options[$key] );

		return update_option( $option_group, $options );
	}
	
	function _delete_options() {
		return delete_option( $this->option_group );
	}

	function _update_options( $options ) {
		return update_option( $this->option_group, $options );
	}

	// extend this method to provide your own set of default options
	function get_default_options() {
		return array();
	}

	function add_default_options() {
		$options = self::contextual_callback( 'get_default_options' );
		return add_option( $this->option_group, $options );
	}
}